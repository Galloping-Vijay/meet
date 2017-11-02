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
        $where['news_flag'] = ['like', '%a%'];
        $order = ['n_id' => 'desc'];
        //推荐
        $data = $model::getWhereNews($where, 3, $order, false);
        $this->assign('active', 1);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 推荐
     * Author: wjf <1937832819@qq.com>
     */
    public function hot()
    {
        $news = new News();
        $phpWhere['cat_id'] = ['eq', 1];
        $phpWhere['news_open'] = 1;
        $phpOrder = ['news_hits' => 'desc', 'news_like' => 'desc', 'n_id' => 'desc'];
        $phpCode = $news::getWhereNews($phpWhere, 3, $phpOrder, false);
        $this->assign('active', 2);
        $this->assign('phpCode', $phpCode);
        return $this->fetch();
    }

    public function about(){
        throw new \think\exception\HttpException(404, '页面不存在');
    }

}