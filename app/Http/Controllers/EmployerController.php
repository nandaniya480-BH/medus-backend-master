<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Http\Controllers\Validations;
use App\Models\Job;
use Exception;
use App\Mail\CompanyActivated;

class EmployerController extends Controller
{
    public function putRules()
    {
        return [
            'name' => 'sometimes|min:2',
            'kantone_id' => "sometimes|numeric|exists:kantones,id",
            "plz_id" => "sometimes|numeric|exists:plzs,id",
            "ort" => "sometimes",
            "category_id" => "sometimes|numeric|exists:employer_categories,id",
        ];
    }
    //admin access
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
        $items = Employer::select('id', 'name', 'logo_url', 'email', 'phone', 'c_p_name', 'c_p_surname', 'c_p_email')
            ->paginate($perPage, ['*'], 'page', $page);
        return $this->respondWithSuccess($items);
    }
    // employer access
    public function show(Request $request)
    {
        $included = explode(',', $request->query('with'));
        $user = Auth::user();
        $item = $this->getEmployerProfile($user);
        if (!$item) {
            return $this->respondInvalidInputs("Profile not found ", "Not found!", 404);
        }
        in_array('kantone', $included) && $item->kantone;
        in_array('plz', $included) && $item->plz;
        in_array('category', $included) && $item->category;
        in_array('jobs', $included) && $item->jobs;
        return $this->respondWithSuccess($item);
    }
    //employer access
    public function update(Request $request, Employer $employer)
    {
        $user = Auth::user();
        $item = $user->employer;
        $validator = $this->validateRequest($request, $this->putRules());
        if ($validator) {
            return $validator;
        }
        if (!$item) {
            return $this->respondInvalidInputs("Profile not found ", "Not found!", 404);
        }
        try {
            $item->fill(request()->all());
            $item->save();
            return $this->show($request);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs($e->getMessage(), "Invalid data", 404);
        }
    }
    //employer
    public function destroy(Employer $employer)
    {
        $user = Auth::user();
        $user->is_active = false;
        if ($user instanceof User) {
            $saved = $user->save();
            if ($saved) {
                return $this->respondWithSuccess("User deactivated!");
            }
            return $this->respondInvalidInputs("User does not deactivated", 404);
        }
        return $this->respondInvalidInputs("User does not deactivated", 404);
    }

    //employer access
    public function uploadProfileImage(Request $request)
    {
        $user = Auth::user();
        if (!$request->hasFile("image")) {
            return $this->respondInvalidInputs(null, "No image uploaded", 404);
        }
        try {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'employer_images';
            $path = $image->storeAs($directory, $image_name, 'public');
            $item = $user->employer;
            $file_exists = Storage::disk('public')->exists($item->logo_url);
            if ($file_exists)
                Storage::disk('public')->delete($item->logo_url);
            $item->logo_url = $path;
            $item->save();
            return $this->respondWithSuccess($path);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs($e->getMessage(), 500);
        }
    }

    public function getEmployerFile(Request $request)
    {
        $path = $request->query('url');
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
                $content_type = 'image';
                break;
        }
        if ($file_content) {
            return response($file_content)
                ->header('Content-Type', $content_type);
        }
        return response()->json("file not found");
    }

    public static function getEmployerProfile(User $user)
    {
        if ($user->role == "employer") {
            $parentUser = User::where('id', $user->parent_id)->first();
            if (!$parentUser) {
                return null;
            }
            $employer = $parentUser->employer;
            return $employer;
        }
        $employer = $user->employer;
        return $employer;
    }

    public function createEmployer(Request $request)
    {
        $parentUser = Auth::user();
        $validator = Validations::registerEmployer($request);
        if ($validator->fails()) {
            return $this->respondInvalidInputs($validator->errors(), "Invalid input data!");
        }
        if ($parentUser->parent_id || $parentUser->role != "employeradmin") {
            return $this->respondInvalidInputs("You are not authorized to add new user", "Not authenticate", "403");
        }
        $user = new User([
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role" => "employer",
            "is_active" => 1,
            "parent_id" => $parentUser->id
        ]);
        try {
            $user->save();
            return $this->respondWithSuccess($user, "employer created successfuly");
        } catch (Exception $e) {
            return $this->respondWithError($e);
        }
    }

    public function getCorrespondingEmployers()
    {
        $parentUser = Auth::user();
        $employerList = User::where('parent_id', $parentUser->id)->get();
        
        return $this->respondWithSuccess($employerList);
    }

    public function deleteCorrespondingEmployerAccount($id)
    {
        $parentUser = Auth::user();
        $employer = User::where('parent_id', $parentUser->id)->where('id', $id)->first();
        $employer->delete();
        
        return $this->respondWithSuccess($employer, 'Account erfolgreich gelöscht');
    }

    public function getPublicEmployerProfile($slug)
    {
        $item = Employer::where("slug", $slug)->first();
        if (!$item) {
            return $this->respondInvalidInputs("Profile not found ", "Not found!", 404);
        }
        $item->kantone;
        $item->plz;
        $item->category;
        $jobs = Job::where("employer_id", $item->id)
            ->where('is_active', 1)
            ->with(['job_details', 'kantone:id,name', 'contract_type:id,name', 'job_category:id,name', 'job_subcategory:id,name', 'plz:id,plz,ort,berzirk'])
            ->get();
        $item->jobs = $jobs;
        return $this->respondWithSuccess($item);
    }

    public function getAnnonymousEmployeeProfile(Request $request, $id){
        $included = explode(',', $request->query('with'));
        $user = User::find($id);
        try {
            $item = $user->annonymous_employee;
            if (!$item) {
                return $this->respondInvalidInputs("Profile not found ", "Not found!", 404);
            }
            in_array('kantone', $included) && $item->kantone;
            in_array('plz', $included) && $item->plz;
            in_array('contract_types', $included) && $item->contract_types->makeHidden('pivot');
            in_array('soft_skills', $included) && $item->soft_skills->makeHidden('pivot');
            in_array('languages', $included) && $item->languages->makeHidden('pivot');
            in_array('educations', $included) && $item->educations->makeHidden('pivot');
            in_array('work_experiences', $included) && $item->work_experiences;
            in_array('job_sub_categories', $included) && $item->job_sub_categories;
            return $this->respondWithSuccess($item);
        } catch (ModelNotFoundException $e) {
            return $this->respondInvalidInputs("Profile not found", "Not found!", 404);
        }
    }

    // Admin functions related to Employers
    public function getAllEmployers(){
        $employers = Employer::all();
        return $this->respondWithSuccess($employers);
    }

    public function activateEmployerProfile(Request $request ,$id){
        $employer = Employer::where('id', $id)->first();
        $employer->is_active = $request->is_active;
        $employer->save();
        // dd($employer);
        // \Mail::to($employer->email)->send(new CompanyActivated($employer));
        \Mail::to('granit.sejdiu@gmail.com')->send(new CompanyActivated($employer));
        return $this->respondWithSuccess($employer, 'Unternehmen wurde erfolgreich Aktiviert!');
    }

    public function getNoneActivatedEmployers(){
        $employers = Employer::where('role', 'employeradmin')->where('is_active', 0)->get();
        return $this->respondWithSuccess($employers);
    }
}
