<?php
// +----------------------------------------------------------------------
// | Tuling. 图灵机器人接口
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/25
// +----------------------------------------------------------------------

namespace program\tuling;

class Tuling
{
    //图灵机器人API
    protected $api_key = '7b8cd5e1d099489887a0d74a1c976e25';
    protected $app_url = 'http://www.tuling123.com/openapi/api';

    public function __construct()
    {

    }

    public function config($config = [])
    {
        return array_merge($config, ['key' => $this->api_key]);
    }

    public function app_url()
    {
        return $this->app_url;
    }


}