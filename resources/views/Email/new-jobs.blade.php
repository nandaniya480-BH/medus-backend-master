@extends('Email.theme.email-layout')

@section('content')

<tr>
    <td align="left" bgcolor="#"
        style="background-color:#e8f5e9;background:#e8f5e9;border-top-left-radius:5px;border-top-right-radius:3px;text-align:left">
        <center>

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                    <tr>
                        <td
                            style="font-family:Arial,sans-serif;font-size:13px;line-height:1.5em;padding:10px 10px 10px 40px">
                            <a href="https://www.medus.work/MaterialCSS/img/medusLogo.png" style="border:0"
                                target="_blank">
                                <img alt="medus.work" src="https://www.medus.work/MaterialCSS/img/email.png"
                                    style="border:0" height="33">
                            </a>
                        </td>

                        <td style="font-family:Arial,sans-serif;font-size:13px;line-height:1.5em; text-align: left;">
                            <p style=" color: black; font-size: 16px;"> <strong>{{ count($jobs) }}</strong> Neue Job
                                gefunden </p>
                        </td>

                    </tr>
                </tbody>
            </table>

        </center>
    </td>
</tr>





<tr>
    <td align="left" bgcolor="#ffffff"
        style="background-color:#ffffff;padding:30px;border-bottom:1px solid #dfdfe0;border-bottom-left-radius:3px;border-bottom-right-radius:3px;font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;text-align:left">


        <h3
            style="font-size:23px;line-height:1.5em;font-family:'Helvetica Neue',Arial,sans-serif;font-weight:normal;color:#393941;text-align:left;margin:0;padding:0 0 0.9em 0">
            Lieber User {{ $user->name }}</h3>

        <p
            style="font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#000000;margin:1em 0">
            Es freut uns sehr Ihnen mitzuteilen, dass medus.work passende Job-Empfehlungen für Ihr Profil gefunden hat.
            Sie können die Job-Empfehlungen schnell und unkompliziert über Ihr medus.work Account ansehen.</p>


        <div style="padding:1em 0">

            <table border="0" cellpadding="0" cellspacing="0">
                <tbody>

                    @foreach($jobs as $element)
                        <tr>
                            <td align="right"
                                style="text-align:left; margin-top: 15px;border-radius:3px;display:block;padding-left:15px;padding-right:15px;background: white; width: 70%;"
                                valign="left">
                                <a href="{{ env('ENVIRONMENT_URL') }}/jobs/{{ $element->id }}"
                                    style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;font-weight:bold;color:#0097a7;text-decoration:none;width:100%;display:inline-block"
                                    target="_blank">{{ $element->jobTitle }} </a>
                                <span
                                    style="font-size:12px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#aaaaaa;margin:1em 0">{{ $element->pensumFrom.'%'.' - '.$element->pensumTo.'%' }}</span>
                                <br>
                                <span
                                    style="font-size:12px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#aaaaaa;margin:1em 0">{{ $element->Contract_Type->name != '' ? $element->Contract_Type->name : 'nicht vorhanden' }}
                                </span> | <span
                                    style="font-size:12px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#aaaaaa;margin:1em 0">{{ $element->startDate }}
                                </span>
                            </td>
                            <td style="width: 25%;">
                                <span
                                    style="font-size:12px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#0097a7;margin:1em 0">{{ $element->Job_Suggestions->where('employeeID',$user->userID)->first()->recommandedValue }}%
                                </span> <br>
                                <span
                                    style="font-size:12px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#aaaaaa;margin:1em 0">{{ $element->Company_Name->name }}
                                </span> <br>
                                <span
                                    style="font-size:12px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#aaaaaa;margin:1em 0">{{ $element->ort }}
                                </span> <br>
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="text-align:left; margin-top: 15px;border-radius:3px;padding-left:15px;background: white; width: 70%; padding-top: 15px;">
                                <hr style="border-top: 1px dotted grey;">
                            </td>
                            <td
                                style="text-align:left; margin-top: 15px;border-radius:3px;background: white; width: 25%; padding-top: 15px;">
                                <hr style="border-top: 1px dotted grey;">
                            </td>
                        </tr>
                    @endforeach


                </tbody>

            </table>
        </div>
        <p
            style="font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#000000;margin:1em 0 0 0">
            Freundliche Grüsse,<br>Das <a href="http://medus.work" target="_blank">medus.work</a> Team</p>


    </td>
</tr>

@endsection
