<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Job;
use App\Models\JobCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\EmployerController;
use Illuminate\Validation\Rule;
use App\Models\JobDetail;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SuggestedEmployeeController;

class JobController extends Controller
{
    public function createJobRules()
    {

        return [
            'job_title' => 'required|min:3',
            'ort' => 'required|exists:plzs,ort',
            'position' => ['required', Rule::in([0, 1, 2, 3])],
            'work_experience' => ['required', Rule::in([0, 1, 2, 3, 4, 5])],
            'work_time' => ['required', Rule::in([0, 1, 2, 3])],
            'job_category_id' => 'required|exists:job_categories,id',
            'job_subcategory_id' => 'required|exists:job_sub_categories,id',
            'kantone_id' => "required|numeric|exists:kantones,id",
            "plz_id" => "required|numeric|exists:plzs,id",
            "by_arrangement" => ['sometimes', Rule::in([0, 1])],
            // "c_person_name" => "required",
            // "c_person_last_name" => "required",
            // "c_person_email" => "required",
            // "c_person_phone" => "required",
            // "c_person_fax" => "required",

        ];
    }
    public function updateJobRules()
    {
        return [
            'job_title' => 'sometimes|min:3',
            'ort' => 'sometimes|exists:plzs,ort',
            'position' => ['sometimes', Rule::in([0, 1, 2, 3])],
            'work_experience' => ['sometimes', Rule::in([0, 1, 2, 3, 4, 5])],
            'work_time' => ['sometimes', Rule::in([0, 1, 2, 3])],
            'job_category_id' => 'sometimes|numeric|exists:job_categories,id',
            'job_subcategory_id' => 'sometimes|numeric|exists:job_sub_categories,id',
            'kantone_id' => "sometimes|numeric|exists:kantones,id",
            "plz_id" => "sometimes|numeric|exists:plzs,id",
            "by_arrangement" => ['sometimes', Rule::in([0, 1])],
            "c_person_name" => "sometimes",
            "c_person_last_name" => "sometimes",
            "c_person_email" => "sometimes",
            "c_person_phone" => "sometimes",
            "c_person_fax" => "sometimes",

        ];
    }

    // employer route
    public function getEmployerJobs(Request $request)
    {
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        if (!$employer) {
            return $this->respondInvalidInputs("not found", "parent user not found", 404);
        }
        $jobs = $employer->jobs;
        return $this->respondWithSuccess($jobs);
    }
    // public route
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 60);
        $workTime = $request->query('work_time');
        $position = $request->query('position');
        $distance = $request->query('distance');
        $workloadFrom = $request->query('workload_from');
        $workloadTo = $request->query('workload_to');
        $employerCategories = $request->query('employer_category_id', []);
        $contractTypes = $request->query('contract_type_id', []);
        $jobCategories = $request->query('job_categories', []);
        $jobSubcategories = $request->query('job_subcategories', []);
        $jobRegions = $request->query('job_regions', []);
        $topJobs = $request->query('on_top');
        $slug = $request->query('slug');
        $plz = $request->query('plz');
        $title = $request->query('job_title');
        $location = $request->query('ort');
        $homepagePlz = $request->query('homepage_plz');
        $query = Job::query()->where("is_active", "1");
        $start = microtime(true);
        if ($topJobs) {
            $query->where("on_top", $topJobs);
        }
        if ($workTime) {
            if ($workTime == 1) {
                $query->whereIn('work_time', [1, 3]);
            } else if ($workTime == 2) {
                $query->whereIn('work_time', [2, 3]);
            } else if ($workTime == 3) {
                $query->whereIn('work_time', [1, 2, 3]);
            }
        }
        if ($position) {
            if ($position == 1) {
                $query->whereIn('position', [1, 3]);
            } else if ($position == 2) {
                $query->whereIn('position', [2, 3]);
            } else if ($position == 3) {
                $query->whereIn('position', [1, 2, 3]);
            }
        }
        if ($title) {
            $query->where('job_title', 'LIKE', '%'.$title.'%');
        }
        if ($location) {
            $query->where('ort', 'LIKE', '%'.$location.'%');
        }
        if ($homepagePlz) {
            $query->where('plz_id', $homepagePlz);
        }
        if ($workloadFrom && $workloadTo) {
            $query->where('workload_from', "<=", $workloadTo)
                ->where('workload_to', ">=", $workloadFrom);
        }
        if ($distance && $plz) {
            $distancePlzs = $this->getDistanceResult($plz, (float) $distance);
            $query->whereIn('plz_id', $distancePlzs);
        }
        if ($employerCategories) {
            $query->whereIn("employer_category_id", explode(',', $employerCategories));
        }
        if ($contractTypes) {
            $query->whereIn("contract_type_id", explode(',', $contractTypes));
        }
        if ($jobCategories) {
            $query->whereIn("job_category_id", explode(',', $jobCategories));
        }
        if ($jobSubcategories) {
            $query->whereIn("job_subcategory_id", explode(',', $jobSubcategories));
        }
        if ($jobRegions) {
            $query->whereIn("kantone_id", explode(',', $jobRegions));
        }


        $items = $query->select(['id', 'job_title', 'slug', 'ort', "kantone_id", "contract_type_id", "workload_from", "workload_to", "on_top", "created_at", "is_active"])
            ->with(["job_details:job_id,start_date,job_description,by_arrangement,job_url,job_file_url"])
            ->with(["kantone:id,name"])
            ->with(["contract_type:id,name"])
            ->orderBy('on_top', 'desc')
            ->orderBy('id', 'desc')
            ->paginate((int) $perPage, ['*'], 'page', $page);
        $time = microtime(true) - $start;
        return $this->respondWithSuccess($items, $time . " seconds");
    }
    public function saveJob($jobData, $jobDetailData, $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $job = Job::create($jobData);
            $jobDetails = JobDetail::create(array_merge($jobDetailData, ["job_id" => $job->id]));
            if ($request->has('soft_skills')) {
                $this->syncJobToMany($job->soft_skills(), json_decode($request->soft_skills, true));
            }
            if ($request->has('languages')) {
                $data = [];
                foreach (json_decode($request->languages, true) as $lng) {
                    $data[$lng['id']] = ['level' => $lng['level']];
                }
                $this->syncJobToMany($job->languages(), $data);
            }
            if ($request->has('prices')) {
                $data = [];
                foreach (json_decode($request->prices, true) as $prc) {
                    $data[$prc] = ['employer_email' => $user->email];
                }
                $this->syncJobToMany($job->prices(), $data);
            }
            if ($request->has('educations')) {
                $this->syncJobToMany($job->educations(), json_decode($request->educations, true));
            }
            DB::commit();
            return ["job_id" => $job->id, "result" => true];
        } catch (\Exception $e) {
            DB::rollback();
            return ["error" => $e, "result" => false];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateRequest($request, $this->createJobRules());
        if ($validator) {
            return $validator;
        }
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        if (!$employer) {
            $this->respondInvalidInputs("Corresponding employer profile not found!");
        }
        $uid = Str::uuid();
        $jobP1 = [
            "job_id" => $uid,
            "slug" => Str::slug($request->job_title . '-' . $uid, "-"),
            "employer_id" => $employer->id,
            "employer_category_id" => $employer->category->id,
            "is_active" => 1,
            "is_promoted" => in_array(5, json_decode($request->prices ?? "[]", true)),
            "on_top" => in_array(6, json_decode($request->prices ?? "[]", true))
        ];
        $jobData = array_merge($request->all(), $jobP1);
        $job_file_url = "";
        if ($request->hasFile("job_file")) {
            $image = $request->file('job_file');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'job_files';
            $job_file_url = $image->storeAs($directory, $image_name, 'public');
        }
        $jobDetailData = array_merge($request->all(), ["job_file_url" => $job_file_url]);
        $jobDetailData['start_date'] = null;
        if ($request->has('start_date') && $request->start_date != null) {
            $jobDetailData['start_date'] = date('Y-m-d', strtotime($request->start_date));
        }
        $saveResult = $this->saveJob($jobData, $jobDetailData, $request);
        if (!$saveResult["result"]) {
            if ($job_file_url) {
                Storage::disk('public')->delete($job_file_url);
            }
            return $this->respondWithError($saveResult["error"]->getMessage(), "something went wrong");
        }
        $jobId = $saveResult["job_id"];
        $suggestEmployees = new SuggestedEmployeeController();
        $newJob = Job::where('id', $jobId)->first();
        if ($newJob) {
            $suggestEmployees->matchJob($newJob);
        }

        return $this->show($request, $jobId);
    }

    public function show(Request $request, $id)
    {
        $included = explode(',', $request->query('with'));
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        if (!$employer) {
            $this->respondInvalidInputs("Corresponding employer profile not found!");
        }
        $item = $employer->jobs()->where('id', $id)->first();
        if (!$item) {
            return $this->respondInvalidInputs("Not found", "job not found");
        }
        in_array('kantone', $included) && $item->kantone;
        in_array('plz', $included) && $item->plz;
        in_array('soft_skills', $included) && $item->soft_skills->makeHidden('pivot');
        in_array('languages', $included) && $item->languages->makeHidden('pivot');
        in_array('educations', $included) && $item->educations->makeHidden('pivot');
        in_array('prices', $included) && $item->prices->makeHidden('pivot');
        in_array("employer", $included) && $item->employer;
        in_array("employer_category", $included) && $item->employer_category;
        in_array("job_category", $included) && $item->job_category;
        in_array("job_subcategory", $included) && $item->job_subcategory;
        in_array("contract_type", $included) && $item->contract_type;
        in_array("suggestetd_employees", $included) && $item->suggestetd_employees;
        in_array("favourites", $included) && $item->favourites;
        in_array("contacted_employees", $included) && $item->contacted_employees;

        $jobDetail = JobDetail::where("job_id", $item->id)->first();
        $job = collect($item);
        $jobDetails = collect($jobDetail);
        $mergedJob = $jobDetails->merge($job);
        return $this->respondWithSuccess($mergedJob);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $employer = EmployerController::getEmployerProfile($user);
        if (!$employer) {
            $this->respondInvalidInputs("Corresponding employer profile not found!");
        }
        $job = $employer->jobs()->where('id', $id)->first();
        if (!$job) {
            return $this->respondInvalidInputs("Not found", "job not found");
        }
        $validator = $this->validateRequest($request, $this->updateJobRules());
        if ($validator) {
            return $validator;
        }
        if ($request->has('contract_types')) {
            $this->syncJobToMany($job->contract_types(), json_decode($request->contract_types, true));
        }
        if ($request->has('soft_skills')) {
            $this->syncJobToMany($job->soft_skills(), json_decode($request->soft_skills, true));
        }
        if ($request->has('languages')) {
            $data = [];
            foreach (json_decode($request->languages, true) as $lng) {
                $data[$lng['id']] = ['level' => $lng['level']];
            }
            $this->syncJobToMany($job->languages(), $data);
        }
        if ($request->has('prices')) {
            $data = [];

            foreach (json_decode($request->prices, true) as $prc) {
                $data[$prc] = ['employer_email' => $user->email];
            }
            $this->syncJobToMany($job->prices(), $data);
        }
        if ($request->has('educations')) {
            $this->syncJobToMany($job->educations(), json_decode($request->educations, true));
        }
        $jobDetails = $job->job_details;
        $job_file_url = $jobDetails->job_file_url;
        if ($request->hasFile("job_file")) {
            $image = $request->file('job_file');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $directory = 'job_files';
            $job_file_url = $image->storeAs($directory, $image_name, 'public');
            $file_exists = Storage::disk('public')->exists($job->job_details->job_file_url);
            if ($file_exists && $job_file_url)
                Storage::disk('public')->delete($job->job_details->job_file_url);
            else
                $job_file_url = $jobDetails->job_file_url;
        }
        $jobDetailData = array_merge($request->all(), ["job_file_url" => $job_file_url]);
        $jobDetailData['start_date'] = null;
        if ($request->has('start_date') && $request->start_date != null) {
            $jobDetailData['start_date'] = date('Y-m-d', strtotime($request->start_date));
        }

        try {
            $job->fill(request()->all());
            $job->save();
            $jobDetails->fill($jobDetailData);
            $jobDetails->save();
            return $this->show($request, $job->id);
        } catch (Exception $e) {
            return $this->respondInvalidInputs($e->getMessage(), "Invalid data", 404);
        }
    }
    public function destroy($id)
    {
        $job = Job::where('id', $id)->first();
        if (!$job) {
            return $this->respondInvalidInputs("", "job not found");
        }
        $job->is_active = 0;
        $job->save();
        return $this->respondWithSuccess("job deleted");
    }

    public static function syncJobToMany($relation, $items = [])
    {
        try {
            $relation->sync($items);
        } catch (Exception $e) {

            return false;
        }
        return true;
    }

    public function getDistanceResult($plz, $radius)
    {
        $arr = array();

        $result = DB::select("SELECT * FROM plzs WHERE plz = ?", [$plz]);

        if ($result) {
            $row = $result[0];
            $lng = $row->longitude / 180 * M_PI;
            $lat = $row->latitude / 180 * M_PI;

            $result = DB::select("SELECT DISTINCT plzs.id,(6367.41*SQRT(2*(1-cos(RADIANS(plzs.latitude))*cos(" . $lat . ")*(sin(RADIANS(plzs.longitude))*sin(" . $lng . ")+cos(RADIANS(plzs.longitude))*cos(" . $lng . "))-sin(RADIANS(plzs.latitude))* sin(" . $lat . ")))) AS Distance FROM plzs AS plzs WHERE (6367.41*SQRT(2*(1-cos(RADIANS(plzs.latitude))*cos(" . $lat . ")*(sin(RADIANS(plzs.longitude))*sin(" . $lng . ")+cos(RADIANS(plzs.longitude))*cos(" . $lng . "))-sin(RADIANS(plzs.latitude))*sin(" . $lat . "))) <= $radius) ORDER BY Distance");
            foreach ($result as $row) {
                array_push($arr, $row->id);
            }
        }

        return $arr;
    }

    public function showPublicJob($slug)
    {

        $item = Job::where('slug', $slug)->first();
        if (!$item) {
            return $this->respondInvalidInputs("Not found", "job not found");
        }
        $item->kantone;
        $item->plz;
        $item->soft_skills->makeHidden('pivot');
        $item->languages->makeHidden('pivot');
        $item->educations->makeHidden('pivot');
        $item->prices->makeHidden('pivot');
        $item->employer;
        $item->employer_category;
        $item->job_category;
        $item->job_subcategory;
        $item->contract_type;
        $jobDetail = JobDetail::where("job_id", $item->id)->first();
        $job = collect($item);
        $jobDetails = collect($jobDetail);
        $mergedJob = $jobDetails->merge($job);
        return $this->respondWithSuccess($mergedJob);
    }


    // Admin functions related to Jobs
    public function getLastMonthJobs(){
        $jobs = Job::where( DB::raw('MONTH(created_at)'), '=', date('n') )->get();
        return $this->respondWithSuccess($jobs);
    }

    public function getJobsOfEmployer($id){
        $foundEmployer = Employer::find($id);
        $jobList = $foundEmployer->jobs()->get();
        $employer = [];
        $employer['employer'] = $foundEmployer;
        $employer['jobs'] = $jobList;
        return $this->respondWithSuccess($employer);
    }

    public function getCostOfEmployerJob($id){
        $foundJob = Job::find($id);
        $costs = $foundJob->prices()->get();
        return $this->respondWithSuccess($costs);
    }

    public function storePayments(Request $request)
    {
        if (count(request('selected_payments')) !=0 ) {
            foreach (request('selected_payments') as $key)
            {
                $cost = JobCost::where('id', $key)->first();

                $cost->is_paid = $request->is_paid;
                $cost->save();
            }
        }
        return $this->respondWithSuccess('Payments stored succesfully!');
    }
}
