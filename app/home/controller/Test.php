<?php
// +----------------------------------------------------------------------
// | Test.测试模块
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/25
// +----------------------------------------------------------------------

namespace app\home\controller;

use EasyWeChat\Message\Text;

class Test extends Base
{
    public $userid = '123456';
    public $info = '';

    public function index()
    {
        //return $this->fetch('test:index');
    }

    public function ceshi()
    {
        $text = new Text();
        $data = $text->reply('测试一下');
        pr($data);
    }
}