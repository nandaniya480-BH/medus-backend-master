<?php

namespace App\Http\Controllers;

use App\Models\SupportEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportEmailsController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user){
            $supportEmail = new SupportEmails;
            $supportEmail->type = $request->type;
            $supportEmail->message = $request->message;
            $supportEmail->employer_id = $request->employer_id;
            $supportEmail->save();

            \Mail::to(env('MEDUS_SUPPORT_EMAIL'))->send(new \App\Mail\SupportEmail($user, $request->type, $request->message));
        }
        return $this->respondWithSuccess("Email erfolgreich gesendet!");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportEmails  $supportEmails
     * @return \Illuminate\Http\Response
     */
    public function show(SupportEmails $supportEmails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportEmails  $supportEmails
     * @return \Illuminate\Http\Response
     */
    public function edit(SupportEmails $supportEmails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportEmails  $supportEmails
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportEmails $supportEmails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportEmails  $supportEmails
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportEmails $supportEmails)
    {
        //
    }
}
