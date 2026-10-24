<?php

namespace App\Jobs;

use App\Helpers\FunctionHelpers;
use App\Helpers\MoodleHelpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSendingMoodle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 180;

    protected $user;
    protected $moodle_helpers;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->moodle_helpers = new MoodleHelpers('');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //TODO: finish testing this methods and replace old
        //$this->moodle_helpers->createMoodleCabinetsForChildrens($this->user);
        //$this->moodle_helpers->addChildrensToCohorts($this->user);
        //$this->moodle_helpers->jammws_update_completion_goals_multi($this->user);
        //$this->moodle_helpers->send_deadlines_to_childrens($user);

        $moodle = new MoodleHelpers('core_user_create_users', $this->user);
        $moodle->createMoodleUser();
    }

}
