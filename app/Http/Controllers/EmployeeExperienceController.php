<?php

namespace App\Http\Controllers;

use App\Models\EmployeeExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class EmployeeExperienceController extends Controller
{
    public function singelRowRule()
    {
        return [
            'employer_name' => 'required',
            'activitites' => 'required',
            'start_date' => 'required|date_format:Y-m-d'
        ];
    }

    public function multiRowsRule()
    {
        return [
            'work_experiences' => 'required|array',
            'work_experiences.*.employer_name' => 'required',
            'work_experiences.*.activitites' => 'required',
            'work_experiences.*.start_date' => 'required|date_format:Y-m-d'
        ];
    }

    public function updateSingelRowRule()
    {
        return [
            'employer_name' => 'sometimes',
            'activitites' => 'sometimes',
            'start_date' => 'sometimes|date_format:Y-m-d'
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $employee->work_experiences;
        return $this->respondWithSuccess($items);
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $items = $request->work_experiences;
        $item = $request->all();
        $deleteOld = $request->query('replace');
        if (is_array($items)) {
            $validator = $this->validateRequest($request, $this->multiRowsRule());
            if ($validator) {
                return $validator;
            }
            if ($deleteOld) {
                $employee->work_experiences()->delete();
            }
            $employee->work_experiences()->createMany(
                $items
            );
            return $this->respondWithSuccess($employee->work_experiences);
        }
        $validator = $this->validateRequest($request, $this->singelRowRule());
        if ($validator) {
            return $validator;
        }
        try {
            $newItem=$employee->work_experiences()->create($item);
        } catch (Exception $e) {
            return $this->respondInvalidInputs($e->getMessage(), "Invalid data", 404);
        }
        return $this->respondWithSuccess($newItem);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateRequest($request, $this->updateSingelRowRule());
        if ($validator) {
            return $validator;
        }
        $user = Auth::user();
        $employee = $user->employee;
        $item = $request->all();
        $workExperience = $employee->work_experiences()->where('id', $id)->first();
        if (!$workExperience) {
            return $this->respondInvalidInputs("not found", "resource with this id not found!");
        }
        $workExperience->update($item);
        return $this->respondWithSuccess($workExperience);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $workExperience = $employee->work_experiences()->where('id', $id)->first();
        if (!$workExperience) {
            return $this->respondInvalidInputs("not found", "resource with this id not found!");
        }
        $workExperience->delete();
        return $this->respondWithSuccess($workExperience,"resource deleted!");
    }
}
