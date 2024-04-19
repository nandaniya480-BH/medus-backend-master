<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContractType;
use App\Models\Kantone;
use App\Models\Education;
use App\Models\EmployerCategory;
use App\Models\JobCategory;
use App\Models\JobSubCategory;
use App\Models\Language;
use App\Models\Plz;
use App\Models\Price;
use App\Models\SoftSkill;
use Illuminate\Support\Facades\File;
use App\Models\Job;
use Illuminate\Support\Str;
use Exception;

class initSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function syncJobToMany($relation, $items = [])
    {
        try {
            $relation->sync($items);
        } catch (Exception $e) {

            return false;
        }
        return true;
    }
    public function run()
    {
        if (ContractType::all()->count() == 0) {
            $json = File::get('database/data/contract_types.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                ContractType::create([
                    "name" => $value->name,
                ]);
            }
        }
        if (Education::all()->count() == 0) {
            $json = File::get('database/data/educations.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                Education::create([
                    "name" => $value->name,
                ]);
            }
        }
        if (EmployerCategory::all()->count() == 0) {
            $json = File::get('database/data/employer_categories.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                EmployerCategory::create([
                    "name" => $value->name,
                ]);
            }
        }

        if (JobCategory::all()->count() == 0) {
            $json = File::get('database/data/job_categories.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                JobCategory::create([
                    "name" => $value->name,
                ]);
            }
        }

        if (JobSubCategory::all()->count() == 0) {
            $json = File::get('database/data/job_subcategories.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                JobSubCategory::create([
                    "name" => $value->name,
                    'category_id' => $value->categoryId,
                ]);
            }
        }
        if (Kantone::all()->count() == 0) {
            $json = File::get('database/data/kantones.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                Kantone::create([
                    "name" => $value->name,
                    "short_name" => $value->shortName
                ]);
            }
        }

        if (Language::all()->count() == 0) {
            $json = File::get('database/data/languages.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                Language::create([
                    "name" => $value->name,
                ]);
            }
        }

        if (count(Plz::all()) == 0) {
            $json = File::get('database/data/plzs.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                Plz::create([
                    "plz" => $value->plz,
                    "ort" => $value->ort,
                    "latitude" => $value->latitude,
                    "longitude" => $value->longitude,
                    "berzirk" => $value->berzirk,
                    "kantone_id" => $value->kantoneId,
                ]);
            }
        }

        if (Price::all()->count() == 0) {
            $json = File::get('database/data/prices.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                Price::create([
                    "name" => $value->name,
                    "price" => $value->price,
                ]);
            }
        }

        if (SoftSkill::all()->count() == 0) {
            $json = File::get('database/data/soft_skills.json');
            $elements = json_decode($json);
            foreach ($elements as $key => $value) {
                SoftSkill::create([
                    "name" => $value->name,
                    "approved" => 1,
                ]);
            }
        }
        for ($i = 0; $i <= 50; $i++) {
            $job = new Job();
            $job->job_title = "job title " . $i;
            $job->job_id = Str::uuid();
            $job->ort = "Lousane";
            $job->workload_from = rand(10, 50);
            $job->workload_to = rand(60, 100);
            $job->position = rand(1, 4);
            $job->work_time = rand(1, 2);
            $job->employer_id = 1;
            $job->employer_category_id = rand(1, 12);
            $job->job_category_id = rand(1, 18);
            $job->job_subcategory_id = rand(1, 83);
            $job->plz_id = rand(1, 3000);
            $job->kantone_id = rand(1, 26);
            $job->contract_type_id = rand(1, 6);
            $job->is_active = rand(0, 1);
            $job->save();
            // $this->syncJobToMany($job->contract_types(), [rand(1, 6), rand(1, 6)]);
            $this->syncJobToMany($job->soft_skills(), [rand(1, 6), rand(1, 340)]);
            $this->syncJobToMany($job->educations(), [rand(1, 6), rand(1, 200)]);
            $this->syncJobToMany($job->languages(), [rand(1, 25) => ["level" => rand(1, 3)]]);
        }
    }
}
