<?php

namespace App\Http\Controllers;

use App\Models\ContactedEmployee;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployerController;
use Illuminate\Support\Facades\DB;
use App\Models\JobCost;
use App\Models\EmployeeJobCost;
use App\Mail\ContactEmployee;
use App\Mail\ContactEmployer;
use App\Models\Employer;

class ContactedEmployeeController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        $jobId = $request->job_id;
        $employeeId = $request->employee_id;
        $employee = Employee::where('id', $employeeId)->first();
        $job = $employer->jobs()->where("id", $jobId)->first();
        if (!$job) {
            return $this->respondInvalidInputs("not found", "job with provided id not found!", 404);
        }
        $isContactedBefore=ContactedEmployee::where('job_id',$jobId)->where('employee_id',$employeeId)->first();
        if($isContactedBefore){
            return $this->respondInvalidInputs("","employee is already contacted for this job !",409);
        }
        DB::beginTransaction();
        try {
            // contacted
            $contactedEmployee = new ContactedEmployee;
            $contactedEmployee->employee_id = $employeeId;
            $contactedEmployee->job_id = $jobId;
            $contactedEmployee->employer_id = $employer->id;
            $contactedEmployee->save();
            // cost
            $jobCost = new JobCost;
            $jobCost->employer_email = $employer->email;
            $jobCost->job_id = $jobId;
            $jobCost->price_id = 7;
            $jobCost->save();
            // employee job costs
            $employeJobcost = new EmployeeJobCost;
            $employeJobcost->job_cost_id = $jobCost->id;
            $employeJobcost->employee_id = $employeeId;
            $employeJobcost->save();
            DB::commit();
            \Mail::to($employee->email)->send(new ContactEmployee($job));
            return $this->respondWithSuccess($contactedEmployee);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactedEmployee  $contactedEmployee
     * @return \Illuminate\Http\Response
     */
    public function show(ContactedEmployee $contactedEmployee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactedEmployee  $contactedEmployee
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactedEmployee $contactedEmployee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContactedEmployee  $contactedEmployee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactedEmployee $contactedEmployee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactedEmployee  $contactedEmployee
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactedEmployee $contactedEmployee)
    {
        //
    }

    // API to get contact request for a user
    public function getContactRequests(){
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $contactRequests = $employee->contacted_jobs()
        ->with('employer', 'employer_category', 'contract_type')
        ->get();
        return $this->respondWithSuccess($contactRequests);
    }

    // API for user to respond to a request
    public function employeeContactResponse(Request $request){
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $contactedEntry = ContactedEmployee::where('id', $request->id)->where('employee_id', $employee->id)->first();
        $employer = Employer::where('id', $contactedEntry->employer_id)->first();
        $job = $employer->jobs()->where("id", $contactedEntry->job_id)->first();
        DB::beginTransaction();
        try {
            $contactedEntry->employee_response = $request->employee_response;
            $contactedEntry->save();
            DB::commit();
            if($request->employee_response == 'accepted'){
                // \Mail::to($employer->email)->send(new ContactEmployer($job));
                \Mail::to('granit.sejdiu@gmail.com')->send(new ContactEmployer($job));
            }
            return $this->respondWithSuccess($contactedEntry);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->respondWithError($e);
        }
    }
}
