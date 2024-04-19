<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;

class EmployeeJobSubCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $employee->job_sub_categories->makeHidden('pivot');
        return $this->respondWithSuccess($items);
    }

    public function arrayPostRules()
    {
        return  [
            'job_sub_categories.*' => 'exists:job_sub_categories,id', // check each item in the array
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->job_sub_categories;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $insertedRows = EmployeeController::attachEmployeeToMany($employee->job_sub_categories(), $items);

        if ($insertedRows != 0) {
            return $this->respondWithSuccess($employee->job_sub_categories, $insertedRows . " rows inserted");
        }
        return $this->respondInvalidInputs("Unknown error", "0 rows inserted");
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->job_sub_categories;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $res = EmployeeController::syncEmployeeToMany($employee->job_sub_categories(), $items);

        if ($res) {
            return $this->respondWithSuccess($employee->job_sub_categories);
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = explode(',', $request->query('job_sub_categories'));
        $res = EmployeeController::detachEmployeeToMany($employee->job_sub_categories(), $items);
        if ($res) {
            return $this->respondWithSuccess("Selected job_sub_categories deleted successfully ");
        }
        return $this->respondInvalidInputs("Unknown error", "Invalid input data!");
    }
}
