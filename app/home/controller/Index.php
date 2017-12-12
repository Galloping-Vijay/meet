<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/11/30
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\home\lib\Base;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
}