<?php
// +----------------------------------------------------------------------
// | ModuleInit.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/3/23
// +----------------------------------------------------------------------

namespace app\common\behavior;

use \think\View;

class ModuleInit
{
    //module_init
    public function moduleInit(&$request)
    {
        //设置全局的模板变量
        View::share(
            [
                'Config' => C(),
                'config_siteurl' => C('siteurl'),
            ]
        );
        //兼容处理,提倡用Request类获取这些
        switch ($request->method()) {
            case 'GET':
                $_GET = array_merge($_GET, $request->param());
                break;
            case 'POST':
                $_POST = array_merge($_POST, $request->param());
                break;
        }
        return true;
    }
}