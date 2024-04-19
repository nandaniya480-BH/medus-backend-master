<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Validations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Employer;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\WelcomeEmail;
use App\Mail\WelcomeEmailCompany;
use App\Mail\EmployeeAccountDeactivated;
use App\Mail\EmployeeAccountReactivated;
use App\Mail\EmployerAccountDeactivated;
use App\Mail\EmployerAccountReactivated;
use App\Mail\NewCompany;
use App\Notifications\PasswordReset as ResetPasswordMail;


class UserController extends Controller
{
    use SendsPasswordResetEmails;

    private function getUserData($user)
    {
        return ["email" => $user->email, 'role' => $user->role, 'verified' => $user->email_verified_at ? true : false];
    }

    private function registerEmployee($user, $name, $lastName)
    {
        DB::beginTransaction();
        try {
            $user->save();
            $employee = new Employee;
            $employee->user_id = $user->id;
            $employee->email = $user->email;
            $employee->name = $name;
            $employee->last_name = $lastName;
            $employee->save();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            // $user->delete();
            return $e;
        }
    }


    private function registerEmployer($user, $name)
    {
        $uid = Str::uuid();
        DB::beginTransaction();
        try {
            $user->save();
            $employer = new Employer;
            $employer->user_id = $user->id;
            $employer->email = $user->email;
            $employer->name = $name;
            $employer->slug = Str::slug($name . '-' . $uid, '-');
            $employer->save();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            // $user->delete();
            return $e;
        }
    }


    public function register(Request $request)
    {
        $validator = Validations::registerUser($request);
        if ($validator->fails()) {
            return $this->respondInvalidInputs($validator->errors(), "Invalid input data!");
        }

        $user = new User([
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role" => $request->role,
            "is_active" => 1,
        ]);
        
        if ($user->role == "employee") {
            $createdUser = $this->registerEmployee($user, $request->first_name, $request->last_name);
            if (!($createdUser instanceof User)) {
                return $this->respondWithError([], $createdUser);
            }
            $token = $createdUser->createToken('MedusAppToken')->accessToken;
            // try {
            //     \Mail::to($user->email)->send(new WelcomeEmail($createdUser));
            // } catch (\Swift_TransportException $e) {
            //     \Log::error('E-mail konnte nicht gesendet werden');
            // }
            return $this->respondWithSuccess([
                'access_token' => $token,
                'user' => $this->getUserData($user),
                'token_type' => 'Bearer',
            ]);
        }
        if ($user->role == "employeradmin") {
            $createdUser = $this->registerEmployer($user, $request->name);
            if (!($createdUser instanceof User)) {
                return $this->respondWithError([], $createdUser);
            }
            $token = $createdUser->createToken('MedusAppToken')->accessToken;
            // try {
            //     \Mail::to($user->email)->send(new WelcomeEmailCompany($createdUser));
            // } catch (\Swift_TransportException $e) {
            //     \Log::error('E-mail konnte nicht gesendet werden');
            // }
            // \Mail::to(env('MEDUS_SUPPORT_EMAIL'))->send(new NewCompany($createdUser));
            return $this->respondWithSuccess([
                'access_token' => $token,
                'user' => $this->getUserData($user),
                'token_type' => 'Bearer',

            ]);
        }
        return $this->respondInvalidInputs("only employee and emplyeradmin can register on this route");
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user =  Auth::user();
            if ($user->is_active == 0) {
               $user->is_active = 1;
               $user->save();
               if ($user->role == 'employee') {
                   \Mail::to($user->email)->send(new EmployeeAccountReactivated($user));
               } else if ($user->role == 'employeradmin' || $user->role == 'employer') {
                   \Mail::to($user->email)->send(new EmployerAccountReactivated($user));
               }
            }
            if ($user instanceof User) {
                $token = $user->createToken('MedusAppToken')->accessToken;
                return $this->respondWithSuccess([
                    'access_token' => $token,
                    'user' => $this->getUserData($user),
                    'token_type' => 'Bearer',
                ]);
            }
        } else {
            return $this->respondInvalidInputs("Username or password incorrect");
        }
    }

    public function userDetails()
    {
        $user = Auth::user();
        return $this->respondWithSuccess($this->getUserData($user));
    }

    public function sendPassowrdResetLink(Request $request) {


        $rules = [
            'email' => 'required|exists:users,email|email',
        ];

        $customMessages = [
            'email' => 'Es muss eine gültige E-Mail-Adresse angegeben werden.',
            'required' => 'Es muss eine gültige E-Mail-Adresse angegeben werden.',
            'exists' => 'Diese e-Mail-Adresse ist ungültig.',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors()->all());
        }

        $email = $request->get('email');
        $user = User::whereEmail($request->email)->first();
        // generate token
        $token = Str::random(60);

        // Add or replace in password_resets table
        \DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        \Notification::send($user, new ResetPasswordMail($token));

        return $this->respondWithSuccess("Link zum Zurücksetzen des Passworts wurde an Ihre E-Mail-Adresse gesendet. Bitte überprüfen Sie Ihren Posteingang.");
    }

    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->respondInvalidInputs($validator->errors());
        }
        $user = Auth::user();
        $compare = Hash::check($request->password, $user->password);
        if (!$compare) {
            return $this->respondInvalidInputs(["password" => "Password incorrect!"]);
        }
        try {
            $new_pass = Hash::make($request->new_password);
            $user->password = $new_pass;
            if ($user instanceof User) {
                $user->save();
            }
            return $this->respondWithSuccess(["success" => true]);
        } catch (Exception $e) {
            return $this->respondWithError("Unknown error", 520);
        }
    }

    public function resetForgotPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->respondInvalidInputs($validator->errors());
        }
        $password = $request->password;
        $token = $request->token;
        $tokenData = DB::table('password_resets')
        ->where('token', $token)->first();

        $user = User::where('email', $tokenData->email)->first();
         if ( !$user ) return respondWithError('User wurde nicht gefunden', 404);

         $user->password = Hash::make($password);
         $user->update();

        try {
            $new_pass = Hash::make($request->new_password);
            $user->password = $new_pass;
            if ($user instanceof User) {
                $user->save();
            }
            // Delete the entry in the reset password table
            DB::table('password_resets')->where('token', $token)->delete();
            return $this->respondWithSuccess(["success" => true, "message" => "Ihr passwort wurde erfolgreich geändert!"]);
        } catch (Exception $e) {
            return $this->respondWithError("Unbekannter Fehler", 520);
        }
    }

    public function showPasswordResetForm($token)
    {
        $tokenData = DB::table('password_resets')
        ->where('token', $token)->first();
        if (!$tokenData) {
            return $this->respondInvalidInputs("Token ist ungültig");
        } else {
            return $this->respondWithSuccess($tokenData);
        }
    }   

    public function createAdmin(Request $request)
    {
        $parentUser = Auth::user();
        if ($parentUser->role != "admin" && $parentUser->role != 'superadmin' ) {
            return $this->respondInvalidInputs("You are not authorized to add new Admin", "Not authenticate", "403");
        }
        $user = new User([
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role" => $request->role,
            "is_active" => 1
        ]);
        try {
            $user->save();
            return $this->respondWithSuccess($user, "Admin created successfuly");
        } catch (Exception $e) {
            return $this->respondWithError($e);
        }
    }

    public function getAdmins()
    {
        $parentUser = Auth::user();
        if ($parentUser->role != "admin" && $parentUser->role != 'superadmin' ) {
            return $this->respondInvalidInputs("You are not authorized to perform this Action", "Not authenticate", "403");
        }
        $admins = User::where('role', 'admin')->orWhere('role', 'superadmin')->get();
        
        return $this->respondWithSuccess($admins);
    }

    public function deleteAdminAccount($id)
    {
        $parentUser = Auth::user();
        if ($parentUser->role != "admin" && $parentUser->role != 'superadmin' ) {
            return $this->respondInvalidInputs("You are not authorized to perform this Action", "Not authenticate", "403");
        }
        $admin = User::where('id', $id)->first();
        $admin->delete();
        
        return $this->respondWithSuccess('Admin Account erfolgreich gelöscht');
    }

    public function deactivateUserAccount()
    {
        $user = Auth::user();
        $userToDeactivate = User::where('id', $user->id)->first();
        if (!$userToDeactivate) {
           return $this->respondInvalidInputs("Account nicht gefunden"); 
        }
        $userToDeactivate->is_active = 0;
        $userToDeactivate->save();
        
        if ($userToDeactivate == 'employeradmin' || $userToDeactivate == 'employer'){
            \Mail::to($ususerToDeactivateer->email)->send(new EmployerAccountDeactivated($userToDeactivate));
        } else if ($userToDeactivate == 'employee'){
            \Mail::to($userToDeactivate->email)->send(new EmployeeAccountDeactivated($userToDeactivate));
        }

        return $this->respondWithSuccess('Account erfolgreich deaktiviert');
    }

    public function verifyAccount($token){
        $decryptedId = \Crypt::decrypt($token);
	    $user = User::find($decryptedId);
	    $user->email_verified_at = new \DateTime();
	    $user->save();
	    return $this->respondWithSuccess('Account erfolgreich verifiziert');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $user->token()->revoke();
            return $this->respondWithSuccess("User logged out successfully");
        }
        return $this->respondInvalidInputs("User not found");
    }
}
