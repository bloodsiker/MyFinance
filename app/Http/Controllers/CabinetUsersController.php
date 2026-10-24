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
        return response()->json(['users' => $query->get()->map(fn ($user) => [
            'id' => $user->id, 'name' => $user->name, 'email' => $user->email,
            'telegram_id' => $links->displayId($user), 'is_self' => $user->id === $request->user()->id,
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
}
