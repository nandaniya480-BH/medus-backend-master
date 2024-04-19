@extends('Email.theme.layout')
@section('content')
<tr>
    <td align="left" bgcolor="#ffffff" class="content-section">
        <h3 class="content-header">
            Willkommen auf medus.work
        </h3>
        <p class="content-text">
            Lieber User, <br>
            Es freut uns sehr Dich auf <a href=""
            style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #0097a7;">medus.work</a>
            begrüssen zu dürfen. Wir wünschen Dir eine angenehme Zeit auf medus.work.
        </p>
        <p class="content-text">
            Bitte verifizieren Dein medus.work Konto und ergänze die fehlenden Daten auf
            Deinem Profil. Durch das Ergänzen Deines Profils bist Du für Arbeitgeber
            besser auffindbar und medus.work
            kann Dich über passende Stellen-Angebote informieren.
        </p>
        <div style="padding:1em 0">
            <table border="0" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td align="center" height="33" class="content-td-btn"
                        valign="middle">
                        <a href="https://www.medus.work/verified/{{ Crypt::encrypt($user->id) }}"
                        target="_blank">Account verifizieren</a>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
        <p class="content-text">
            Liebe Grüsse,<br>Das <a style="color: #0097a7" href="http://medus.work" target="_blank">medus.work</a> Team
        </p>
    </td>
</tr>
@endsection