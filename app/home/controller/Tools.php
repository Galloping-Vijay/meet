<?php
// +----------------------------------------------------------------------
// | Tools.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.suckseed.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <184521508@qq.com> 2017-09-19
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\home\lib\Base;

class Tools extends Base
{
    //百度站长认证
    public function baiduVerify()
    {
        return $this->fetch('tools/baidu_verify_5BvCfjWUag');
    }
}