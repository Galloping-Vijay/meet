<?php
// +----------------------------------------------------------------------
// | Base.live基类控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-08
// +----------------------------------------------------------------------

namespace app\live\controller;

use app\common\controller\FrontBase;
use think\Db;

class Base extends FrontBase
{
    protected function _initialize()
    {
        parent::_initialize();
        //导航菜单
        $nav = nav_menu(2);
        $this->assign('nav', $nav);
    }
}