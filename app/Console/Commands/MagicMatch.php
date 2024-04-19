<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ContractType;
use App\Models\Job;
use App\Http\Controllers\SuggestedEmployeeController;

class MagicMatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magicmatch:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively execute match alorithm every day.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jobs = Job::all();
        $matchJobsWithEmployees = new SuggestedEmployeeController();
        foreach ($jobs as $job) {
            $matchJobsWithEmployees->matchJob($job);
        }
        $this->info('Magic Match executed successfully');

        return Command::SUCCESS;
    }
}
