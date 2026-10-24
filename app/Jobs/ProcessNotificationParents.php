<?php

namespace App\Jobs;

use App\Helpers\EmailHelpers;
use App\Helpers\FunctionHelpers;
use App\Helpers\CronHelpers;
use App\Helpers\MoodleHelpers;
use App\Models\Children;
use App\Models\User;
use App\Models\UserSms;
use App\Services\Sms\SmsInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Bitrix24\Bitrix24API;
use Illuminate\Support\Facades\Log;

class ProcessNotificationParents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $method;
    private $user;
    private $text;
    private $phone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($method, $user, $phone = '', $text = '')
    {
        $this->method = $method;
        $this->user   = $user;
        $this->phone   = $phone;
        $this->text   = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle(SmsInterface $smsService)
    {
        $sendSms = false;
        $user = User::find($this->user->id);

        if (!$user) {
            Log::info('[SMS] User not found: ' . $this->user->id);
            return;
        }

        foreach ($user->childrens as $child) {
            Log::info('[SMS] Moodle id: ' . $child->moodle_id . ' Child id: ' . $child->id);
            if ($child->moodle_id) {
                $sendSms = true;
                break;
            }
        }

        if (!$sendSms) {
            Log::info('[SMS] There are no eligible children for the study: ' . $this->user->id);
            return;
        }

        $emailHelper = new EmailHelpers();
        switch ($this->method) {
            case 'step_1':
                // 25 числа кожного місяця повинна надсилатись СМС та email з повідомленням про те що потрібно сплатити за наступний місяц на навчання
                $nextMouth = Carbon::now()->addMonths()->startOfMonth();

                $text = sprintf("Внесіть оплату до %s. Не допускайте блокування кабінету учня.Для оплати - %s", $nextMouth->format('d.m.Y'), $this->getInvoiceLink($this->user));
                $smsService->sendSms($user->phone, $text);
                $this->addHistorySms($user, $this->method, $text);
                $emailHelper->notificationPaymentParent($user->email, $text);
                break;
            case 'step_2':
                // 1 числа кожного місяця повинна надсилатись СМС та email з повідомленням про те що потрібно сплатити за наступний місяц на навчання
                $currentMouth = Carbon::now()->startOfMonth();

                $text = sprintf("Внесіть оплату сьогодні, %s, до кінця дня. Не допускайте блокування кабінету учня. Для оплати - %s", $currentMouth->format('d.m.Y'), $this->getInvoiceLink($this->user));
                $smsService->sendSms($user->phone, $text);
                $this->addHistorySms($user, $this->method, $text);
                $emailHelper->notificationPaymentParent($user->email, $text);
                break;
            case 'step_3':
                // 2 числа місяця повинна надсилатись СМС та email з повідомленням про те що кабінет заблокований і потрібно сплатити за навчання
                $this->blockAccount($user);
                $text = "На жаль, доступ до JAMM24 закрито. Щоб продовжити навчання здійсніть оплату - " . $this->getInvoiceLink($this->user);
                $smsService->sendSms($user->phone, $text);
                $this->addHistorySms($user, $this->method, $text);
                $emailHelper->notificationPaymentParent($user->email, $text);
                break;
            case 'step_4':
                // 5 числа місяця повинна надсилатись СМС та email з повідомленням про те що вже 2 дні кабінет заблокований і потрібно сплатити за навчання
                $text = "Упс. Доступ до JAMM24 заблоковано вже 2 дні. Щоб продовжити навчання здійсніть оплату - " . $this->getInvoiceLink($this->user);
                $smsService->sendSms($user->phone, $text);
                $this->addHistorySms($user, $this->method, $text);
                $emailHelper->notificationPaymentParent($user->email, $text);
                break;
            case 'test_sms':
                $smsService->sendSms($this->phone, $this->text);
                $emailHelper->notificationPaymentParent('maldini2@ukr.net', $this->text);
                break;
        }
    }

    protected function addHistorySms(User $user, $step, $text)
    {
        $history = new UserSms();
        $history->user_id = $user->id;
        $history->text = $text;
        $history->step = $step;
        $history->save();
    }

    protected function blockAccount(User $user)
    {
        $user->disable = 1;
        $user->save();

        foreach ($user->childrens as $children) {
            $moodleIds[] = $children->moodle_id;
        }

        if (!empty($moodleIds)) {
            $moodle = new MoodleHelpers('core_user_update_users');
            $answer = [];
            foreach ($moodleIds as $moodleId) {
                $response = $moodle->deactivate_user($moodleId);

                if ($response === null) {
                    $answer[] = ['moodle_id' => $moodleId, 'result' => 'success'];
                } else {
                    $answer[] = [
                        'moodle_id' => $moodleId,
                        'result' => 'error',
                        'error_description' => (isset($response['errorcode']) ? $response['errorcode'] : 'moodle service error'),
                    ];
                }
            }

            Log::info('UserID: ' . $user->id . ', Autoblock moodle cabinet diactivate: ' . json_encode($answer));
        }
    }

    protected function getInvoiceLink(User $user)
    {
        return sprintf('https://parent.jammschool.com/pay/%s/%s', $user->id, $user->bx_id);

//        return route('short-link-payment', ['id' => $user->bx_id]);

//        $deals = '';
//        foreach ($user->childrens as $children) {
//            $deals .= $children->deal_id . ',';
//        }
//
//        $token = hash('sha256', $deals . 'salt');
//
//        return 'https://parent.jammschool.com/payment/link_payment?company_id=' . $user->bx_id . '&token=' . $token;
    }
}
