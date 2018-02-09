<?php
// +----------------------------------------------------------------------
// | Adhibition.小应用开发
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/2/9
// +----------------------------------------------------------------------

namespace app\web\controller;

use app\web\lib\Base;

class Adhibition extends Base
{
    /**
     * 会员卡
     * @return mixed
     */
    public function vip()
    {
        return $this->fetch();
    }

    /**
     * 预约
     */
    public function subscribe()
    {
        return $this->fetch();
    }
}