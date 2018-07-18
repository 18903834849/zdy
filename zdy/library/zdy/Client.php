<?php

namespace zdy;

/**
 * 客户端助手类
 * Class Client
 * @package zdy
 */
class Client
{
    
    /**
     * 获取当前浏览器
     * @return mixed
     */
    public static function getBrowser()
    {
        $browser = array('IE', 'QQBrowser', 'UCBrowser', 'Firefox', 'Chrome', 'Opera', 'Safari');
        foreach ($browser as $v) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $v)) {
                return $v;
            }
        }
        return $_SERVER['HTTP_USER_AGENT'];
    }
    
    /**
     * 获取当前系统
     * @return mixed
     */
    public static function getSystem()
    {
        $agents = array("Windows", "Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
        foreach ($agents as $v) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $v)) {
                return $v;
            }
        }
        return $_SERVER['HTTP_USER_AGENT'];
    }
    
    /**
     * 获取IP
     * @param bool $type
     * @return mixed
     */
    public static function getIP($type = false)
    {
        static $ip = NULL;
        $type = $type ? 1 : 0;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
    
}