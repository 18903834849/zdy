<?php

namespace zdy;

/**
 *  数据表操作扩展
 * Class Tables
 * @package zdy
 */
class Tables
{
    public $query;
    
    /**
     * 构造方法
     * Tables constructor.
     * @param null $query 实现一个sql查询方法接口
     */
    public function __construct($query = null)
    {
        $this->query = $query;
        $this->getTableInfo();
    }
    
    /**
     * 初始化方法
     * @param null $query
     * @param null $exec
     * @return Tables
     */
    public static function init($query = null)
    {
        return new self($query);
    }
    
    /**
     * 数据库查询方法
     * @param $sql
     * @return array
     */
    private function query($sql)
    {
        return call_user_func($this->query, $sql);
    }
    
    /**
     *  获取数据表信息
     * @param string $table
     * @return array
     */
    public function getTableInfo($table = '')
    {
        static $tableInfo = [];
        if (empty($tableInfo)) {
            $rows = $this->query('SHOW TABLE STATUS');
            // 字段统一转换到小写
            foreach ($rows as $v) {
                $info                     = $this->key2lower($v);
                $info['size']             = $info['data_length'] + $info['index_length'];
                $tableInfo[$info['name']] = $info;
            }
        }
        return $table ? (isset($tableInfo[$table]) ? $tableInfo[$table] : []) : $tableInfo;
    }
    
    /**
     * 获取当前数据全部数据表
     * @return array
     */
    public function getTableList()
    {
        return array_keys($this->getTableInfo());
    }
    
    /**
     *  获取表的全部字段
     * @param $table
     * @return array
     */
    public function getFields($table)
    {
        return array_keys($this->getFieldInfo($table));
    }
    
    /**
     * 获取字段信息
     * @param        $table
     * @param string $filed
     * @return array
     */
    public function getFieldInfo($table, $filed = '')
    {
        // 静态存储
        static $findInfo = [];
        if (empty($findInfo[$table])) {
            $info = $this->query("SHOW FULL COLUMNS FROM `{$table}`");
            foreach ($info as $v) {
                $v                             = $this->key2lower($v);
                $findInfo[$table][$v['field']] = $v;
            }
        }
        return $filed ? ($findInfo[$table][$filed] ? $findInfo[$table][$filed] : []) : $findInfo[$table];
    }
    
    /**
     * 获取数据表创建SQL
     * @param $table
     * @return string
     */
    public function getCreateSql($table)
    {
        // 静态存储
        static $sql = [];
        if (empty($sql[$table])) {
            $row         = $this->query("SHOW CREATE TABLE {$table}");
            $sql[$table] = isset($row[0]['Create Table']) ? $row[0]['Create Table'] : '';
        }
        return $sql[$table];
    }
    
    /**
     * 获取表的创建数据
     * @param $table
     * @return array
     */
    public function getCreateData($table)
    {
        $sql  = $this->getCreateSql($table);
        $arr  = explode("\n", $sql);
        $data = [];
        foreach ($arr as $string) {
            preg_match('/`(.*)` (.*),/', $string, $match);
            if (!empty($match[2])) {
                $data[$match[1]] = $match[2];
            }
        }
        return $data;
    }
    
    /**
     * 获取数据表尺寸
     * @param $table
     * @return int
     */
    public function getTableSize($table)
    {
        return $this->getTableInfo($table)['size'];
    }
    
    /**
     * 获取数据表总记录
     * @param $table
     * @return int
     */
    public function getTableRows($table)
    {
        return $this->getTableInfo($table)['rows'];
    }
    
    /**
     * 判断字段是否存在
     * @param $table
     * @param $field
     * @return boolean
     */
    public function existsField($table, $field)
    {
        return in_array($field, $this->getFields($table));
    }
    
    /**
     *判断数据表是否存在
     * @param $table
     * @return bool
     */
    public function existsTable($table)
    {
        return in_array($table, $this->getTableList());
    }
    
    /**
     * 获取字段注释
     * @param $table
     * @param $field
     * @return string
     */
    public function getFieldComment($table, $field)
    {
        $info = $this->getFieldInfo($table, $field);
        return isset($info['comment']) ? $info['comment'] : '';
    }
    
    /**
     *  将数组键值转换到小写
     * @param  array $array
     * @return mixed
     */
    private function key2lower($array)
    {
        foreach ($array as $k => $v) {
            $arr[strtolower($k)] = $v;
        }
        return $arr;
    }
    
}