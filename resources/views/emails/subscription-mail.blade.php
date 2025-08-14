<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription</title>
</head>

<body>
    <div
        style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 10px; overflow: hidden;">
        <!-- Header -->
        <div style="background-color: #004080; color: #ffffff; padding: 20px; text-align: center;">
            <img src="https://app.gocoompany.com/logo.png" alt="Company Logo" style="height: 50px; margin-bottom: 10px;">
            <h1 style="margin: 0; font-size: 24px; text-transform: uppercase;">GoTap</h1>
        </div>

        <!-- Content -->
        <div style="padding: 20px; text-align: center;">
            <h2 style="color: #333;">Hello, {{ $name }}</h2>
            <p style="color: #555; line-height: 1.6;">
                {{ $body }}
            </p>
            <div
                style="margin: 20px 0; padding: 15px; background-color: #e6f7ff; border: 1px solid #b3e5fc; border-radius: 5px; text-align: left;">
                <strong>Subscription Details:</strong><br>
                <span style="color: #333;">Subscription Type:
                    {{ $subscription->descriotion ? $subscription->descriotion : 'Trail' }}</span><br>
                <span style="color: #333;">Start Date:
                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</span><br>
                <span style="color: #333;">End Date:
                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</span><br>
                <strong style="color: #333;">Profile Limit:</strong>
                <span style="color: #004080;">
                    @if ($subscription->enterprise_type == 1)
                        1-6 Profiles
                    @elseif ($subscription->enterprise_type == 2)
                        6-20 Profiles
                    @elseif ($subscription->enterprise_type == 3)
                        20+ Profiles
                    @else
                        Unknown Limit
                    @endif
                </span>
            </div>
            <a href="{{ config('app.mail_url') . '/enterprise/login' }}"
                style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #004080; color: #ffffff; text-decoration: none; border-radius: 5px;">
                Go to Login Page
            </a>
        </div>

        <!-- Footer -->
        <div style="background-color: #f1f1f1; color: #777; padding: 10px; text-align: center; font-size: 12px;">
            &copy; {{ date('Y') }} GoTap. All rights reserved.<br>
            <a href="{{ config('app.mail_url') }}" style="color: #004080; text-decoration: none;">Visit Our Website</a>
        </div>
    </div>


</body>

</html>
