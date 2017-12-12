<?php
// +----------------------------------------------------------------------
// | Test.测试模块
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/25
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\home\lib\Base;
use EasyWeChat\Message\Text;

class Test extends Base
{
    public function index()
    {
        pr(11);

    }

    public function ceshi()
    {
        $text = new Text();
        $data = $text->reply('测试一下');
        pr($data);
    }
}