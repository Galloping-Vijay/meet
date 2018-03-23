<?php
// +----------------------------------------------------------------------
// | ${NAME}.配置
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/3/23
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    // 显示错误信息
    'show_error_msg' => true,
    'exception_handle' => '\\app\\api\\exception\\Http',
    // 返回值中错误代码编号
    'system_error_code' => [
        //sign不能为空
        10000 => 'sign can not be empty',
        //sign错误
        10001 => 'sign error',
        //请求超时
        10002 => 'request timeout',
        //安全检查失败
        10003 => 'security check failed',
        //这个版本的接口不存在
        10004 => 'this version of the interface does not exist',
        //app_id不为空
        10005 => 'app_id not be empty',
        //你没有权限
        10006 => 'you do not have permission',
        //未知错误
        10007 => 'unknown error',
        //接口请求错误
        10008 => 'interface request error',
        //接口请求模式不正确
        10009 => 'the interface request mode is incorrect'
    ],
];