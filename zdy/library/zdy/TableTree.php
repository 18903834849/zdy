<?php

namespace zdy;

/**
 * 多级数据递归查询
 * @author Administrator
 */
class TableTree
{
    private $db = null;
    private $order = null;
    private $field = null;
    private $level = null;
    private $where = array();
    private $p_name = 'pid';
    private $s_name = '_list';
    private $list_type = true;
    
    /**
     * 构造方法
     * @param static $name
     */
    public function __construct($name, $p_name, $list_type)
    {
        $this->db = is_object($name) ? $name : M($name);
        $p_name && $this->p_name = $p_name;
        $this->list_type = $list_type;
    }
    
    /**
     * 初始化
     */
    public static function init($name, $p_name, $list_type = false)
    {
        return new self($name, $p_name, $list_type);
    }
    
    /**
     * 排序
     * @param unknown $order
     * @return \lib\Tree
     */
    public function order($order)
    {
        $order && $this->order = $order;
        return $this;
    }
    
    /**
     * 查询的字段
     * @param unknown $field
     * @return \lib\Tree
     */
    public function field($field)
    {
        $field && $this->field = $field;
        return $this;
    }
    
    /**
     * 补充条件
     * @param unknown $where
     */
    public function where($where)
    {
        $this->where = $where;
        return $this;
    }
    
    /**
     * 递归层次
     * @param unknown $level
     * @return \lib\Tree
     */
    public function level($level)
    {
        $level && $this->level = $level;
        return $this;
    }
    
    /**
     * 子列表字段名称
     * @param unknown $s_name
     */
    public function sonName($s_name)
    {
        if ($s_name) {
            $this->s_name    = $s_name;
            $this->list_type = false;
        }
        return $this;
    }
    
    /**
     * 查询下级子列表:内部方法
     * @return array
     */
    private function _getSonList($id, $_level, &$list = array(), $level = -1)
    {
        $where = array_merge([$this->p_name => $id], $this->where);
        $rows  = $this->db->where($where)->order($this->order)->field($this->field)->select();
        $this->list_type == false && $list = array();
        if ($rows) {
            if (isset($_level)) {
                $_level--;
                if ($_level == 0) {
                    return $list;
                }
            }
            // 列表方式
            if ($this->list_type) {
                // 列表方式
                ++$level;
                foreach ($rows as $key => $value) {
                    $value['level'] = $level;
                    $list[]         = $value;
                    unset($rows[$key]);// 删除无用节点
                    if ($value['id'] != $value[$this->p_name]) {
                        // 避免父ID等于本身ID进入死循环状态
                        $this->_getSonList($value['id'], $_level, $list, $level);
                    }
                }
            } else {
                // 树形图方式
                foreach ($rows as $key => $value) {
                    // 避免父ID等于本身ID进入死循环状态
                    if ($value['id'] != $value[$this->p_name]) {
                        $_list = $this->_getSonList($value['id'], $_level);
                    }
                    $_list && $value[$this->s_name] = $_list;
                    $list[] = $value;
                    unset($rows[$key]);
                }
            }
        }
        return $list;
    }
    
    /**
     * 查询下级子列表
     * @param unknown $id   节点ID
     * @param string  $self 是否查询自身
     */
    public function getSonList($id, $self = true)
    {
        $list = $this->_getSonList($id, $this->level);
        if ($self) {
            $row = $this->db->order($this->order)->field($this->field)->find($id);
            if ($this->list_type) {
                // 列表方式
                $row['level'] = 0;
                $row          = array_merge([$row], $list);
            } else {
                // 树形图方式
                $list && $row[$this->s_name] = $list;
            }
            return $row;
        }
        return $list;
    }
    
    /**
     * 查询上级列表
     * @param unknown $id 节点ID
     */
    function getParentList($id)
    {
        return $this->_getParentList($id, $this->level);
    }
    
    /**
     * 查询上级列表:内部方法
     * @param unknown $id
     * @param unknown $_level
     * @param unknown $list
     * @return unknown|mixed|boolean|NULL|string|unknown|object
     */
    private function _getParentList($id, $_level, &$list = array())
    {
        $where = array_merge(['id' => $id], $this->where);
        $row   = $this->db->where($where)->field($this->field)->find();
        if ($row) {
            if (isset($_level)) {
                if ($_level < 0) {
                    return $list;
                }
                $_level--;
            }
            $list[] = $row;
            // 避免死循环
            if ($row[$this->p_name] != $id) {
                $this->_getParentList($row[$this->p_name], $_level, $list);
            }
        }
        return $list;
    }
    
    
}