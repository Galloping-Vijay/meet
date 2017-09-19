<?php
// +----------------------------------------------------------------------
// | News.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/18
// +----------------------------------------------------------------------

namespace app\home\model;

use think\Db;

class News extends Base
{
    //主键
    protected $pk = 'n_id';
    //自动完成
    protected $auto = [];
    //插入时自动完成
    protected $insert = [
        'news_hits' => 200
    ];
    //更新时自动完成
    protected $update = [];
    //类型转换
    protected $type = [
        'news_columnid' => 'integer',
        'news_titleshort' => 'integer',
        'news_hits' => 'integer',
        'news_like' => 'integer',
        'news_pic_type' => 'integer',
        'news_modified' => 'integer',
        'news_open' => 'integer',
        'news_lvtype' => 'integer',
        'comment_status' => 'integer',
        'comment_count' => 'integer',
        'news_auto' => 'integer',
    ];

    /**
     * 初始化处理
     * @access protected
     * @return void
     */
    protected static function init()
    {

    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('MemberList', 'news_auto', 'member_list_id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo('Menu', 'news_columnid', 'id');
    }

    /**
     * 获取文章审核状态
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getNewsOpenNameAttr($value, $data)
    {
        $news_open = [0 => '×', 1 => '√', 2 => '?'];
        return $news_open[$data['news_open']];
    }
}