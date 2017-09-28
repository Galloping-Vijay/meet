<?php
// +----------------------------------------------------------------------
// | Test.测试模块
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/25
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\common\lib\Encrypt;
use app\common\lib\ValidateBasic;
use app\common\lib\Visitor;
use EasyWeChat\Message\Text;

class Test extends Base
{
    public function index()
    {
       $redis = redis();
       $ret = $redis->set('test:str1','predis');
       //echo $ret;
       $ret1 = $redis->get('test:str1');
       echo $ret1;
    }

    public function ceshi()
    {
        $text = new Text();
        $data = $text->reply('测试一下');
        pr($data);
    }
}