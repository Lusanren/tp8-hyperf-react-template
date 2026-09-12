<?php
namespace app\admin\middleware;

use think\Request;
use think\Response as TpResponse;

/**
 * 跨域处理中间件（主要用于本地独立开发调试）
 */
class Cors
{
    public function handle(Request $request, \Closure $next)
    {
        $header = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
            'Access-Control-Max-Age'           => '1728000',
        ];

        if ($request->isOptions()) {
            return TpResponse::create('', 'html', 204)->header($header);
        }

        $response = $next($request);
        return $response->header($header);
    }
}
