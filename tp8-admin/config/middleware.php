<?php
// 中间件配置
return [
    // 别名或分组
    'alias'    => [
        'jwt'  => \app\admin\middleware\JwtAuth::class,
        'cors' => \app\admin\middleware\Cors::class,
    ],
    // 优先级设置，此处的顺序决定了中间件的执行顺序
    'priority' => [],
];
