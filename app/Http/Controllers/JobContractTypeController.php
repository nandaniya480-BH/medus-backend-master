<?php

namespace App\Http\Controllers;

use App\Models\JobContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobController;
use App\Models\Job;

class JobContractTypeController extends Controller
{
    public function arrayPostRules()
    {
        return  [
            // 'soft_skills' => 'required|array',
            'contract_types.*' => 'exists:contract_types,id', // check each item in the array
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobContractType  $jobContractType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        $job = Job::where("id", $id)->where("employer_id", $employer->id)->first();
        if (!$job) {
            return $this->respondInvalidInputs("Not found", "job not found", 404);
        }
        $items = $request->contract_types;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $res = JobController::syncJobToMany($job->contract_types(), $items);
        if ($res) {
            return $this->respondWithSuccess("job contract type updated");
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobContractType  $jobContractType
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobContractType $jobContractType)
    {
        //
    }
}
