<?php
// +----------------------------------------------------------------------
// | Article.文章页
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017-10-08
// +----------------------------------------------------------------------

namespace app\live\controller;

use app\admin\model\News;
use think\Request;

class Article extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->assign('active', 22);
    }

    //文章集
    public function index()
    {
        $news = new News();
        $newsList = $news::getWhereNews([],5,['news_time'=>'desc']);
        $page = $newsList->render();
        $this->assign('newsList', $newsList);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function info()
    {
        $n_id = input('id');
        $news = new News();
        $info = $news->getNews($n_id);
        if (empty($info)) {
            $this->error('文章不存在或已被删除', 'index');
        }
        $this->assign('info', $info);
        return $this->fetch();
    }
}