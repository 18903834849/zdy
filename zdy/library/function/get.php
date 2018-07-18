<?php

if (!function_exists('get_distance')) {
    /**
     * 获取两经纬度之间的距离
     * @param float $lng1
     * @param float $lat1
     * @param float $lng2
     * @param float $lat2
     * @return number
     */
    function get_distance($lng1, $lat1, $lng2, $lat2)
    {
        $earthRadius = 6371393;
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return round($calculatedDistance);
    }
}

if (!function_exists('get_client_browser')) {
    /**
     * 获取客户端浏览器
     * @return mixed
     */
    function get_client_browser()
    {
        $browser = array('IE', 'QQBrowser', 'UCBrowser', 'Firefox', 'Chrome', 'Opera', 'Safari');
        foreach ($browser as $v) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $v)) {
                return $v;
            }
        }
        return $_SERVER['HTTP_USER_AGENT'];
    }
}

if (!function_exists('get_client_system')) {
    /**
     * 获取客户端操作系统
     */
    function get_client_system()
    {
        $agents = array("Windows", "Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
        foreach ($agents as $v) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $v)) {
                return $v;
            }
        }
        return $_SERVER['HTTP_USER_AGENT'];
    }
}

if (!function_exists('get_client_ip2')) {
    /**
     * 获取客户端IP
     * @return string
     */
    function get_client_ip2($type = 0)
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
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}

if (!function_exists('str2time')) {
    /**
     * 将时间字符串转换成时间戳:2015-11-14 17:07:49
     * @param string $str
     * @return number
     */
    function str2time($str = null)
    {
        if (empty($str)) {
            return time();
        }
        return strtotime($str);
    }
}

if (!function_exists('time2str')) {
    /**
     * 将时间戳转换成时间字符串;2015-11-14 17:07:49
     * @param string $time
     * @param string $his 时分秒
     * @return string
     */
    function time2str($time = null, $his = true)
    {
        if (empty($time)) {
            $time = time();
        }
        return date($his ? "Y-m-d H:i:s" : "Y-m-d", $time);
    }
}

if (!function_exists('get_month_day_num')) {
    /**
     * 获取某月天数
     */
    function get_month_day_num($month)
    {
        $day31 = explode(',', '01,03,05,07,08,10,12');
        $day30 = explode(',', '04,06,09,11');
        if (in_array($month, $day31)) {
            return 31;
        } elseif (in_array($month, $day30)) {
            return 31;
        } else {
            $y = date('Y');
            return ($y % 4 == 0) ? 29 : 28;
        }
    }
}

if (!function_exists('get_day_begin_time')) {
    /**
     * 获取今天开始时间
     */
    function get_day_begin_time()
    {
        return strtotime(date('Ymd', time()));
    }

}

if (!function_exists('get_day_end_time')) {
    /**
     * 获取今天结束时间
     * @return string
     */
    function get_day_end_time()
    {
        return strtotime(date('Y-m-d', time()) . ' 23:59:59');
    }

}

if (!function_exists('get_domain')) {
    /**
     * 获取当前完整的域名
     */
    function get_domain()
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    }
}

if (!function_exists('get_constellation')) {
    /**
     * 获取指定日期对应星座
     * @param integer $month 月份 1-12
     * @param integer $day 日期 1-31
     * @return boolean|string
     */
    function get_constellation($month, $day = null)
    {
        //通过时间戳判断
        if (is_numeric($month) && empty($day)) {
            $month = date('m', $month);
            $day = date('d', $month);
        }
        $day = intval($day);
        $month = intval($month);
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) return false;
        $signs = array(
            array('20' => '宝瓶座'),
            array('19' => '双鱼座'),
            array('21' => '白羊座'),
            array('20' => '金牛座'),
            array('21' => '双子座'),
            array('22' => '巨蟹座'),
            array('23' => '狮子座'),
            array('23' => '处女座'),
            array('23' => '天秤座'),
            array('24' => '天蝎座'),
            array('22' => '射手座'),
            array('22' => '摩羯座')
        );
        list($start, $name) = each($signs[$month - 1]);
        if ($day < $start)
            list($start, $name) = each($signs[($month - 2 < 0) ? 11 : $month - 2]);

        return $name;
    }
}

if (!function_exists('friendly_date')) {
    /**
     * 友好的时间显示
     * @param int $sTime 待显示的时间
     * @param string $type 类型. normal | mohu | full | ymd | other
     * @param string $alt 已失效
     * @return string
     */
    function friendly_date($sTime, $type = 'normal', $alt = 'false')
    {
        // 如果不是时间戳,强制转换到时间戳
        if (!is_numeric($sTime)) {
            $sTime = strtotime($sTime);
        }
        //sTime=源时间，cTime=当前时间，dTime=时间差
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay = intval(date("z", $cTime)) - intval(date("z", $sTime));
        //$dDay     =   intval($dTime/3600/24);
        $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
        //normal：n秒前，n分钟前，n小时前，日期
        if ($type == 'normal') {
            if ($dTime < 60) {
                if ($dTime < 10) {
                    return '刚刚';    //by yangjs
                } else {
                    return intval(floor($dTime / 10) * 10) . "秒前";
                }
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
                //今天的数据.年份相同.日期相同.
            } elseif ($dYear == 0 && $dDay == 0) {
                //return intval($dTime/3600)."小时前";
                return '今天' . date('H:i', $sTime);
            } elseif ($dYear == 0) {
                return date("m月d日 H:i", $sTime);
            } else {
                return date("Y-m-d H:i", $sTime);
            }
        } elseif ($type == 'mohu') {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dDay > 0 && $dDay <= 7) {
                return intval($dDay) . "天前";
            } elseif ($dDay > 7 && $dDay <= 30) {
                return intval($dDay / 7) . '周前';
            } elseif ($dDay > 30) {
                return intval($dDay / 30) . '个月前';
            }
            //full: Y-m-d , H:i:s
        } elseif ($type == 'full') {
            return date("Y-m-d , H:i:s", $sTime);
        } elseif ($type == 'ymd') {
            return date("Y-m-d", $sTime);
        } else {
            if ($dTime < 60) {
                return $dTime . "秒前";
            } elseif ($dTime < 3600) {
                return intval($dTime / 60) . "分钟前";
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600) . "小时前";
            } elseif ($dYear == 0) {
                return date("Y-m-d H:i:s", $sTime);
            } else {
                return date("Y-m-d H:i:s", $sTime);
            }
        }
    }
}

