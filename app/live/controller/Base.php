<?php
// +----------------------------------------------------------------------
// | Base.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/29
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