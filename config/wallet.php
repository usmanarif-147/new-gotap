<?php

return [
    'pkpass' => [
        'certificate_path' => base_path(env('PKPASS_CERTIFICATE_PATH', 'config/certificates/Certificates.p12')),
        'certificate_password' => env('PKPASS_CERTIFICATE_PASSWORD', '11223344'),
        'passTypeIdentifier' => env('PKPASS_TYPE_IDENTIFIER', 'pass.com.horizam.gotap'),
        'teamIdentifier' => env('PKPASS_TEAM_IDENTIFIER', '6G6B9728RW'),
    ],
];