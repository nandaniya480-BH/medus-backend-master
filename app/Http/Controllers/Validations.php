<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Validations extends Controller
{
    public static function registerUser(Request $request)
    {
        $validator =
            Validator::make(
                $request->all(),
                [
                    'email' => 'required|unique:users|email',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|min:8|same:password',
                    'role' => ['required', Rule::in(['employeradmin', 'employer', 'employee']),]
                ],
                [
                    'email.required' => ['en' => "Email is required", 'de' => "E-Mail ist erforderlich"],
                    'email.unique' => ['en' => "Email already exists", 'de' => "E-Mail existiert bereits"],
                    'email.email' => ['en' => "Incorrect email format", 'de' => "Falsches E-Mail-Format"],
                    'password.required' => ['en' => "Password field is empty", 'de' => "Passwortfeld ist leer"],
                    'password.min' => ['en' => "Password should be at least 8 characters ", 'de' => "Das Passwort sollte mindestens 8 Zeichen lang sein"],
                    'confirm_password.same' => ['en' => "Password and confirm password does not match", 'de' => "Passwort und Passwort bestätigen stimmen nicht überein"],
                    'role.required' => ['en' => "The selected role is not valid", 'de' => "Die ausgewählte Rolle ist ungültig"],
                    'role.in' => ['en' => "The selected role is not valid", 'de' => "Die ausgewählte Rolle ist ungültig"],
                ]
            );
        return $validator;
    }
    public static function registerEmployer(Request $request)
    {
        $validator =
            Validator::make(
                $request->all(),
                [
                    'email' => 'required|unique:users|email',
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|min:8|same:password',

                ],
                [
                    'email.required' => ['en' => "Email is required", 'de' => "E-Mail ist erforderlich"],
                    'email.unique' => ['en' => "Email already exists", 'de' => "E-Mail existiert bereits"],
                    'email.email' => ['en' => "Incorrect email format", 'de' => "Falsches E-Mail-Format"],
                    'password.required' => ['en' => "Password field is empty", 'de' => "Passwortfeld ist leer"],
                    'password.min' => ['en' => "Password should be at least 8 characters ", 'de' => "Das Passwort sollte mindestens 8 Zeichen lang sein"],
                    'confirm_password.same' => ['en' => "Password and confirm password does not match", 'de' => "Passwort und Passwort bestätigen stimmen nicht überein"],
                ]
            );
        return $validator;
    }
    public static function softSkillPut(Request $request, $rules = [], $messages = [])
    {
        $validator =
            Validator::make(
                $request->all(),
                [
                    'name' => 'sometimes|min:2',
                    'index' => 'numeric',
                    'approved' => 'numeric',
                ],
                []
            );
        return $validator;
    }
}
