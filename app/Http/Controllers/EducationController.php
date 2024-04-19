<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    private $postRules = [
        'name' => 'required|min:2',
    ];
    private $putRules = [
        'name' => 'sometimes|min:2',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Education::select('id', 'name', 'index', 'created_at')->get();
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
        $validator = $this->validateRequest($request, $this->postRules);
        if ($validator) {
            return $this->respondInvalidInputs($validator->errors(), "Invalid input data!");
        }
        $softSkill = new Education($request->all());
        try {
            $s = $softSkill->save();
            return $this->respondWithSuccess($s);
        } catch (ModelNotFoundException $e) {
            return $this->respondWithError("Internal server error!", 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function show(Education $education)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function edit(Education $education)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, $this->putRules);
        if ($validator) {
            return $this->respondInvalidInputs($validator->errors(), "Invalid input data!");
        }
        try {
            $item = Education::findOrFail($id);
            $item->name = $request->has('name') ? $request->name : $item->name;
            $item->save();
            return $this->respondWithSuccess("Updated!");
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs('Item with id ' . $id . " not found", "Not found!", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Education  $education
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $education = Education::find($id);
        $education->delete();
        return $this->respondWithSuccess("Aus- und Weiterbildung erfolgreich gelöscht!");
    }
}
