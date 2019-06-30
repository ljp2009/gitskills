<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Schedules\PkTaskSchedule as PTS;

class RunPKSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:runpkschedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'run pk task schedule now';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pts = new PTS;
        $pts->run();
    }
}
