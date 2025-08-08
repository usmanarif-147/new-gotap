<!-- Email Signature Template - Optimized for Gmail and other email clients -->
<table cellpadding="0" cellspacing="0" border="0" style="background:#ffffff; border-radius:8px; box-shadow:0 2px 8px #eeeeee; padding:20px; width:350px; font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    @if (isset($preview['banner']) && $preview['banner'])
        <tr>
            <td colspan="2" style="padding:0; margin:0;">
                <img src="{{ asset('storage/' . $preview['banner']) }}" alt="Banner" style="width:100%; height:60px; object-fit:cover; border-radius:8px 8px 0 0; display:block;">
            </td>
        </tr>
    @elseif (isset($preview['banner_path']) && $preview['banner_path'])
        <tr>
            <td colspan="2" style="padding:0; margin:0;">
                <img src="{{ asset('storage/' . $preview['banner_path']) }}" alt="Banner" style="width:100%; height:60px; object-fit:cover; border-radius:8px 8px 0 0; display:block;">
            </td>
        </tr>
    @elseif (isset($preview['banner_temp']) && $preview['banner_temp'])
        <tr>
            <td colspan="2" style="padding:0; margin:0;">
                <img src="{{ route('temp.banner', basename($preview['banner_temp'])) }}" alt="Banner" style="width:100%; height:60px; object-fit:cover; border-radius:8px 8px 0 0; display:block;">
            </td>
        </tr>
    @endif
    <tr>
        <td style="vertical-align:top; width:120px;">
            @if ($preview['profile_pic'] ?? null)
                <img src="{{ asset('storage/' . ($preview['profile_pic'] ?? '')) }}" alt="Profile" style="border-radius:50%; width:50px; height:50px; display:block; margin-bottom:8px; border:1px solid #eeeeee;">
            @else
                <div style="width:50px; height:50px; border-radius:50%; background:#f3f3f3; border:1px solid #eeeeee; display:block; margin-bottom:8px; text-align:center; line-height:50px; color:#bbbbbb; font-size:24px;">👤</div>
            @endif
            <div style="font-size:14px; line-height:1.4; color:#333333;">
                @if ($preview['name'] ?? null)
                    <div style="font-weight:bold; margin-bottom:3px; color:#000000;">{{ $preview['name'] }}</div>
                @endif
                @if ($preview['job_title'] ?? null)
                    <div style="color:#666666; margin-bottom:3px;">{{ $preview['job_title'] }}</div>
                @endif
                @if ($preview['company_name'] ?? null)
                    <div style="color:#666666; margin-bottom:3px;">{{ $preview['company_name'] }}</div>
                @endif
                @if ($preview['phone_number'] ?? null)
                    <div style="color:#666666; margin-bottom:3px;">📞 {{ $preview['phone_number'] }}</div>
                @endif
                @if ($preview['location'] ?? null)
                    <div style="color:#666666; margin-bottom:3px;">📍 {{ $preview['location'] }}</div>
                @endif
            </div>
        </td>
        <td style="vertical-align:top; text-align:center; width:130px;">
            <div style="font-size:12px; color:#888888; margin-bottom:6px;">Connect with Me</div>
            <div style="width:90px; height:90px; display:inline-block; position:relative;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(config('app.url') . '/profile/' . ($profile ? $profile->id : '')) }}" alt="QR Code" style="width:90px; height:90px; border-radius:6px; border:1px solid #eeeeee;">
            </div>
            <div style="margin-top:6px;">
                <a href="{{ config('app.url') . '/profile/' . ($profile ? $profile->id : '') }}" style="color:#007bff; text-decoration:none; font-weight:500; font-size:12px;">My Digital Business Card</a>
            </div>
        </td>
    </tr>
</table>
<div style="text-align:center; color:#888888; font-size:11px; margin-top:6px; font-family:Arial, Helvetica, sans-serif;">
    Preview based on {{ $preview['email'] ?? '' }}
</div>
