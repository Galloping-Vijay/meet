<?php
// +----------------------------------------------------------------------
// | general.通用函数(不依赖于任何框架)
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <184521508@qq.com> 2017/9/27
// +----------------------------------------------------------------------

/**
 * print_r格式化输出
 * @param $data
 */
function pr($data, $choice = 0)
{
    if ($choice == 1) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } elseif ($choice == 2) {
        \think\Debug::dump($data, true, null);
    } else {
        echo "<pre>";
        var_export($data);
        echo "</pre>";
    }
    exit;
}

/**
 * Redis缓存
 * @param string $name
 * @param string $value
 * @param null $options
 * @param null $tag
 * @return bool|mixed|null|\think\cache\driver\Redis
 */
function redis($name = '', $value = '', $options = null, $tag = null)
{
    static $redis = null;
    if (is_null($redis)) {
        $options = Config('redis');
        $redis = new \think\cache\driver\Redis($options);
    }
    if ('' == $name && '' === $value) {
        return $redis;
    } elseif ('' === $value) {
        // 获取缓存
        return $redis->get($name);
    } elseif (is_null($value)) {
        // 删除缓存
        return is_null($tag) ? $redis->rm($name) : $redis->clear($tag);
    } else {
        // 缓存数据
        if (is_array($options)) {
            $expire = isset($options['expire']) ? $options['expire'] : null; //修复查询缓存无法设置过期时间
        } else {
            $expire = is_numeric($options) ? $options : null; //默认快捷缓存设置过期时间
        }
        if (!is_null($tag)) {
            $redis->tag($tag);
        }
        return $redis->set($name, $value, $expire);
    }
}

/**
 * 获取某个模块下的导航菜单
 * Author: wjf <1937832819@qq.com>
 * @param int $mid
 * @param bool $isCache
 * @return false|mixed|PDOStatement|string|\think\Collection
 */
function nav_menu($mid = 1, $isCache = true)
{
    $live_menu = cache('live_menu');
    if ($isCache == false || empty($live_menu)) {
        $live_menu = \think\Db::name('menu')->where('menu_moduleid', $mid)->select();
        cache('live_menu', $live_menu);
    }
    return $live_menu;
}

/**
 * 发送HTTP状态
 * @param integer $code 状态码
 * @return void
 */
function send_http_status($code)
{
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    );
    if (isset($_status[$code])) {
        header('HTTP/1.1 ' . $code . ' ' . $_status[$code]);
        // 确保FastCGI模式下正常
        header('Status:' . $code . ' ' . $_status[$code]);
    }
}