<?php

if (!function_exists('array_get_val')) {
    /**
     * 安全获取数组某值,支持多维数组1.2.3形式
     * @param array  $array
     * @param string $keyStr
     * @return mixed
     */
    function array_get_val($array, $keyStr)
    {
        $data = "\$array['" . join("']['", explode('.', $keyStr)) . "']";
        $code = "return isset($data) ? $data : null;";
        return eval($code);
    }
}

if (!function_exists('array_merge2')) {
    /**
     * 将数组1和数组2合并成一个索引数组
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    function array_merge2($arr1, $arr2)
    {
        foreach ($arr1 as $v) $arr[] = $v;
        foreach ($arr2 as $v) $arr[] = $v;
        return $arr;
    }
}

if (!function_exists('array_sort')) {
    /**
     * 对查询结果集进行排序
     * @access public
     * @param array  $list   查询结果
     * @param string $field  排序的字段名
     * @param array  $sortby 排序类型 asc正向排序 desc逆向排序 nat自然排序
     * @return array
     */
    function array_sort($list, $field, $sortby = 'asc')
    {
        if (is_array($list)) {
            $refer = $resultSet = array();
            foreach ($list as $i => $data)
                $refer[$i] = &$data[$field];
            switch ($sortby) {
                case 'asc': // 正向排序
                    asort($refer);
                    break;
                case 'desc': // 逆向排序
                    arsort($refer);
                    break;
                case 'nat': // 自然排序
                    natcasesort($refer);
                    break;
            }
            foreach ($refer as $key => $val)
                $resultSet[] = &$list[$key];
            return $resultSet;
        }
        return [];
    }
}

if (!function_exists('array2json')) {
    /**
     * 将数组变量转换成json字符串
     * @param string $array
     * @param null   $options
     * @return string
     */
    function array2json($array = '', $options = null)
    {
        return json_encode($array, $options);
    }
}

if (!function_exists('array_unique_sort')) {
    /**
     * 数组去除重复值并且排序
     * @param array $array
     * @return array
     */
    function array_unique_sort($array)
    {
        $_a = array_unique($array);
        sort($_a);
        return $_a;
    }
}

if (!function_exists('json2array')) {
    /**
     * 将json字符串转换成数组变量
     * @param string $json
     * @return mixed
     */
    function json2array($json = '')
    {
        $arr = json_decode($json, true);
        return is_array($arr) ? $arr : array();
    }
}

if (!function_exists('str2array')) {
    /**
     * 将参数对的字符串转换到数组格式
     * @param string $string
     * @return array
     */
    function str2array($string)
    {
        $ret = array();
        // 将字符串分割成数组
        $arr = explode('&', $string);
        foreach ($arr as $str) {
            list ($k, $v) = explode('=', $str);
            $ret[$k] = $v;
        }
        return $ret;
    }
}

if (!function_exists('array2str')) {
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $array
     * @return string
     */
    function array2str($array)
    {
        $arg = "";
        foreach ($array as $key => $val) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = rtrim($arg, '&');
        //如果存在转义字符，那么去掉转义
        get_magic_quotes_gpc() && $arg = stripslashes($arg);
        return $arg;
    }
}

if (!function_exists('array2trees')) {
    /**
     * 将数组转换到树形列表
     * @param array  $array
     * @param string $field
     * @return array
     */
    function array2trees($array, $field = 'name')
    {
        $ret = array();
        foreach ($array as $v) {
            if (isset($v[$field]) && isset($v['level'])) {
                if ($v['level'] == 0) {
                    $sign = '';
                } elseif ($v['level'] == 1) {
                    $sign = '├&nbsp;&nbsp;';
                } else {
                    $sign = str_repeat("│&nbsp;&nbsp;&nbsp;&nbsp;", $v['level'] - 1) . '├&nbsp;&nbsp;';
                }
                $v['name'] = $sign . $v[$field];
                $ret[]     = $v;
            }
        }
        return $ret;
    }
}

if (!function_exists('array_query')) {
    /**
     * 在数组中查询
     * @param array $list
     * @param array $condition
     * @return array
     */
    function array_query($list, $condition)
    {
        if (is_string($condition)) {
            parse_str($condition, $condition);
        }
        // 返回的结果集合
        $resultSet = array();
        foreach ($list as $key => $data) {
            $find = false;
            foreach ($condition as $field => $value) {
                if (isset($data[$field])) {
                    if (0 === strpos($value, '/')) {
                        $find = preg_match($value, $data[$field]);
                    } elseif ($data[$field] == $value) {
                        $find = true;
                    }
                }
            }
            if ($find)
                $resultSet[] = &$list[$key];
        }
        return $resultSet;
    }
}

if (!function_exists('array_get_key_vals')) {
    /**
     * 获取二维数组中指定键值下的所有值
     * @param string $key
     * @param array  $arr
     * @return array
     */
    function array_get_key_vals($arr, $key = 'id')
    {
        $ret = array();
        if (is_array($arr)) {
            foreach ($arr as $v) {
                if (isset($v[$key])) {
                    $ret[] = $v[$key];
                }
            }
        }
        return $ret;
    }
}

if (!function_exists('array_reset_key')) {
    /**
     * 重新定义数组键值
     * @param array  $arr
     * @param string $key
     * @return array
     */
    function array_reset_key($arr, $key)
    {
        $result = [];
        foreach ($arr as $v) {
            $result[][$key] = $v;
        }
        return $result;
    }
}

if (!function_exists('array_isset')) {
    /**
     * 判断数组的键值是否存在,支持多维数组1.2.3形式
     * @param string $keyStr
     * @param array  $array
     * @return bool
     */
    function array_isset($keyStr, $array)
    {
        $code = "return isset(\$array['" . join("']['", explode('.', $keyStr)) . "']);";
        return eval($code);
    }
}

if (!function_exists('xml2array')) {
    /**
     * xml转换到数组
     * @param mixed  $xmlResource
     * @param string $flag
     * @return array
     */
    function xml2array($xmlResource, $flag = true)
    {
        if ($flag === true) {
            $dom = new DOMDocument();
            $dom->loadXML($xmlResource);
            $xmlResource  = $dom->documentElement;
            $rootNodeName = $xmlResource->nodeName;
        }
        $result = [];
        if ($xmlResource->hasChildNodes()) {
            foreach ($xmlResource->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    /**@var \DOMElement $childNode * */
                    if ($childNode->hasAttribute('name')) {
                        $key = $childNode->getAttribute('name');
                    } else {
                        $key = $childNode->nodeName;
                    }
                    $child = xml2array($childNode, false);
                    if ($child === false) {
                        $result[$key] = $childNode->nodeValue;
                    } else {
                        $result[$key] = $child;
                    }
                }
            }
        }
        return $flag === true ? array($rootNodeName => $result) : $result;
    }
}

if (!function_exists('array_merge_by_dir')) {
    /**
     * 合并目录下数组文件
     * @return array
     */
    function array_merge_by_dir()
    {
        $files = [];
        $dirs  = func_get_args();
        foreach ($dirs as $dir) {
            $_files = file_recursion($dir, 'php');
            if (is_array($_files)) {
                $files = array_merge($files, $_files);
            }
        }
        return call_user_func_array('array_merge_by_file', $files);
    }
}

if (!function_exists('array_merge_by_file')) {
    /**
     * 合并数组通过数组文件
     * @return array|mixed
     */
    function array_merge_by_file()
    {
        $files = func_get_args();
        $arr   = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                $_arr = include($file);
                if (is_array($_arr)) {
                    $arr = array_merge_ex($arr, $_arr);
                }
            }
            
        }
        return $arr;
    }
}

if (!function_exists('array_merge_ex')) {
    /**
     * 数组合并自动覆盖版
     * @return array|mixed
     */
    function array_merge_ex()
    {
        $args = func_get_args();
        $res  = array_shift($args);
        while (!empty($args)) {
            foreach (array_shift($args) as $k => $v) {
                if (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = array_merge_ex($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }
        return $res;
    }
}

if (!function_exists('list2')) {
    /**
     *  系统方法list函数优化
     */
    function list2($arr, &$val0 = null, &$val1 = null, &$val2 = null, &$val3 = null, &$val4 = null, &$val5 = null, &$val6 = null, &$val7 = null, &$val8 = null, &$val9 = null)
    {
        $val0 = array_shift($arr);
        $val1 = array_shift($arr);
        $val2 = array_shift($arr);
        $val3 = array_shift($arr);
        $val4 = array_shift($arr);
        $val5 = array_shift($arr);
        $val6 = array_shift($arr);
        $val7 = array_shift($arr);
        $val8 = array_shift($arr);
        $val9 = array_shift($arr);
    }
}
