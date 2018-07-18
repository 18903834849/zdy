<?php

namespace zdy;

/**
 *  极光推送
 * @author Administrator
 */
class JPush
{
    public static function init()
    {
        return include_once __DIR__ . '/jpush/autoload.php';
    }
}
