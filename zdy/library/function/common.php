<?php


if (!function_exists('php_batch_include')) {
    /**
     * 批量加载php文件
     * @param string $path    加载目录
     * @param array  $exclude 排除文件
     */
    function php_batch_include($path, $exclude = array())
    {
        $files = glob("$path/*.php");
        foreach ($files as $php) {
            if (!in_array($php, $exclude)) {
                require_once $php;
            }
        }
    }
    
    // 自动加载全部函数文件
    php_batch_include(__DIR__);
}


if (!function_exists('for_fun')) {
    /**
     * 快速循环
     * @param callable $fun
     * @param integer  $count
     */
    function for_fun($fun, $count)
    {
        for ($i = 1; $i <= $count; $i++) {
            call_user_func($fun, $i);
        }
    }
}

if (!function_exists('echo_ex')) {
    /**
     * 通用类型输出
     */
    function echo_ex()
    {
        array_map('print_ex', func_get_args());
    }
}

if (!function_exists('print_ex')) {
    /**
     * 打印任何类型变量
     * @param mixed $var
     */
    function print_ex($var)
    {
        static $_static = array();
        if (empty($_static)) {
            $_static = true;
            header('content-type:text/html;charset=utf-8');
        }
        echo '<pre><hr>';
        if (is_null($var) || is_bool($var) || $var === '') {
            var_dump($var);
        } else {
            print_r($var);
        }
        echo '</pre>';
        flush();
    }
}

if (!function_exists('exit_ex')) {
    /**
     * 通用数据断点输出
     */
    function exit_ex()
    {
        array_map('print_ex', func_get_args());
        exit();
    }
}

if (!function_exists('test_code_time')) {
    /**
     * 测试代码执行时间
     * @param mixed $token 标示索引
     */
    function test_code_time($token = 1)
    {
        static $time = array();
        list($usec, $sec) = explode(" ", microtime());
        $t = ((float)$usec + (float)$sec);
        if (isset($time [$token] ['begin'])) {
            echo($t - $time [$token] ['begin'] . '<br/>');
        } else {
            $time [$token] ['begin'] = $t;
        }
    }
}

if (!function_exists('build_order_no')) {
    /**
     * 根据当前的时间生成一个订单号
     * @return string
     */
    function build_order_no()
    {
        //获取微妙数 1000微妙 = 1秒
        $s = intval(trim(substr(microtime(), 2, 6), '0') / 1000);
        return date('YmdHis', time()) . str_repeat('0', 3 - strlen($s)) . $s;
    }
}

if (!function_exists('local_path_to_web_path')) {
    /**
     *  本地路径转换到网络路径
     * @param $local_path
     * @return mixed
     */
    function local_path_to_web_path($local_path)
    {
        $local_path = str_replace('\\', '/', $local_path);
        $srcname    = dirname($_SERVER['SCRIPT_FILENAME']);
        $local_path = str_replace($srcname, '', $local_path);
        return $local_path;
    }
}

if (!function_exists('zdy_add_log')) {
    /**
     * 添加测试日志
     * @param $data
     * @return bool|int
     */
    function zdy_add_log($data)
    {
        $data = var_export($data, true);
        return file_put_contents('zdy.log', get_self_url() . '---' . time2str() . "\n" . $data . "\n\n");
    }
}

if (!function_exists('get_time_season')) {
    /**
     *  获取时间的季节
     * @param $time
     * @return string
     */
    function get_time_season($time)
    {
        if (!is_numeric($time)) {
            $time = strtotime($time);
        }
        $m      = date('m', $time);
        $season = [
            '春' => ['03', '04', '05'],
            '夏' => ['06', '07', '08'],
            '秋' => ['09', '10', '11'],
            '冬' => ['12', '01', '02'],
        ];
        foreach ($season as $key => $arr) {
            if (in_array($m, $arr)) {
                return $key;
            }
        }
        return null;
    }
}

if (!function_exists('ip2area')) {
    /**
     *  获取IP地址
     * @param string $ip
     * @param string $charset
     * @return mixed
     */
    function ip2area($ip = '', $charset = 'gbk')
    {
        static $_ip = array();
        is_numeric($ip) && $ip = long2ip($ip);
        if (empty($_ip [$ip])) {
            $iplocation = new \zdy\IpLocation();
            $location   = $iplocation->getlocation($ip);
            $_ip [$ip]  = $location ['country'] . $location ['area'];
            if ('utf-8' != $charset) {
                $_ip [$ip] = iconv($charset, 'utf-8', $_ip [$ip]);
            }
        }
        return $_ip [$ip];
    }
}

if (!function_exists('get_self_url')) {
    /**
     * 获取当前的请求URL
     * @return string
     */
    function get_self_url()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self     = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
        $path_info    = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        $relate_url   = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }
}

if (!function_exists('get_distance')) {
    /**
     * @desc 根据两点间的经纬度计算距离(km)
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return string
     */
    function get_distance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius        = 6367000;
        $lat1               = ($lat1 * pi()) / 180;
        $lng1               = ($lng1 * pi()) / 180;
        $lat2               = ($lat2 * pi()) / 180;
        $lng2               = ($lng2 * pi()) / 180;
        $calcLongitude      = $lng2 - $lng1;
        $calcLatitude       = $lat2 - $lat1;
        $stepOne            = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo            = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return sprintf('%.2f', round($calculatedDistance) / 1000);
    }
}

if (!function_exists('get_coordinate_range')) {
    /**
     *  获取百度地图坐标范围,用作附近人搜索
     * @param float $lon    纬度
     * @param float $lat    经度
     * @param float $radius 半径
     * @return array
     */
    function get_coordinate_range($lon, $lat, $radius)
    {
        //计算纬度
        $degree    = (24901 * 1609) / 360.0;
        $dpmLat    = 1 / $degree;
        $radiusLat = $dpmLat * $radius;
        $minLat    = $lat - $radiusLat; //得到最小纬度
        $maxLat    = $lat + $radiusLat; //得到最大纬度
        //计算经度
        $mpdLng    = abs($degree * cos($lat * (pi() / 180)));
        $dpmLng    = 1 / $mpdLng;
        $radiusLng = $dpmLng * $radius;
        $minLng    = $lon - $radiusLng;  //得到最小经度
        $maxLng    = $lon + $radiusLng;  //得到最大经度
        //范围
        $range = array(
            'minLat' => $minLat,
            'maxLat' => $maxLat,
            'minLon' => $minLng,
            'maxLon' => $maxLng
        );
        return $range;
    }
}

if (!function_exists('qr_code')) {
    /**
     * 生成二维码
     * @param      $text
     * @param bool $outfile
     * @param int  $level
     * @param int  $size
     * @param int  $margin
     */
    function qr_code($text, $outfile = false, $level = 3, $size = 10, $margin = 0)
    {
        include_once ZDY_PATH . 'zdy/phpqrcode/phpqrcode.php';
        call_user_func_array('QRcode::png', [$text, $outfile, $level, $size, $margin]);
    }
}

if (!function_exists('base64_to_image')) {
    /**
     * 上传图片
     * @param        $base64
     * @param string $path
     * @return string
     */
    function base64_to_image($base64, $path = 'upload')
    {
        preg_match('/^data:(.*);base64,(.*)$/i', $base64, $meatch);
        $exts = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gig'];
        if (isset($meatch[1]) && isset($meatch[2]) && isset($exts[$meatch[1]])) {
            $ext  = $exts[$meatch[1]]; // 图片后缀
            $name = md5($meatch[2]); // 图片名称
            // 图片路径
            $filename = $path . '/' . substr($name, 0, 4) . '/' . $name . '.' . $ext;
            // 检查路径是否存在
            is_dir(dirname($filename)) || mkdir(dirname($filename), 0777, true);
            // 生成图片文件
            file_put_contents($filename, base64_decode($meatch[2]));
            return $filename;
        }
        return '';
    }
}

if (!file_exists('compile_php_file')) {
    /**
     * 编译PHP代码文件
     * @param string $filename 文件名
     * @return string
     */
    function compile_php_file($filename)
    {
        $content = php_strip_whitespace($filename);
        $content = trim(substr($content, 5));
        // 替换预编译指令
        $content = preg_replace('/\/\/\[RUNTIME\](.*?)\/\/\[\/RUNTIME\]/s', '', $content);
        if (0 === strpos($content, 'namespace')) {
            $content = preg_replace('/namespace\s(.*?);/', 'namespace \\1{', $content, 1);
        } else {
            $content = 'namespace {' . $content;
        }
        if ('?>' == substr($content, -2)) {
            $content = substr($content, 0, -2);
        }
        return $content . '}';
    }
}

if (!file_exists('dwz_create')) {
    /**
     * 百度短网址生成
     * @param $url
     * @return mixed
     */
    function dwz_create($url)
    {
        $data['url'] = $url;
        $curl        = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://dwz.cn/create.php');
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content, $status,) = [curl_exec($curl), curl_error($curl)];
        curl_close($curl);
        if ($status !== null) {
            $res = json_decode($content, true);
            if ($res['status'] == 0) {
                return $res['tinyurl'];
            }
        }
        return $url;
    }
}

if (!file_exists('is_local_env')) {
    /**
     * 判断是否为本地环境
     * @return bool
     */
    function is_local_env()
    {
        // 在A类地址中，10.0.0.0到10.255.255.255是私有地址
        // 在B类地址中，172.16.0.0到172.31.255.255是私有地址。
        // 在C类地址中，192.168.0.0到192.168.255.255是私有地址。
        $patterns = [
            '/127.0.0.1/',
            '/^10.(.*)/',
            '/^172.[16,31]{2}.(.*)/',
            '/^192.168.(.*)/'
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $_SERVER['SERVER_ADDR'])) {
                return true;
            }
        }
        return false;
    }
}
