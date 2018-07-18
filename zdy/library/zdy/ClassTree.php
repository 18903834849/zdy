<?php

namespace zdy;

/**
 * 无限分类树（支持子分类排序）
 * Class ClassTree
 * @package zdy
 */
class ClassTree
{
    
    /**
     * 分类排序（降序）
     * @param        $arr
     * @param string $cols
     * @param int    $sortOrder
     * @return mixed
     */
    public static function sort($arr, $cols = 'sort', $sortOrder = SORT_ASC)
    {
        // 子分类排序
        foreach ($arr as $k => &$v) {
            if (!empty ($v ['_child'])) {
                $v ['_child'] = self::sort($v ['_child'], $cols);
            }
            $sort [$k] = $v [$cols];
        }
        if (isset ($sort)) {
            array_multisort($sort, $sortOrder, $arr);
        }
        return $arr;
    }
    
    /**
     * 横向分类树
     * @param        $arr
     * @param string $keyName
     * @param int    $pid
     * @param int    $level
     * @return array
     */
    public static function hTree($arr, $keyName = 'id', $pid = 0, $level = -1)
    {
        $level++;
        foreach ($arr as $k => $v) {
            if ($v ['pid'] == $pid) {
                $v['_level']                   = $level;
                $data[$v[$keyName]]            = $v;
                $data[$v[$keyName]] ['_child'] = self::hTree($arr, $keyName, $v [$keyName], $level);
            }
        }
        return isset ($data) ? $data : array();
    }
    
    /**
     *  纵向分类树
     * @param     $arr
     * @param int $pid
     * @param int $level
     * @return array|mixed
     */
    public static function vTree($arr, $pid = 0, $level = -1)
    {
        $level++;
        foreach ($arr as $k => $v) {
            if ($v ['pid'] == $pid) {
                $v['_level']     = $level;
                $data [$v['id']] = $v;
                $data            += self::vTree($arr, $v ['id'], $level);
            }
        }
        return isset ($data) ? $data : array();
    }
}