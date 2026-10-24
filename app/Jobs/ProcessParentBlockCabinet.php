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

class ProcessParentBlockCabinet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        try {
            $user = User::find($this->userId);
            if (!$user) {
                throw new \Exception('Not found user id: ' . $this->userId);
            }

            $user->disable = 1;
            $user->save();

            $moodle = new MoodleHelpers('core_user_update_users');

            foreach ($user->childrens as $children) {
                $moodle->deactivate_user($children->moodle_id);
            }

        } catch (\Exception $e) {
            Log::info('User ID: ' . $this->userId . ', Error block user cabinet: ' . $e->getMessage());
        }

    }
}
