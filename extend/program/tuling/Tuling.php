<?php
// +----------------------------------------------------------------------
// | Tuling. 图灵机器人接口
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/25
// +----------------------------------------------------------------------

namespace program\tuling;

use app\common\lib\Curl;

class Tuling
{
    //图灵机器人API
    protected $api_key = 'a3b071de720749c9a83ba7cdea8f5f18';
    protected $app_url = 'http://openapi.tuling123.com/openapi/api/v2';
    //官方文档
    //http://doc.tuling123.com/openapi2/263611
    protected $param = [];
    /**
     * 错误信息
     * @var string
     */
    protected $error = '';

    /**
     * 设置参数
     * Author: vijay <1937832819@qq.com>
     * @param int $reqType
     * @param null $str
     * @param int $uid
     * @return $this|bool
     */
    public function param($str = null, $reqType = 0, $uid = 12345)
    {
        if (is_null($str)) {
            $this->setError('参数错误');
            return false;
        }
        if ($reqType == 0) {
            $perception = [
                'inputText' => [
                    'text' => $str
                ]];
        } elseif ($reqType == 1) {
            $perception = [
                'inputImage' => [
                    'url' => $str
                ]
            ];
        } elseif ($reqType == 2) {
            $perception = [
                'inputMedia' => [
                    'url' => $str
                ]
            ];
        } else {
            $this->setError('参数错误');
            return false;
        }
        $this->param = [
            'reqType' => $reqType,
            'perception' => $perception,
            'userInfo' => [
                'userId' => $uid,
                'apiKey' => $this->api_key
            ]
        ];
        return $this;
    }

    /**
     * 获取最后一次上传错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置错误
     * @param $error
     * @return $this
     */
    protected function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * 回复内容
     * @return string
     */
    public function reply()
    {
        $curl = new Curl();
        $data = $curl->post($this->app_url, json_encode($this->param));
        if ($data) {
            return json_decode($data, true);
        } else {
            $this->setError('获取失败');
            return '亲，不明白您说什么';
        }
    }

    public function text($content = '')
    {
        $data = $this->param($content)->reply();

        if (!isset($data['results'])) {
            $text = '亲，不明白您说什么';
        } else {
            $text = $data['results'][0]['values']['text'];
        }
        return $text;
    }
}