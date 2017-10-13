<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 文章模型
 * @package app\admin\model
 */
class News extends Model
{
    protected $insert = ['news_hits' => 200];

    /**
     * Author: wjf <1937832819@qq.com>
     */
    protected static function init()
    {
        //更新后
        self::event(
            'after_update', function ($object) {
            cache('news/' . $object->n_id, null);
        }
        );
        //删除后
        self::event(
            'after_delete', function ($object) {
            //删除缓存
            cache('news/' . $object->n_id, null);
        }
        );
    }

    public function user()
    {
        return $this->belongsTo('MemberList', 'member_list_id');
    }

    public function menu()
    {
        return $this->belongsTo('Menu', 'id');
    }

    /**
     * 文章关联分类
     * Author: wjf <1937832819@qq.com>
     * @return \think\model\relation\BelongsTo
     */
    public function cat()
    {
        return $this->belongsTo('Cat', 'cat_id', 'cat_id');
    }

    /**
     * 获取文章内容
     * @param $id
     * @param bool $iscache
     * @return array|false|mixed|\PDOStatement|string|Model
     */
    public function getNews($id, $iscache = true)
    {
        $data = cache('news/' . $id);
        if ($iscache == false || empty($data)) {
            $data = self::where('n_id', $id)->where('news_back', 0)->where('news_open', 1)->find();
            cache('news/' . $id, $data);
        }
        return $data;
    }
}
