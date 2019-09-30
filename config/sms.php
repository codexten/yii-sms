<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 8/3/19
 * Time: 11:40 AM
 */

use \codexten\yii\sms\Sms;

return [
    'components' => [
        'sms' => [
            'class' => Sms::class,
            'defaultDriver' => $params['sms.defaultDriver'],
            'testMode' => $params['sms.testMode'],
            'drivers' => [
                Sms::DRIVER_TEXTLOCAL => [
                    'class' => \codexten\yii\sms\drivers\Textlocal::class,
                    'username' => $params['textlocal.username'],
                    'hash' => $params['textlocal.hash'],
                    'sender' => $params['textlocal.sender'],
                ],
                Sms::DRIVER_XPRESSSMS => [
                    'class' => \codexten\yii\sms\drivers\Xpresssms::class,
                    'username' => $params['xpresssms.username'],
                    'password' => $params['xpresssms.password'],
                    'sender' => $params['xpresssms.sender'],
                ],
            ],
        ],
    ],
];