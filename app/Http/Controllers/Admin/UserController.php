<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MoodleHelpers;
use App\Helpers\PaymentHelpers;
use App\Http\Controllers\Controller;
use App\Http\Request\SendSmsRequest;
use App\Jobs\ProcessNotificationParents;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserSms;
use App\Services\Sms\SmsInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\BitrixHelpers;
use App\Bitrix24\Bitrix24API;


class UserController extends Controller
{
    public function listParents(Request $request)
    {
        $query = User::select(['id', 'user_name', 'last_name', 'middle_name', 'phone', 'email', 'disable', 'training_format']);

        if ($request->get('fio')) {
            $query->where(function ($builder) use ($request) {
                $builder->where('user_name', 'LIKE', '%'.$request->get('fio').'%')
                    ->orWhere('last_name', 'LIKE', '%'.$request->get('fio').'%')
                    ->orWhere('middle_name', 'LIKE', '%'.$request->get('fio').'%');
            });
        }

        if ($request->get('phone')) {
            $query->where('phone', 'LIKE', '%'.$request->get('phone').'%');
        }

        if ($request->get('email')) {
            $query->where('email', 'LIKE', '%'.$request->get('email').'%');
        }

        if ($request->get('disable')) {
            $query->where('disable', '=', $request->get('disable') === 'on' ? 0 : 1);
        }

        if ($request->get('training_format')) {
            $query->where('training_format', '=', $request->get('training_format'));
        }

        $users = $query->orderByDesc('id')->paginate(50);

        return view('admin/parents', compact('users'));
    }

    public function parentHasChildren($id)
    {
        $user = User::find($id);

        return view('admin/parent_has_children', compact('user'));
    }

    public function smsHistory($id)
    {
        $user = User::find($id);
        $histories = UserSms::where('user_id', $id)->orderByDesc('id')->get();
        return view('admin/sms_history', compact('histories', 'user'));
    }

    public function paymentHistory($id)
    {
        $user = User::find($id);
        $payments = Payment::where('user_id', $id)->orderByDesc('id')->get();
        return view('admin/payment_history', compact('payments', 'user'));
    }

    public function addTransaction(Request $request, $id)
    {
        try {
            $user = User::find($id);

            $lastPayment = Payment::where('user_id', $user->id)->orderByDesc('id')->first();
            $now = Carbon::now()->timestamp;

            if ($lastPayment && ($now - $lastPayment->created_at->timestamp) < 60) {
                return back()->with("error", 'Дозволенно створювати одну транзакцію за 1хв');
            }

            $beforeDate = Carbon::parse($request->get('before'))->startOfMonth();
            $month = $request->get('months');

            $payment = Payment::create([
                'user_id' => $user->id,
                'payment_id' => null,
                'status' => 'pay',
                'method' => 'manual',
                'token' => null,
                'amount' => 0,
                'sale' => 0,
                'referal' => 0,
                'months' => $month,
                'before' => $beforeDate
            ]);

            $user->update([
                'active'        => 1,
                'status'        => 6,
                'months'        => 1,
                'stageRegister' => $user->stageRegister === 8 ? 8 : 3,
                'paid_before'   => $beforeDate,
            ]);

            $moodle = new MoodleHelpers('core_user_update_users');

            foreach ($user->childrens as $children) {
                $moodle->activate_user($children->moodle_id);
                PaymentHelpers::addServicePeriod($children, $beforeDate, $payment, $month, $payment->id);
            }

            return back()->with("success", "Транзакцію додано і активовано аккаунт");
        } catch (\Exception $exception) {
            return back()->with("error", $exception->getMessage());
        }
    }

    public function sms(SmsInterface $smsService)
    {
        $balance = $smsService->getBalance();

        return view('admin/sms', compact('balance'));
    }

    public function sendSmsPeriod(Request $request, $id)
    {
        try {
            $user = User::find($id);
            ProcessNotificationParents::dispatch($request->get('step'), $user)->onQueue('parent_sms');

            return back()->with("success", "SMS відправленно в чергу на відправку");
        } catch (\Exception $exception) {
            return back()->with("error", $exception->getMessage());
        }
    }

    public function sendSms(SendSmsRequest $request, SmsInterface $smsService)
    {
        try {
            $smsService->sendSms($request->get('phone'), $request->get('text'));
//            ProcessNotificationParents::dispatch('test_sms', null, $request->get('phone'), $request->get('text'));

            return back()->with("success", "SMS відправленно в чергу на відправку");
        } catch (\Exception $exception) {
            return back()->with("error", $exception->getMessage());
        }
    }

    public function toggleEnableUser(Request $request)
    {
        if ($request->filled(['active', 'id'])) {
            try {
                $moodle = new MoodleHelpers('core_user_update_users');

                $user = User::find($request->get('id'));
                $user->disable = $request->get('active');
                $user->save();
                foreach ($user->childrens as $children) {
                    if ($request->get('active') == 1) {
                        $moodle->deactivate_user($children->moodle_id);
                    } elseif ($request->get('active') == 0) {
                        $moodle->activate_user($children->moodle_id);
                    }
                }

                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'No mandatory parameters passed']);
    }
}
