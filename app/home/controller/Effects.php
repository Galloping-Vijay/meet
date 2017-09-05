<?php
// +----------------------------------------------------------------------
// | Effects. 前端特效控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/5
// +----------------------------------------------------------------------

namespace app\home\controller;


class Effects extends Base
{
    public function index()
    {
        echo '特效控制器';
    }

    //秋天
    public function fall()
    {
        return $this->view->fetch('effect:fall');
    }
}