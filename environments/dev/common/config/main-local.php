<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=121.40.176.149;dbname=ota',
            'username' => 'ota',
            'password' => '123456',
            'charset' => 'utf8mb4',
            'tablePrefix' => 'o_'
        ],
        'easyeuro' => [
            'class' => 'common\extensions\pay\easyeuro\Service',
            'mchId' => '124520000087',
            'signKey' => 'eea6248507c886b4f63b7201b448015c',
        ],
        'globepay' => [
            'class' => 'ota\globepay\Application',
            'partnerCode' => 'PINE',
            'credentialCode' => 'xOjMTaG0Tk7fqNYjsNqDKXtJvE9MFCqC'
        ],
        'pkfare' => [
            'class' => '\ota\pkfare\Application',
            'partnerId' => 'GsieUOkmCKlbu6MQK/fWENSnEeA=',
            'partnerKey' => 'M2EwMWU0NjY4NDNjZTllYTM3ODBkYjA2ZGJjZWZiOWU=',
            'debug' => true
        ],
        'googlemap' => [
            'class' => 'liyifei\googleapi\GoogleApiLibrary',
            'geocode_api_key' => 'AIzaSyCwAw5r1MJRTGQoz_phkuJhx37BJZXNg8E',
            'geocodeApiUrl' => 'https://maps.google.cn',
        ],
        // 国际验证码短信
        'externalVerifyCodeSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_159626561',
        ],
        // 国内验证码短信
        'internalVerifyCodeSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_159626563',
        ],
        // 国际旅行团确认短信
        'externalTravelConfirmSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164100514',
        ],
        // 国内旅行团确认短信
        'internalTravelConfirmSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164150450',
        ],
        // 国际婚纱摄影确认短信
        'externalMarryConfirmSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164095549',
        ],
        // 国内婚纱摄影确认短信
        'internalMarryConfirmSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164155463',
        ],
        // 国际接送机确认短信
        'externalTransferConfirmSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164095534',
        ],
        // 国内接送机确认短信
        'internalTransferConfirmSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164150448',
        ],
        // 国际机票出票短信
        'externalTicketingSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164100500',
        ],
        // 国内机票出票短信
        'internalTicketingSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_164095528',
        ],
        // 国际支付通知短信
        'externalPaymentSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_170330413',
        ],
        // 国内支付通知短信
        'internalPaymentSms' => [
            'class' => '\liyifei\aliyunsms\Sms',
            'accessKeyId' => 'LTAIMEnr00sHf4bO',
            'accessKeySecret' => 'LQpNq642J4K0KlDynbi1vdEzszz3EE',
            'signName' => '留学僧',
            'templateId' => 'SMS_170156388',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
