<?php
// +----------------------------------------------------------------------
// | Index.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-16
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\admin\model\News;

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
        $this->assign('active',1);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 推荐
     * Author: wjf <1937832819@qq.com>
     */
    public function hot()
    {
        $this->assign('active',2);
        return $this->fetch();
    }

    /**
     * 分类
     * @return mixed
     */
    public function cat()
    {
        $this->assign('active',2);
        return $this->fetch();
    }
}