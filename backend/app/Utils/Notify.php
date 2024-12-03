<?php

namespace App\Utils;

class Notify
{
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const DANGER = 'danger';
    const INFO   = 'info';

    /**
     * Make a notification object with given type and message
     * 
     * @param string $type Defines the notification type
     * @param ?string $msg Defines the notification message
     * 
     * @return array
     */
    private static function notify(string $type, ?string $msg = null): array
    {
        $notify = ['type' => $type];
        if ($msg != null) {
            $notify['msg'] = $msg;
        }
        return $notify;
    }

    /**
     * Make a success notification object with given message
     * 
     * @param ?string $msg Defines the notification message
     * 
     * @return array
     */
    public static function success(?string $msg = null): array
    {
        return ['notify' => self::notify(self::SUCCESS, $msg)];
    }

    /**
     * Make a warning notification object with given message
     * 
     * @param ?string $msg Defines the notification message
     * 
     * @return array
     */
    public static function warning(?string $msg = null): array
    {
        return ['notify' => self::notify(self::WARNING, $msg)];
    }

    /**
     * Make a error notification object with given message
     * 
     * @param ?string $msg Defines the notification message
     * 
     * @return array
     */
    public static function error(?string $msg = null): array
    {
        return ['notify' => self::notify(self::DANGER, $msg)];
    }

    /**
     * Make a info notification object with given message
     * 
     * @param ?string $msg Defines the notification message
     * 
     * @return array
     */
    public static function info(?string $msg = null): array
    {
        return ['notify'=> self::notify(self::INFO, $msg)];
    }
}
