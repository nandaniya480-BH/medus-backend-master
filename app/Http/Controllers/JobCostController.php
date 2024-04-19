<?php

namespace App\Http\Controllers;

use App\Models\JobCost;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployerController;
use App\Models\Employer;
use Illuminate\Support\Facades\DB;
use Exception;

class JobCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getEmployerYearsCosts()
    {
        $user = Auth::user();
        $collection = [];
        $employer = EmployerController::getEmployerProfile($user);
        $jobs = $employer->jobs()->select("id", "job_id", "job_title", 'created_at')->with("prices")->orderByDesc('created_at')->get();
        foreach ($jobs as $job) {
            $year = Carbon::parse($job->created_at)->year;
            $month = Carbon::parse($job->created_at)->month;
            $job->year = $year;
            $job->month = $month;

            if (!isset($collection[$year])) {
                $collection[$year] = [];
            }

            if (!isset($collection[$year][$month])) {
                $collection[$year][$month] = [];
            }

            $collection[$year][$month][] = $job;
        }
        return $this->respondWithSuccess($collection);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobCost  $jobCost
     * @return \Illuminate\Http\Response
     */
    public function show(JobCost $jobCost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobCost  $jobCost
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCost $jobCost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobCost  $jobCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCost $jobCost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobCost  $jobCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobCost $jobCost)
    {
        //
    }
}
