<?php

return [
    // JWT 签名密钥（与 FastAPI 保持一致）
    'secret'         => env('JWT.SECRET', 'your_shared_super_secure_jwt_secret_key_change_me_in_prod'),
    // Token 有效期（秒）默认 24 小时
    'expire_seconds' => env('JWT.EXPIRE_SECONDS', 86400),
    // 加密算法
    'algorithm'      => env('JWT.ALGORITHM', 'HS256'),
];
