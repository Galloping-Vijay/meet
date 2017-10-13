<?php
// +----------------------------------------------------------------------
// | Article. 前台文章操作方法
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/18
// +----------------------------------------------------------------------

namespace app\home\controller;

use app\admin\model\Cat;
use think\Db;
use \app\home\model\News;
use think\Validate;

class Article extends Base
{
    public function index()
    {
        $art = News::get(41);
        echo $art->menu->menu_name;
    }

    public function newsAdd()
    {
        if (empty(session('user'))) {
            $this->error('请先登录!', url('/'));
        }
        $news_columnid = input('news_columnid', 0, 'intval');
        $menu_text = user_news($this->lang);
        $this->assign('menu', $menu_text);
        $source = Db::name('source')->select();
        $newsCat = Cat::cat_all();//分类
        $this->assign('newsCat', $newsCat);
        $this->assign('news_columnid', $news_columnid);//栏目id
        $this->assign('source', $source);

        return $this->fetch('article/news_add');
    }

    /**
     * 添加操作
     */
    public function news_runadd()
    {
        if (empty(session('user'))) {
            $this->error('请先登录!', url('/'));
        }
        if (!request()->isAjax()) {
            $this->error('提交方式不正确', url('/newsAdd'));
        }
        //上传图片部分
        $file = request()->file('pic_one');
        $files = request()->file('pic_all');
        $imgInfo = $this->fileUpload($file, $files, '/newsAdd');
        $sl_data = array(
            'news_title' => input('news_title', '', 'trim'),
            'news_columnid' => input('news_columnid', 0, 'intval'),
            'news_key' => input('news_key', '', 'trim'),
            'news_tag' => input('news_tag', '', 'trim'),
            'news_source' => input('news_source', '', 'trim'),
            'news_pic_type' => input('news_pic_type', 0, 'intval'),
            'news_pic_content' => input('news_pic_content', '', 'trim'),
            'news_pic_allurl' => $imgInfo['picall_url'],//多图路径
            'news_img' => $imgInfo['img_one'],//封面图片路径
            'news_open' => input('news_open', 0),
            'news_scontent' => input('news_scontent', '', 'trim'),
            'news_content' => htmlspecialchars_decode(input('news_content')),
            'news_auto' => session('user.member_list_id'),
            'news_time' => time(),
            'cat_id' => input('cat_id'),
        );
        //验证开始
        $validate = new Validate(
            [
                ['news_title|文章标题', ['require']],
                ['news_columnid|所属栏目', ['require', 'egt:16']],
                ['news_open|审核状态', ['eq:0']],
                ['news_content|文章内容', ['require']],
                ['news_auto|用户信息', ['require', 'integer']],
            ]
        );
        if (true !== $validate->check($sl_data)) {
            return json(['code' => 0, 'msg' => $validate->getError()]);
        }
        //根据栏目id,获取语言
        $news_l = Db::name('menu')->where('id', input('news_columnid'))->value('menu_l');
        $sl_data['news_l'] = $news_l;
        //附加字段
        $showtime = input('showdate', '');
        $news_extra['showdate'] = ($showtime == '') ? time() : strtotime($showtime);
        $sl_data['news_extra'] = json_encode($news_extra);

        News::create($sl_data);
        $continue = input('continue', 0, 'intval');
        if ($continue) {
            $this->success('文章添加成功,待后台审核', url('/newsAdd', ['news_columnid' => input('news_columnid')]));
        } else {
            $this->success('文章添加成功,待后台审核,返回列表页', url('/newsAdd'));
        }
    }

    /**
     * 编辑显示
     */
    public function newsEdit()
    {
        if (empty(session('user'))) {
            $this->error('请先登录!', url('/'));
        }
        $n_id = input('n_id');
        if (empty($n_id)) {
            $this->error('参数错误');
        }
        $news_list = News::get($n_id);
        $news_extra = json_decode($news_list['news_extra'], true);
        $news_extra['showdate'] = ($news_extra['showdate'] == '') ? $news_list['news_time'] : $news_extra['showdate'];
        //多图字符串转换成数组
        $text = $news_list['news_pic_allurl'];
        $pic_list = array_filter(explode(",", $text));
        $this->assign('pic_list', $pic_list);
        //栏目数据
        $menu_text = user_news($this->lang);
        $newsCat = Cat::cat_all();//分类
        $this->assign('newsCat', $newsCat);
        $this->assign('menu', $menu_text);
        $source = Db::name('source')->select();//来源
        $this->assign('source', $source);
        $this->assign('news_extra', $news_extra);
        $this->assign('news_list', $news_list);
        return $this->fetch('article/news_edit');
    }

    /**
     * 编辑操作
     */
    public function news_runedit()
    {
        if (empty(session('user'))) {
            $this->error('请先登录!', url('/'));
        }
        if (!request()->isAjax()) {
            $this->error('提交方式不正确');
        }
        //获取图片上传后路径
        $pic_oldlist = input('pic_oldlist');//老多图字符串
        $file = request()->file('pic_one');
        $files = request()->file('pic_all');
        $imgInfo = $this->fileUpload($file, $files, '/newsEdit');
        $img_one = $imgInfo['img_one'];
        $picall_url = $imgInfo['picall_url'];

        $sl_data = array(
            'n_id' => input('n_id'),
            'news_title' => input('news_title', '', 'trim'),
            'news_columnid' => input('news_columnid', 0, 'intval'),
            'news_key' => input('news_key', '', 'trim'),
            'news_tag' => input('news_tag', '', 'trim'),
            'news_source' => input('news_source', '', 'trim'),
            'news_pic_type' => input('news_pic_type', 0, 'intval'),
            'news_pic_content' => input('news_pic_content', '', 'trim'),
            'news_pic_allurl' => $imgInfo['picall_url'],//多图路径
            'news_img' => $imgInfo['img_one'],//封面图片路径
            'news_open' => input('news_open', 0),
            'news_scontent' => input('news_scontent', '', 'trim'),
            'news_content' => htmlspecialchars_decode(input('news_content')),
            'news_auto' => session('user.member_list_id'),
            'cat_id' => input('cat_id'),
        );
        //验证开始
        $validate = new Validate(
            [
                ['news_title|文章标题', ['require']],
                ['news_columnid|所属栏目', ['require', 'egt:16']],
                ['news_open|审核状态', ['eq:0']],
                ['news_content|文章内容', ['require']],
                ['news_auto|用户信息', ['require', 'integer']],
            ]
        );
        if (true !== $validate->check($sl_data)) {
            return json(['code' => 0, 'msg' => $validate->getError()]);
        }
        //图片字段处理
        if (!empty($img_one)) {
            $sl_data['news_img'] = $img_one;
        }
        $sl_data['news_pic_allurl'] = $pic_oldlist . $picall_url;
        //根据栏目id,获取语言
        $news_l = Db::name('menu')->where('id', input('news_columnid'))->value('menu_l');
        $sl_data['news_l'] = $news_l;
        //附加字段
        $showtime = input('showdate', '');
        $news_extra['showdate'] = ($showtime == '') ? time() : strtotime($showtime);
        $sl_data['news_extra'] = json_encode($news_extra);
        $rst = News::update($sl_data);
        if ($rst !== false) {
            $this->success('文章修改成功,返回列表页');
        } else {
            $this->error('文章修改失败');
        }
    }

    /**
     * 作者删除文章
     */
    public function news_del()
    {
        if (empty(session('user'))) {
            $this->error('您没有权限删除文章!', url('/'));
        }
        $id = input("id", 0, "intval");
        $result = News::destroy($id);
        if ($result) {
            $this->success('删除成功!');
        } else {
            $this->error('删除失败');
        }
    }
}