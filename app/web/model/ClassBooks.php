<?php
// +----------------------------------------------------------------------
// | ClassBooks.通讯录
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/1/17
// +----------------------------------------------------------------------

namespace app\web\model;

use app\common\model\BaseModel;
use think\Db;

class ClassBooks extends BaseModel
{
    protected $auto = [
        'update_time' => NOW_TIME
    ];
    //插入时自动完成
    protected $insert = [
        'create_time' => NOW_TIME,
    ];
    //类型转换
    protected $type = [
        'sex' => 'integer',
        'status' => 'integer',
        'update_time' => 'integer',
        'create_time' => 'integer',
    ];

    public function getStatusTextAttr($value,$data)
    {
        $status = [0=>'禁用',1=>'正常'];
        return $status[$data['status']];
    }
}