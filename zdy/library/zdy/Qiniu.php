<?php

namespace zdy;

use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/**
 * 七牛
 * @author Administrator
 *
 */
class Qiniu
{
    protected $config = array(
        'accessKey' => 'p9cIHK85rntkhzWcjC4UerWxRJ6yQPoX97OwKj7F',
        'secretKey' => 'EVCSINvr3JVSkvFEbEchECZ46NxvkYzkR1nYfRvQ',
        'bucket'    => 'test',
        'domain'    => 'http://oxndx9ouf.bkt.clouddn.com/'
    );
    protected $error = null;
    
    /**
     * 初始化
     * @param array $config
     * @return \zdy\Qiniu
     */
    public static function init($config = array())
    {
        static $_init = [];
        if (empty($_init)) {
            $_init = new self($config);
        }
        return $_init;
    }
    
    /**
     * 构造方法
     */
    public function __construct($config = array())
    {
        $this->config = array_merge($this->config, $config);
        include_once __DIR__ . '/qiniu/autoload.php';
    }
    
    /**
     * 七牛鉴权凭证
     * @return \Qiniu\Auth
     */
    public function auth()
    {
        return new Auth($this->config['accessKey'], $this->config['secretKey']);
    }
    
    /**
     * 主要涉及了空间资源管理及批量操作接口的实现，具体的接口规格可以参考
     * @return \Qiniu\Storage\BucketManager
     */
    public function bucketManager()
    {
        return new BucketManager($this->auth());;
    }
    
    /**
     * 主要涉及了资源上传接口的实现
     * @return \Qiniu\Storage\UploadManager
     */
    public function uploadManager()
    {
        return new UploadManager();
    }
    
    /**
     * 上传本地文件或者远程URL文件
     * @param        $filename
     * @param string $key
     * @param string $bucket
     * @return bool|string
     * @throws \Exception
     */
    public function uploadFile($filename, $key = '', $bucket = '')
    {
        $bucket = $bucket ? $bucket : $this->config['bucket'];
        $key    = $key ? $key : (date('Ymd') . '/' . md5($filename . time()) . substr(strrchr($filename, '.'), 0));
        // 上传本地文件
        if (is_file($filename)) {
            // 调用 UploadManager 的 putFile 方法进行文件的上传
            list($ret, $err) = $this->uploadManager()->putFile($this->auth()->uploadToken($bucket), $key, $filename);
        } else {
            // 远程文件采集
            list($ret, $err) = $this->bucketManager()->fetch($filename, $bucket, $key);
        }
        if (isset($ret['key'])) {
            return $this->config['domain'] . $ret['key'];
        } else {
            $this->error = $err;
        }
        return false;
    }
    
    /**
     * 获取错误信息
     * @return string
     */
    public function error()
    {
        return $this->error;
    }
    
}