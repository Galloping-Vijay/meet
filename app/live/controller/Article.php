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
        $newsList = $news::getWhereNews([],20,['news_time'=>'desc']);
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

    /**
     * Author: wjf <1937832819@qq.com>
     * @return mixed
     */
    public function search()
    {
        $k = input("keyword");
        $page = input("post.page");
        $pagesize = 5;
        if (empty($k)) {
            $this->error('关键字为空');
        }
        if (request()->isAjax()) {
            if (empty($page)) {
                $this->success('操作成功', url('live/article/search', array('keyword' => $k)));
            } else {
                $lists = get_news('order:news_time desc', 1, $pagesize, 'keyword', $k, array(), $page);
                //替换成带ajax的class
                $page_html = $lists['page'];
                $page_html = preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)", "<a href='javascript:ajax_page($1);'>$2</a>", $page_html);
                $this->assign('page_html', $page_html);
                $this->assign('lists', $lists);
                $this->assign("keyword", $k);
                return $this->fetch(":ajax_list");
            }
        } else {
            $lists = get_news('order:news_time desc', 1, $pagesize, 'keyword', $k);
            //替换成带ajax的class
            $page_html = $lists['page'];
            $page_html = preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)", "<a href='javascript:ajax_page($1);'>$2</a>", $page_html);
            $this->assign('page_html', $page_html);
            $this->assign('lists', $lists);
            $this->assign("keyword", $k);
            return $this->fetch(':search');
        }
    }
}