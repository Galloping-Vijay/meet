<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/24
// +----------------------------------------------------------------------

namespace app\blog\controller\admin;

use app\common\controller\AdminBase;

class Index extends AdminBase
{
    public function index()
    {
        return $this->fetch();
    }
}