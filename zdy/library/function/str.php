<?php

if (!function_exists('gbk2utf8')) {
    /**
     * 将gbk字符串转换成utf8字符串
     * @param string $str
     * @return string
     */
    function gbk2utf8($str)
    {
        return mb_convert_encoding($str, 'utf-8', 'gbk');
    }
}

if (!function_exists('utf82gbk')) {
    /**
     * 将utf8字符串转换成gbk字符串
     * @param string $str
     * @return string
     */
    function utf82gbk($str)
    {
        return mb_convert_encoding($str, 'gbk', 'utf-8');
    }
}

if (!function_exists('str_add_color')) {
    /**
     * 字符串添加颜色
     * @param string $str     源字符串
     * @param string $replace 要设置的字符串
     * @param string $color   设置的颜色 默认红色
     * @return mixed
     */
    function str_add_color($string, $replace, $color = 'red')
    {
        return str_ireplace($replace, '<b style="color:' . $color . '">' . $replace . '</b>', $string);
    }
}

if (!function_exists('str_get_center')) {
    /**
     * 获取字符串中间
     * @param string $content  源字符串
     * @param string $leftStr  左边字符串
     * @param string $rightStr 右边字符串
     * @return string
     */
    function str_get_center($content, $leftStr, $rightStr)
    {
        $b = mb_strpos($content, $leftStr) + mb_strlen($leftStr);
        $e = mb_strpos($content, $rightStr) - $b;
        return mb_substr($content, $b, $e);
    }
}

if (!function_exists('str_get_left')) {
    /**
     * 从左边开始截取字符串/支持中文截取
     * @param string $string 源字符串
     * @param int    $num    获取长度
     * @return string
     */
    function str_get_left($string, $num)
    {
        return get_substr($string, $num);
    }
}

if (!function_exists('get_substr')) {
    /**
     * 实现中文字串截取无乱码的方法
     * @param $string $string 截取的字符串
     * @param $start  $start 截取开始位置
     * @param $length $length 截取的长度
     * @return string
     */
    function get_substr($string, $start, $length = null)
    {
        if (empty($length)) {
            $length = $start;
            $start  = 0;
        }
        if (mb_strlen($string, 'utf-8') > $length) {
            $str = mb_substr($string, $start, $length, 'utf-8');
            return $str . '';
        } else {
            return $string;
        }
    }
}

if (!function_exists('uuid')) {
    /**
     * 返回一个的唯一编号,常用与订单生成
     * @return string
     */
    function uuid()
    {
        static $i = 0;
        $i++;
        $microtime = substr(microtime(), 2, 4) + $i;
        $uuid      = time() . sprintf("%04d", $microtime) . rand();
        return substr(md5($uuid), 16);
    }
}

if (!function_exists('size2mb')) {
    /**
     * 字节数转换成带单位的
     * @param integer $size   字节大小
     * @param integer  $digits 保留小数位
     * @return string
     */
    function size2mb($size, $digits = 2)
    {
        // digits，要保留几位小数
        $i    = floor(log($size, 1024));
        $size = round($size / pow(1024, $i), $digits);
        $unit = substr(' KMGTP', $i, 1) . "B";
        return "$size $unit";
    }
}

if (!function_exists('pad_zero')) {
    /**
     *  补零
     * @param     $string
     * @param int $length
     * @return mixed
     */
    function pad_zero($string, $length = 2)
    {
        return $string;
    }
}

if (!function_exists('retain_decimal')) {
    /**
     * 保留小数,不四舍五入
     * @param  string $k
     * @return string
     */
    function retain_decimal($number, $num = 2)
    {
        $arr    = explode('.', $number);
        $a      = substr($arr[1], 0, $num);
        $number = empty($a) ? '00' : $a;
        $ok     = $arr[0] . '.' . $number;
        return $ok;
    }
}

if (!function_exists('html_get_img')) {
    /**
     * 获取html标签中的src属性
     * @return mixed
     */
    function html_get_img($content)
    {
        preg_match_all("<img.*?src=\"(.*?.*?)\".*?>", $content, $match);
        return isset($match[1]) ? $match[1] : [];
    }
}

if (!function_exists('images2layer')) {
    /**
     * 将图片集列表转换到layer.photo插件所需要否数组格式
     * @param array $data /数组中包含图片名&&图片路径
     * @return string
     */
    function images2layer($data)
    {
        $data['title'] = '';
        $data['id']    = '';
        $data['start'] = 1;
        foreach ($data as $k => $v) {
            $src            = $v['image'];
            $a['alt']       = $v['name'];
            $a['pid']       = $k;
            $a['src']       = $src;
            $a['thumb']     = $src;
            $data['data'][] = $a;
        }
        return json_encode($data);
    }
}

if (!function_exists('rand_float')) {
    /**
     * 取随机小数
     * @param integer $min       最小数
     * @param integer $max       最大数
     * @param integer $precision 保留位数
     * @return integer
     */
    function rand_float($min = 0, $max = 1, $precision = 0)
    {
        if (is_float($min + 0) || is_float($max + 0)) {
            return round($min + mt_rand() / mt_getrandmax() * ($max - $min), $precision);
        } else {
            return mt_rand($min, $max);
        }
    }
}

if (!function_exists('str_encrypt')) {
    /**
     * 字符串加密/解密
     * @param string $string
     * @param string $operation
     * @param string $key
     * @param int    $expire
     * @return bool|mixed|string
     */
    function str_encrypt($string, $operation, $key = '', $expire = 0)
    {
        static $_StringCrypt = [];
        if (empty($_StringCrypt)) {
            $_StringCrypt = new \zdy\StringCrypt();
        }
        if ($operation == 'D') {
            return $_StringCrypt->decode($string, $key);
        } else {
            return $_StringCrypt->encode($string, $key, $expire);
        }
    }
}

if (!function_exists('str_encode')) {
    /**
     * 字符串加密
     * @param        $str
     * @param string $key
     * @return bool|mixed|string
     */
    function str_encode($str, $key = 'zhengdongying')
    {
        return str_encrypt($str, 'E', md5($key));
    }
}

if (!function_exists('str_decode')) {
    /**
     * 字符串解密
     * @param        $str
     * @param string $key
     * @return bool|mixed|string
     */
    function str_decode($str, $key = 'zhengdongying')
    {
        return str_encrypt($str, 'D', md5($key));
    }
}

if (!function_exists('str_in_en')) {
    /**
     * 检查字符串是否含有中文
     * @param $str
     * @return bool
     */
    function str_in_en($str)
    {
        return boolval(preg_match("/[\x7f-\xff]/", $str));
    }
    
}

if (!function_exists('str_hide')) {
    /**
     * 字符串掩码
     * @param $string
     * @return string
     */
    function str_hide($string)
    {
        $a = substr($string, 0, 3);
        $c = substr($string, -3, 3);
        $b = str_repeat('*', strlen($string) - 6);
        return $a . $b . $c;
    }
}

if (!function_exists('hide_tel')) {
    /**
     * 隐藏手机号
     * @param string $tel
     * @return string
     */
    function hide_tel($tel)
    {
        $a = substr($tel, 0, 3);
        $c = substr($tel, -4, 4);
        $b = str_repeat('*', 4);
        return $a . $b . $c;
    }
}

if (!function_exists('msubstr')) {
    /*
     * $str:要截取的字符串
     * $start=0：开始位置，默认从0开始
     * $length：截取长度
     * $charset=”utf-8″：字符编码，默认UTF－8
     * $suffix=true：是否在截取后的字符后面显示省略号，默认true显示，false为不显示
     * 模版使用：{$vo.title|msubstr=0,5,'utf-8',false}
     */
    function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr")) {
            if ($suffix)
                return mb_substr($str, $start, $length, $charset) . "...";
            else
                return mb_substr($str, $start, $length, $charset);
        } elseif (function_exists('iconv_substr')) {
            if ($suffix)
                return iconv_substr($str, $start, $length, $charset) . "...";
            else
                return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8']  = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
        $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
        $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
        $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        if ($suffix) return $slice . "…";
        return $slice;
    }
}

if (!function_exists('url_encode')) {
    /**
     * url加密
     * @param $str
     * @return string
     */
    function url_encode($str)
    {
        return urlencode(str_encode($str));
    }
}

if (!function_exists('url_decode')) {
    /**
     * url解密
     * @param $str
     * @return bool|mixed|string
     */
    function url_decode($str)
    {
        return str_decode(urldecode($str));
    }
}

if (!function_exists('url_add_param')) {
    /**
     * url添加参数返回新的url
     * @param $url
     * @param $params
     * @return string
     */
    function url_add_param($url, $params)
    {
        // 拆分url参数部分
        $arr     = explode('?', $url);
        $_params = [];
        if (isset($arr[1])) {
            // 拆分为多个参数
            $arr2 = explode('&', $arr[1]);
            foreach ($arr2 as $v) {
                // 拆分键值对为数组
                $arr3 = explode('=', $v);
                // 强制获取参数
                $arr3[1] = isset($arr3[1]) ? $arr3[1] : '';
                list($name, $val) = $arr3;
                if ($name) {
                    $_params[$name] = $val;
                }
            }
        }
        return $arr[0] . '?' . http_build_query(array_merge($_params, $params));
    }
}

if (!function_exists('str_name_type_parse')) {
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @access public
     * @param  string  $name    字符串
     * @param  integer $type    转换类型
     * @param  bool    $ucfirst 首字母是否大写（驼峰规则）
     * @return string
     */
    function str_name_type_parse($name, $type = 0, $ucfirst = true)
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);
            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

if (!function_exists('json_encode_file')) {
    /**
     * @param string $filename
     * @param mixed  $value
     * @param int    $flags
     * @return bool|int
     */
    function json_encode_file($filename, $value, $flags = 0)
    {
        return file_put_contents($filename, json_encode($value), $flags);
    }
}

if (!function_exists('json_decode_file')) {
    /**
     * @param $filename
     * @return array|mixed
     */
    function json_decode_file($filename)
    {
        if (is_file($filename)) {
            $json = file_get_contents($filename);
            if ($json) {
                return json_decode($json, true);
            }
        }
        return [];
    }
}
