<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Closure;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $per_page;
    public $user_language;
    

    public function __construct()
    {
        $this->per_page = floatval($_GET['per_page'] ?? 10);

        $this->user_language = request()->header('Accept-Language', 'en');
    }

    public function respondWithSuccess($data = [], $message = null, $status_code = 200)
    {
        $response = [
            "message" => $message ?? "Success",
            "status_code" => $status_code,
        ];


        if ($data instanceof LengthAwarePaginator) {
            $response["data"] = $data->items();
            $response["pagination"] = $this->paginator($data);
        } else {
            $response["data"] = $data;
        }
        return response()->json($response, $status_code);
    }

    public function respondWithError($errors = [], $message = null, $status_code = 400)
    {

        // if (count($errors) > 0) {
        //     $message = implode("\n", $errors);
        // }

        return response()->json(['errors' => $errors, 'message' => $message ?? "An error occurred", 'success' => false], $status_code);
    }

    public function respondInvalidInputs($errors = [], $message = null, $status_code = 400)
    {

        // $message = implode("\n", $errors);

        return response()->json(['errors' => $errors, 'message' => $message ?? "Invalid inputs", 'success' => false], $status_code);
    }


    public function hasPagination($data)
    {
        if (method_exists($data, 'total')) {
            return ["total" => $data->total(), "current_page" => $data->currentPage(), "item_per_page" => $data->perPage(), "last_page" => $data->lastPage()];
        }

        return false;
    }

    public function pagination($results)
    {
        if (empty($results)) {
            return [
                "data" => [],
                "pagination" => []
            ];
        }
        return [
            "data" => $results->items(),
            "pagination" => self::pagination_data($results)
        ];
    }

    public function pagination_data($data)
    {
        if (method_exists($data, 'total')) {
            return ["total" => $data->total(), "current_page" => $data->currentPage(), "item_per_page" => $data->perPage(), "last_page" => $data->lastPage()];
        }

        return false;
    }


    public function validateRequest($request, $rules = [], $messages = [])
    {
        $validator =
            Validator::make(
                $request->all(),
                $rules,
                $messages
            );
        if ($validator->fails()) {
            return $this->respondInvalidInputs($validator->errors(), "Invalid data!");
        }
        return false;
    }

    public function paginator(LengthAwarePaginator $paginator)
    {
        return [
            "total"         => $paginator->total(),
            "current_page"  => $paginator->currentPage(),
            "item_per_page" => $paginator->perPage(),
            "last_page"     => $paginator->lastPage()
        ];
    }
}
