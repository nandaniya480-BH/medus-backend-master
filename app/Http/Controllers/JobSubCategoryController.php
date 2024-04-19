<?php

namespace App\Http\Controllers;

use App\Models\JobSubCategory;
use Illuminate\Http\Request;

class JobSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = JobSubCategory::select('id', 'name', 'index', 'category_id', 'created_at')->get();
        return $this->respondWithSuccess($data);
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
     * @param  \App\Models\JobSubCategory  $jobSubCategory
     * @return \Illuminate\Http\Response
     */
    public function show(JobSubCategory $jobSubCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobSubCategory  $jobSubCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(JobSubCategory $jobSubCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobSubCategory  $jobSubCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobSubCategory $jobSubCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobSubCategory  $jobSubCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobSubCategory $jobSubCategory)
    {
        //
    }
}
