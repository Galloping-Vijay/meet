<?php
// +----------------------------------------------------------------------
// | Curl.封装
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/25
// +----------------------------------------------------------------------

namespace app\common\lib;

use think\Exception;

class Curl
{
    /**
     * 执行curl
     * @param string $method 请求方式
     * @param string $url 地址
     * @param string|array $fields 附带参数，可以是数组，也可以是字符串
     * @param string $userAgent 浏览器UA
     * @param array $httpHeaders header头部，数组形式
     * @param string $username 用户名
     * @param string $password 密码
     * @return boolean
     */
    public function execute($method, $url, $fields = '', $userAgent = '', $httpHeaders = '', $username = '', $password = '')
    {
        $ch = $this->create();
        if (false === $ch) {
            return false;
        }
        if (is_string($url) && strlen($url)) {
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            return false;
        }
        //是否显示头部信息
        curl_setopt($ch, CURLOPT_HEADER, false);
        //不直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //传递一个形如[username]:[password]风格的字符串
        if (!empty($username)) {
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        }
        //ssl
        if (false !== stripos($url, "https://")) {
            //对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //从证书中检查SSL加密算法是否存在
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $method = strtolower($method);
        if ('post' == $method) {
            curl_setopt($ch, CURLOPT_POST, true);
            if (is_array($fields)) {
                $fields = http_build_query($fields);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        } else if ('put' == $method) {
            curl_setopt($ch, CURLOPT_PUT, true);
        }
        //curl_setopt($ch, CURLOPT_PROGRESS, true);
        //curl_setopt($ch, CURLOPT_VERBOSE, true);
        //curl_setopt($ch, CURLOPT_MUTE, false);
        //设置curl超时秒数
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //在HTTP请求中包含一个'user-agent'头的字符串
        if (strlen($userAgent)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        }
        if (is_array($httpHeaders)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }
        try {
            $ret = curl_exec($ch);
            //返回最后一次的错误号
            if (curl_errno($ch)) {
                curl_close($ch);
                return [curl_error($ch), curl_errno($ch)];
            }
            curl_close($ch);
            if (!is_string($ret) || !strlen($ret)) {
                return false;
            }
            return $ret;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 发送POST请求
     * @param type $url 地址
     * @param type $fields 附带参数，可以是数组，也可以是字符串
     * @param type $userAgent 浏览器UA
     * @param type $httpHeaders header头部，数组形式
     * @param type $username 用户名
     * @param type $password 密码
     * @return boolean
     */
    public function post($url, $fields, $userAgent = '', $httpHeaders = '', $username = '', $password = '')
    {
        $ret = $this->execute('POST', $url, $fields, $userAgent, $httpHeaders, $username, $password);
        if (false === $ret) {
            return false;
        }
        if (is_array($ret)) {
            return false;
        }
        return $ret;
    }

    /**
     * GET
     * @param type $url 地址
     * @param type $userAgent 浏览器UA
     * @param type $httpHeaders header头部，数组形式
     * @param type $username 用户名
     * @param type $password 密码
     * @return boolean
     */
    public function get($url, $userAgent = '', $httpHeaders = '', $username = '', $password = '')
    {
        $ret = $this->execute('GET', $url, "", $userAgent, $httpHeaders, $username, $password);
        if (false === $ret) {
            return false;
        }
        if (is_array($ret)) {
            return false;
        }
        return $ret;
    }

    /**
     * curl支持 检测
     * @return boolean
     */
    public function create()
    {
        $ch = null;
        if (!function_exists('curl_init')) {
            return false;
        }
        $ch = curl_init();
        if (!is_resource($ch)) {
            return false;
        }
        return $ch;
    }
}