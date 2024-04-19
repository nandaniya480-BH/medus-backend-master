<?php

namespace App\Http\Controllers;

use App\Models\SoftSkill;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Validations;

class SoftSkillController extends Controller
{
    private $postRules = [
        'name' => 'required|min:2|unique:soft_skills',
        'index' => 'numeric',
        'approved' => 'numeric',
    ];
    private $putRules = [
        'name' => 'sometimes|min:2',
        'index' => 'numeric',
        'approved' => 'numeric',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SoftSkill::select('id', 'name', 'approved', 'index', 'created_at')->get();
        return $this->respondWithSuccess($data);
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
        $softSkill = new SoftSkill($request->all());
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
     * @param  \App\Models\SoftSkill  $softSkill
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $item = SoftSkill::findOrFail($id);
            return $this->respondWithSuccess($item);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs('Item with id ' . $id . " not found", "Not found!", 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoftSkill  $softSkill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, $this->putRules);
        if ($validator) {
            return $this->respondInvalidInputs($validator->errors(), "Invalid input data!");
        }
        try {
            $item = SoftSkill::find($id);
            $item->name = $request->has('name') ? $request->name : $item->name;
            $item->index = $request->has('index') ? $request->index : $item->index;
            $item->approved = $request->has('approved') ? $request->approved : $item->approved;
            $item->save();
            return $this->respondWithSuccess("Updated!");
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs('Item with id ' . $id . " not found", "Not found!", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoftSkill  $softSkill
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $softSkill = SoftSkill::find($id);
        $softSkill->delete();
        return $this->respondWithSuccess("Soft Skill erfolgreich gelöscht!");
    }

}
