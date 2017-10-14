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

    protected static function init()
    {
        //写入后
        self::event(
            'after_write', function ($object) {
            self::cat_all(false);
        }
        );
        //删除后
        self::event(
            'after_delete', function ($object) {
            cache('cat_info/cat_id' . $object->cat_id, null);
            self::cat_all(false);
        }
        );
    }

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
        return $this->delete() !== false ? true : false;
    }

    /**
     * 获取指定的分类
     * Author: wjf <1937832819@qq.com>
     * @param int $cat_id 分类id
     * @param bool $isCache 是否缓存
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     */
    public static function cat_info($cat_id, $isCache = true)
    {
        $cat_info = cache('cat_info/cat_id' . $cat_id);
        if (empty($cat_info) || $isCache == false) {
            $cat_info = self::where('cat_id', $cat_id)->where('status', 1)->find();
            cache('cat_info/cat_id' . $cat_id, serialize($cat_info));
            return $cat_info;
        }
        return unserialize($cat_info);
    }

    /**
     * 获取所有分类
     * Author: wjf <1937832819@qq.com>
     * @param bool $isCache
     * @return array|mixed
     */
    public static function cat_all($isCache = true)
    {
        $list = cache('article_cat');
        if (empty($list) || $isCache == false) {
            $list = self::where('status', 1)->column('cat_id,cat_name,cat_intro,cat_img,cid,cat_order', 'cat_id');
            cache('article_cat', serialize($list));
            return $list;
        }
        return unserialize($list);

    }
}