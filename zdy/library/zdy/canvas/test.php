<?php

$Canvas = \zdy\canvas\Canvas::create(500, 500, '#f20');
$layer = \zdy\canvas\Layer::createByFile('https://www.baidu.com/img/bd_logo1.png');
$Canvas->append($layer);
$Canvas->display();
