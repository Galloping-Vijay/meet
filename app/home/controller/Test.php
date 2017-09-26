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
        $APIs = $this->jsApiList;
        //微信平台
        $config=config('we_options');
        if(!empty($config)) $this->options=array_merge($this->options,$config);
       $app = new Application($this->options);
       $js = $app->js;
       $wxconfig = $js->config($APIs, $debug = true, $beta = false, $json = false);
       pr($wxconfig);
    }

    public function ceshi()
    {
       $text = new Text();
       $data = $text->reply('测试一下');
        pr($data);
    }
}