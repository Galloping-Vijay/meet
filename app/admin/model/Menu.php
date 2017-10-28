<?php
// +----------------------------------------------------------------------
// | Menu.菜单
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-09
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 前台菜单模型
 * @package app\admin\model
 */
class Menu extends Model
{
    public function news()
    {
        return $this->hasMany('News', 'news_columnid')->bind('menu_name');
    }

    /**
     * 关联模块
     * Author: wjf <1937832819@qq.com>
     * @return \think\model\relation\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo('Module', 'menu_moduleid', 'id');
    }
}
