<?php
namespace codexten\yii\sms;

use yii\base\BaseObject;
use yii\base\Component;
use yii\di\Instance;
use yii\httpclient\Client;

/**
 * Class Driver
 * @package codexten\yii\sms
 * @author Jomon Johnson <jomonjohnson.dev@gmail.com>
 */
abstract class Driver extends BaseObject
{
    /**
     * Http Client.
     *
     * @var Client
     */
    public $client = Client::class;

    /**
     * To Numbers array.
     *
     * @var array
     */
    protected $recipients = [];
    /**
     * Message body.
     *
     * @var string
     */
    protected $body = "";

    public function init()
    {
        parent::init();
        $this->client = Instance::ensure($this->client, Client::class);
    }


    /**
     * String or Array of numbers.
     *
     * @param $numbers string|array
     *
     * @return $this
     * @throws \Exception
     */
    public function to($numbers)
    {
        $this->recipients = [];
        $recipients = is_array($numbers) ? $numbers : [$numbers];
        $recipients = array_map(function ($item) {
            return trim($item);
        }, array_merge($this->recipients, $recipients));
        $this->recipients = array_values(array_filter($recipients));
        if (count($this->recipients) < 1) {
            throw new \Exception("Message recipient could not be empty.");
        }

        return $this;
    }

    /**
     * Set text message body.
     *
     * @param $message string
     *
     * @return $this
     * @throws \Exception
     */
    public function message($message)
    {
        if (!is_string($message)) {
            throw new \Exception("Message text should be a string.");
        }
        if (trim($message) == '') {
            throw new \Exception("Message text could not be empty.");
        }
        $this->body = $this->unicodeMessageEncode($message);

        return $this;
    }

    public function unicodeMessageDecode($message)
    {
        if (stripos($message, '@U') !== 0) {
            return $message;
        }
        $message = substr($message, 2);
        $_message = hex2bin($message);
        $message = mb_convert_encoding($_message, 'UTF-8', 'UCS-2');
        return $message;
    }

    public function unicodeMessageEncode($message)
    {
        return '@U' . strtoupper(bin2hex(mb_convert_encoding($message, 'UCS-2', 'auto')));
    }

    /**
     * @return object
     */
    abstract public function send();

}