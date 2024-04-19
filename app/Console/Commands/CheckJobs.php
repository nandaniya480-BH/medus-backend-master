<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Job;

class CheckJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkjobs:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check jobs that need to be deleted every day and deactivate them';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jobs=Job::where('is_active','=',1)->get();
	  
        foreach ($jobs as $job)
        {
            $jobDate=Carbon::parse($job->created_at);
            $now  = Carbon::now();
            $length = $jobDate->diffInDays($now);
            if($length>35)
            {
                $job->is_active=false;
                $job->save;
            }
        }
        $this->info('Job check executed successfully');

        return Command::SUCCESS;
    }
}
