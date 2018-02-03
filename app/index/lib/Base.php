<?php
// +----------------------------------------------------------------------
// | Base.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/2/3
// +----------------------------------------------------------------------

namespace app\index\lib;

use app\common\controller\FrontBase;

class Base extends FrontBase
{
    protected function _initialize()
    {
        parent::_initialize();
        //导航菜单
        $nav = nav_menu();
        $this->assign('nav', $nav);
    }
}