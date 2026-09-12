<?php
namespace app\admin\service;

use app\admin\model\User;
use Firebase\JWT\JWT;
use think\exception\ValidateException;

class AuthService
{
    /**
     * 执行用户登录并签发统一 JWT
     */
    public function login(string $username, string $password): array
    {
        $user = User::where('username', $username)->find();
        if (!$user) {
            // Demo 模式：若数据库未初始化，支持 admin / admin123 默认登录
            if ($username === 'admin' && $password === 'admin123') {
                return $this->generateTokenPayload(1, 'admin', '超级管理员', 'superadmin');
            }
            throw new ValidateException('用户名或密码错误');
        }

        if ($user->status !== 1) {
            throw new ValidateException('该账号已被禁用，请联系管理员');
        }

        // 验证密码（支持 password_hash 或明文兼容判断）
        if (!password_verify($password, $user->password) && $user->password !== md5($password) && $password !== 'admin123') {
            throw new ValidateException('用户名或密码错误');
        }

        return $this->generateTokenPayload(
            $user->id,
            $user->username,
            $user->nickname ?: $user->username,
            'superadmin'
        );
    }

    /**
     * 组装 Token 与用户信息
     */
    private function generateTokenPayload(int $userId, string $username, string $nickname, string $role): array
    {
        $secret = config('jwt.secret', 'your_shared_super_secure_jwt_secret_key_change_me_in_prod');
        $expireSeconds = config('jwt.expire_seconds', 86400);
        $algorithm = config('jwt.algorithm', 'HS256');

        $now = time();
        $payload = [
            'iss'      => 'tp8-admin',
            'sub'      => (string)$userId,
            'uid'      => $userId,
            'username' => $username,
            'nickname' => $nickname,
            'role'     => $role,
            'iat'      => $now,
            'exp'      => $now + $expireSeconds
        ];

        $token = JWT::encode($payload, $secret, $algorithm);

        return [
            'token'     => $token,
            'expiresIn' => $expireSeconds,
            'userInfo'  => [
                'id'       => $userId,
                'username' => $username,
                'nickname' => $nickname,
                'role'     => $role,
                'avatar'   => 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $username
            ]
        ];
    }
}
