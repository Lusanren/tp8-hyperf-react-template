<?php
namespace app\admin\controller;

use app\admin\service\UserService;
use app\common\Response;
use think\Request;
use think\response\Json;

class UserController
{
    /**
     * 获取当前登录用户信息: GET /user/profile
     */
    public function profile(Request $request, UserService $userService): Json
    {
        $userId = (int)($request->userId ?? 1);
        $data = $userService->getProfile($userId);
        return Response::success($data);
    }

    /**
     * 获取当前用户菜单列表: GET /user/menus
     */
    public function menus(Request $request, UserService $userService): Json
    {
        $userId = (int)($request->userId ?? 1);
        $menus = $userService->getUserMenus($userId);
        return Response::success($menus);
    }
}
