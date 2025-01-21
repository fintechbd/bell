<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style type="text/css">
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td class="header">
                        <a href="#" style="display: inline-block;">
                            @if (trim($slot) === 'Laravel')
                                <img src="https://laravel.com/img/notification-logo.png" class="logo"
                                     alt="Laravel Logo">
                            @else
                                {{ config('app.name') }}
                            @endif
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    {{ $content ?? '' }}

                                    <p>
                                        Best Regards,<br>
                                        {{ config('app.name') }} Support Team
                                    </p>

                                    <table class="subcopy" width="100%" cellpadding="0" cellspacing="0"
                                           role="presentation">
                                        <tr>
                                            <td>
                                                <div style="font-size: 80%; font-family: 'Times New Roman',sans-serif">
                                                    <p style="font-family: 'Consolas',sans-serif; border-radius:4px; border:1px solid yellow; text-align: center; line-height: 30px; width: 100%; background-color: yellow; color: red; font-weight: bolder">
                                                        <code>
                                                            This is an auto generated notification email. Please do not
                                                            reply to this email.
                                                        </code>
                                                    </p>
                                                    <span style="color: #500050">
                    This email and any attachments are confidential and may also be privileged. <br>
                    If you are not the intended recipient, please delete all copies and notify the sender immediately.<br>
                    For any queries, please forward to {{ config('app.name') }} 24x7 Customer support center through below
                channels:<br>
                Phone: <a href="tel:{{ $phone }}" target="_blank">{{ $phone }}</a> (from anywhere in the world), Email:
                <a
                    href="mailto:{{ $email }}" target="_blank">{{ $email }}</a>.<br>
                <br>
                For more information on {{ config('app.name') }} products and services, please
                visit {{ config('app.name') }} website: <a href="mailto:{{ $website }}"
                                                           target="_blank">{{ $website }}</a>.
                </span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td class="content-cell" align="center">
                                    © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
