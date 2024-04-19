<?php

namespace App\Http\Controllers;

use App\Models\Kantone;
use Illuminate\Http\Request;

class KantoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Kantone::select('id', 'name', 'index', 'short_name', 'created_at')->get();
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
     * @param  \App\Models\Kantone  $kantone
     * @return \Illuminate\Http\Response
     */
    public function show(Kantone $kantone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kantone  $kantone
     * @return \Illuminate\Http\Response
     */
    public function edit(Kantone $kantone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kantone  $kantone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kantone $kantone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kantone  $kantone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kantone $kantone)
    {
        //
    }
}
