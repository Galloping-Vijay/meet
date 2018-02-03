<?php
// +----------------------------------------------------------------------
// | Test.测试控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/2/3
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\index\lib\Base;
use program\tuling\Tuling;

class Test extends Base
{
    public function index()
    {
        echo 'index/test/index';
    }

    public function tl()
    {
        //http://turing-chat.oss.tuling123.com/9282213c5f3163c03e907ee6efcc8d51.jpg
        $str = '斗图';
        $type = 0;
        $data = Tuling::handle()->param($str, $type)->reply();
        pr($data, 1);
    }

}