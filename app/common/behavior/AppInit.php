<?php
// +----------------------------------------------------------------------
// | AppInit.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/3/23
// +----------------------------------------------------------------------

namespace app\common\behavior;

use think\Config;
use think\Cookie;
use think\exception\HttpResponseException;
use think\Request;
use think\Response;

class AppInit
{
    //行为入口
    public function appInit()
    {
        if (IS_CLI) {
            return true;
        }
        $this->url();
        //浏览器缓存
        if (Request::instance()->has('HTTP_IF_MODIFIED_SINCE', 'server') && false === Cookie::has('no_display_cache')) {
            $httpIfModifiedSince = strtotime(Request::instance()->server('HTTP_IF_MODIFIED_SINCE'));
            if ($httpIfModifiedSince > NOW_TIME) {
                throw new HttpResponseException(Response::create()->code(304));
            }
        }
    }

    //内容输出后,设置浏览器缓存时间
    public function appEnd(Response &$response)
    {
        //跨域请求
        $response->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With');
        //页面缓存
        $browserCache = C('browser_cache');
        if (empty($browserCache) || false !== Cookie::has('no_display_cache')) {
            return true;
        }
        $response
            ->cacheControl('public')
            ->expires(gmdate("D, d M Y H:i:s", NOW_TIME + (is_numeric($browserCache) ? $browserCache : 60)) . ' GMT')
            ->lastModified(gmdate("D, d M Y H:i:s", NOW_TIME + (is_numeric($browserCache) ? $browserCache : 60)) . ' GMT');
        return true;
    }

    //view_filter行为
    public function viewFilter(&$content)
    {
        $replace = Config::get('view_replace_str');
        if (!empty($replace)) {
            $content = strtr($content, $replace);
        }
        return true;
    }

    //URL访问地址处理
    protected function url()
    {
        $request = Request::instance();
        $pathInfo = $request->pathinfo();
        if (empty($pathInfo)) {
            return true;
        }
        $info = pathinfo($pathInfo);
        //是否以斜杠结尾
        $isXhuax = '/' == substr($pathInfo, -1);
        $isExt = isset($info['extension']);
        if ($isExt || $isXhuax) {
            return true;
        }
        //如果不是.html后缀,也不是/结尾,301重定向到/结尾
        if (!$isExt && !$isXhuax) {
            $url = $request->url(true) . '/';
            $this->redirect($url);
            return true;
        }
        return true;
    }

    // 分析 PATH_INFO
    protected function parsePathinfo(array $config)
    {
        $pathInfo = '';
        $get = $_GET;
        if (isset($get[$config['var_pathinfo']])) {
            // 判断URL里面是否有兼容模式参数
            $pathInfo = $get[$config['var_pathinfo']];
            unset($get[$config['var_pathinfo']]);
        }
        // 分析PATHINFO信息
        if (!isset($pathInfo)) {
            foreach ($config['pathinfo_fetch'] as $type) {
                if (!empty($_SERVER[$type])) {
                    $pathInfo = (0 === strpos($_SERVER[$type], $_SERVER['SCRIPT_NAME'])) ?
                        substr($_SERVER[$type], strlen($_SERVER['SCRIPT_NAME'])) : $_SERVER[$type];
                    break;
                }
            }
        }
        return $pathInfo;
    }

    /**
     * URL重定向
     * @access public
     * @param string $url 跳转的URL表达式
     * @param array|int $params http code
     * @return void
     */
    protected function redirect($url, $params = 301)
    {
        $http_response_code = 301;
        if (is_int($params) && in_array($params, [301, 302])) {
            $http_response_code = $params;
        }
        throw new HttpResponseException(redirect($url, [], $http_response_code));
    }
}