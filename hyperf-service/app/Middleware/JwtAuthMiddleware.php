<?php
declare(strict_types=1);

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class JwtAuthMiddleware implements MiddlewareInterface
{
    #[Inject]
    protected ConfigInterface $config;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || !preg_match('/^Bearer\s+(.*?)$/i', $authHeader, $matches)) {
            return $this->unauthorizedResponse('未登录或 Token 缺失');
        }

        $token = $matches[1];
        $secret = $this->config->get('jwt.secret', 'your_shared_super_secure_jwt_secret_key_change_me_in_prod');
        $alg = $this->config->get('jwt.algorithm', 'HS256');

        try {
            $decoded = JWT::decode($token, new Key($secret, $alg));
            // 将用户信息写入请求上下文
            $request = $request->withAttribute('user_id', $decoded->sub ?? ($decoded->uid ?? 0))
                ->withAttribute('username', $decoded->username ?? '')
                ->withAttribute('role', $decoded->role ?? 'user');
            
            Context::set(ServerRequestInterface::class, $request);
        } catch (Throwable $e) {
            return $this->unauthorizedResponse('Token 已过期或签名无效: ' . $e->getMessage());
        }

        return $handler->handle($request);
    }

    protected function unauthorizedResponse(string $message): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        return $response->withStatus(401)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream(json_encode([
                'code' => 401,
                'msg'  => $message,
                'data' => (object)[]
            ], JSON_UNESCAPED_UNICODE)));
    }
}
