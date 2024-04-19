<?php

namespace App\Http\Controllers;

use App\Models\SuggestedEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeContractType;
use App\Models\EmployeeJobSubCategory;
use App\Models\Job;
use Carbon\Carbon;
use App\Models\EmployeeLanguage;
use App\Models\JobLanguage;
use Illuminate\Support\Facades\DB;


class SuggestedEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getSuggestedJobs(){
        $user=Auth::user();
        $employee=$user->employee;
        
        
    }
    public function matchJob(Job $job)
    {
        $employees = Employee::where('is_active', "1")->get();
        foreach ($employees as $employee) {
            $workExperience = $this->getWorkExperienceMatch($job, $employee);
            $educations = $this->getEducationsMatch($job, $employee);
            $language = $this->getLanguageMatch($job, $employee);
            $softSkills = $this->getSoftSkillsMatch($job, $employee);
            $position = $this->getPositionMatch($job, $employee);
            $subcategory = $this->getSubcategoryMatch($job, $employee);
            $contractType = $this->getContractTypeMatch($job, $employee);
            $pensum = $this->getPensumMatch($job, $employee);
            $distance = $this->getDistanceMatch($job, $employee);
            $workTime = $this->getworkTimeMatch($job, $employee);
            $leadership = $this->getLeadershipMatch($job, $employee);
            $result = $workExperience + $educations + $language + $softSkills + $position + $subcategory + $contractType + $pensum + $distance + $workTime + $leadership;
            if ($result > 30) {
                $suggestedEmployee = SuggestedEmployee::where("job_id", $job->id)->where("employee_id", $employee->id)->first();
                if (!$suggestedEmployee) {
                    $employer = $job->employer;
                    $suggestedEm = new SuggestedEmployee;
                    $suggestedEm->employee_id = $employee->id;
                    $suggestedEm->employer_id = $employer->id;
                    $suggestedEm->job_id = $job->id;
                    $suggestedEm->employee_match = $result;
                    $suggestedEm->employer_match = $result;
                    $suggestedEm->save();
                    continue;
                }

                if ($suggestedEmployee->re_suggest) {
                    if ((int) $suggestedEmployee->employee_match < $result) {
                        $suggestedEmployee->employee_match = $result;
                        $suggestedEmployee->save();
                    }
                    if ((int) $suggestedEmployee->employer_match < $result) {
                        $suggestedEmployee->employer_match = $result;
                        $suggestedEmployee->save();
                    }
                }
            }

        }
    }
    public function matchEmployee(Employee $employee)
    {
        $jobs = Job::where("is_active", "1")->get();
        foreach ($jobs as $job) {
            $workExperience = $this->getWorkExperienceMatch($job, $employee);
            $educations = $this->getEducationsMatch($job, $employee);
            $language = $this->getLanguageMatch($job, $employee);
            $softSkills = $this->getSoftSkillsMatch($job, $employee);
            $position = $this->getPositionMatch($job, $employee);
            $subcategory = $this->getSubcategoryMatch($job, $employee);
            $contractType = $this->getContractTypeMatch($job, $employee);
            $pensum = $this->getPensumMatch($job, $employee);
            $distance = $this->getDistanceMatch($job, $employee);
            $workTime = $this->getworkTimeMatch($job, $employee);
            $leadership = $this->getLeadershipMatch($job, $employee);
            $result = $workExperience + $educations + $language + $softSkills + $position + $subcategory + $contractType + $pensum + $distance + $workTime + $leadership;
            if ($result > 30) {
                $suggestedEmployee = SuggestedEmployee::where("job_id", $job->id)->where("employee_id", $employee->id)->first();
                if (!$suggestedEmployee) {
                    $employer = $job->employer;
                    $suggestedEm = new SuggestedEmployee;
                    $suggestedEm->employee_id = $employee->id;
                    $suggestedEm->employer_id = $employer->id;
                    $suggestedEm->job_id = $job->id;
                    $suggestedEm->employee_match = $result;
                    $suggestedEm->employer_match = $result;
                    $suggestedEm->save();
                    continue;
                }
                if ($suggestedEmployee->re_suggest) {
                    if ((int) $suggestedEmployee->employee_match < $result) {
                        $suggestedEmployee->employee_match = $result;
                        $suggestedEmployee->save();
                    }
                    if ((int) $suggestedEmployee->employer_match < $result) {
                        $suggestedEmployee->employer_match = $result;
                        $suggestedEmployee->save();
                    }
                }
            }

        }
    }

    public function getWorkExperienceMatch(Job $job, Employee $employee)
    {
        $employeeWorkExperiences = $employee->work_experiences;
        $jobExp = 0;
        switch ($job->work_experience) {
            case '1':
                $jobExp = 1;
                break;
            case '2':
                $jobExp = 5;
                break;
            case '3':
                $jobExp = 10;
                break;
            default:
                $jobExp = 0;
                break;
        }
        $employeTotalExp = 0;
        foreach ($employeeWorkExperiences as $eWexp) {
            $endDate = Carbon::today();
            if ($eWexp->end_date) {
                $endDate = Carbon::parse($eWexp->end_date);
            }
            $startTime = Carbon::parse($eWexp->start_date);
            $experience = $startTime->diff($endDate);
            $years = $experience->y;
            $months = $experience->m;
            $employeTotalExp = $employeTotalExp + $years + (12 / ($months + 2));
        }
        if ($employeTotalExp >= $jobExp) {
            return 1;
        }
        return 0;
    }
    public function getEducationsMatch(Job $job, Employee $employee)
    {
        $jobEducations = $job->educations;
        if (count($jobEducations) == 0) {
            return 0;
        }
        $employeeEducations = $employee->educations;
        $intersectEducations = $jobEducations->intersect($employeeEducations);
        return 9 * (count($intersectEducations) / count($jobEducations));
    }
    public function getLanguageMatch(Job $job, Employee $employee)
    {
        $jobLanguages = JobLanguage::select("language_id", "level")->where("job_id", $job->id)->get();
        if (count($jobLanguages) == 0) {
            return 9;
        }
        $jobLngIds = $jobLanguages->map(function ($jobEd) {
            return $jobEd->language_id;
        });
        $employeeLanguages = EmployeeLanguage::select("language_id", "level")
            ->where("employee_id", $employee->id)
            ->whereIn("language_id", $jobLngIds)
            ->get();
        $accepted = 0;
        foreach ($employeeLanguages as $emLng) {
            $jobLng = $jobLanguages->first(function ($jobLn) use ($emLng) {
                return $jobLn->language_id == $emLng->language_id;
            });
            if ((int) $jobLng->level <= (int) $emLng->level) {
                $accepted++;
            }
            if ((int) $jobLng->level <= (int) $emLng->level + 1) {
                $accepted += 0.8;
            }
        }
        return 9 * ($accepted / count($jobLanguages));
    }

    public function getSoftSkillsMatch(Job $job, Employee $employee)
    {
        $jobSoftSkills = $job->soft_skills;
        if (count($jobSoftSkills) == 0) {
            return 0;
        }
        $employeeSoftSkills = $employee->soft_skills;
        $intersectSoftSkills = $jobSoftSkills->intersect($employeeSoftSkills);
        return 9 * (count($intersectSoftSkills) / count($jobSoftSkills));
    }

    public function getPositionMatch(Job $job, Employee $employee)
    {
        $jobPosition = $job->position;
        $employeePosition = $employee->position;
        if ($jobPosition == $employeePosition) {
            return 9;
        }
        if ($jobPosition == 0 || $employeePosition == 3) {
            return 9;
        }
        return 0;
    }
    public function getSubcategoryMatch(Job $job, Employee $employee)
    {
        $employeeCategory = EmployeeJobSubCategory::where("employee_id", $employee->id)
            ->where("job_subcategory_id", $job->job_subcategory_id)->first();
        if ($employeeCategory) {
            return 9;
        }
        return 0;
    }

    public function getContractTypeMatch(Job $job, Employee $employee)
    {
        $employeeCategory = EmployeeContractType::where("employee_id", $employee->id)
            ->where("contract_type_id", $job->contract_type_id)->first();
        if ($employeeCategory) {
            return 9;
        }
        return 0;
    }

    public function getPensumMatch(Job $job, Employee $employee)
    {
        if ($job->workload_to < $employee->workload_from || $employee->workload_to < $job->workload_from)
            return 9;
        else
            return 0;
    }

    public function getDistanceMatch(Job $job, Employee $employee)
    {
        $plzs = $this->getDistanceResult($employee->plz, $employee->prefered_distance);
        if (in_array($job->plz, $plzs)) {
            return 9;
        }
        return 0;
    }
    public function getworkTimeMatch(Job $job, Employee $employee)
    {
        $jobWorkTime = $job->work_time;
        $employeeWorkTime = $employee->work_time;
        if ($jobWorkTime == $employeeWorkTime) {
            return 9;
        }
        if ($jobWorkTime == 0 || $employeeWorkTime == 3) {
            return 9;
        }
        return 0;
    }
    public function getLeadershipMatch(Job $job, Employee $employee)
    {
        if ($job->leadership == $employee->leadership) {
            return 9;
        }
        if ($job->leadership == 0) {
            return 5;
        }
        return 0;
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
}