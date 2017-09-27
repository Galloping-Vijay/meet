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
function pr($data, $isDumo = false)
{
    if ($isDumo) {
        \think\Debug::dump($data, true, null);
    } else {
        echo "<pre>";
        print_r($data);
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