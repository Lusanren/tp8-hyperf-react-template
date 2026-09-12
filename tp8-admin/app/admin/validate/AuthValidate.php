<?php
namespace app\admin\validate;

use think\Validate;

class AuthValidate extends Validate
{
    protected $rule = [
        'username' => 'require|min:3|max:32',
        'password' => 'require|min:6|max:64',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'username.min'     => '用户名至少3个字符',
        'username.max'     => '用户名最多32个字符',
        'password.require' => '密码不能为空',
        'password.min'     => '密码至少6个字符',
    ];

    protected $scene = [
        'login' => ['username', 'password'],
    ];
}
