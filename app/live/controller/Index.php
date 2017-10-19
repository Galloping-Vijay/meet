<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/29
// +----------------------------------------------------------------------

namespace app\live\controller;

use app\admin\model\News;
use think\Request;

class Index extends Base
{
    /**
     * home
     * Author: wjf <1937832819@qq.com>
     * @return mixed
     */
    public function index()
    {
        $model = new News();
        $where['news_flag'] = ['like','%a%'];
        $order = ['n_id'=> 'desc'];
        //推荐
        $data = $model::getWhereNews($where,3,$order,false);
        $this->assign('active', 19);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 推荐
     * Author: wjf <1937832819@qq.com>
     */
    public function hot()
    {
        $this->assign('active', 20);
        return $this->fetch();
    }
}