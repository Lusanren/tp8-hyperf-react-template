<?php

return [
    // 默认数据库驱动
    'default'         => env('DATABASE.TYPE', 'mysql'),

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'            => env('DATABASE.TYPE', 'mysql'),
            // 服务器地址
            'hostname'        => env('DATABASE.HOSTNAME', '127.0.0.1'),
            // 数据库名
            'database'        => env('DATABASE.DATABASE', 'tp8_admin_db'),
            // 用户名
            'username'        => env('DATABASE.USERNAME', 'root'),
            // 密码
            'password'        => env('DATABASE.PASSWORD', 'root_password_123'),
            // 端口
            'hostport'        => env('DATABASE.HOSTPORT', '3306'),
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8mb4
            'charset'         => env('DATABASE.CHARSET', 'utf8mb4'),
            // 数据库表前缀
            'prefix'          => '',
            // 数据库调试模式
            'debug'           => env('DATABASE.DEBUG', true),
        ],
    ],
];
