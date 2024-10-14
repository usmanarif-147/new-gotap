<?php

return [
    'pkpass' => [
        'certificate_path' => base_path(env('PKPASS_CERTIFICATE_PATH', 'config/certificates/Certificates.p12')),
        'certificate_password' => env('PKPASS_CERTIFICATE_PASSWORD', 'otap123otap'),
        'passTypeIdentifier' => env('PKPASS_TYPE_IDENTIFIER', 'pass.com.horizam.otap'),
        'teamIdentifier' => env('PKPASS_TEAM_IDENTIFIER', 'X3739468C2'),
    ],
];