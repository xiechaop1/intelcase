<?php
/**
 * Created by PhpStorm.
 * User: liyifei
 * Date: 2019/4/8
 * Time: 下午4:40
 */

namespace common\definitions;


class Admin
{
    const ROLE_ALL      = -1;       // 所有角色
    const ROLE_PLATFORM = 0;        // 总管理员
    const ROLE_EDITOR   = 3;        // 编辑

    const ROLE_USER     = 4;        // 用户

    const ROLE_VIDEO    = 5;        // 视频管理员



    public static $adminRole2Name = [
        self::ROLE_ALL      => '所有角色',
        self::ROLE_PLATFORM => '平台管理员',
        self::ROLE_EDITOR   => '编辑',
        self::ROLE_USER     => '用户',
        self::ROLE_VIDEO    => '视频管理员',
    ];

    public static $adminRoleEdit2Name = [
        self::ROLE_PLATFORM => '平台管理员',
        self::ROLE_EDITOR   => '编辑',
        self::ROLE_USER     => '用户',
        self::ROLE_VIDEO    => '视频管理员',
    ];


}