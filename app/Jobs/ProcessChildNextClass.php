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

class ProcessChildNextClass implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $child;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($child)
    {
        $this->child = $child;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        try {
            $child = Children::find($this->child->id);
            if (!$child) {
                throw new \Exception('Not found child id: ' . $this->child->id);
            }

            if ($child->class <= 11 && $child->moodle_id) {
                $child->class += 1;

                $lessonsJson = file_get_contents(public_path('dist/json/lesson.json'));

                $classes = json_decode($lessonsJson, true);
                $child->lesson = json_encode($classes[$child->class]);

                $child->save();

                $moodle = new MoodleHelpers('core_user_update_users');
                $moodle->updateMoodleUserByCron($child);
            }
        } catch (\Exception $e) {
            Log::info('Child ID: ' . $this->child->id . ', Error update next class to Moodle: ' . $e->getMessage());
        }

    }
}
