<?php
// +----------------------------------------------------------------------
// | Effects. 前端特效控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/5
// +----------------------------------------------------------------------

namespace app\home\controller;


class Effects extends Base
{
    public function index()
    {
        echo '特效控制器';
    }

    //秋天
    public function fall()
    {
        return $this->view->fetch('effect:fall');
    }

    //画板
    public function palette()
    {
        return $this->view->fetch('effect:palette');
    }

    //抽奖
    public function Lottery()
    {
        return $this->view->fetch('effect:Lottery');
    }

    //视频vip
    public function vip()
    {
        $menu = [
            'menu_seo_title' => 'vip视频播放器!',
            'menu_seo_key' => 'vip视频播放器!',
            'menu_seo_des' => 'vip视频播放器!',
        ];
        $this->view->assign('menu', $menu);
        if (request()->isMobile()) {
            return $this->view->fetch('effect:m_vip');
        }
        return $this->view->fetch('effect:vip');
    }
}