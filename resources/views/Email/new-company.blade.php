@extends('Email.theme.email-layout')

@section('content')
<tr>
    <td align="left" bgcolor="#ffffff"
        style="background-color:#ffffff;padding:30px;border-bottom:1px solid #dfdfe0;border-bottom-left-radius:3px;border-bottom-right-radius:3px;font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;text-align:left">


        <h3
            style="font-size:23px;line-height:1.5em;font-family:'Helvetica Neue',Arial,sans-serif;font-weight:normal;color:#393941;text-align:left;margin:0;padding:0 0 0.9em 0">
            New Company has joined medus.work!</h3>
        <p
            style="font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#000000;margin:1em 0">
            {{ $user->email }}.</p>


        <div style="padding:1em 0">
            <table border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td align="center" height="33"
                            style="text-align:center;border-radius:3px;display:block;padding-left:15px;padding-right:15px;background: linear-gradient(60deg, #26c6da, #0097a7)"
                            valign="middle">
                            <a href="{{ env('ENVIRONMENT_URL') }}/company/{{ $user->employer->slug }}"
                                style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;font-weight:bold;color:#ffffff;text-decoration:none;line-height:33px;width:100%;display:inline-block"
                                target="_blank">See Profile</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p
            style="font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#000000;margin:1em 0 0 0">
            Liebe Grüsse,<br>Das <a href="http://medus.work" target="_blank">medus.work</a> Team</p>


    </td>
</tr>
@endsection
