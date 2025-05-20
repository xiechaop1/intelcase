<?php
/**
 * Created by PhpStorm.
 * User: leeyifiei
 * Date: 2019/2/25
 * Time: 9:16 PM
 */

namespace common\models;


class Msg extends \common\models\gii\Msg
{

    const MSG_STATUS_UNREAD       = 0;      // 未读
    const MSG_STATUS_READ         = 1;      // 已读
    const MSG_STATUS_DELETE       = 2;      // 删除

    public static $msgStatus2Name = [
        self::MSG_STATUS_UNREAD       => '未读',
        self::MSG_STATUS_READ         => '已读',
        self::MSG_STATUS_DELETE       => '删除',
    ];

    const MSG_TYPE_SYSTEM         = 1;      // 系统消息
    const MSG_TYPE_USER           = 2;      // 用户消息

    public static $msgType2Name = [
        self::MSG_TYPE_SYSTEM         => '系统消息',
        self::MSG_TYPE_USER           => '用户消息',
    ];

    public function behaviors()
    {

        return [
            [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }


}