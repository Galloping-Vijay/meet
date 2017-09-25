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
use tuling\Tuling;

class Test extends Base
{
    public $userid = '123456';
    public  $info = '';

    public function index()
    {
        if($this->request->isPost()) {
            $this->info = input('content');
            $config = [
                'userid' => $this->userid,
                'info' => $this->info
            ];
            $tuling = new Tuling();
            $conf = $tuling->config($config);

            $curl = new Curl();
            $data = $curl->post($tuling->app_url(),$conf);
            $json = json_decode($data, true);
            pr($json);
        }
        return $this->fetch('test/index');
    }
}