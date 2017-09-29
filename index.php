<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/app/');
define('WJF_PATH', __DIR__ );
define("RUNTIME_PATH", __DIR__ . '/data/runtime/');
defined('NOW_TIME') or define('NOW_TIME', $_SERVER['REQUEST_TIME']);
// 加载模块文件
/*$file = WJF_PATH . '/data/conf/module.php';
if (file_exists($file)) {
    require WJF_PATH . '/data/conf/module.php';
} else {
    define("BIND_MODULE","home");
}*/
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
