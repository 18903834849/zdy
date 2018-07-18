<?php


if (!function_exists('file_recursion')) {
    /**
     * 递归遍历目录下所有文件
     * @param string $path     遍历目录
     * @param string $exts     搜索后缀名
     * @param string $callback 回调函数
     * @param array  $_list    结果集
     * @return boolean|array
     */
    function file_recursion($path, $exts = '*', $callback = '', &$_list = array())
    {
        $dir = dir($path);
        while (false !== ($file = $dir->read())) {
            if ($file != '.' && $file != '..') {
                $filename = $path . '/' . $file;
                if (is_dir($filename)) {
                    $fun = __FUNCTION__;
                    $fun($filename, $exts, $callback, $_list);
                } else {
                    if ($exts === '' || $exts === '*') {
                        if ($callback instanceof \Closure) {
                            $callback($filename);
                        } else {
                            $_list[] = $filename;
                        }
                    } else {
                        $ext      = substr(strrchr($file, '.'), 1);
                        $haystack = explode('|', $exts);
                        if (in_array($ext, $haystack)) {
                            if ($callback instanceof \Closure) {
                                $callback($filename);
                            } else {
                                $_list[] = $filename;
                            }
                        }
                    }
                }
            }
        }
        $dir->close();
        clearstatcache();
        return $_list;
    }
}

if (!function_exists('file_get_ext')) {
    /**
     * 获取文件扩展名
     * @param $file
     * @return bool|string
     */
    function file_get_ext($file)
    {
        return substr(strrchr($file, '.'), 1);
    }
}

if (!function_exists('file_get_name')) {
    /**
     * 获取文件名,不包含扩展名
     * @param $file
     * @return bool|string
     */
    function file_get_name($file)
    {
        $name = basename($file);
        $name = substr($name, 0, strrpos($name, '.'));
        return $name;
    }
}

if (!function_exists('dir_delete')) {
    /**
     * 删除非空目录
     * @param string $dir
     * @return boolean
     */
    function dir_delete($dirname)
    {
        if (is_dir($dirname)) {
            $handle = opendir($dirname);
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    $dir  = $dirname . '/' . $file;
                    $self = __FUNCTION__;
                    is_dir($dir) ? $self($dir) : unlink($dir);
                }
            }
            closedir($handle);
            return rmdir($dirname);
        }
        return false;
    }
}

if (!function_exists('dir_copy')) {
    /**
     * 复制目录
     * @param string $source 要复制的文件
     * @param string $dest   复制文件的目的地
     * @return bool
     */
    function dir_copy($source, $dest)
    {
        $ret = false;
        file_recursion($source, '*', function ($file) use ($source, $dest, &$ret) {
            $newfilename = $dest . '/' . str_replace($source, '', $file);
            $newpaht     = dirname($newfilename);
            is_dir($newpaht) || mkdir($newpaht, '0755', true);
            $ret = copy($file, $newfilename);
        });
        return $ret;
    }
}

if (!function_exists('download_file')) {
    /**
     * 下载文件
     * 可以指定下载显示的文件名，并自动发送相应的Header信息
     * 如果指定了content参数，则下载该参数的内容
     * @static
     * @access public
     * @param string  $filename 下载文件名
     * @param string  $showname 下载显示的文件名
     * @param string  $content  下载的内容
     * @param integer $expire   下载内容浏览器缓存时间
     * @return void
     */
    function download_file($filename, $showname = '', $content = '', $expire = 180)
    {
        if (is_file($filename)) {
            $length = filesize($filename);
        } elseif (is_file(UPLOAD_PATH . $filename)) {
            $filename = UPLOAD_PATH . $filename;
            $length   = filesize($filename);
        } elseif ($content != '') {
            $length = strlen($content);
        } else {
            exit('下载文件不存在');
        }
        if (empty($showname)) {
            $showname = $filename;
        }
        $showname = basename($showname);
        if (!empty($filename)) {
            $finfo = new \finfo(FILEINFO_MIME);
            $type  = $finfo->file($filename);
        } else {
            $type = "application/octet-stream";
        }
        //发送Http Header信息 开始下载
        header("Pragma: public");
        header("Cache-control: max-age=" . $expire);
        //header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expire) . "GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", time()) . "GMT");
        header("Content-Disposition: attachment; filename=" . $showname);
        header("Content-Length: " . $length);
        header("Content-type: " . $type);
        header('Content-Encoding: none');
        header("Content-Transfer-Encoding: binary");
        if ($content == '') {
            readfile($filename);
        } else {
            echo($content);
        }
        exit();
    }
}

if (!function_exists('file_zip')) {
    /**
     * 文件夹压缩
     * @param string $filename
     * @param string $path
     * @return bool
     */
    function file_zip($filename, $path)
    {
        //检查压缩zip类扩展是否开启
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            //覆盖原压缩文件
            $zip->open($filename, 1);
            $files = file_recursion($path);
            foreach ($files as $file) {
                //遍历添加待压缩文件
                $zip->addFile($file, basename($file));
            }
            return $zip->close();
        }
        return false;
    }
}

if (!function_exists('file_merge')) {
    /**
     * 文件合并
     * @param array  $files       待合并文件数组
     * @param string $newFileName 保存名称
     * @return bool
     */
    function file_merge($files, $newFileName)
    {
        foreach ($files as $file) {
            $handle = fopen($file, 'r');
            $buffer = 1024 * 2048;//每次读取2M
            while (!feof($handle)) {
                $data = fread($handle, $buffer);
                file_put_contents($newFileName, $data, FILE_APPEND);
            }
            fclose($handle);
        }
        return true;
    }
}

if (!function_exists('mkdir_ex')) {
    /**
     * 创建一个目录或者多级目录并且拥有最大权限
     * @param string $pathname
     * @return bool
     */
    function mkdir_ex($pathname)
    {
        if (!is_dir($pathname)) {
            mkdir($pathname, 0777, true);
            return chmod($pathname, 0777);
        }
        return false;
    }
}

if (!function_exists('curl_get')) {
    /**
     * get方式获取Url内容
     * @param $url
     * @return mixed
     */
    function curl_get($url, $params = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content, $status) = [curl_exec($curl), curl_error($curl)];
        curl_close($curl);
        if ($status !== null) {
            return $content;
        }
        return false;
    }
}

if (!function_exists('curl_post')) {
    /**
     * post方式获取Url内容
     * @param        $url
     * @param array  $data
     * @param string $header
     * @return bool|mixed
     */
    function curl_post($url, $data = [], $header = '')
    {
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL, $url);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, $header);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, true);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        list($content, $status) = [curl_exec($curl), curl_error($curl)];
        curl_close($curl);
        if ($status !== null) {
            return $content;
        }
        return false;
    }
}
