<?php
// +----------------------------------------------------------------------
// | MemberList.会员表
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/18
// +----------------------------------------------------------------------

namespace app\home\model;

class MemberList extends Base
{
    //主键
    protected $pk = 'member_list_id';
    //自动完成
    protected $auto = [];
    //插入时自动完成
    protected $insert = ['member_list_addtime' => NOW_TIME];
    //更新时自动完成
    protected $update = [];
    //类型转换
    protected $type = [
        'member_list_groupid' => 'integer',
        'member_list_province' => 'integer',
        'member_list_city' => 'integer',
        'member_list_town' => 'integer',
        'news_pic_type' => 'integer',
        'member_list_sex' => 'integer',
        'member_list_open' => 'integer',
        'member_list_addtime' => 'integer',
        'user_status' => 'integer',
    ];
}