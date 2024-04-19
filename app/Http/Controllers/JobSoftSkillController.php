<?php

namespace App\Http\Controllers;

use App\Models\JobSoftSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobController;
use App\Models\Job;

class JobSoftSkillController extends Controller
{
    public function arrayPostRules()
    {
        return  [
            // 'soft_skills' => 'required|array',
            'soft_skills.*' => 'exists:soft_skills,id', // check each item in the array
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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


    public function show(JobSoftSkill $jobSoftSkill)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        $job = Job::where("id", $id)->where("employer_id", $employer->id)->first();
        if (!$job) {
            return $this->respondInvalidInputs("Not found", "job not found", 404);
        }
        $items = $request->soft_skills;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $res = JobController::syncJobToMany($job->soft_skills(), $items);
        if ($res) {
            return $this->respondWithSuccess("job soft skills updated");
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobSoftSkill  $jobSoftSkill
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobSoftSkill $jobSoftSkill)
    {
        //
    }
}
