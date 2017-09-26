<?php
// +----------------------------------------------------------------------
// | Test.测试模块
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/25
// +----------------------------------------------------------------------

namespace app\home\controller;


use app\common\lib\Curl;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
use tuling\Tuling;

class Test extends Base
{
    public $userid = '123456';
    public $info = '';

    public function index()
    {
        /*$APIs = $this->jsApiList;
        //微信平台
        $config = config('we_options');
        if (!empty($config)) $this->options = array_merge($this->options, $config);
        $app = new Application($this->options);
        $js = $app->js;
        $wxconfig = $js->config($APIs, $debug = true, $beta = false, $json = true);
        pr($wxconfig);*/
        $wxconfig = '{"debug":true,"beta":false,"appId":"wxf31792477fe34e63","nonceStr":"fciudIX6mP","timestamp":1506405652,"url":"http:\/\/www.meetoyou.com\/home\/test\/index","signature":"36399be49c4864253680ade8d7e28e9e86ffc1a5","jsApiList":["onMenuShareTimeline","onMenuShareAppMessage","onMenuShareQQ","onMenuShareWeibo","onMenuShareQZone"]}';
        $this->assign('wxconfig', $wxconfig);
        return $this->fetch('test:index');
    }

    public function ceshi()
    {
        $text = new Text();
        $data = $text->reply('测试一下');
        pr($data);
    }
}