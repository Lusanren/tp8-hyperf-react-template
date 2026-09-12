<?php
namespace app\admin\middleware;

use app\common\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\Request;
use Throwable;

/**
 * 统一 JWT 鉴权中间件
 */
class JwtAuth
{
    public function handle(Request $request, \Closure $next)
    {
        $authHeader = $request->header('Authorization', '');
        
        if (empty($authHeader) || !preg_match('/^Bearer\s+(.*?)$/i', $authHeader, $matches)) {
            return Response::error('未登录或 Token 缺失', 401, null, 401);
        }

        $token = $matches[1];
        $secret = config('jwt.secret', 'your_shared_super_secure_jwt_secret_key_change_me_in_prod');
        $alg = config('jwt.algorithm', 'HS256');

        try {
            $decoded = JWT::decode($token, new Key($secret, $alg));
            // 将解析出的用户信息注入请求上下文
            $request->userId = $decoded->sub ?? ($decoded->uid ?? 0);
            $request->username = $decoded->username ?? '';
            $request->roleKey = $decoded->role ?? 'user';
        } catch (Throwable $e) {
            return Response::error('Token 已过期或签名无效: ' . $e->getMessage(), 401, null, 401);
        }

        return $next($request);
    }
}
