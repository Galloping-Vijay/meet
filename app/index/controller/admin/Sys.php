<?php
// +----------------------------------------------------------------------
// | Sys.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-24
// +----------------------------------------------------------------------

namespace app\index\controller\admin;

use app\common\controller\AdminBase;

class Sys extends AdminBase
{
    /**
     * 首页
     */
    public function index(){
        return $this->fetch();
    }
}