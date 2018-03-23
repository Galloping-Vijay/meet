<?php
// +----------------------------------------------------------------------
// | ApiDispatch.Api接口调度行为
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/3/23
// +----------------------------------------------------------------------

namespace app\common\behavior;

use app\common\model\Api;
use think\App;
use think\Config;
use think\exception\HttpResponseException;
use think\Loader;

class ApiDispatch
{
    //进行接口调度
    public function moduleInit($request)
    {
        $dispatch = $request->dispatch();
        //如果是后台控制器 admin. 开头的，就跳过
        if (isset($dispatch['module'][1]) && 'admin.' === substr($dispatch['module'][1], 0, 6)) {
            return true;
        }
        $request = request();
        $action = $request->param('action', 'index', 'trim');
        $version = $request->param('version', 0, 'intval');
        if ($version < 1) {
            $response = json(['status' => 0, 'error_code' => 10004, 'msg' => 'this version of the interface does not exist']);
            throw new HttpResponseException($response);
        }
        defined('API_VERSION') or define('API_VERSION', $version);
        $controller = $request->param('controller', 'index', 'trim');
        $appId = $request->param('app_id', 0, 'intval');
        if (empty($appId)) {
            $response = json(['status' => 0, 'error_code' => 10005, 'msg' => 'app_id not be empty']);
            throw new HttpResponseException($response);
        }
        //调试模式下,不需要验证签名
        $dev = $request->param('dev', '', 'trim');
        //接口权限验证
        /*if (!defined('APP_DEBUG') ||
            (defined('APP_DEBUG') && APP_DEBUG !== true) ||
            !$request->has('dev') ||
            ($request->has('dev') && 'develop' != $dev)
        ) {
            //请求时间
            $requestTime = $request->param('request_time', 0, 'intval');
            //sign加密秘钥
            $sign = $request->param('sign', '', 'trim');
            if (empty($sign)) {
                $response = json(['status' => 0, 'error_code' => 10000, 'msg' => 'sign can not be empty']);
                throw new HttpResponseException($response);
            }
            //获取应用信息
            $apiInfo = Api::appInfo($appId);
            if (empty($apiInfo) || empty($apiInfo['status'])) {
                $response = json(['status' => 0, 'error_code' => 10006, 'msg' => 'you do not have permission']);
                throw new HttpResponseException($response);
            }
            //请求时间和服务器接收时间不得相差120秒
            if (NOW_TIME - $requestTime >= 120) {
                $response = json(['status' => 0, 'error_code' => 10002, 'msg' => 'request timeout']);
                throw new HttpResponseException($response);
            }
            //请求参数
            $params = [];
            foreach ($_GET as $key => $val) {
                if (!in_array($key, Api::$filterParameter)) {
                    $params[$key] = $val;
                }
            }
            //sign验证
            if ($apiInfo->generateSingStr($params) != $sign) {
                $data = ['status' => 0, 'error_code' => 10001, 'msg' => 'sign error'];
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    $data['params'] = $params;
                }
                throw new HttpResponseException(json($data));
            }
            //权限检查
            if (true !== $apiInfo->isAccess()) {
                $response = json(['status' => 0, 'error_code' => 10006, 'msg' => 'you do not have permission']);
                throw new HttpResponseException($response);
            }
        }*/
        if ($version > 1) {
            $controller = "{$controller}.V{$version}";
        }
        $instance = Loader::controller($controller, 'controller', false, Config::get('empty_controller'));
        if (is_null($instance)) {
            $response = json(['status' => 0, 'error_code' => 10004, 'msg' => 'this version of the interface does not exist']);
            throw new HttpResponseException($response);
        }
        $data = App::invokeMethod([$instance, $action], []);
        // 输出数据到客户端
        throw new HttpResponseException($this->appEnd($data));
        return true;
    }

    //内容输出处理
    protected function appEnd($data)
    {
        //接口返回数据
        $apiData = ['status' => 0, 'error_code' => 0, 'data' => null, 'msg' => '', 'returns_time' => time()];
        if (is_integer($data)) {//数字整型的情况下,默认作为错误代码,状态为0
            $apiData['status'] = 0;
            $apiData['error_code'] = $data;
            $apiData['msg'] = Config('system_error_code.' . $data) ?: 'interface request error';
        } else if (is_string($data)) {//字符串的情况下,默认当做请求成功作为数据返回,状态为1
            $apiData['status'] = 1;
            $apiData['data'] = $data;
        } else if (is_bool($data)) {//布尔值的情况下,直接返回status状态
            $apiData['status'] = $data ? 1 : 0;
            $apiData['data'] = $data;
            if (false === $data) {
                $apiData['error_code'] = 10007;
                $apiData['msg'] = 'unknown error';
            }
        } else if (is_array($data)) {
            //如果包含data字段
            if (isset($data['data'])) {
                $apiData['data'] = $data['data'];
                $apiData['status'] = isset($data['status']) ? ($data['status'] ? 1 : 0) : (isset($data['msg']) ? 0 : 1);
                $apiData['error_code'] = isset($data['error_code']) ? $data['error_code'] : 0;
                if (!$apiData['status']) {
                    $apiData['msg'] = isset($data['msg']) ? $data['msg'] : '';
                }
            } else {
                $apiData['data'] = $data;
                //数组状态下,如果包含 status msg 会赋值给 $apiData里相同键名
                if (isset($data['status'])) {
                    $apiData['status'] = $data['status'] ? 1 : 0;
                    if (!$apiData['status'] && isset($data['msg'])) {
                        $apiData['msg'] = $data['msg'];
                        if (isset($data['error_code'])) {
                            $apiData['error_code'] = $data['error_code'];
                        }
                    }
                } else {
                    $apiData['status'] = 1;
                }
            }
        }
        return json($apiData);
    }
}