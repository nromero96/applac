<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 630px;
            margin: 0 auto;
            background-color: #E0E0E0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding: 0;
        }
        .footer {
            text-align: center;
            background-color: #161515;
            color: #fff;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 0px;
        }
        th {
            background-color: #fff;
        }

        @media only screen and (max-width: 600px) {
            body, .container{
                background-color: #fff !important;
            }
            .spacehdr{
                display: none !important;
            }
            .imgbannerdesk{
                display: none !important;
            }

            .imgbannermobil{
                width: 100% !important;
                display: block !important;
                margin: 0 auto !important;
            }
        }

    </style>
</head>
<body style="background-color: #E0E0E0;">
    <br class="spacehdr">
    <img class="imgbannermobil" src="@yield('header_image')" alt="LAC" style="display: none; max-width: 630px; height: auto; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;">
    <table class="container" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 630px; margin: 0 auto; background-color: #fff; padding: 20px;">
        <tr class="header" style="text-align: center; padding: 0;">
            <td style="text-align: center; padding: 0;">
                <img class="imgbannerdesk" src="@yield('header_image')" alt="LAC" style="display: block; max-width: 630px; height: auto; border: 0; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;">
            </td>
        </tr>
        <tr class="content">
            <td style="padding: 0 15px; color: #161515">
                @yield('content')
            </td>
        </tr>
        <tr class="footer" style="background-color: #161515; color: #fff; text-align: center; padding: 20px; font-size: 12px;">
            <td>
                <table width="100%">
                    <tr>
                        <td style="padding: 14px; text-align: left;">
                            <img src="https://app.latinamericancargo.com/assets/img/lg-white-lac-mlg.png" alt="Logo LAC" style="max-width: 101px; height: auto;">
                        </td>
                        <td>
                            <p style="font-size:20px;line-height:1.6;padding: 14px;text-align: right;">Specialization Makes a Difference</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0px 14px; text-align: left;">
                            <b><a href="https://www.latinamericancargo.com/request-shipping-quote/" style="color:#D2CDC5; font-size: 14px; line-height: 1.5;text-decoration: underline;margin-right: 10px;">Get a Quote</a> <a href="https://quemtr.webtracker.wisegrid.net/Login/Login.aspx?ReturnUrl=%2fDefault.aspx" style="color:#D2CDC5; font-size: 14px; line-height: 1.5; text-decoration: underline;">Track your Shipment</a></b>
                        </td>
                        <td style="padding: 0px 14px; text-align: right;">
                            <table>
                                <tr>
                                    <td style="padding: 0px 3px 4px;">
                                        <b style="color:#D2CDC5; font-size: 14px; line-height: 1.5;">Connect with us</b>
                                    </td>
                                    <td style="width: 95px;">
                                        <a href="https://www.linkedin.com/company/latin-american-cargo/" style="color:#D2CDC5; font-size: 14px; line-height: 1.5; text-decoration: none; margin: 0px 2px 0px;"><img src="https://app.latinamericancargo.com/assets/img/mlg_linkedIn_white.png" alt="LinkedIn" style="width: 24px; height: 24px;"></a>
                                        <a href="https://www.youtube.com/user/LatinAmericanCargo" style="color:#D2CDC5; font-size: 14px; line-height: 1.5; text-decoration: none; margin: 0px 2px 0px;"><img src="https://app.latinamericancargo.com/assets/img/mlg_outube_white.png" alt="YouTube" style="width: 24px; height: 24px;"></a>
                                        <a href="https://www.facebook.com/LatinAmericanCargo/" style="color:#D2CDC5; font-size: 14px; line-height: 1.5; text-decoration: none; margin: 0px 2px 0px;"><img src="https://app.latinamericancargo.com/assets/img/mlg_facebook_white.png" alt="Facebook" style="width: 24px; height: 24px;"></a>
                                    </td>
                                </tr>
                            </table>                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 32px 14px 5px;">
                            <hr style="border: 0; border-top: 1px solid #606060;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px 14px; text-align: left;">
                            <a href="https://www.latinamericancargo.com/privacy-policy/" style="color:#D2CDC5; font-size: 10px; line-height: 1.3; text-decoration: underline;">Privacy Policy</a>
                        </td>
                        <td style="padding: 30px 14px; text-align: right;">
                            <p style="color:#D2CDC5; font-size: 10px; line-height: 1.3;">&copy; {{ date('Y') }} Latin American Cargo</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>