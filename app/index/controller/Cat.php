<?php
// +----------------------------------------------------------------------
// | Cat.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-16
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\admin\model\News;
use think\Request;
use app\admin\model\Cat as CatModel;

class Cat extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('active', 3);
    }

    public function index()
    {
        $model = new CatModel();
        $catList = $model::cat_all();
        $this->assign('catList', $catList);
        return $this->fetch();
    }

    /**
     * 分类文章列表
     * Author: wjf <1937832819@qq.com>
     * @return mixed
     */
    public function info()
    {
        $catId = input('cat_id/d');
        $info = CatModel::cat_info($catId);
        if (empty($info)) {
            $this->error('该分类不存在');
        }
        $catList = CatModel::cat_all();
        $newsmodel = new News();
        $where['news_open'] = 1;
        $where['cat_id'] = $catId;
        $order['news_hits'] = 'desc';
        $newsList = $newsmodel::getWhereNews($where, 3, $order);
        $page = $newsList->render();
        $this->assign('newsList', $newsList);
        $this->assign('catList', $catList);
        $this->assign('page', $page);
        $this->assign('catInfo', $info);
        return $this->fetch();
    }
}