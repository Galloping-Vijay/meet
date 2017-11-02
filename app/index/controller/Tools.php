<?php
// +----------------------------------------------------------------------
// | Tools.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/27
// +----------------------------------------------------------------------

namespace app\index\controller;

use think\Config;
use think\Response;

class Tools extends Base
{
    /**
     * 百度站长认证
     * @return mixed
     */
    public function baiduVerify()
    {
        return $this->fetch();
    }

    /**
     * robots内容
     * @return Response|\think\response\Json|\think\response\Jsonp|\think\response\Redirect|\think\response\View|\think\response\Xml
     */
    public function robots()
    {
        $data =[
            '*' => "User-agent: * \nDisallow: /index.php?*"
        ];
        $httpHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '*';
        $robots = isset($data[$httpHost]) ? $data[$httpHost] : $data['*'];
        $response = Response::create();
        $response->header('Content-type', 'text/plain');
        $response->content($robots);
        Config::set('app_trace', false);
        return $response;
    }
}