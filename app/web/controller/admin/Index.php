<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: vijay <1937832819@qq.com> 2018-01-16
// +----------------------------------------------------------------------

namespace app\web\controller\admin;

use app\common\controller\AdminBase;

class Index extends AdminBase
{
    /**
     * 后台首页
     */
    public function index()
    {
        return $this->fetch();
    }
}