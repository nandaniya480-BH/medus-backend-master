@extends('Email.theme.email-layout')

@section('content')
<tr>
    <td align="left" bgcolor="#ffffff"
        style="background-color:#ffffff;padding:30px;border-bottom:1px solid #dfdfe0;border-bottom-left-radius:3px;border-bottom-right-radius:3px;font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;text-align:left">


        <h3
            style="font-size:23px;line-height:1.5em;font-family:'Helvetica Neue',Arial,sans-serif;font-weight:normal;color:#393941;text-align:left;margin:0;padding:0 0 0.9em 0">
            Lieber Kunde {{ $job->Company_Name->name }}</h3>

        <p
            style="font-size:13px;font-family:'Helvetica Neue',Arial,sans-serif;line-height:1.5em;color:#000000;margin:1em 0">
            Es freut uns sehr Ihnen mitzuteilen, dass medus.work passende Kandidaten für Ihr Stellen-Inserat
            <strong>'{{ $job->jobTitle }}'</strong> gefunden hat. Sie können die Kandidaten schnell und unkompliziert
            über Ihr medus.work Account kontaktieren.</p>


        <div style="padding:1em 0">
            <table border="0" cellpadding="0" cellspacing="0">
                <tbody>

                    @foreach($users as $element)
                        <tr>
                            <td align="right" height="33"
                                style="text-align:center; margin-top: 15px;border-radius:3px;padding-left:15px;padding-right:15px;background: white; width: 10%;padding-top: 15px;"
                                valign="right">
                                <a style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;font-weight:bold;color:#0097a7;text-decoration:none;line-height:33px;width:100%;display:inline-block"
                                    target="_blank">Kandidat</a>
                            </td>
                            <td align="right" height="33"
                                style="text-align:center; margin-top: 15px;border-radius:3px;padding-left:15px;padding-right:15px;background: white; width: 25%;padding-top: 15px;"
                                valign="right">
                                <a style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;font-weight:bold;color:#0097a7;text-decoration:none;line-height:33px;width:100%;display:inline-block"
                                    target="_blank">Match {{ $element->matchValue }}%</a>
                            </td>
                            <td align="right" height="33" style="padding-top: 15px; padding-right: 25px; width: 10%"
                                valign="right">
                                <a href="https://www.medus.work/Company/{{ $job->companyID }}/edit?job_tab={{ $job->id }}"
                                    style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;font-weight:bold;color:#ffffff;text-decoration:none;line-height:33px;width:100%;display:inline-block;background: linear-gradient(60deg, #26c6da, #0097a7);text-align:center;margin-top: 15px ;border-radius:3px;padding-left:15px;padding-right:15px;"
                                    target="_blank"> anschauen</a>
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
