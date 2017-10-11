<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/29
// +----------------------------------------------------------------------

namespace app\live\controller;

use think\Request;

class Index extends Base
{
    /**
     * home
     * Author: wjf <1937832819@qq.com>
     * @return mixed
     */
    public function index()
    {
        $this->assign('active',22);
        return $this->fetch();
    }

    /**
     * 推荐
     * Author: wjf <1937832819@qq.com>
     */
    public function hot()
    {
        $this->assign('active',23);
        return $this->fetch();
    }
}