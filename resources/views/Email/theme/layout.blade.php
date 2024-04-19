<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:300,400,600,700" rel="stylesheet">
    <style>
        html {
            font-family: 'Titillium Web', sans-serif;
        }

        .gradient-header {
            background-color: #004099;
            background: linear-gradient(60deg, #26c6da 0%, #0097a7 100%);
            border-top-left-radius: 5px;
            border-top-right-radius: 3px;
            text-align: left
        }

        .content-section {
            background-color: #ffffff;
            padding: 30px;
            border-bottom: 1px solid #dfdfe0;
            border-bottom-left-radius: 3px;
            border-bottom-right-radius: 3px;
            font-size: 13px;
            line-height: 1.5em;
            text-align: left;
        }

        .content-header {
            font-size: 18px;
            color: #000000;
            margin: 0;
            padding: 0 0 0.9em 0;
        }

        .content-text {
            font-size: 13px;
            color: #000000;
            margin: 1em 0 0 0;
        }

        .content-td-btn {
            text-align: center;
            border-radius: 3px;
            display: block;
            padding-left: 15px;
            padding-right: 15px;
            background: linear-gradient(60deg, #26c6da, #0097a7);

            >a {
                font-size: 13px;
                font-weight: bold;
                color: #ffffff;
                text-decoration: none;
                line-height: 33px;
                width: 100%;
                display: inline-block;
            }
        }

        .footer-wrapper {
            font-size: 13px;
            border-radius: 5px;
            background: linear-gradient(60deg, #26c6da, #0097a7);
            text-align: left;
            padding: 20px;
        }

        .footer-header {
            font-size: 18px;
            color: #ffffff;
            margin: 0;
            text-align: left;
        }

        .footer-text {
            font-size: 13px;
            color: #ffffff;
            line-height: 1.5em;
            padding-bottom: 3px;
            text-align: left;
        }

        .footer-legal {
            font-size: 12px;
            color: #5f6062;
            line-height: 1.5em;
        }

        .footer-legal-wrapper {
            padding: 20px 30px 30px 30px;
            line-height: 0.5em;
            text-align: center;
        }

    </style>
</head>

<body>
    <table bgcolor="#efefef" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
        <tbody>
            <tr>
                <td align="center" bgcolor="#efefef" style="background-color:#efefef" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600">
                        <tbody>
                            <tr>
                                <td style="padding:5px 0;line-height:1em">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="left" bgcolor="#" class="gradient-header">
                                    <center>
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td style="padding:20px 30px">
                                                        <a href="https://www.medus.work/img/medusLogo.png"
                                                            style="border:0" target="_blank">
                                                            <img alt="medus.work" height="50"
                                                                src="https://www.medus.work/img/medusLogo.png"
                                                                style="border:0" width="160">
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </center>
                                </td>
                            </tr>

                            @yield('content')

                            <tr>
                                <td style="padding:2px 0;line-height:1em">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding:2px 0;line-height:1em">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="left" bgcolor="#f7f7f7" class="footer-wrapper">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td align="left">
                                                    <h3 class="footer-header">
                                                        Hol Dir jetzt die medus.work App
                                                    </h3>
                                                    <table cellspacing="0" cellpadding="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" valign="top" class="footer-text">
                                                                    Mit der medus.work App verpasst Du keine Jobangebote
                                                                    mehr.
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table>
                                                        <tbody>
                                                            <tr>
                                                                <td align="left" style="padding-top:20px;">
                                                                    <a href="https://play.google.com/store/apps/details?id=ch.medus.medus"
                                                                        target="_blank">
                                                                        <img src="https://www.medus.work/img/gplay.png"
                                                                            height="33" width="110" alt="Google Play"
                                                                            style="border:0" class="CToWUd"></a>
                                                                </td>
                                                                <td style="padding-top:20px;">
                                                                    &nbsp;
                                                                </td>
                                                                <td align="left" style="padding-top:20px;">
                                                                    <a href="https://itunes.apple.com/gb/app/medus-ch/id1460653476?fbclid=IwAR27XhEE14pVcGKOhMCfr-2pTxqxQ8u8mp4BbWSObemUhJOS1cvRYWI2SMQ"
                                                                        target="_blank"><img
                                                                            src="https://www.medus.work/img/appstore.png"
                                                                            height="33" width="110" alt="AppStore"
                                                                            style="border:0" class="CToWUd"></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" class="footer-legal-wrapper">
                                    <div class="footer-legal">
                                        Hast Du Fragen ?
                                        <u></u>
                                        <a href="mailto:info@medus.work" style="color:#0088cc;text-decoration:none"
                                            target="_blank">
                                            info@medus.work
                                        </a>
                                        <u></u>
                                    </div>
                                    <br>
                                    <div class="footer-legal">
                                        <u></u>
                                        © <script>
                                            document.write(new Date().getFullYear())

                                        </script> medus.work.
                                        <u></u>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
