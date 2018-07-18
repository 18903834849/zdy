<?php

namespace zdy;
date_default_timezone_set('Asia/Shanghai');
// 定义扩展库目录
define('ZDY_PATH', __DIR__ . '/library/');
// 自动加载类
require_once ZDY_PATH . 'zdy/Autoloader.php';
Autoloader::register(['zdy' => [ZDY_PATH . '/zdy/', '.php']]);
// 加载常用函数
require_once ZDY_PATH . '/function/common.php';