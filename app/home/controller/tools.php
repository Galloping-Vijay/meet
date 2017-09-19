<?php
// +----------------------------------------------------------------------
// | tools.各种网站认证
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.suckseed.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <184521508@qq.com> 2017-09-19
// +----------------------------------------------------------------------

namespace app\home\controller;


class tools extends Base
{
    //百度站长认证
    public function baiduVerify()
    {
        return $this->view->fetch('tools/baidu_verify_5BvCfjWUag');
    }
}