<?php
// +----------------------------------------------------------------------
// | Test.测试模块
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/25
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\admin\model\Module;
use EasyWeChat\Message\Text;

class Test extends Base
{
    public function index()
    {
        $bb =['default_module' => 'admin'];
        //sys_config_setbyarr($bb);

    }

    public function ceshi()
    {
        $text = new Text();
        $data = $text->reply('测试一下');
        pr($data);
    }
}