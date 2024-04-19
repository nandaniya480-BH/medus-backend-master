<?php

namespace App\Http\Controllers;

use App\Models\JobFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobFavouriteController extends Controller
{

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
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $favoriteJobs = $employee->with('favourites')->with('favourites.employer')->get();
        return $this->respondWithSuccess($favoriteJobs);
    }
    public function update($id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $jobFavourite = JobFavourite::where('employee_id', $employee->id)->where('job_id', $id)->first();
        $created = false;
        if (!$jobFavourite) {
            $newJobFavourite = new JobFavourite;
            $newJobFavourite->employee_id = $employee->id;
            $newJobFavourite->job_id = $id;
            $created = $newJobFavourite->save();
        }
        if ($created) {
            return $this->respondWithSuccess("Favourite job added!");
        }
        $deleted = $jobFavourite->delete();
        if ($deleted) {
            return $this->respondWithSuccess("favourite job deleted");
        }
        return $this->respondInvalidInputs("cannot add or delete favoutire job!", "make sure that this job exists");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobFavourite  $jobFavourite
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobFavourite $jobFavourite)
    {
        //
    }
}
