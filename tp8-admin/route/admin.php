<?php
use think\facade\Route;

// 全局预检跨域
Route::options(':any', function() {
    return response('', 204);
})->middleware(\app\admin\middleware\Cors::class);

// 开放接口（无需登录）
Route::group('auth', function () {
    Route::post('login', 'app\admin\controller\AuthController@login');
    Route::post('logout', 'app\admin\controller\AuthController@logout');
})->middleware(\app\admin\middleware\Cors::class);

// 受保护的业务接口（需 JWT 认证）
Route::group('', function () {
    // 用户个人与权限接口
    Route::get('user/profile', 'app\admin\controller\UserController@profile');
    Route::get('user/menus', 'app\admin\controller\UserController@menus');

    // 可以在此根据业务继续扩展：
    // Route::resource('customer', 'app\admin\controller\CustomerController');
})->middleware([
    \app\admin\middleware\Cors::class,
    \app\admin\middleware\JwtAuth::class,
]);
