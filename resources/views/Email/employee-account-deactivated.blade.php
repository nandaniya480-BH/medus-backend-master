@extends('Email.theme.email-layout')

@section('content')
<tr>
    <td align="left" bgcolor="#ffffff" style="
        background-color: #ffffff;
        padding: 30px;
        border-bottom: 1px solid #dfdfe0;
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        font-size: 13px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.5em;
        text-align: left;
    ">
        <h3
            style="font-size: 23px; line-height: 1.5em; font-family: 'Helvetica Neue', Arial, sans-serif; font-weight: normal; color: #393941; text-align: left; margin: 0; padding: 0 0 0.9em 0;">
            Account deaktiviert
        </h3>
        <p
            style="font-size: 13px; font-family: 'Helvetica Neue', Arial, sans-serif; line-height: 1.5em; color: #000000; margin: 1em 0;">
            Lieber User, <br /> Schade, dass Du Dein Konto deaktiviert hast. Wir würden uns freuen, wenn Du uns
            mitteilst, weshalb Du Dein Konto deaktiviert hast. So können wir <a href=""
                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #0097a7;">medus.work</a> für Dich und andere User
            verbessern.
        </p>
        <p
            style="font-size: 13px; font-family: 'Helvetica Neue', Arial, sans-serif; line-height: 1.5em; color: #000000; margin: 1em 0 0 0;">
            Liebe Grüsse,<br />
            Das <a href="http://medus.work" target="_blank">medus.work</a> Team
        </p>
    </td>
</tr>
@endsection
