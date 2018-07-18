<?php

namespace zdy\canvas;

/**
 * 画布
 * Class ImageCanvas
 */
class Canvas
{
    
    // 当前画布图层对象
    protected $canvas;
    // 图层集合
    protected $layers = [];
    
    /**
     * 新建一个空白画布
     * @param        $width
     * @param        $height
     * @param string $colorBackground
     * @return Canvas
     */
    public static function create($width, $height, $colorBackground = '')
    {
        return new self($width, $height, $colorBackground);
    }
    
    /**
     * 构造方法(新建一个空白画布)
     * ImageCanvas constructor.
     * @param        $width
     * @param        $height
     * @param string $colorBackground
     */
    public function __construct($width, $height, $colorBackground = '')
    {
        $this->canvas = Layer::create($width, $height, $colorBackground);
    }
    
    /**
     * 在画布上添加图层
     * @param Layer  $layer
     * @param int    $x
     * @param int    $y
     * @param string $w
     * @param string $h
     * @param int    $z
     * @return $this
     */
    public function append($layer, $x = 0, $y = 0, $w = '', $h = '', $z = 0)
    {
        $this->layers [$z][] = [
            'layer' => $layer,
            'x'     => $x,
            'y'     => $y,
            'w'     => empty($w) ? $layer->width : $w,
            'h'     => empty($h) ? $layer->height : $h,
        ];
        ksort($this->layers);
        return $this;
    }
    
    /**
     * 合并图层
     * @return $this
     */
    public function merge()
    {
        foreach ($this->layers as $item) {
            foreach ($item as $item2) {
                $src_image = $item2['layer']->image;
                $dst_x     = $item2['x'];
                $dst_y     = $item2['y'];
                $dst_w     = $item2['w'];
                $dst_h     = $item2['h'];
                imagecopyresampled($this->getImage(), $src_image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $item2['layer']->width, $item2['layer']->height);
            }
        }
        return $this;
    }
    
    /**
     * 获取图层对应的图像资源
     * @return mixed
     */
    public function getImage()
    {
        return $this->canvas->image;
    }
    
    /**
     * 显示图层
     */
    public function display()
    {
        $this->merge();
        header('Content-type:image/jpeg');
        imagejpeg($this->getImage());
        exit;
    }
    
    /**
     * 保存到图片文件
     * @param      $filename
     * @param null $quality
     */
    public function save($filename, $quality = null)
    {
        $this->merge();
        imagejpeg($this->getImage(), $filename);
    }
    
}