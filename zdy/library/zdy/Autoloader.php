<?php

namespace zdy;

/**
 * 类的自动加载
 * Class Autoloader
 * @package zdy
 */
class Autoloader
{
    
    /**
     * 注册自动加载类
     * @param string $config
     */
    public static function register($config = '')
    {
        spl_autoload_register(function ($className) use ($config) {
            static::load($className, $config);
        });
    }
    
    /**
     * 加载类
     * @param       $className
     * @param array $namespacePath 命名空间参数
     * @return bool
     */
    public static function load($className, $config = [])
    {
        $ext = '.php';
        $path = '';
        
        if ($arr = static::parseClassName($className)) {
            list($root, $class) = $arr;
            
            if (isset($config[$root])) {
                
                if (is_array($config[$root])) {
                    list($path, $ext) = $config[$root];
                } elseif (is_string($config[$root])) {
                    $path = $config[$root];
                }
                
                // 文件存在加载
                $filename = str_replace('\\', DIRECTORY_SEPARATOR, $path . DIRECTORY_SEPARATOR . $class . $ext);
                if (is_file($filename)) {
                    require_once $filename;
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 解析类名[name, path]
     * @param $className
     * @return array|bool
     */
    public static function parseClassName($className)
    {
        $arr = explode('\\', $className);
        if ($arr) {
            $name = array_shift($arr);
            return [$name, join('\\', $arr)];
        }
        return false;
    }
    
}
