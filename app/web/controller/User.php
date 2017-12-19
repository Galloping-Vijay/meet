<?php
// +----------------------------------------------------------------------
// | User.用户控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/12/19
// +----------------------------------------------------------------------

namespace app\web\controller;

use app\web\lib\Base;

class User extends Base
{
    //用户主页
    public function index()
    {
        return $this->fetch();
    }

    //我的发布
    public function release()
    {
        return $this->fetch();
    }
}