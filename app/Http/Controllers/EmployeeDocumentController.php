<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Storage;

class EmployeeDocumentController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;
        if (!$employee) {
            return $this->respondInvalidInputs("Not found", "Employee not found!", 404);
        }
        $documents = $employee->documents;
        return $this->respondWithSuccess($documents);
    }

    public function show($id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        $user = Auth::user();
        $employee = $user->employee;
        if (!$employee) {
            return $this->respondInvalidInputs("employee not found!");
        }
        $document = EmployeeDocument::where('id', $id)
            ->where("employee_id", $employee->id)
            ->first();
        if (!$document) {
            return $this->respondInvalidInputs("document not found", "", 404);
        }
        return $this->respondWithSuccess($document);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;
        if (!$request->hasFile("documents")) {
            return $this->respondInvalidInputs("request has no files", "File missing", 409);
        }
        $files = $request->file('documents');
        $docIds = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                try {
                    $employeedocument = new EmployeeDocument;
                    $nameToSave = time() . '.' . $file->getClientOriginalExtension();
                    $fileName = $file->getClientOriginalName();
                    $directory = 'employee_documents';
                    $file_url = $file->storeAs($directory, $nameToSave, 'public');
                    $employeedocument->file_name = $fileName;
                    $employeedocument->url = $file_url;
                    $employeedocument->employee_id = $employee->id;
                    $employeedocument->save();
                    array_push($docIds, $employeedocument->id);
                } catch (Exception $e) {
                    //throw $th;
                }
            }
            $insertedDocuments = EmployeeDocument::whereIn('id', $docIds)->get();
            return $this->respondWithSuccess($insertedDocuments);
        }
        $employeedocument = new EmployeeDocument;
        $nameToSave = time() . '.' . $files->getClientOriginalExtension();
        $fileName = $files->getClientOriginalName();
        $directory = 'employee_documents';
        $file_url = $files->storeAs($directory, $nameToSave, 'public');
        $employeedocument->file_name = $fileName;
        $employeedocument->url = $file_url;
        $employeedocument->employee_id = $employee->id;
        $employeedocument->save();
        array_push($docIds, $employeedocument->id);
        $insertedDocuments = EmployeeDocument::whereIn('id', $docIds)->get();
        return $this->respondWithSuccess($insertedDocuments);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $employee = $user->employee;
        if (!$employee) {
            return $this->respondInvalidInputs("employee not found!");
        }
        $document = EmployeeDocument::where('id', $id)
            ->where("employee_id", $employee->id)
            ->first();
        if (!$document) {
            return $this->respondInvalidInputs("document not found", "", 404);
        }
        Storage::disk('public')->delete($document->url);
        $document->delete();
        return $this->respondWithSuccess($document, "document deleted");
    }
}
