<?php

namespace App\Http\Controllers;

use App\Models\JobEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobController;
use App\Models\Job;

class JobEducationController extends Controller
{
    public function arrayPostRules()
    {
        return  [
            // 'soft_skills' => 'required|array',
            'educations.*' => 'exists:educations,id', // check each item in the array
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


    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobEducation  $jobEducation
     * @return \Illuminate\Http\Response
     */
    public function show(JobEducation $jobEducation)
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
        $items = $request->educations;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $res = JobController::syncJobToMany($job->educations(), $items);
        if ($res) {
            return $this->respondWithSuccess("job educations updated");
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobEducation  $jobEducation
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobEducation $jobEducation)
    {
        //
    }
}
