<?php
// +----------------------------------------------------------------------
// | Effects.特效控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/27
// +----------------------------------------------------------------------

namespace app\index\controller;

use think\Request;
use app\index\lib\Base;

class Effects extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('active', 2);
    }

    public function index()
    {
        echo '特效控制器';
    }

    //秋天
    public function fall()
    {
        return $this->fetch('effect:fall');
    }

    //画板
    public function palette()
    {
        return $this->fetch('effect:palette');
    }

    //抽奖
    public function Lottery()
    {
        return $this->fetch('effect:Lottery');
    }

    //视频vip
    public function vip()
    {
        $menu = [
            'menu_seo_title' => 'vip视频播放器!',
            'menu_seo_key' => 'vip视频播放器!',
            'menu_seo_des' => 'vip视频播放器!',
        ];
        $this->assign('menu', $menu);
        if (request()->isMobile()) {
            return $this->fetch('effect:m_vip');
        }
        return $this->fetch('effect:vip');
    }
}