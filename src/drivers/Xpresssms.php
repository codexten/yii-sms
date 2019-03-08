<?php
/**
 * Created by PhpStorm.
 * User: jomon
 * Date: 8/3/19
 * Time: 4:21 PM
 */

namespace codexten\yii\sms\drivers;

use codexten\yii\sms\Driver;
use Exception;
use function implode;
use function is_array;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;

/**
 * Class Sms
 *
 * @package enapp\common\components
 * @
 */
class Xpresssms extends Driver
{
    /* login name for the testlocal sms gateway*/
    public $username;

    /* api key of testlocal sms gateway*/
    public $apiKey;

    /* login name for the xpress sms gateway*/
    public $loginName;

    /* api password for the xpress sms gateway*/
    public $apiPassword;

    /* sender id will get from xpress sms and it configure in component*/
    public $senderId;

    /* three types of route are given by xpress sms and it configure in component */
    public $route;

    /* url for the testlocal sms gateway*/
    const TEST_LOCAL_REQUEST_URL = 'https://api.textlocal.in/';

    //url for the xpress sms
    const XPRESS_SMS_REQUEST_URL = 'http://sms.xpresssms.in/api/api.php';

    //TYPES OF SMS ROUTE
    const ROUTE_PROMOTIONAL_SMS = 1;
    const ROUTE_TRANSACTIONAL_SMS = 2;
    const ROUTE_OTP_SMS = 4;

    //SMS TYPE
    const TYPE_NORMAL_SMS = 1;
    const TYPE_UNICODE_SMS = 2;

    /**
     * @param $numbers
     *
     * @return bool
     */
    public function send($numbers, $message, $type = false)
    {
        if (is_array($numbers)) {
            $numbers = implode(',', $numbers);
        }

        if (Settings::get('sms.xpress')) {
            $config = [
                'login_name' => $this->loginName,
                'api_password' => $this->apiPassword,
                'ver' => '1',
                'mode' => '1',
                'action' => 'push_sms',
                'type' => $type,
                'route' => $this->route,
                'message' => $message,
                'number' => $numbers,
                'sender' => $this->senderId,
            ];

            try {
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl(static::XPRESS_SMS_REQUEST_URL)
                    ->setData($config)
                    ->send();
                if ($response->isOk) {
                    return true;
                }
            } catch (Exception $e) {
                die('Error: ' . $e->getMessage());
            }
        } else {
            $config = [
                'username' => $this->username,
                'apiKey' => $this->apiKey,
                'message' => rawurlencode($message),
                'numbers' => $numbers,
                'sender' => null,
                'schedule_time' => null,
                'test' => false,
                'receipt_url' => null,
                'custom' => null,
                'optouts' => false,
                'simple_reply' => false,
            ];

            try {
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('post')
                    ->setUrl(static::TEST_LOCAL_REQUEST_URL . '/send/')
                    ->setData($config)
                    ->send();
                if ($response->isOk) {
                    return false;
                }
            } catch (Exception $e) {
                die('Error: ' . $e->getMessage());
            }
        }

        return false;
    }

    public function sendByTemplate($number, $templateCode, $type = false, $params = [])
    {
        if (($smsTemplate = SmsTemplate::find()->byCode($templateCode)->active()->one()) === null) {
            return new InvalidConfigException('Invalid templated code');
        }
        $message = StringHelper::replaceByArray($smsTemplate->message, $params);

        return $this->send($number, $message, $type);
    }
}