<?php
// +----------------------------------------------------------------------
// | Classbook.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/1/18
// +----------------------------------------------------------------------

namespace app\web\controller;

use app\web\lib\Base;
use app\web\model\ClassBooks;

class Classbook extends Base
{
    /**
     * 列表页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * ajax请求数据
     * @return \think\response\Json
     */
    public function ajaxList()
    {
        $list = ClassBooks::where('status', 1)->paginate(3);
        $data = json_encode($list);
        $data = json_decode($data, true);

        if (empty($data['data']) || !$this->request->isAjax()) {
            $data = [
                'code' => 0,
                'msg' => '非ajax请求',
                'data' => []
            ];
            return json($data);
        }
        $data['code'] = 1;
        $data['msg'] = '请求成功';
        return json($data);
    }

    public function region()
    {
        return $this->fetch();
    }
}