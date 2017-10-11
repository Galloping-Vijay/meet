<?php
// +----------------------------------------------------------------------
// | Gallery.画廊
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-08
// +----------------------------------------------------------------------

namespace app\live\controller;

use think\Request;

class Cat extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('active', 24);
    }

    public function index()
    {
        return $this->fetch();
    }
}