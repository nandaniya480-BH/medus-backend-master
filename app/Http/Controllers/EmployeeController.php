<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SuggestedEmployeeController;
use Exception;

class EmployeeController extends Controller
{

    public function putRules()
    {

        return [
            'name' => 'sometimes|min:2',
            'last_name' => 'sometimes|min:2',
            'gender' => ['sometimes', Rule::in(['male', 'female', 'transgender', 'bigender', 'another'])],
            'age' => 'sometimes|date',
            'address' => "sometimes",
            'kantone_id' => "sometimes|numeric|exists:kantones,id",
            "plz_id" => "sometimes|numeric|exists:plzs,id",
            "ort" => "sometimes",

            'educations' => 'sometimes|array',
            'educations.*.id' => 'sometimes|exists:educations,id',
            'educations.*.start_date' => 'required|date_format:Y-m-d',

            'contract_types' => 'sometimes|array',
            'contract_types.*' => 'sometimes|exists:contract_types,id',

            'languages' => 'sometimes|array',
            'languages.*.id' => 'sometimes|exists:languages,id',
            'languages.*.level' => ['sometimes', Rule::in([1, 2, 3, 4, 5, 6])],

            'soft_skills' => 'sometimes|array',
            'soft_skills.*' => 'sometimes|exists:soft_skills,id',

            'job_sub_categories' => 'sometimes|array',
            'job_sub_categories.*' => 'sometimes|exists:job_sub_categories,id',

            'work_experiences' => 'sometimes|array',
            'work_experiences.*.employer_name' => 'sometimes',
            'work_experiences.*.position_title' => 'required',
            'work_experiences.*.activitites' => 'required',
            'work_experiences.*.start_date' => 'required|date_format:Y-m-d'
        ];
    }
    // Admin access
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 100);
        $items = Employee::select('id', 'name', 'email', 'last_name', 'phone', 'address')
            ->paginate($perPage, ['*'], 'page', $page);
        return $this->respondWithSuccess($items);
    }

    // employee access
    public function show(Request $request)
    {
        $included = explode(',', $request->query('with'));
        $user = Auth::user();
        try {
            $item = $user->employee;
            if (!$item) {
                return $this->respondInvalidInputs("Profile not found ", "Not found!", 404);
            }
            in_array('kantone', $included) && $item->kantone;
            in_array('plz', $included) && $item->plz;
            in_array('contract_types', $included) && $item->contract_types->makeHidden('pivot');
            in_array('soft_skills', $included) && $item->soft_skills->makeHidden('pivot');
            in_array('languages', $included) && $item->languages->makeHidden('pivot');
            in_array('educations', $included) && $item->educations->makeHidden('pivot');
            in_array('favourites', $included) && $item->favourites;
            in_array('work_experiences', $included) && $item->work_experiences;
            in_array('job_sub_categories', $included) && $item->job_sub_categories;
            in_array('documents', $included) && $item->documents;
            in_array('suggestetd_jobs', $included) && $item->suggestetd_jobs;
            return $this->respondWithSuccess($item);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs("Profile not found", "Not found!", 404);
        }
    }
    // employee access
    public function update(Request $request)
    {
        $user = Auth::user();
        $item = Employee::where('user_id', $user->id)->first();
        $validator = $this->validateRequest($request, $this->putRules());
        if ($validator) {
            return $validator;
        }
        if (!$item) {
            return $this->respondInvalidInputs("Profile not found ", "Not found!", 404);
        }
        if ($request->has('contract_types')) {
            $this->syncEmployeeToMany($item->contract_types(), $request->contract_types);
        }
        if ($request->has('soft_skills')) {
            $this->syncEmployeeToMany($item->soft_skills(), $request->soft_skills);
        }
        if ($request->has('languages')) {
            $data = [];
            foreach ($request->languages as $lng) {
                $data[$lng['id']] = ['level' => $lng['level']];
            }
            $this->syncEmployeeToMany($item->languages(), $data);
        }
        if ($request->has('educations')) {
            $data = [];
            foreach ($request->educations as $ed) {
                $data[$ed['id']] = ['start_date' => $ed['start_date'], 'end_date' => $ed['end_date']];
            }
            $this->syncEmployeeToMany($item->educations(), $data);
        }
        if ($request->has('job_sub_categories')) {
            $this->syncEmployeeToMany($item->job_sub_categories(), $request->job_sub_categories);
        }

        if ($request->has('work_experiences')) {
            $item->work_experiences()->delete();
            $item->work_experiences()->createMany(
                $request->work_experiences
            );
        }

        try {
            $item->fill(request()->all());
            $item->save();
            $suggestjobs = new SuggestedEmployeeController();
            $suggestjobs->matchEmployee($item);
            return $this->show($request);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs($e->getMessage(), "Invalid data", 404);
        }
    }
    // employee access
    public function destroy()
    {
        $user = Auth::user();
        $user->is_active = false;
        if ($user instanceof User) {
            $saved = $user->save();
            if ($saved) {
                return $this->respondWithSuccess("Employee Deactivated!");
            }
            return $this->respondInvalidInputs("User does not deactivated", 404);
        }
        return $this->respondInvalidInputs("User does not deactivated", 404);
    }
    // employee access
    public function getEmployeeFile($path)
    {
        $file_extension = pathinfo($path, PATHINFO_EXTENSION);
        $file_content = Storage::disk('public')->get($path);
        switch ($file_extension) {
            case 'pdf':
                $content_type = 'application/pdf';
                break;
            case 'doc':
            case 'docx':
                $content_type = 'application/msword';
                break;
            case 'xls':
            case 'xlsx':
                $content_type = 'application/vnd.ms-excel';
                break;
            default:
                $content_type = 'application/octet-stream';
                break;
        }
        if ($file_content) {
            return response($file_content)
                ->header('Content-Type', $content_type);
        }
        return response()->json("file not found");
    }
    // employee access
    public function uploadProfileImage(Request $request)
    {
        $user = Auth::user();
        if (!$request->hasFile("image")) {
            return $this->respondInvalidInputs(null, "No image uploaded", 404);
        }
        try {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'employee_images';
            $path = $image->storeAs($directory, $image_name, 'public');
            $item = $user->employee;
            $file_exists = Storage::disk('public')->exists($item->image_url);
            if ($file_exists)
                Storage::disk('public')->delete($item->image_url);
            $item->image_url = $path;
            $item->save();
            return $this->respondWithSuccess($path);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs($e->getMessage(), 500);
        }
    }

    public static function attachEmployeeToMany($relation, $items = [])
    {
        $count = 0;
        foreach ($items as $item) {
            try {
                $count += 1;
                $relation->attach($item);
            } catch (Exception $e) {
                $count -= 1;
            }
        }
        return $count;
    }
    public static function syncEmployeeToMany($realtion, $items = [])
    {
        try {
            $realtion->sync($items);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    static function detachEmployeeToMany($relation, $items = [])
    {
        try {
            $relation->detach($items);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getSuggestedJobs(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $jobs = $employee->suggestetd_jobs()->with('employer')->get();
        return $this->respondWithSuccess($jobs);
    }
}