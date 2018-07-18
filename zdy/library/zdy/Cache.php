<?php

namespace zdy;

/**
 * 缓存工具类
 * Class Cache
 * @package zdy
 */
class Cache
{
    /**
     * 缓存文件目录
     * @var string
     */
    private $cachePath = '';
    
    /**
     * 缓存有效期
     * @var string
     */
    private $expireTime = '';
  
    /**
     * 构造方法
     * Cache constructor.
     * @param string $cachePath
     * @param string $expireTime
     */
    public function __construct($cachePath = '', $expireTime = '')
    {
        $this->cachePath  = $cachePath ? $cachePath : __DIR__ . '/cache';
        $this->expireTime = $expireTime;
    }
    
    /**
     * 初始化
     * @param string $cachePath
     * @param string $expireTime
     * @return Cache
     */
    public static function init($cachePath = '', $expireTime = '')
    {
        return new self($cachePath, $expireTime);
    }
    
    /**
     * 获取缓存数据
     * @param string $name        缓存名称
     * @param string $cachePath  缓存目录
     * @param string $expireTime 缓存有效时间
     * @return null
     */
    public function get($name, $cachePath = '', $expireTime = '')
    {
        $filename = $this->getCacheFileName($name, $cachePath);
        
        // 缓存文件不存在
        if (!is_file($filename)) {
            return null;
        }
        
        $data = unserialize(file_get_contents($filename));
        
        // 检查缓存数据是否完整
        if (!isset($data['value']) || !isset($data['caeate_time']) || !isset($data['expire_time'])) {
            return null;
        }
        
        // 有效期为0长期有效
        if ($data['expire_time'] == 0) {
            return $data['value'];
        }
        
        // 在有效期内
        if (time() - $data['caeate_time'] < $data['expire_time']) {
            return $data['value'];
        }
        
        // 删除缓存
        $this->del($name, $cachePath);
        return null;
    }
    
    /**
     * 设置缓存数据
     * @param string $name        缓存名称
     * @param mixed  $value       缓存数据
     * @param string $cachePath  缓存目录
     * @param string $expireTime 缓存有效时间
     * @return bool
     */
    public function set($name, $value, $cachePath = '', $expireTime = '')
    {
        $filename = $this->getCacheFileName($name, $cachePath);
        // 检查缓存目录是否存在
        $cachePath = dirname($filename);
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0755, true);
        }
        $data['value']       = $value;
        $data['caeate_time'] = time();
        $data['expire_time'] = intval($expireTime ? $expireTime : $this->expireTime);
        return (bool)file_put_contents($filename, serialize($data));
    }
    
    /**
     * 删除缓存
     * @param string $name       缓存名称
     * @param string $cachePath 缓存目录
     * @return bool
     */
    public function del($name, $cachePath)
    {
        $filename = $this->getCacheFileName($name, $cachePath);
        return is_file($filename) ? unlink($filename) : false;
    }
    
    /**
     *  获取缓存文件名
     * @param $name
     */
    private function getCacheFileName($name, $cachePath)
    {
        $name     = md5($name . '_cache');
        $filename = (is_dir($cachePath) ? $cachePath : $this->cachePath) . '/' . substr($name, -2, 2) . '/' . $name . '.cache';
        return $filename;
    }
    
}