<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 8/3/19
 * Time: 11:40 AM
 */

return [
    'components' => [
        'sms' => [
            'class' => \codexten\yii\sms\Sms::class,
            'defaultDriver' => $params['sms.defaultDriver'],
            'drivers' => [
                'textlocal' => [
                    'class' => \codexten\yii\sms\drivers\Textlocal::class,
                    'username' => $params['textlocal.username'],
                    'hash' => $params['textlocal.hash'],
                    'sender' => $params['textlocal.sender'],
                ],
            ],
        ],
    ],
];