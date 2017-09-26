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
       $wxconfig = $js->config($APIs, $debug = true, $beta = false, $json = true);
       pr($wxconfig);
        $wxconfig = Array
        (
            'debug' => 1,
            'beta' => '',
            'appId' => 'wxf31792477fe34e63',
            'nonceStr' => '4OT9JTUBjy',
            'timestamp' => '1506404953',
            'url' => 'http://www.meetoyou.com/home/test/index',
            'signature' => '1855727310348ea245b586e348ac09f3dcd01b2d',
            'jsApiList' => ["onMenuShareTimeline",//分享到朋友圈
                "onMenuShareAppMessage",//分享给朋友
                "onMenuShareQQ",//分享到QQ
                "onMenuShareWeibo",//分享到腾讯微博
                "onMenuShareQZone",//分享到QQ空间
            ]
        );
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