<?php
// +----------------------------------------------------------------------
// | Module.模块类
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/28
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;

class Module extends BaseModel
{
    //主键
    protected $pk = 'id';
    //自动完成
    protected $auto = [
        'update_time' => NOW_TIME
    ];
    //类型转换
    protected $type = [
        'module_status' => 'integer',
        'update_time' => 'integer',
    ];

    /**
     * 获取当前应用模块
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getModuleStatusNameAttr($value, $data)
    {
        $module_status = [0 => '未启用', 1 => '启用'];
        return $module_status[$data['module_status']];
    }

    /**
     * 添加编辑操作
     * @param $data
     * @return bool|mixed
     */
    public function plus($data)
    {
        if (empty($data)) {
            $this->error = '数据不能为空';
            return false;
        }
        if ($this->save($data) === false) {
            return false;
        }
        return $this->id;
    }

    /**
     * 删除
     * @return bool
     */
    public function rm()
    {
        return $this->delete() !== false ? true : false;
    }
}