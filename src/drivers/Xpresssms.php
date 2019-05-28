<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 8/3/19
 * Time: 4:21 PM
 */

namespace codexten\yii\sms\drivers;

use codexten\yii\sms\Driver;

/**
 * Class Sms
 *
 * @package enapp\common\components
 */
class Xpresssms extends Driver
{
    const ROUTE_PROMOTIONAL = 1;
    const ROUTE_TRANSACTIONAL = 2;
    const ROUTE_OTP = 4;

    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;
    /**
     * @var
     */
    public $sender;
    public $route = self::ROUTE_OTP;
    /**
     * @var bool
     */
    public $testMode = false;
    /**
     * @var string
     */
    protected $url = 'http://sms.xpresssms.in/api/api.php';

    /**
     * Send text message and return response.
     *
     * @return void
     * @throws \yii\base\InvalidConfigException
     */
    public function send()
    {
        $numbers = implode(",", $this->recipients);
        $data = [
            'login_name' => $this->username,
            'api_password' => $this->password,
            'ver' => '1',
            'mode' => '1',
            'action' => 'push_sms',
            'type' => 1,
            'route' => $this->route,
            'message' => $this->body,
            'number' => $numbers,
            'sender' => $this->sender,
        ];

        $response = $this->client
            ->createRequest()
            ->setMethod('GET')
            ->setUrl($this->url)
            ->setData($data)
            ->send();

        return true;


//        $data = $this->getResponseData($response);

//        return $data['status'];
//        $time = asDatetime(time());
//        exec("echo '{$time} : {$numbers}' >> /tmp-dir/sms.txt");
//        return (object)array_merge($data, ["status" => true]);
    }

}