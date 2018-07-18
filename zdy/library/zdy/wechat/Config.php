<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

namespace zdy\wechat;

class Config
{

    protected static $_config = [
        'appid' => 'wxf854059ff2a1243b',
        'appsecret' => '11351fedf52a81095a5c780e3bb4a7e8',
        'token' => 'f9fa233757876696775dd8054fccc5d7',
        'encodingaeskey' => '',
        'mch_id' => "", // 配置商户支付参数
        'mch_key' => '',
        'ssl_key' => '',
        'ssl_cer' => '',
    ];

    function __construct($config = [])
    {
        self::$_config = array_merge(self::$_config, $config);
    }

    public static function set($name, $value)
    {
        self::$_config[$name] = $value;
    }

    public static function get($name = '')
    {
        return isset(self::$_config[$name]) ? self::$_config[$name] : self::$_config;
    }
}
