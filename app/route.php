<?php
// +----------------------------------------------------------------------
// | ${NAME}.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/26
// +----------------------------------------------------------------------
//调用extend的路由类
$Route = new \Route;
$route_array = $Route->route_array();
$route_more = [
    //也可以这里添加路由规则
    '__domain__' => [
        'blog' => 'blog',

    ],
    'baidu_verify_5BvCfjWUag' => 'index/tools/baiduVerify',
    'vip' => 'index/effects/vip',
    'fall' => 'index/effects/fall',
];
$route = array_merge($route_more, $route_array);
return $route;