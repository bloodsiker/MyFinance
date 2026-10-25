<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FinanceTelegram\AccountLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\{Rule, ValidationException};

class CabinetUsersController extends Controller
{
    public function index(Request $request, AccountLink $links)
    {
        $query = User::orderBy('name');
        if (!$request->user()->canManageUsers()) $query->whereKey($request->user()->id);
        $users = $query->get();
        $connectedUserIds = DB::table('finance_telegram_chats')->whereIn('user_id', $users->modelKeys())
            ->distinct()->pluck('user_id')->map(fn ($id) => (string) $id)->all();

        return response()->json(['users' => $users->map(fn ($user) => [
            'id' => $user->id, 'name' => $user->name, 'email' => $user->email,
            'telegram_id' => $links->displayId($user), 'is_self' => $user->id === $request->user()->id,
            'telegram_notifications_enabled' => (bool) $user->telegram_notifications_enabled,
            'telegram_chat_connected' => in_array((string) $user->id, $connectedUserIds, true),
        ]), 'can_manage_users' => $request->user()->canManageUsers()]);
    }

    private function target(Request $request, int $id): User
    {
        abort_unless($id === $request->user()->id || $request->user()->canManageUsers(), 403);
        return User::findOrFail($id);
    }

    public function telegram(Request $request, int $id, AccountLink $links)
    {
        $target = $this->target($request, $id);
        $data = $request->validate(['telegram_id' => ['present', 'nullable', 'string', 'regex:/^[1-9][0-9]{0,18}$/', Rule::unique('users', 'telegram_id')->ignore($id)]]);
        $telegramId = $data['telegram_id'] ?: null;
        if ($telegramId && ($owner = $links->resolve($telegramId)) && $owner->id !== $target->id) {
            throw ValidationException::withMessages(['telegram_id' => 'Цей Telegram ID вже прив’язаний до іншого користувача.']);
        }
        DB::transaction(function () use ($target, $telegramId) {
            $locked = User::whereKey($target->id)->lockForUpdate()->firstOrFail();
            $locked->forceFill(['telegram_id' => $telegramId, 'telegram_link_managed_at' => now()])->save();
            DB::table('finance_telegram_chats')->where('user_id', $target->id)->delete();
            DB::table('finance_telegram_links')->where('user_id', $target->id)->delete();
        });
        return response()->json(['message' => $telegramId ? 'Telegram ID збережено' : 'Telegram відв’язано']);
    }

    public function link(Request $request, int $id, AccountLink $links)
    {
        return response()->json($links->issue($this->target($request, $id)));
    }

    public function telegramNotifications(Request $request, int $id)
    {
        $target = $this->target($request, $id);
        $data = $request->validate(['enabled' => 'required|boolean']);

        if ($data['enabled'] && !DB::table('finance_telegram_chats')->where('user_id', $target->id)->exists()) {
            throw ValidationException::withMessages([
                'enabled' => 'Спершу відкрийте бота й натисніть «Старт», щоб під’єднати чат.',
            ]);
        }

        $target->forceFill(['telegram_notifications_enabled' => (bool) $data['enabled']])->save();

        return response()->json([
            'message' => $data['enabled'] ? 'Сповіщення про транзакції увімкнено' : 'Сповіщення вимкнено',
            'enabled' => (bool) $target->telegram_notifications_enabled,
        ]);
    }
}
