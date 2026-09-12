<?php
declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;
use App\Middleware\JwtAuthMiddleware;
use App\Middleware\CorsMiddleware;

// 预检路由
Router::addRoute(['OPTIONS'], '/{path:.*}', function () {
    return '';
});

// 公开健康检查
Router::addRoute(['GET'], '/v1/health', 'App\Controller\HealthController@index');

// AI 模块路由（JWT 鉴权保护）
Router::addGroup('/v1/ai', function () {
    Router::post('/chat/stream', 'App\Controller\AiController@stream');
}, [
    'middleware' => [JwtAuthMiddleware::class]
]);
