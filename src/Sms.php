<?php

namespace codexten\yii\sms;

use codexten\yii\sms\drivers\Textlocal;
use yii\base\Component;
use yii\di\Instance;

/**
 * Class SmsManager
 * @package codexten\yii\sms
 */
class Sms extends Component
{
    const DRIVER_TEXTLOCAL = 'textLocal';

    public $defaultDriver = 'textLocal';
    /**
     * @var Driver[]
     */
    public $drivers;
    /**
     * @var Driver
     */
    protected $driver = null;

    public static $componentName = 'smsManager';

    public function with($diver)
    {
        $this->driver = Instance::ensure($this->drivers[$diver]);
        return $this;
    }

    public function send($message, $numbers)
    {
        if ($this->driver == null) {
            $this->with($this->defaultDriver);
        }

        return $this->driver->message($message)->to($numbers)->send();
    }

    /**
     * @return SmsManager Get SmsManager component
     */
    public static function getComponent()
    {
        try {
            return \Yii::$app->get(static::$componentName);
        } catch (InvalidConfigException $ex) {
            return null;
        }
    }

}