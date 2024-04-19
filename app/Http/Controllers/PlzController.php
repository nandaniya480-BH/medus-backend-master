<?php

namespace App\Http\Controllers;

use App\Models\Plz;
use Illuminate\Http\Request;

class PlzController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Plz::select('id', 'plz', 'ort', 'kantone_id', 'latitude', 'longitude', 'berzirk')
            ->with(["kantone:id,name"])
            ->get();
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
     * @param  \App\Models\Plz  $plz
     * @return \Illuminate\Http\Response
     */
    public function show(Plz $plz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plz  $plz
     * @return \Illuminate\Http\Response
     */
    public function edit(Plz $plz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plz  $plz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plz $plz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plz  $plz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plz $plz)
    {
        //
    }
}
