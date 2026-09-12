<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $name = 'sys_users';
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    // 隐藏敏感字段
    protected $hidden = ['password'];
}
