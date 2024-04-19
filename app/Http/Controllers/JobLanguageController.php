<?php

namespace App\Http\Controllers;

use App\Models\JobLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\JobController;
use App\Models\Job;
use Illuminate\Validation\Rule;
use Exception;

class JobLanguageController extends Controller
{
    public function arrayPostRules()
    {
        return  [
            'languages' => 'required|array',
            'languages.*.id' => 'exists:languages,id',
            'languages.*.level' => [Rule::in([1, 2, 3, 4, 5, 6])],
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobLanguage  $jobLanguage
     * @return \Illuminate\Http\Response
     */
    public function show(JobLanguage $jobLanguage)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        $job = Job::where("id", $id)->where("employer_id", $employer->id)->first();
        if (!$job) {
            return $this->respondInvalidInputs("Not found", "Only array data allowed", 404);
        }
        $items = $request->languages;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $data = [];
        foreach ($items as $lng) {
            $data[$lng['id']] = ['level' => $lng['level']];
        }
        $res = JobController::syncJobToMany($job->languages(), $data);
        if ($res) {
            return $this->respondWithSuccess("job languages updated");
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobLanguage  $jobLanguage
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobLanguage $jobLanguage)
    {
        //
    }
}
