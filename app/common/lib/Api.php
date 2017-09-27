<?php
// +----------------------------------------------------------------------
// | Api.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <184521508@qq.com> 2017/9/27
// +----------------------------------------------------------------------

namespace app\common\lib;

use think\Exception;
use think\Log;

class Api
{
    /**
     * $this
     * @var
     */
    protected static $instance;
    //是否开发模式
    protected $debug = false;
    //接口请求数据
    protected $requestData = [];
    //接口地址
    protected $apiPath = 'demo/index';
    //接口版本
    protected $version = '';
    //接口服务器地址
    protected $serverUrl = 'http://www.meetoyou.com/api/';
    //接口返回数据
    protected $data = null;
    //最后一次错误信息
    protected $error = '';
    //配置
    protected $config = [
        'app_id' => 0,
        'token' => '',
    ];
    //数据返回格式
    protected $format = 'json';
    //请求方式
    protected $method = 'GET';
    //系统参数
    protected $systemFilterParameters = ['sign', 'version', 'controller', 'action', 'app_id', 'sign'];

    /**
     * 初始化
     * @param string $apiPath 接口地址
     * @param array $requestData 请求参数
     * @param string $version 接口版本
     * @return mixed
     */
    public static function instance($apiPath = '', $requestData = [], $version = 'v1')
    {
        $name = $version . '/' . trim($apiPath);
        if (!isset(self::$instance[$name])) {
            self::$instance[$name] = new static($apiPath);
        }
        self::$instance[$name]->requestData($requestData)->version($version);
        return self::$instance[$name];
    }

    /**
     * 架构函数
     * Api constructor.
     * @param string $apiPath 接口地址
     * @param array $requestData 请求参数
     * @param string $version 接口版本
     */
    public function __construct($apiPath = '', $requestData = [], $version = 'v1')
    {
        $this->apiPath = $apiPath;
        $this->version($version);
        if (!empty($requestData)) {
            $this->requestData($requestData);
        }
        $this->debug(defined('APP_DEBUG') && APP_DEBUG ? APP_DEBUG : false);
    }

    /**
     * 设置请求参数
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (!in_array($name, $this->systemFilterParameters)) {
            $this->requestData[$name] = $value;
        }
    }

    /**
     * 获取请求参数
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->requestData[$name]) ? $this->requestData[$name] : null;
    }

    /**
     * 获取最后一次错误
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 获取接口请求数据
     * @return null
     */
    public function getData()
    {
        if (is_null($this->data)) {
            $this->request();
        }
        switch ($this->format) {
            default:
            case 'JSON':
                $data = $this->data;
                break;
            case 'ARRAY':
                $data = json_decode($this->data, true);
                break;
        }
        return $data;
    }

    /**
     * 请求接口
     * @return bool
     */
    public function request()
    {
        $curl = new Curl();
        try {
            $requestUrl = $this->getApiUrl();
            switch ($this->method) {
                case 'POST':
                    if ($this->debug) {
                        Log::record("Api接口调试:\nmethod:POST \nurl:{$requestUrl} \nrequestData:" . http_build_query($this->requestData) . "\n");
                    }
                    $data = $curl->post($requestUrl, $this->requestData);
                    break;
                case 'GET':
                default:
                    if ($this->debug) {
                        Log::record("Api接口调试\nmethod:GET \nurl:{$requestUrl}\n");
                    }
                    $data = $curl->get($requestUrl);
                    break;
            }
            if (false !== $data) {
                $this->data = $data;
                return true;
            }
            $this->error = '接口请求失败';
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
        return false;
    }

    /**
     * 获取接口请求地址
     * @return string
     */
    public function getApiUrl()
    {
        switch ($this->method) {
            case 'POST':
                $params = [];
                $this->signature($params);
                break;
            case 'GET':
            default:
                //get方式接口参数
                $params = $this->requestData;
                $this->signature($params);
                break;
        }
        $apiUrl = strtolower($this->serverUrl . '/' . $this->version . '/' . $this->apiPath . '.html') . '?' . http_build_query($params);
        return $apiUrl;
    }

    /**
     * 开启调试模式
     * @param bool $debug
     * @return $this
     */
    public function debug($debug = true)
    {
        if (defined('APP_DEBUG') && APP_DEBUG) {
            $this->debug = is_bool($debug) ? $debug : ($debug ? true : false);
            if ($this->debug) {
                $this->serverUrl = is_bool($debug) ? 'http://www.meetoyou.com/api/' : $debug;
            } else {
                $this->serverUrl = 'http://www.meetoyou.com/api/';
            }
        } else {
            $this->debug = false;
            $this->serverUrl = 'http://www.meetoyou.com/api/';
        }
        return $this;
    }

    /**
     * 设置接口请求方式
     * @param null $method GET、POST
     * @return $this|string
     */
    public function method($method = null)
    {
        if (!is_null($method) && in_array(strtoupper($method), ['GET', 'POST'])) {
            $this->method = strtoupper($method);
        } else if (is_null($method)) {
            return $this->method;
        }
        return $this;
    }

    /**
     * 设置数据返回格式
     * @param null $format
     * @return $this|string
     */
    public function format($format = null)
    {
        if (!is_null($format) && in_array(strtoupper($format), ['JSON', 'ARRAY'])) {
            $this->format = strtoupper($format);
        } else if (is_null($format)) {
            return $this->format;
        }
        return $this;
    }

    /**
     * 设置获取config配置
     * @param string $name 配置项
     * @return $this|array
     */
    public function config($name = '')
    {
        if (is_array($name)) {
            $this->config = array_merge($this->config, $name);
            return $this;
        } else if ($name) {
            return isset($this->config[$name]) ? $this->config[$name] : null;
        }
        return $this->config;
    }

    /**
     * 设置请求参数
     * @param array|string $name 参数名称
     * @param null $value 值
     * @return $this
     */
    public function requestData($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $n => $v) {
                $this->__set($n, $v);
            }
        } else {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * 版本设置
     * @param null|string $version
     * @return $this|string
     */
    public function version($version = null)
    {
        if (!is_null($version)) {
            $this->version = is_integer($version) ? "v{$version}" : $version;
            return $this;
        } else {
            return $this->version;
        }
    }

    /**
     * 生成请求秘钥sign
     * @param array $params 请求参数
     * @return string
     */
    private function signature(&$params = [])
    {
        //注入参数
        $params['request_time'] = $_SERVER['REQUEST_TIME'];
        $params['app_id'] = $this->config('app_id');
        $str = '';  //待签名字符串
        //先将参数以其参数名的字典序升序进行排序
        ksort($params);
        //遍历排序后的参数数组中的每一个key/value对
        foreach ($params as $k => $v) {
            //为key/value对生成一个key=value格式的字符串，并拼接到待签名字符串后面
            $str .= "{$k}={$v}";
        }
        $str = strtolower($str);
        //将签名密钥拼接到签名字符串最后面
        $str .= $this->config('token');
        //通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
        $params['sign'] = md5($str);
        return $params['sign'];
    }
}