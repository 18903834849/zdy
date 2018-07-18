<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3
 * Time: 13:38
 */

namespace zdy;

/**
 * api接口
 * Class Api
 * @package zdy
 */

/**
 * api接口响应类
 * Class Response
 * @package zdy
 */
class Response
{

    // 响应码
    private static $code = null;
    // 响应消息
    private static $message = null;
    // 返回数据
    private static $data = [];
    //
    private static $init = null;

    /**
     * 返回json数据
     * @param $data
     * @return \think\response\Json
     */
    private static function json($data)
    {
        return json_encode($data);
    }

    public static function init()
    {
        if (empty(self::$init)) {
            self::$init = new self();
        }
        return self::$init;
    }

    /**
     * 发送数据
     * @param array $data
     * @param null $msg
     * @param null $code
     * @return \think\response\Json
     */
    public static function send($data = [], $message = null, $code = null)
    {
        self::init()->message($message)->code($code);
        /*------------------错误--------------*/
        if ($data === false) {
            $result['code'] = is_null(self::$code) ? "0" : self::$code;
            $result['msg'] = is_null(self::$message) ? '请求失败' : self::$message;
        } else {
            /*------------------正确--------------*/
            $data = is_array($data) ? json_decode(json_encode($data), true) : $data;
            $result['code'] = '1'; // 请求成功全部返回1
            $result['msg'] = is_null(self::$message) ? '请求成功' : self::$message;
            $result['data'] = self::_arrayFilterNull($data);// 过滤数组null值
        }
        return self::json($result);
    }

    /**
     * 设置消息
     * @param null $message
     * @return static
     */
    public function message($message = null)
    {
        if (!is_null($message)) {
            self::$message = $message;
        }
        return $this;
    }

    /**
     * 设置错误码
     * @param $code
     * @return static
     */
    public function code($code = null)
    {
        if (!is_null($code)) {
            self::$code = $code;
        }
        return $this;
    }

    /**
     * 过滤多维数组空值
     * @param $array
     */
    private static function _arrayFilterNull(&$array)
    {
        if (is_array($array)) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    static::_arrayFilterNull($value);
                } else {
                    $value = $value . '';
                }
            }
        }
        return $array;
    }

}