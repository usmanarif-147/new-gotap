<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $subject }}</title>
    <style>
        /* Basic reset and font */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: {{ $bgColor }};
            color: {{ $textColor }};
            text-align: {{ $textAlign }};
        }

        .email-container {
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }

        h2 {
            margin-top: 0;
        }

        p {
            white-space: pre-line;
            /* preserve newlines and spaces */
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div style="background-color: #004080; color: #ffffff; padding: 20px; text-align: center;">
        <img src="https://app.gocoompany.com/logo.png" alt="Company Logo" style="height: 50px; margin-bottom: 10px;">
        <h1 style="margin: 0; font-size: 24px; text-transform: uppercase;">GoTap</h1>
    </div>
    <div class="email-container">
        <h2>{{ $subject }}</h2>
        <p>{{ $bodyText }}</p>

        @if ($buttonText && $buttonUrl)
            <div style="text-align: {{ $textAlign }};">
                <a href="{{ $buttonUrl }}" class="btn" target="_blank" rel="noopener noreferrer">
                    {{ $buttonText }}
                </a>
            </div>
        @endif
    </div>
    <div style="background-color: #f1f1f1; color: #777; padding: 10px; text-align: center; font-size: 12px;">
        &copy; {{ date('Y') }} GoTap. All rights reserved.<br>
        <a href="{{ config('app.mail_url') }}" style="color: #004080; text-decoration: none;">Visit Our Website</a>
    </div>
</body>

</html>
