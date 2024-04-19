<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;
use App\Models\EmployeeEducation;

class EmployeeEducationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $employee->educations->makeHidden('pivot');
        return $this->respondWithSuccess($items);
    }

    public function arrayPostRules()
    {
        return  [
            'start_date' => 'required|date_format:Y-m-d',
            'education_id' => 'required|exists:educations,id',
        ];
    }

    public function updateRules()
    {
        return  [
            'start_date' => 'sometimes|date_format:Y-m-d',
            'education_id' => 'sometimes|exists:educations,id',
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $validator = $this->validateRequest($request, $this->arrayPostRules());
        if ($validator) {
            return $validator;
        }
        $id = $request->education_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date ? $request->end_date : null;
        $exists = EmployeeEducation::where('employee_id', $employee->id)->where('education_id', $id)->first();
        if ($exists) {
            return $this->respondInvalidInputs("Die gewählte Aus- und Weiterbildung existiert bereits auf ihren Profil", "Invalid data", 409);
        }
        $emEducation = new EmployeeEducation;
        $res = $emEducation->create([
            "education_id" => $id,
            "employee_id" => $employee->id,
            "start_date" => $start_date,
            "end_date" => $end_date
        ]);
        return $this->respondWithSuccess($employee->educations()
            ->where('education_id', $id)
            ->first()->makeHidden('pivot'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $item = EmployeeEducation::where('employee_id', $employee->id)->where('education_id', $id)->first();
        if (!$item) {
            return $this->respondInvalidInputs("", "Item not found", 404);
        }
        return $this->respondWithSuccess($employee->educations()
            ->where('education_id', $id)
            ->first()->makeHidden('pivot'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $validator = $this->validateRequest($request, $this->updateRules());
        if ($validator) {
            return $validator;
        }
        $item = EmployeeEducation::where('employee_id', $employee->id)->where('id', $id)->first();
        if (!$item) {
            return $this->respondInvalidInputs("item not found", "", 404);
        }
        $item->start_date = $request->start_date ? $request->start_date : $item->start_date;
        $item->education_id = $request->education_id ? $request->education_id : $item->education_id;
        if ($request->has("end_date")) {
            $item->end_date = $request->end_date;
        }
        $exists = EmployeeEducation::where('employee_id', $employee->id)
            ->where('id', "!=", $id)
            ->where('education_id', $request->education_id)
            ->first();
        if ($exists) {
            return $this->respondInvalidInputs("Die gewählte Aus- und Weiterbildung existiert bereits auf ihren Profil", "Invalid data", 409);
        }
        $item->save();
        return $this->respondWithSuccess($employee->educations()
            ->where('education_id', $item->education_id)
            ->first()->makeHidden('pivot'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $item = EmployeeEducation::where('employee_id', $employee->id)->where('id', $id)->first();
        if (!$item) {
            return $this->respondInvalidInputs("Item not found", "Invalid input data!", 404);
        }
        $item->delete();
        return $this->respondWithSuccess($item, "Item deleted succesfully");
    }
}
