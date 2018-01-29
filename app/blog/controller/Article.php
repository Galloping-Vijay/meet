<?php
// +----------------------------------------------------------------------
// | Article.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/1/29
// +----------------------------------------------------------------------

namespace app\blog\controller;

use app\blog\lib\Base;

class Article extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function cat()
    {
        return $this->fetch();
    }

    public function search()
    {
        return $this->fetch();
    }

    public function tags()
    {
        return $this->fetch();
    }

    public function tougao()
    {
        return $this->fetch();
    }
}