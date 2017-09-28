<?php
// +----------------------------------------------------------------------
// | FacePlusPlus.人脸识别接口
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/26
// +----------------------------------------------------------------------

namespace program\face;

class FacePlusPlus
{
    private $api_server_url;
    private $auth_params;

    public function __construct()
    {
        $this->api_server_url = "http://apicn.faceplusplus.com/";
        $this->auth_params = array();
        $this->auth_params['api_key'] = "sz_1uXJVE5Fjay1k5IBcDfpbUTtIT7T2";
        $this->auth_params['api_secret'] = "cuOi6hNePriq1jcpDofZDXZFpndJt5Vq";
    }

    //人脸检测
    public function face_detect($urls = null)
    {
        return $this->call("detection/detect", array("url" => $urls));
    }

    //人脸比较
    public function recognition_compare($face_id1, $face_id2)
    {
        return $this->call("recognition/compare", array("face_id1" => $face_id1, "face_id2" => $face_id2));
    }

    protected function call($method, $params = array())
    {
        $url = $this->api_server_url . "$method?" . http_build_query(array_merge($this->auth_params, $params));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data);
        return $result;
    }
}