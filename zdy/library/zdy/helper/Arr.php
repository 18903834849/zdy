<?php

namespace zdy\helper;


class Arr
{
    
    /**
     * 检查是否为键值数据
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array $array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }
    
    /**
     * 递归排序
     * @param $array
     * @return mixed
     */
    public static function sortRecursive($array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = static::sortRecursive($value);
            }
        }
        if (static::isAssoc($array)) {
            ksort($array);
        } else {
            sort($array);
        }
        return $array;
    }
    
    /**
     * 安全获取数组某值,支持多维数组1.2.3形式
     * @param $array
     * @param $keys
     * @return mixed
     */
    public static function getVal($array, $keys)
    {
        $data = "\$array['" . join("']['", explode('.', $keys)) . "']";
        $code = "return isset($data) ? $data : null;";
        return eval($code);
    }
    
    /**
     * 数组中查询
     * @param array $list
     * @param array $condition
     * @return array
     */
    public static function query($list, $condition)
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
    
    /**
     * 判断数组的键值是否存在,支持多维数组1.2.3形式
     * @param $array
     * @param $keys
     * @return mixed
     */
    public static function _isset($array, $keys)
    {
        $code = "return isset(\$array['" . join("']['", explode('.', $keys)) . "']);";
        return eval($code);
    }
    
    
    public static function xml2array($xmlResource, $flag = true)
    {
        if ($flag === true) {
            $dom = new \DOMDocument();
            $dom->loadXML($xmlResource);
            $xmlResource  = $dom->documentElement;
            $rootNodeName = $xmlResource->nodeName;
        }
        $result = false;
        if ($xmlResource->hasChildNodes()) {
            /**@var \DOMElement $childNode * */
            foreach ($xmlResource->childNodes as $childNode) {
                if ($childNode->nodeType != XML_TEXT_NODE) {
                    if ($childNode->hasAttribute('name')) {
                        $key = $childNode->getAttribute('name');
                    } else {
                        $key = $childNode->nodeName;
                    }
                    $child = self::xml2array($childNode, false);
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
    
    /**
     * 数组转XML内容
     * @param array $data
     * @return string
     */
    public static function _toXml($data)
    {
        return "<xml>" . self::_toXml($data) . "</xml>";
    }
    
    /**
     * XML内容生成
     * @param array $data 数据
     * @param string $content
     * @return string
     */
    private static function __toXml($data, $content = '')
    {
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = 'item';
            $content .= "<{$key}>";
            if (is_array($val) || is_object($val)) {
                $content .= self::_arr2xml($val);
            } elseif (is_string($val)) {
                $content .= '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $val) . ']]>';
            } else {
                $content .= $val;
            }
            $content .= "</{$key}>";
        }
        return $content;
    }
    
    /**
     * 解析XML内容到数组
     * @param string $xml
     * @return array
     */
    public static function xml2arr($xml)
    {
        return json_decode(self::arr2json(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
    
    
    
    
}