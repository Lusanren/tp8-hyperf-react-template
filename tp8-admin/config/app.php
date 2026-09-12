<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用名称
    'app_name'               => 'tp8-admin',
    // 应用地址
    'app_host'               => '',
    // 应用调试模式
    'app_debug'              => env('APP_DEBUG', true),
    // 默认时区
    'default_timezone'       => env('APP.DEFAULT_TIMEZONE', 'Asia/Shanghai'),
    // 默认全局异常处理类
    'exception_handle'       => '\\app\\common\\exception\\HttpExceptionHandle',
];
