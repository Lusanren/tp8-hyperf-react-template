<?php
namespace app\admin\service;

use app\admin\model\User;

class UserService
{
    /**
     * 获取用户个人信息
     */
    public function getProfile(int $userId): array
    {
        $user = User::find($userId);
        if (!$user) {
            return [
                'id'       => $userId,
                'username' => 'admin',
                'nickname' => '超级管理员',
                'role'     => 'superadmin',
                'avatar'   => 'https://api.dicebear.com/7.x/avataaars/svg?seed=admin'
            ];
        }

        return $user->toArray();
    }

    /**
     * 获取用户有权访问的动态菜单树
     */
    public function getUserMenus(int $userId): array
    {
        return [
            [
                'id'        => 1,
                'parentId'  => 0,
                'title'     => '控制台概览',
                'path'      => '/dashboard',
                'component' => 'pages/dashboard/index',
                'icon'      => 'LayoutDashboard',
                'sort'      => 1
            ],
            [
                'id'        => 2,
                'parentId'  => 0,
                'title'     => 'AI 对话助手',
                'path'      => '/ai-chat',
                'component' => 'pages/ai-chat/index',
                'icon'      => 'Bot',
                'sort'      => 2
            ]
        ];
    }
}
