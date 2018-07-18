<?php

namespace zdy;

/**
 * 实现简单的路由
 * Class Route
 * @package zdy
 */
class Route
{
    // 路由参数键值
    public static $varKey = 'a';
    public static $pass = false;
    
    /**
     * 获取pathInfo
     * @return string
     */
    public static function pathInfo()
    {
        if (isset($_GET[static::$varKey])) {
            return $_GET[static::$varKey];
        }
        return '';
    }
    
    /**
     * 获取请求方法
     * @return string
     */
    public static function requestMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        }
        return 'GET';
    }
    
    /**
     * 处理get请求
     * @param $name
     * @param $value
     * @return bool|mixed
     */
    public static function get($name, $value)
    {
        // 非GET请求
        if (!static::requestMethod() == 'GET') {
            return false;
        }
        return self::run($name, $value);
    }
    
    /**
     * 处理post请求
     * @param $name
     * @param $value
     * @return bool|mixed
     */
    public static function post($name, $value)
    {
        // 非GET请求
        if (!static::requestMethod() == 'POST') {
            return false;
        }
        return self::run($name, $value, $_POST);
    }
    
    /**
     * 执行路由方法
     * @param       $name
     * @param       $value
     * @param array $params
     * @return bool|mixed
     */
    private static function run($name, $value, $params = [])
    {
        // 如果已经通过路由直接返回
        if (static::$pass) {
            return false;
        }
        
        // 没有匹配到
        if (!preg_match("/" . $name . "/i", static::pathInfo())) {
            return false;
        }
        
        static::$pass = true;
        return call_user_func_array($value, $params);
    }
    
}