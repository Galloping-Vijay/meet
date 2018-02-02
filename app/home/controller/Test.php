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
use program\tuling\Tuling;

class Test extends Base
{
    public function index()
    {
        $params = [
            'reqType' => 0,
            'perception' => [
                'inputText' => [
                    'text' => 'zheshi'
                ]
            ],
            'userInfo' => [
                'userId' => 32
            ]
        ];
        return json_encode($params);

    }

    public function ceshi()
    {
        $data = Tuling::handle()->images('http://mmbiz.qpic.cn/mmbiz_jpg/mdsFG64gjW7UFIleeicNrCwJMP73xKM7SjwjrID26CAzO06Cd7RnAdLdNHd0UrmmJjz4TicqB8unu8dFTgyxRwNA/640?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1');
        pr($data);
    }
}