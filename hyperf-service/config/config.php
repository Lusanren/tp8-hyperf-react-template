<?php
declare(strict_types=1);

use function Hyperf\Support\env;

return [
    'app_name' => env('APP_NAME', 'hyperf-service'),
    'app_env' => env('APP_ENV', 'dev'),
    'scan_cacheable' => env('SCAN_CACHEABLE', false),
    'jwt' => [
        'secret' => env('JWT_SECRET', 'your_shared_super_secure_jwt_secret_key_change_me_in_prod'),
        'algorithm' => env('JWT_ALGORITHM', 'HS256'),
    ],
    'ai' => [
        'api_key' => env('OPENAI_API_KEY', ''),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('AI_MODEL_NAME', 'gpt-3.5-turbo'),
    ]
];
