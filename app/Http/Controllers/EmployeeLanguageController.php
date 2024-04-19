<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLanguage;
use Illuminate\Http\Request;;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\EmployeeController;

class EmployeeLanguageController extends Controller
{
    public function arrayPostRules()
    {
        return  [
            'languages' => 'required|array',
            'languages.*.id' => 'exists:languages,id',
            'languages.*.level' => [Rule::in([1, 2, 3, 4, 5, 6])],
        ];
    }
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $employee->languages->makeHidden('pivot');
        return $this->respondWithSuccess($items);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->languages;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $data = [];
        foreach ($items as $item) {
            array_push($data, [$item['id'] => ['level' => $item['level']]]);
        }
        $insertedRows = EmployeeController::attachEmployeeToMany($employee->languages(), $data);

        if ($insertedRows != 0) {
            return $this->respondWithSuccess($employee->languages, $insertedRows . " rows inserted");
        }
        return $this->respondInvalidInputs("Unknown error", "0 rows inserted");
    }


    public function update(Request $request, EmployeeLanguage $employeeLanguage)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->languages;
        if (!is_array($items)) {
            return $this->respondInvalidInputs("Error data format!", "Only array data allowed");
        }
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $data = [];
        foreach ($items as $item) {
            $data[$item['id']] = ['level' => $item['level']];
        }
        $res = EmployeeController::syncEmployeeToMany($employee->languages(), $data);

        if ($res) {
            return $this->respondWithSuccess($employee->languages);
        }
        return $this->respondInvalidInputs("Unknown error", "");
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = explode(',', $request->query('languages'));
        $res = EmployeeController::detachEmployeeToMany($employee->languages(), $items);
        if ($res) {
            return $this->respondWithSuccess("Selected languages deleted successfully ");
        }
        return $this->respondInvalidInputs("Unknown error", "Invalid input data!");
    }
}
