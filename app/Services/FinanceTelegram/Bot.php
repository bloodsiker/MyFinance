<?php

namespace App\Services\FinanceTelegram;

use App\Models\{Category, Month, MonthCategory, Transaction, User, Year};
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Http};

class Bot
{
    private const KEYBOARD_ACTIONS = [
        '📊 Статистика місяця' => '/stats',
        '🧾 Транзакції' => '/transactions',
        '📅 Витрати сьогодні' => '/today',
        '➕ Додати витрату' => '/add',
        '🗓 Обрати місяць' => '/month',
        '📈 Загальний огляд' => '/overview',
        '🌐 Відкрити кабінет' => '/cabinet',
        '❌ Скасувати' => '/cancel',
    ];

    public function handle(array $update): void
    {
        $message = $update['message'] ?? $update['callback_query']['message'] ?? [];
        $sender = $update['callback_query']['from'] ?? $update['message']['from'] ?? [];
        $chatId = $message['chat']['id'] ?? null;
        $senderId = $sender['id'] ?? null;
        if (($message['chat']['type'] ?? '') !== 'private' || !$chatId || (string) $senderId !== (string) $chatId || !isset($update['update_id'])) return;
        $links = app(AccountLink::class);
        $text = trim($update['message']['text'] ?? '');
        $linkedNow = false;
        if (preg_match('/^\/start(?:@[A-Za-z0-9_]+)? link_([a-f0-9]{48})$/', $text, $match)) {
            $result = $links->consume($match[1], (string) $senderId);
            if (!$result['ok']) {
                $this->send($chatId, ['text' => $result['message']]);
                $this->acknowledge($update);
                return;
            }
            $linkedNow = true;
            $update['message']['text'] = '/start';
        }
        $user = $links->resolve((string) $senderId);
        if (!$user) {
            $this->send($chatId, ['text' => "Доступ ще не налаштований. Ваш Telegram ID: {$senderId}. Відкрийте розділ «Користувачі» в кабінеті та натисніть «Відкрити Telegram-бота» або введіть цей ID вручну."]);
            $this->acknowledge($update);
            return;
        }
        // Serialize a chat and persist its reply in the SAME transaction as a new
        // expense. Telegram retries replay the reply instead of repeating a write.
        $reply = DB::transaction(function () use ($update, $chatId, $user, $linkedNow) {
            $freshUser = User::whereKey($user->id)->lockForUpdate()->first();
            if (!$freshUser || (($freshUser->telegram_id || $freshUser->telegram_link_managed_at) && (string) $freshUser->telegram_id !== (string) $chatId)) {
                return ['text' => 'Прив’язку Telegram змінено. Відкрийте бота заново через кабінет.'];
            }
            DB::table('finance_telegram_chats')->insertOrIgnore(['chat_id' => $chatId, 'user_id' => $user->id, 'state' => '{}', 'created_at' => now(), 'updated_at' => now()]);
            $chat = DB::table('finance_telegram_chats')->where('chat_id', $chatId)->lockForUpdate()->first();
            $processed = DB::table('finance_telegram_updates')->where('update_id', $update['update_id'])->first();
            if ($processed) return json_decode($processed->reply, true);
            $state = $chat->user_id == $user->id ? json_decode($chat->state, true) : [];
            if (!empty($state['expires']) && $state['expires'] < time()) $state = ['month_id' => $state['month_id'] ?? null];
            $reply = $this->respond($update, $state, $user);
            if ($linkedNow) $reply['text'] = 'Telegram успішно прив’язано до акаунта '.($user->name ?: $user->email).".\n\n".$reply['text'];
            DB::table('finance_telegram_chats')->where('chat_id', $chatId)->update(['user_id' => $user->id, 'state' => json_encode($state, JSON_UNESCAPED_UNICODE), 'updated_at' => now()]);
            DB::table('finance_telegram_updates')->insert(['update_id' => $update['update_id'], 'chat_id' => $chatId, 'reply' => json_encode($reply, JSON_UNESCAPED_UNICODE), 'created_at' => now()]);
            return $reply;
        }, 3);
        $this->send($chatId, $reply);
        $this->acknowledge($update);
    }

    private function respond(array $update, array &$state, User $user): array
    {
        $text = trim($update['message']['text'] ?? '');
        $text = self::KEYBOARD_ACTIONS[$text] ?? $text;
        $callback = $update['callback_query']['data'] ?? '';
        if (in_array($text, ['/start', '/help', '/cancel'], true) || $callback === 'cancel') {
            $state = ['month_id' => $state['month_id'] ?? null];
            return $this->menu($text === '/cancel' || $callback === 'cancel' ? 'Додавання скасовано.' : 'Finance: статистика та витрати вашого кабінету.\n/stats — місячна статистика\n/transactions — транзакції за місяць\n/today — витрати сьогодні\n/cabinet — відкрити кабінет\n/month — обрати місяць\n/add — додати витрату\n/overview — загальний огляд\n/cancel — скасувати');
        }
        if ($text === '/month' || $callback === 'years') {
            $state = ['month_id' => $state['month_id'] ?? null];
            $buttons = Year::orderByDesc('name')->get()->map(fn ($year) => [['text' => (string) $year->name, 'callback_data' => 'year:'.$year->id]])->all();
            return ['text' => $buttons ? 'Оберіть рік:' : 'Спочатку створіть рік у кабінеті.', 'buttons' => $buttons];
        }
        if (preg_match('/^year:(\d+)$/', $callback, $match)) {
            $buttons = Month::where('year_id', $match[1])->orderByRaw('CAST(number AS UNSIGNED)')->get()->map(fn ($month) => ['text' => $month->name, 'callback_data' => 'month:'.$month->id])->chunk(3)->map(fn ($chunk) => $chunk->values()->all())->all();
            return ['text' => 'Оберіть місяць:', 'buttons' => $buttons];
        }
        if (preg_match('/^month:(\d+)$/', $callback, $match)) {
            $month = Month::find($match[1]);
            if (!$month) return $this->menu('Цього місяця вже немає. Оберіть інший.');
            $state = ['month_id' => $month->id];
            return $this->statistics($month);
        }
        if ($text === '/overview' || $callback === 'overview') return $this->overview();
        if ($text === '/today' || $callback === 'today') return $this->today();
        if ($text === '/cabinet' || $callback === 'cabinet') return $this->cabinet();
        $month = !empty($state['month_id']) ? Month::find($state['month_id']) : null;
        $month = $month ?: $this->defaultMonth();
        if ($text === '/stats' || $callback === 'stats') return $month ? $this->statistics($month) : $this->menu('Спочатку створіть рік у кабінеті.');
        if ($text === '/transactions' || $callback === 'transactions' || preg_match('/^transactions:(\d+):(\d{1,9})$/', $callback, $pageMatch)) {
            $page = 1;
            if (!empty($pageMatch)) {
                $month = Month::find($pageMatch[1]);
                $page = max(1, (int) $pageMatch[2]);
            }
            if (!$month) return $this->menu('Цього місяця немає. Оберіть інший через /month.');
            $state = ['month_id' => $month->id];
            $reply = $this->transactions($month, $page);
            if (isset($update['callback_query']['message']['message_id'])) $reply['edit_message_id'] = $update['callback_query']['message']['message_id'];
            return $reply;
        }

        if ($text === '/add' || $callback === 'add') {
            if (!$month) return $this->menu('Спочатку створіть рік у кабінеті.');
            $categories = MonthCategory::with('category')->where('month_id', $month->id)->get();
            if ($categories->isEmpty()) return $this->menu('Додайте бюджет хоча б однієї категорії в цьому місяці через кабінет.');
            $state = ['month_id' => $month->id, 'flow' => bin2hex(random_bytes(8)), 'step' => 'category', 'expires' => time() + 1800];
            $buttons = $categories->filter(fn ($row) => $row->category)->map(fn ($row) => ['text' => $row->category->name, 'callback_data' => 'category:'.$state['flow'].':'.$row->category_id])->chunk(2)->map(fn ($chunk) => $chunk->values()->all())->all();
            $buttons[] = [['text' => 'Скасувати', 'callback_data' => 'cancel']];
            return ['text' => $this->monthLabel($month)."\nОберіть категорію витрати:", 'buttons' => $buttons];
        }
        if ($callback && preg_match('/^(category|pay|save):([a-f0-9]{16})(?::(.+))?$/', $callback, $parts)) {
            if (($state['flow'] ?? '') !== $parts[2]) return $this->menu('Ця кнопка вже неактуальна. Почніть нову витрату через /add.');
            if ($parts[1] === 'category' && ($state['step'] ?? '') === 'category') {
                $category = MonthCategory::where('month_id', $state['month_id'])->where('category_id', $parts[3] ?? null)->first();
                if (!$category) return $this->menu('Категорія недоступна. Почніть заново через /add.');
                $state['category_id'] = $category->category_id;
                $state['step'] = 'description';
                return ['text' => 'Напишіть опис витрати (до 255 символів). /cancel — скасувати.'];
            }
            if ($parts[1] === 'pay' && ($state['step'] ?? '') === 'payment' && in_array($parts[3] ?? '', ['card', 'cash'], true)) {
                $state['type'] = $parts[3];
                $state['step'] = 'confirm';
                $category = Category::find($state['category_id']);
                return ['text' => "Перевірте витрату:\n".$this->monthLabel($month)."\nКатегорія: ".optional($category)->name."\nОпис: {$state['title']}\nСума: ".$this->money($state['amount'])." грн\nОплата: ".($state['type'] === 'card' ? 'Картка' : 'Готівка'),
                    'buttons' => [[['text' => 'Зберегти транзакцію', 'callback_data' => 'save:'.$state['flow']]], [['text' => 'Скасувати', 'callback_data' => 'cancel']]]];
            }
            if ($parts[1] === 'save' && ($state['step'] ?? '') === 'confirm') {
                if (!MonthCategory::where('month_id', $state['month_id'])->where('category_id', $state['category_id'])->exists()) {
                    $state = ['month_id' => $state['month_id']];
                    return $this->menu('Категорія або місяць більше недоступні. Транзакцію не збережено.');
                }
                Transaction::create(['month_id' => $state['month_id'], 'category_id' => $state['category_id'], 'user_id' => $user->id,
                    'title' => $state['title'], 'amount' => $state['amount'], 'type' => $state['type'], 'created_at' => now()]);
                $state = ['month_id' => $state['month_id']];
                return $this->menu('Транзакцію збережено. Вона вже доступна в кабінеті.');
            }
            return $this->menu('Цей крок уже завершено. Продовжіть поточне додавання або натисніть /cancel.');
        }
        if (!$callback && $text && ($state['step'] ?? '') === 'description') {
            if (mb_strlen($text) > 255 || str_starts_with($text, '/')) return ['text' => 'Введіть опис від 1 до 255 символів або /cancel.'];
            $state['title'] = $text;
            $state['step'] = 'amount';
            return ['text' => 'Введіть суму в гривнях, наприклад 250 або 250,50.'];
        }
        if (!$callback && ($state['step'] ?? '') === 'amount') {
            if (!preg_match('/^(?:0|[1-9]\d{0,9})(?:[.,]\d{1,2})?$/', $text) || (float) str_replace(',', '.', $text) <= 0) return ['text' => 'Потрібна додатна сума з точністю до копійок, наприклад 250,50.'];
            $state['amount'] = str_replace(',', '.', $text);
            $state['step'] = 'payment';
            return ['text' => 'Як оплатили?', 'buttons' => [[['text' => 'Картка', 'callback_data' => 'pay:'.$state['flow'].':card'], ['text' => 'Готівка', 'callback_data' => 'pay:'.$state['flow'].':cash']], [['text' => 'Скасувати', 'callback_data' => 'cancel']]]];
        }
        return $this->menu('Оберіть дію. Для допомоги натисніть /help.');
    }

    private function defaultMonth(): ?Month
    {
        $year = Year::where('name', now()->year)->first() ?: Year::orderByDesc('name')->first();
        return $year ? Month::where('year_id', $year->id)->where('number', (string) now()->month)->first() ?: Month::where('year_id', $year->id)->orderByRaw('CAST(number AS UNSIGNED)')->first() : null;
    }

    private function statistics(Month $month): array
    {
        $budget = (float) MonthCategory::where('month_id', $month->id)->sum('budget');
        $spent = (float) Transaction::where('month_id', $month->id)->sum('amount');
        $lines = [$this->monthLabel($month), 'Бюджет: '.$this->money($budget).' грн', 'Витрачено: '.$this->money($spent).' грн', ($spent > $budget ? '🔴 Перевищення: ' : 'Залишок: ').$this->money(abs($budget - $spent)).' грн', '', 'За категоріями:'];
        $sums = Transaction::where('month_id', $month->id)->selectRaw('category_id, SUM(amount) as total')->groupBy('category_id')->pluck('total', 'category_id');
        $plans = MonthCategory::where('month_id', $month->id)->pluck('budget', 'category_id');
        foreach (Category::whereIn('id', $sums->keys()->merge($plans->keys())->unique())->orderBy('name')->get() as $category) {
            $plan = (float) ($plans[$category->id] ?? 0); $actual = (float) ($sums[$category->id] ?? 0);
            $lines[] = ($actual > $plan ? '🔴 ' : '🔵 ').$category->name.': '.$this->money($actual).' / '.$this->money($plan).' грн';
        }
        return $this->menu(implode("\n", $lines));
    }

    private function transactions(Month $month, int $page): array
    {
        $query = Transaction::where('month_id', $month->id);
        $total = (clone $query)->count();
        $spent = (float) (clone $query)->sum('amount');
        $lastPage = max(1, (int) ceil($total / 10));
        $page = min($page, $lastPage);
        $items = $query->with(['category', 'user'])->orderByDesc('created_at')->orderByDesc('id')->offset(($page - 1) * 10)->limit(10)->get();
        $lines = ['Транзакції: '.$this->monthLabel($month), 'Усього: '.$total.' · '.$this->money($spent).' грн', 'Сторінка '.$page.' з '.$lastPage, ''];
        foreach ($items as $i => $row) {
            $lines[] = (($page - 1) * 10 + $i + 1).'. '.mb_strimwidth($row->title, 0, 90, '…').' — '.$this->money($row->amount).' грн';
            $lines[] = mb_strimwidth(optional($row->category)->name ?? 'Без категорії', 0, 40, '…').' · '.($row->type === 'card' ? 'Картка' : 'Готівка');
            $lines[] = $row->created_at->copy()->setTimezone(config('app.timezone', 'UTC'))->format('d.m.Y H:i').' · '.mb_strimwidth(optional($row->user)->name ?? '—', 0, 40, '…');
            $lines[] = '';
        }
        if (!$total) $lines[] = 'У цьому місяці ще немає транзакцій.';
        else $lines[] = 'На цій сторінці: '.$this->money($items->sum('amount')).' грн';
        $navigation = [];
        if ($page > 1) $navigation[] = ['text' => '← Назад', 'callback_data' => 'transactions:'.$month->id.':'.($page - 1)];
        if ($page < $lastPage) $navigation[] = ['text' => 'Далі →', 'callback_data' => 'transactions:'.$month->id.':'.($page + 1)];
        $buttons = $navigation ? [$navigation] : [];
        $buttons[] = [['text' => 'Статистика місяця', 'callback_data' => 'stats'], ['text' => 'Обрати місяць', 'callback_data' => 'years']];
        $buttons[] = [['text' => 'Додати витрату', 'callback_data' => 'add']];
        return ['text' => implode("\n", $lines), 'buttons' => $buttons];
    }

    private function today(): array
    {
        $timezone = config('app.timezone', 'UTC');
        $day = Carbon::now($timezone)->startOfDay();
        $start = $day->copy()->setTimezone(config('app.timezone'));
        $end = $day->copy()->addDay()->setTimezone(config('app.timezone'));
        $query = Transaction::where('created_at', '>=', $start)->where('created_at', '<', $end);
        $total = (clone $query)->count();
        $spent = (float) (clone $query)->sum('amount');
        $lines = ['Витрати сьогодні · '.$day->format('d.m.Y'), 'Транзакцій: '.$total, 'Разом: '.$this->money($spent).' грн', ''];
        if (!$total) return $this->menu(implode("\n", $lines).'Сьогодні ще немає витрат.');
        $lines[] = 'За категоріями:';
        $sums = (clone $query)->selectRaw('category_id, SUM(amount) as total')->groupBy('category_id')->pluck('total', 'category_id');
        $categories = Category::whereIn('id', $sums->keys())->pluck('name', 'id');
        foreach ($sums as $id => $amount) $lines[] = ($categories[$id] ?? 'Без категорії').': '.$this->money($amount).' грн';
        $lines[] = '';
        $lines[] = $total > 10 ? 'Останні 10 транзакцій:' : 'Транзакції:';
        foreach ($query->with('user')->orderByDesc('created_at')->orderByDesc('id')->limit(10)->get() as $row) {
            $lines[] = $row->created_at->copy()->setTimezone($timezone)->format('H:i').' · '.mb_strimwidth($row->title, 0, 90, '…').' — '.$this->money($row->amount).' грн';
        }
        return $this->menu(implode("\n", $lines));
    }

    private function cabinetUrl(): string
    {
        $url = trim((string) (config('finance-telegram.cabinet_url') ?: config('app.url')));
        return filter_var($url, FILTER_VALIDATE_URL) && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true) ? $url : '';
    }

    private function cabinetButton(): array
    {
        $url = $this->cabinetUrl();
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        $local = !$host || $host === 'localhost' || str_ends_with($host, '.localhost') || str_ends_with($host, '.local') || $host === '[::1]' || (filter_var($host, FILTER_VALIDATE_IP) && !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE));
        return $url && !$local ? ['text' => 'Відкрити кабінет', 'url' => $url] : ['text' => 'Відкрити кабінет', 'callback_data' => 'cabinet'];
    }

    private function cabinet(): array
    {
        $url = $this->cabinetUrl();
        if (!$url) return $this->menu('Адреса кабінету ще не налаштована. Вкажіть FINANCE_TELEGRAM_CABINET_URL у .env.');
        $text = 'Кабінет: '.$url;
        if (!isset($this->cabinetButton()['url'])) $text .= "\n\nЦе локальна адреса: вона відкриється на комп’ютері з кабінетом. Для доступу з телефона вкажіть публічну адресу в FINANCE_TELEGRAM_CABINET_URL.";
        return isset($this->cabinetButton()['url']) ? ['text' => $text, 'buttons' => [[$this->cabinetButton()]]] : $this->menu($text);
    }

    private function overview(): array
    {
        $budget = (float) MonthCategory::sum('budget'); $spent = (float) Transaction::sum('amount');
        return $this->menu("Огляд за всі роки\nБюджет: ".$this->money($budget)." грн\nВитрачено: ".$this->money($spent)." грн\n".($spent > $budget ? 'Перевищення: ' : 'Залишок: ').$this->money(abs($budget - $spent)).' грн');
    }

    private function monthLabel(Month $month): string { return $month->name.' '.optional($month->year)->name; }
    private function money($amount): string { return number_format((float) $amount, 2, ',', ' '); }
    private function menu(string $text): array
    {
        $keyboard = array_chunk(array_map(fn ($label) => ['text' => $label], array_keys(self::KEYBOARD_ACTIONS)), 2);
        return ['text' => str_replace('\\n', "\n", $text), 'keyboard' => $keyboard];
    }

    private function send($chatId, array $reply): void
    {
        if (!empty($reply['edit_message_id']) && mb_strlen($reply['text']) <= 3800) {
            $payload = ['chat_id' => $chatId, 'message_id' => $reply['edit_message_id'], 'text' => $reply['text'], 'reply_markup' => ['inline_keyboard' => $reply['buttons'] ?? []]];
            $response = Http::timeout(10)->post('https://api.telegram.org/bot'.config('finance-telegram.token').'/editMessageText', $payload);
            if (($response->successful() && $response->json('ok')) || str_contains((string) $response->json('description'), 'message is not modified')) return;
            // Deleted or otherwise uneditable messages are replaced with a new one.
        }
        $chunks = mb_str_split($reply['text'], 3800);
        foreach ($chunks as $i => $text) {
            $payload = ['chat_id' => $chatId, 'text' => $text];
            if ($i === count($chunks) - 1) {
                if (!empty($reply['keyboard'])) {
                    $payload['reply_markup'] = ['keyboard' => $reply['keyboard'], 'resize_keyboard' => true, 'is_persistent' => true, 'one_time_keyboard' => false, 'input_field_placeholder' => 'Оберіть дію або введіть повідомлення'];
                } elseif (!empty($reply['buttons'])) {
                    $payload['reply_markup'] = ['inline_keyboard' => $reply['buttons']];
                }
            }
            $response = Http::timeout(10)->post('https://api.telegram.org/bot'.config('finance-telegram.token').'/sendMessage', $payload);
            if (!$response->successful() || !$response->json('ok')) throw new \RuntimeException('Telegram delivery failed');
        }
    }

    private function acknowledge(array $update): void
    {
        if (isset($update['callback_query']['id'])) {
            Http::timeout(5)->post('https://api.telegram.org/bot'.config('finance-telegram.token').'/answerCallbackQuery', ['callback_query_id' => $update['callback_query']['id']]);
        }
    }
}
