<?php

namespace App\Http\Controllers;

use App\Models\EmployerCategory;
use Illuminate\Http\Request;

class EmployerCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = EmployerCategory::select('id', 'name', 'index', 'created_at')->get();
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
     * @param  \App\Models\EmployerCategory  $employerCategory
     * @return \Illuminate\Http\Response
     */
    public function show(EmployerCategory $employerCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployerCategory  $employerCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployerCategory $employerCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployerCategory  $employerCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployerCategory $employerCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployerCategory  $employerCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployerCategory $employerCategory)
    {
        //
    }
}
