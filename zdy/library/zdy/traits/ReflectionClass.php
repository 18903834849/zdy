<?php

namespace zdy\traits;

trait ReflectionClass
{
    
    /**
     * 获取类方法所传参数
     * @param $method
     * @return array
     */
    protected static function getMethodArgs($method)
    {
        $backtrace = debug_backtrace();
        $class     = get_called_class();
        foreach ($backtrace as $item) {
            if (isset($item['class']) && $item['class'] == $class && $item['function'] == $method) {
                return static::getClassMethodArgs($item['class'], $item['function'], $item['args']);
            }
        }
        return [];
    }
    
    /**
     * 通过反射类获取类的方法参数表
     * @param string $args
     * @param string $class
     * @param string $method
     * @return array
     */
    public static function getClassMethodArgs($class, $method, $args)
    {
        $class = new \ReflectionClass($class);
        if ($class->hasMethod($method)) {
            
            $parameters = $class->getMethod($method)->getParameters();
            $params     = [];
            foreach ($parameters as $key => $parameter) {
                $name = $parameter->getName();
                if (isset($args[$key])) {
                    $params[$name] = $args[$key];
                } else {
                    $params[$name] = $parameter->getDefaultValue();
                }
            }
            return $params;
        }
        return [];
    }
    
}