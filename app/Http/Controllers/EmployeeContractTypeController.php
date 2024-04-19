<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;

class EmployeeContractTypeController extends Controller
{

    public function arrayPostRules()
    {
        return  [
            'contract_types.*' => 'exists:contract_types,id', // check each item in the array
        ];
    }
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $employee->contract_types->makeHidden('pivot');
        return $this->respondWithSuccess($items);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->contract_types;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $insertedRows = EmployeeController::attachEmployeeToMany($employee->contract_types(), $items);

        if ($insertedRows != 0) {
            return $this->respondWithSuccess($employee->contract_types, $insertedRows . " rows inserted");
        }
        return $this->respondInvalidInputs("Unknown error", "0 rows inserted");
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->contract_types;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $res = EmployeeController::syncEmployeeToMany($employee->contract_types(), $items);

        if ($res) {
            return $this->respondWithSuccess($employee->contract_types);
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = explode(',', $request->query('contract_types'));
        $res = EmployeeController::detachEmployeeToMany($employee->contract_types(), $items);
        if ($res) {
            return $this->respondWithSuccess("Selected contract types deleted successfully ");
        }
        return $this->respondInvalidInputs("Unknown error", "Invalid input data!");
    }
}
