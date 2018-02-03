<?php
// +----------------------------------------------------------------------
// | Test.测试控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/2/3
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\common\lib\Curl;
use app\common\lib\Download;
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
        /* $path = RUNTIME_PATH;
         pr($path);*/
        //http://turing-chat.oss.tuling123.com/9282213c5f3163c03e907ee6efcc8d51.jpg
        $str = 'www.meetoyou.com';
        $type = 0;
        $data = Tuling::handle()->images($str);
        pr($data, 1);
        /*$url = 'http://turing-chat.oss.tuling123.com/9cb44e1ed86054c8f247df2298eef464.png';
        $up = new Download();
        $name = $up->downloadImage($url);
        echo $name;*/
        /*$curl = new Curl();
        $img = $curl->get($url);
        $filename = pathinfo($img, PATHINFO_BASENAME);
        pr($filename);*/

    }

}