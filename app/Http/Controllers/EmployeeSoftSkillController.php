<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;

class EmployeeSoftSkillController extends Controller
{

    public function arrayPostRules()
    {
        return  [
            // 'soft_skills' => 'required|array',
            'soft_skills.*' => 'exists:soft_skills,id', // check each item in the array
        ];
    }
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $employee->soft_skills->makeHidden('pivot');
        return $this->respondWithSuccess($items);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->soft_skills;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $insertedRows = EmployeeController::attachEmployeeToMany($employee->soft_skills(), $items);

        if ($insertedRows != 0) {
            return $this->respondWithSuccess($employee->soft_skills, $insertedRows . " rows inserted");
        }
        return $this->respondInvalidInputs("Unknown error", "0 rows inserted");
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->soft_skills;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $res = EmployeeController::syncEmployeeToMany($employee->soft_skills(), $items);

        if ($res) {
            return $this->respondWithSuccess($employee->soft_skills,"Updated successfully");
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = explode(',', $request->query('soft_skills'));
        $res = EmployeeController::detachEmployeeToMany($employee->soft_skills(), $items);
        if ($res) {
            return $this->respondWithSuccess("Selected soft skills deleted successfully ");
        }
        return $this->respondInvalidInputs("Unknown error", "Invalid input data!");
    }
}
