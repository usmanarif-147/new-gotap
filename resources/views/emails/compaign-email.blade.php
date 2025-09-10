<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <!-- Meta -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome Email</title>
    <link rel="shortcut icon" href="https://budgetcollab.com/icons/ms-icon-144x144.png" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <link
        href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic|Open+Sans:400,600,700,300,300italic,400italic"
        rel="stylesheet" type="text/css" />
    <!-- CSS -->
    <style type="text/css">
        @import url("https://fonts.googleapis.com/css2?family=Ubuntu&display=swap");

        @font-face {
            font-family: 'Ubuntu', sans-serif;
            font-family: "Nunito Sans", sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Helvetica Neue, Helvetica, sans-serif, "Open Sans";
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        [style*="Open Sans"] {
            font-family: "Open Sans", Helvetica Neue, Helvetica, sans-serif !important;
        }

        header {
            font-family: Helvetica Neue, Helvetica, sans-serif, "Lato";
        }

        [style*="Lato"] {
            font-family: "Lato", Helvetica Neue, Helvetica, sans-serif !important;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        a {
            text-decoration: none;
            color: #0066cc;
        }

        a:hover {
            text-decoration: underline;
        }

        .fallback-image {
            display: none;
            max-height: 0;
            overflow: hidden;
            mso-hide: all;
        }

        @media screen and (max-width: 600px) {
            .responsive-table {
                width: 100% !important;
            }

            .responsive-column {
                display: block !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #ffffff;">
    <!-- Email header -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
        <tr>
            <td align="center" valign="top">
                <!--[if (gte mso 9)|(IE)]>
                <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                <td align="center" valign="top" width="600">
                <![endif]-->
                <table class="responsive-table" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="width: 100%; max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 50px 0px 12px 0px;" colspan="3">
                            <img src="{{ $logo }}" height="77" border="0" alt="GoTap Logo"
                                style="
                                font-family: Arial, Helvetica, sans-serif;
                                color: #2b2b2b;
                                font-size: 17px;
                                font-weight: bold;
                                border-radius: 50%;
                                display: block;
                                " />
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"
                            style="
                            color: #0d1c43;
                            font-family: 'Ubuntu', sans-serif;
                            font-size: 40px;
                            font-weight: 100;
                            line-height: 38px;
                            margin: 0;
                            padding: 0px 0px 16px 0px;
                            "
                            colspan="3">
                            GoTap
                        </td>
                    </tr>

                    <!-- Email body -->
                    <tr>
                        <td align="center" valign="top" bgcolor="#F4F9FC" colspan="3"
                            style="padding: 30px; margin-bottom: 36px; border-radius: 8px;">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td colspan="3"
                                            style="
                                            color: #000;
                                            font-family: 'Ubuntu', sans-serif;
                                            font-size: 20px;
                                            font-weight: 700;
                                            line-height: 30px;
                                            margin: 0px;
                                            text-align: left;
                                            padding: 0px 0px 10px;
                                            ">
                                            Dear <span
                                                style="font-weight: 100">{{ $recipientName ?? 'GoTap User' }}</span>,
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="top"
                                            style="
                                            padding: 0px 0px 24px 0px;
                                            color: #777777;
                                            font-family: Helvetica Neue, Helvetica, sans-serif, Open Sans;
                                            font-size: 17px;
                                            font-weight: 400;
                                            line-height: 25px;
                                            margin: 0;
                                            text-align: left;
                                            "
                                            colspan="3">
                                            {!! $customMessage !!}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="center" valign="top"
                                            style="
                                            padding: 0px 0px 24px 0px;
                                            color: #777777;
                                            font-family: Helvetica Neue, Helvetica, sans-serif, Open Sans;
                                            font-size: 17px;
                                            font-weight: 400;
                                            line-height: 25px;
                                            margin: 0;
                                            text-align: left;
                                            "
                                            colspan="3">
                                            Thank you for choosing GoTap. We're excited about the opportunity to
                                            assist you. If you have any questions or
                                            need assistance, feel free to reach out to our support team at <a
                                                href="mailto:support@Gotap.co"
                                                style="color: #0066cc;">support@GoTap.co</a>.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 0 24px 0" colspan="3"></td>
                    </tr>

                    <tr>
                        <td align="center" valign="top"
                            style="
                            padding: 10px 0px 26px 0px;
                            color: #000;
                            font-family: 'Ubuntu', sans-serif;
                            font-size: 18px;
                            font-weight: 600;
                            line-height: 27px;
                            margin: 0;
                            "
                            colspan="3">
                            GoTap
                        </td>
                    </tr>

                    <tr>
                        <td align="center" valign="top"
                            style="
                            color: #777777;
                            font-family: Helvetica Neue, Helvetica, sans-serif, Open Sans;
                            font-size: 13px;
                            font-weight: 400;
                            line-height: 19px;
                            margin: 0;
                            padding-bottom: 30px;
                            "
                            colspan="3">
                            <span>
                                &copy; {{ now()->year }}
                                <a href="https://gotap.co" target="_blank" style="color: #0066cc;">GoTap</a>
                                All Rights Reserved.
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Email Tracking Pixel -->
    <div style="display:none;font-size:0;line-height:0;">
        <!-- Standard Tracking Pixel -->
        <img src="{{ route('email.track', [
            'compaignId' => $compaignId,
            'recipientEmail' => rawurlencode($recipientEmail),
        ]) }}"
            width="1" height="1" alt="" style="display:none;width:1px;height:1px;">
    </div>

    <!-- Fallback tracking for clients that don't load images -->
    <div class="fallback-image">
        <a href="{{ route('email.track', [
            'compaignId' => $compaignId,
            'recipientEmail' => rawurlencode($recipientEmail),
        ]) }}"
            style="color:transparent;font-size:0;">Track email open</a>
    </div>
</body>

</html>
