<?php

return [
    'integrity' => [
        'enabled' => env('SECURITY_INTEGRITY_ENABLED', false),
        'fail_closed' => env('SECURITY_INTEGRITY_FAIL_CLOSED', true),
        'manifest' => storage_path('app/security/integrity.json'),
        'critical_files' => [
            'routes/web.php',
            'routes/auth.php',
            'routes/console.php',
            'bootstrap/app.php',
            'app/Http/Controllers/AdminCrudController.php',
            'app/Http/Controllers/Auth/AuthenticatedSessionController.php',
            'app/Http/Controllers/Auth/RegisteredUserController.php',
            'app/Http/Middleware/SecurityHeaders.php',
            'app/Http/Middleware/VerifyFileIntegrity.php',
            'config/auth.php',
            'config/session.php',
            'config/security.php',
            'config/permission.php',
        ],
    ],
];
