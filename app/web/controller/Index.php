<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/12/18
// +----------------------------------------------------------------------

namespace app\web\controller;

use app\web\lib\Base;

class Index extends Base
{
    //列表页
    public function index()
    {
        return $this->fetch();
    }

    //微圈
    public function circle()
    {
        return $this->fetch();
    }
}