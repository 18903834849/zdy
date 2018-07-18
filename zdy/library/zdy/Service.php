<?php

namespace zdy;

/**
 * 服务层基类
 * Class Service
 * @package zdy
 */
class Service
{
    // 服务基类实例
    public static $instance;
    
    /**
     * 构造方法
     * Service constructor.
     * @param array $config
     */
    function __construct(array $config = [])
    {
        if (is_array($config)) {
            static::configure($this, $config);
        }
    }
    
    /**
     * Service实例
     * @return mixed
     */
    public static function instance()
    {
        if (static::getBaseClassName() === 'Service') {
            // 单例
            if (empty(static::$instance)) {
                static::$instance = new self();
            }
            return static::$instance;
        }
        return false;
    }
    
    public function __set($name, $value)
    {
    
    }
    
    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        $className = strtolower(static::className()) . '\\' . static::nameTypeParse($name);
        if (class_exists($className)) {
            return new $className();
        }
        return null;
    }
    
    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }
    
    /**
     * 命名风格转换
     * @param      $name
     * @param int  $type
     * @param bool $ucfirst
     * @return string
     */
    public static function nameTypeParse($name, $type = true, $ucfirst = true)
    {
        if ($type) {
            $name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
                return strtoupper($match[1]);
            }, $name);
            return $ucfirst ? ucfirst($name) : lcfirst($name);
        }
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
    
    /**
     * 获取当前类名
     * @param null $className
     * @return null|string
     */
    public static function className($className = null)
    {
        if (empty($className)) {
            $className = get_called_class();
        } elseif (is_object($className)) {
            $className = get_class($className);
        }
        return $className;
    }
    
    /**
     * 获取类名(去掉命名空间路径)
     * @param null $className
     * @return mixed
     */
    public static function getBaseClassName($className = null)
    {
        $name = explode('\\', static::className($className));
        return array_pop($name);
    }
    
    /**
     * 获取类的命名空间
     * @param null $className
     * @return string
     */
    public static function getNamespace($className = null)
    {
        $name = explode('\\', static::className($className));
        array_pop($name);
        return join('\\', $name);
    }
    
    /**
     * 初始化配置
     * @param $object
     * @param $properties
     * @return mixed
     */
    public static function configure($object, $properties)
    {
        $attributes = array_keys(static::getObjectVars($object));
        foreach ($properties as $name => $value) {
            if (in_array($name, $attributes)) {
                $object->$name = $value;
            }
        }
        return $object;
    }
    
    /**
     * 获取对象参数
     * @param $object
     * @return array
     */
    public static function getObjectVars($object)
    {
        return get_object_vars($object);
    }
    
}