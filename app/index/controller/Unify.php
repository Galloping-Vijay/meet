<?php
// +----------------------------------------------------------------------
// | Unify.各个模块通用的请求
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/20
// +----------------------------------------------------------------------

namespace app\index\controller;

use think\captcha\Captcha;
use think\Db;
use app\index\lib\Base;

class Unify extends Base
{
    //获取二维码
    public function verify_msg()
    {
        ob_end_clean();
        $verify = new Captcha(config('verify'));
        return $verify->entry('msg');
    }

    //留言统一处理方法
    public function addmsg()
    {
        if (!request()->isAjax()) {
            $this->error(lang('submission mode incorrect'));
        } else {
            $verify = new Captcha ();
            if (!$verify->check(input('verify'), 'msg')) {
                $this->error(lang('verifiy incorrect'));
            }
            $data = array(
                'plug_sug_name' => input('plug_sug_name'),
                'plug_sug_email' => input('plug_sug_email'),
                'plug_sug_content' => input('plug_sug_content'),
                'plug_sug_addtime' => time(),
                'plug_sug_open' => 0,
                'plug_sug_ip' => request()->ip(),
            );
            $rst = Db::name('plug_sug')->insert($data);
            if ($rst !== false) {
                $this->success(lang('message success'));
            } else {
                $this->error(lang('message failed'));
            }
        }
    }
}