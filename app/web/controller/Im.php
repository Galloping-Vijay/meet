<?php
// +----------------------------------------------------------------------
// | Im.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/3/26
// +----------------------------------------------------------------------

namespace app\web\controller;


use app\web\lib\Base;
use think\Db;

class Im extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function ajaxData()
    {
        if (ob_get_level() == 0) {
            //判断缓冲区等级，如果没有活动缓冲区
            ob_start();        //打开缓冲区
            echo str_repeat('', 4096);
            ob_end_flush();
            ob_flush();
        }
        $where = [
            'rec' => 'admin',
            'is_new' => 0
        ];
        $order = [
            'id' => 'desc'
        ];
        while (true) {
            $chat = Db::name('chat_log')->where($where)->order($order)->find();
            if (!empty($chat)) {
                Db::name('chat_log')->where('id', $chat['id'])->update(['is_new' => 1]);
            }
            ob_flush();       //发送缓冲区数据
            flush();        //刷新缓冲区
            sleep(1);       //暂停1秒
        }
    }

}