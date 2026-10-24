<?php

namespace App\Jobs;

use App\Helpers\FunctionHelpers;
use App\Helpers\MoodleHelpers;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessGetMoodle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->user->id);
        if (!$user) {
            throw new \Exception('[MoodleUpdate] User not fond id: ' . $this->user->id);
        }
        $moodl = new MoodleHelpers('core_user_get_users', $user);
        $moodl->get();
    }

}
