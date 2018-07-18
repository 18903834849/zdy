<?php

namespace zdy\canvas;

/**
 * 图层
 * Class Layer
 * @package zdy\canvas
 */
class Layer
{
    // GD图像资源
    public $image;
    // 图像类型jpeg|png|jif
    public $type;
    // 图层宽度
    public $width;
    // 图层高度
    public $height;
    
    /**
     * 创建一个空白图层
     * @param        $width
     * @param        $height
     * @param string $colorBackground
     * @return Layer
     */
    public static function create($width, $height, $colorBackground = '')
    {
        $ImageLayer = new self(imagecreatetruecolor($width, $height));
        if ($colorBackground) {
            $color = static::createColor($colorBackground);
            imagefilledrectangle($ImageLayer->image, 0, 0, $width, $height, $color);
        }
        return $ImageLayer;
    }
    
    /**
     * 创建图层通过图片文件
     * @param $filename
     * @return Layer
     */
    public static function createByFile($filename)
    {
        $type               = static::getImageType($filename);
        $createImageFunName = "imageCreateFrom{$type}";
        if (function_exists($createImageFunName)) {
            $image = $createImageFunName($filename);
        } else {
            $image = static::create(100, 100, '#fff');
        }
        return new self($image, $type);
    }
    
    /**
     * 创建图像资源通过图片字符串
     * @param $string
     * @return Layer
     */
    public static function createByString($string)
    {
        return new self(imagecreatefromstring($string), static::getImageType($string));
    }
    
    /**
     * 获取图片尺寸相关信息
     * @param $filename
     * @return string
     */
    public static function getImageType($filename)
    {
        try {
            // 检测为图片字符串
            if (strlen($filename) > 512) {
                // 创建临时图片文件来获取信息
                $tempName = tempnam('', time());
                file_put_contents($tempName, $filename);
                $size = getimagesize($tempName);
                unlink($tempName);
            } else {
                $size = getimagesize($filename);
            }
            if (isset($size['mime'])) {
                return substr($size['mime'], 6);
            }
        } catch (\Exception $exception) {
        
        }
        return 'jpeg';
    }
    
    /**
     * 创建一个色板
     * @param      $color
     * @param null $ImageLayer
     * @return int
     */
    public static function createColor($color, $ImageLayer = null)
    {
        $red = $green = $blue = 255;
        
        if (empty($image)) {
            $ImageLayer = static::create(100, 100);
        }
        
        // 数组参数
        if (is_array($color) && count($color) == 3) {
            list($red, $green, $blue) = $color;
        }
        
        if (is_string($color) && strrpos($color, '#') !== false) {
            // #fff
            if (strlen($color) == 4) {
                $red   = str_repeat(substr($color, 1, 1), 2);
                $green = str_repeat(substr($color, 2, 1), 2);
                $blue  = str_repeat(substr($color, 3, 1), 2);
            }
            // #e8e8e8
            if (strlen($color) == 7) {
                $red   = substr($color, 1, 2);
                $green = substr($color, 3, 2);
                $blue  = substr($color, 5, 3);
            }
        }
        
        $color = imagecolorallocate($ImageLayer->image, hexdec($red), hexdec($green), hexdec($blue));
        return $color;
    }
    
    /**
     * 通过载入的GD图像资源创建一个新图层
     * ImageLayer constructor.
     * @param        $image
     * @param string $type
     */
    public function __construct($image, $type = 'jpeg')
    {
        $this->image  = $image;
        $this->type   = $type;
        $this->width  = imagesx($image);
        $this->height = imagesy($image);
    }
    
    /**
     * 复制到新图层
     * @param string $width
     * @param string $height
     * @return Layer
     */
    public function copy($width = '', $height = '')
    {
        $ImageLayer = static::create($width, $height);
        imagecopyresampled($ImageLayer->image, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
        return $ImageLayer;
    }
    
    /**'
     * 写文字
     * @param      $text
     * @param      $size
     * @param int  $x
     * @param int  $y
     * @param null $color
     * @return $this
     */
    public function text($text, $size = 18, $x = 10, $y = 10, $color = '#000', $angle = 0, $fontfile = '')
    {
        $box = imageftbbox($size, $angle, $fontfile, $text);
        imagettftext($this->image, $size, $angle, $x, -$box[7] + $y, static::createColor($color, $this), $fontfile, $text);
        return $this;
    }
    
}