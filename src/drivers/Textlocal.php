<?php

namespace codexten\yii\sms\drivers;

use codexten\yii\sms\Driver;
use yii\httpclient\Response;

/**
 * Class Textlocal
 *
 * @package codexten\yii\sms\drivers
 * @author Jomon Johnson <jomonjohnson.dev@gmail.com>
 */
class Textlocal extends Driver
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var
     */
    public $hash;
    /**
     * @var
     */
    public $sender;
    /**
     * @var bool
     */
    public $testMode = false;
    /**
     * @var string
     */
    protected $url = 'http://api.textlocal.in/send/';

    /**
     * Send text message and return response.
     *
     * @return mixed
     */
    public function send()
    {
        $numbers = implode(",", $this->recipients);

        $response = $this->client
            ->createRequest()
            ->setMethod('POST')
            ->setUrl($this->url)
            ->setData([
                "username" => $this->username,
                "hash" => $this->hash,
                "numbers" => $numbers,
                "sender" => urlencode($this->sender),
                "message" => $this->body,
                'unicode' => true,
                'test' => $this->testMode,
            ])
            ->send();

        $data = $this->getResponseData($response);
        $time = asDatetime(time());
        exec("echo '{$time} : {$numbers}' >> /tmp-dir/sms.txt");

        return (object)array_merge($data, ["status" => true]);
    }

    /**
     * Get the response data.
     *
     * @param  Response $response
     *
     * @return array|object
     */
    protected function getResponseData($response)
    {
        if ($response->getStatusCode() != 200) {
            return ["status" => false, "message" => "Request Error. "];
        }
        $data = $response->data;
        if ($data["status"] != "success") {
            return ["status" => false, "message" => "Something went wrong.", "data" => $data];
        }

        return $data;
    }

}