<?php
namespace app\admin\controller;

use app\admin\service\AuthService;
use app\admin\validate\AuthValidate;
use app\common\Response;
use think\Request;
use think\response\Json;

class AuthController
{
    /**
     * 登录接口: POST /auth/login
     */
    public function login(Request $request, AuthService $authService, AuthValidate $validate): Json
    {
        $params = $request->post();
        
        if (!$validate->scene('login')->check($params)) {
            return Response::error($validate->getError(), 400);
        }

        $data = $authService->login($params['username'], $params['password']);
        return Response::success($data, '登录成功');
    }

    /**
     * 退出登录: POST /auth/logout
     */
    public function logout(): Json
    {
        // 无状态 JWT 直接返回成功，前端清除 Token
        return Response::success(null, '退出登录成功');
    }
}
