<?php
// +----------------------------------------------------------------------
// | Cat.文章分类
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/12
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;

class Cat extends BaseModel
{
    //主键
    protected $pk = 'cat_id';
    //自动完成
    protected $auto = [
        'create_time' => NOW_TIME,
    ];
    //类型转换
    protected $type = [
        'cid' => 'integer',
        'status' => 'integer',
        'cat_order' => 'integer'
    ];


    /**
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getStatusNameAttr($value, $data)
    {
        $status = [0 => '禁用', 1 => '正常'];
        return $status[$data['status']];
    }


    /**
     * 增加
     * @param $data
     * @return bool
     */
    public function plus($data)
    {
        if (empty($data)) {
            $this->error = '数据不能为空!';
            return false;
        }
        if (false === $this->save($data)) {
            return false;
        }
        return $this->data['cat_id'];
    }

    /**
     * 修改
     * @param $data
     * @return bool
     */
    public function edit($data)
    {
        if (empty($data)) {
            $this->error = '数据不能为空!';
            return false;
        }
        if (false === $this->save($data)) {
            return false;
        }
        return true;
    }

    /**
     * 删除
     * @return bool
     */
    public function rm()
    {
        self::startTrans();
        //子栏目检测
        $count = News::where(['cat_id' => $this->cat_id])->count('cat_id');
        if ($count > 0) {
            $this->error = '该栏目下存在文章,不能删除';
            return false;
        }
        if (false !== $this->delete()) {
            self::commit();
            return true;
        }
        self::rollback();
        return false;
    }
}