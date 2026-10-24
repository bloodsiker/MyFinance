<?php

namespace App\Jobs;

use App\Helpers\MoodleHelpers;
use App\Models\Children;
use App\Services\Moodle\MoodleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSyncFormatDates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $deadlines;

    /** @var MoodleHelpers */
    private $moodle_helpers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $deadlines)
    {
        $this->deadlines = $deadlines;
        $this->moodle_helpers = new MoodleHelpers();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->moodle_helpers->service()->sync_deadlines($this->deadlines);
    }
}
