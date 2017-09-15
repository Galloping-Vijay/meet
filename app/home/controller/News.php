<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\home\controller;

use think\Db;

class News extends Base
{
    //文章内页
    public function index()
    {
        $page = input('page', 1);
        $news = Db::name('news')->alias("a")->join(config('database.prefix') . 'member_list b', 'a.news_auto =b.member_list_id')->where(array('n_id' => input('id'), 'news_open' => 1, 'news_back' => 0))->find();
        if (empty($news)) {
            $this->error(lang('operation not valid'));
        }
        $news_data = explode('_ueditor_page_break_tag_', $news['news_content']);
        $total = count($news_data);
        $news['content'] = $news_data[$page - 1];
        $news['page'] = '';
        if ($total > 1) {
            $prevbtn = ($page <= 1) ? '<li class="disabled"><span>&laquo;</span></li>' : '<li><a href="' . url('home/News/index', ['id' => input('id'), 'page' => ($page - 1)]) . '">&laquo;</a></li>';
            $nextbtn = ($page >= $total) ? '<li class="disabled"><span>&raquo;</span></li>' : '<li><a href="' . url('home/News/index', ['id' => input('id'), 'page' => ($page + 1)]) . '">&raquo;</a></li>';
            $link = $this->getLinks($page, $total, input('id'));
            $news['page'] = sprintf(
                '<ul class="pagination">%s %s %s</ul>',
                $prevbtn,
                $link,
                $nextbtn
            );
        }
        $menu = Db::name('menu')->find($news['news_columnid']);
        if (empty($menu)) {
            $this->error(lang('operation not valid'));
        }
        $tplname = $menu['menu_newstpl'];
        $tplname = $tplname ? $tplname : 'news';
        //自行根据网站需要考虑，是否需要判断
        $can_do = check_user_action('news' . input('id'), 0, false, 60);
        if ($can_do) {
            //更新点击数
            Db::name('news')->update(array("n_id" => input('id'), "news_hits" => array("exp", "news_hits+1")));
            $news['news_hits'] += 1;
        }
        $next = Db::name('news')->where(array("news_time" => array("egt", $news['news_time']), "n_id" => array('neq', input('id')), "news_open" => 1, 'news_back' => 0, 'news_columnid' => $news['news_columnid']))->order("news_time asc")->find();
        $prev = Db::name('news')->where(array("news_time" => array("elt", $news['news_time']), "n_id" => array('neq', input('id')), "news_open" => 1, 'news_back' => 0, 'news_columnid' => $news['news_columnid']))->order("news_time desc")->find();
        $t_open = config('comment.t_open');
        if ($t_open) {
            //获取评论数据
            $comments = Db::name('comments')->alias("a")->join(config('database.prefix') . 'member_list b', 'a.uid =b.member_list_id')->where(array("a.t_name" => 'news', "a.t_id" => input('id'), "a.c_status" => 1))->order("a.createtime ASC")->select();
            $count = count($comments);
            $new_comments = array();
            $parent_comments = array();
            if (!empty($comments)) {
                foreach ($comments as $m) {
                    if ($m['parentid'] == 0) {
                        $new_comments[$m['c_id']] = $m;
                    } else {
                        $path = explode("-", $m['path']);
                        $new_comments[$path[1]]['children'][] = $m;
                    }
                    $parent_comments[$m['c_id']] = $m;
                }
            }
            $this->assign("count", $count);
            $this->assign("comments", $new_comments);
            $this->assign("parent_comments", $parent_comments);
        }
        $this->assign("t_open", $t_open);
        $this->assign($news);
        $this->assign('menu', $menu);
        $this->assign("next", $next);
        $this->assign("prev", $prev);
        return $this->view->fetch(":$tplname");
    }

    //分页中间部分链接
    protected function getLinks($page, $total, $id)
    {
        $block = [
            'first' => null,
            'slider' => null,
            'last' => null
        ];

        $side = 3;
        $window = $side * 2;

        if ($total < $window + 6) {
            $block['first'] = $this->getUrlRange(1, $total, $id);
        } elseif ($page <= $window) {
            $block['first'] = $this->getUrlRange(1, $window + 2, $id);
            $block['last'] = $this->getUrlRange($total - 1, $total, $id);
        } elseif ($page > ($total - $window)) {
            $block['first'] = $this->getUrlRange(1, 2, $id);
            $block['last'] = $this->getUrlRange($total - ($window + 2), $total, $id);
        } else {
            $block['first'] = $this->getUrlRange(1, 2, $id);
            $block['slider'] = $this->getUrlRange($page - $side, $page + $side, $id);
            $block['last'] = $this->getUrlRange($total - 1, $total, $id);
        }
        $html = '';
        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first'], $page);
        }
        if (is_array($block['slider'])) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= $this->getUrlLinks($block['slider'], $page);
        }
        if (is_array($block['last'])) {
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= $this->getUrlLinks($block['last'], $page);
        }
        return $html;
    }

    protected function getUrlLinks(array $urls, $page)
    {
        $html = '';
        foreach ($urls as $text => $url) {
            $html .= ($text == $page) ? '<li class="active"><span>' . $text . '</span></li>' : '<li><a href="' . htmlentities($url) . '">' . $text . '</a></li>';
        }
        return $html;
    }

    protected function getUrlRange($start, $end, $id)
    {
        $urls = [];
        for ($page = $start; $page <= $end; $page++) {
            $urls[$page] = url('home/News/index', ['id' => $id, 'page' => $page]);
        }
        return $urls;
    }

    public function dolike()
    {
        $this->check_login();
        $id = input('id', 0, 'intval');
        $can_like = check_user_action('news' . $id, 1);
        if ($can_like) {
            Db::name("news")->where('n_id', $id)->setInc('news_like');;
            $this->success(lang('dolike success'));
        } else {
            $this->error(lang('dolike already'));
        }
    }

    public function dofavorite()
    {
        $this->check_login();
        $key = input('key');
        if ($key) {
            $id = input('id');
            if ($key == encrypt_password('news-' . $id, 'news')) {
                $uid = session('hid');
                $favorites_model = Db::name("favorites");
                $find_favorite = $favorites_model->where(array('t_name' => 'news', 't_id' => $id, 'uid' => $uid))->find();
                if ($find_favorite) {
                    $this->error(lang('favorited already'));
                } else {
                    $data = array(
                        'uid' => $uid,
                        't_name' => 'news',
                        't_id' => $id,
                        'createtime' => time(),
                    );
                    $result = $favorites_model->insert($data);
                    if ($result) {
                        $this->success(lang('favorite success'));
                    } else {
                        $this->error(lang('favorite failed'));
                    }
                }
            } else {
                $this->error(lang('favorite failed'));
            }
        } else {
            $this->error(lang('favorite failed'));
        }
    }

    public function newsAdd()
    {
        $news_columnid = input('news_columnid', 0, 'intval');
        $menu_text = Db::name('menu')->where('parentid', 16)->cache(3600)->select();
        $this->assign('menu', $menu_text);
        $source = Db::name('source')->select();
        $this->assign('news_columnid', $news_columnid);
        $this->assign('source', $source);
        return $this->fetch('news/news_add');
    }
    /**
     * 添加操作
     */
    public function news_runadd()
    {
        if (!request()->isAjax()){
            $this->error('提交方式不正确',url('home/News/newsAdd'));
        }
        //上传图片部分
        $img_one='';
        $picall_url='';
        $file = request()->file('pic_one');
        $files = request()->file('pic_all');
        if($file || $files) {
            if(config('storage.storage_open')){
                //七牛
                $upload = \Qiniu::instance();
                $info = $upload->upload();
                $error = $upload->getError();
                if ($info) {
                    if($file && $files){
                        //有单图、多图
                        if(!empty($info['pic_one'])) $img_one= config('storage.domain').$info['pic_one'][0]['key'];
                        if(!empty($info['pic_all'])) {
                            foreach ($info['pic_all'] as $file) {
                                $img_url=config('storage.domain').$file['key'];
                                $picall_url = $img_url . ',' . $picall_url;
                            }
                        }
                    }elseif($file){
                        //单图
                        $img_one= config('storage.domain').$info[0]['key'];
                    }else{
                        //多图
                        foreach ($info as $file) {
                            $img_url=config('storage.domain').$file['key'];
                            $picall_url = $img_url . ',' . $picall_url;
                        }
                    }
                }else{
                    $this->error($error,url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                }
            }else{
                $validate = config('upload_validate');
                //单图
                if ($file) {
                    $info = $file[0]->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                    if ($info) {
                        $img_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
                        //写入数据库
                        $data['uptime'] = time();
                        $data['filesize'] = $info->getSize();
                        $data['path'] = $img_url;
                        Db::name('plug_files')->insert($data);
                        $img_one = $img_url;
                    } else {
                        $this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                    }
                }
                //多图
                if ($files) {
                    foreach ($files as $file) {
                        $info = $file->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                        if ($info) {
                            $img_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
                            //写入数据库
                            $data['uptime'] = time();
                            $data['filesize'] = $info->getSize();
                            $data['path'] = $img_url;
                            Db::name('plug_files')->insert($data);
                            $picall_url = $img_url . ',' . $picall_url;
                        } else {
                            $this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                        }
                    }
                }
            }
        }

        $sl_data=array(
            'news_title'=>input('news_title'),
            'news_columnid'=>input('news_columnid'),
            'news_key'=>input('news_key',''),
            'news_tag'=>input('news_tag',''),
            'news_source'=>input('news_source',''),
            'news_pic_type'=>input('news_pic_type'),
            'news_pic_content'=>input('news_pic_content',''),
            'news_pic_allurl'=>$picall_url,//多图路径
            'news_img'=>$img_one,//封面图片路径
            'news_open'=>input('news_open',0),
            'news_scontent'=>input('news_scontent',''),
            'news_content'=>htmlspecialchars_decode(input('news_content')),
            'news_auto'=>session('user.member_list_id'),
            'news_time'=>time(),
        );
        //根据栏目id,获取语言
        $news_l=Db::name('menu')->where('id',input('news_columnid'))->value('menu_l');
        $sl_data['news_l']=$news_l;
        //附加字段
        $showtime=input('showdate','');
        $news_extra['showdate']=($showtime=='')?time():strtotime($showtime);
        $sl_data['news_extra']=json_encode($news_extra);
        //改到这里 下面的模型要建一个在home
        \app\admin\model\News::create($sl_data);
        $continue=input('continue',0,'intval');
        if($continue){
            $this->success('文章添加成功,继续发布',url('admin/News/news_add',['news_columnid'=>input('news_columnid')]));
        }else{
            $this->success('文章添加成功,返回列表页',url('admin/News/news_list'));
        }
    }
    /**
     * 编辑显示
     */
    public function news_edit()
    {
        $n_id = input('n_id');
        if (empty($n_id)){
            $this->error('参数错误',url('admin/News/news_list'));
        }
        $news_list=NewsModel::get($n_id);
        $news_extra=json_decode($news_list['news_extra'],true);
        $news_extra['showdate']=($news_extra['showdate']=='')?$news_list['news_time']:$news_extra['showdate'];
        //多图字符串转换成数组
        $text = $news_list['news_pic_allurl'];
        $pic_list = array_filter(explode(",", $text));
        $this->assign('pic_list',$pic_list);
        //栏目数据
        $menu_text=menu_text($this->lang);
        $this->assign('menu',$menu_text);
        $diyflag=Db::name('diyflag')->select();
        $source=Db::name('source')->select();//来源
        $this->assign('source',$source);
        $this->assign('news_extra',$news_extra);
        $this->assign('diyflag',$diyflag);
        $this->assign('news_list',$news_list);
        return $this->fetch();
    }
    /**
     * 编辑操作
     */
    public function news_runedit()
    {
        if (!request()->isAjax()){
            $this->error('提交方式不正确',url('admin/News/news_list'));
        }
        //获取图片上传后路径
        $pic_oldlist=input('pic_oldlist');//老多图字符串
        $img_one='';
        $picall_url='';
        $file = request()->file('pic_one');
        $files = request()->file('pic_all');
        //上传处理
        if($file || $files) {
            if(config('storage.storage_open')){
                //七牛
                $upload = \Qiniu::instance();
                $info = $upload->upload();
                $error = $upload->getError();
                if ($info) {
                    if($file && $files){
                        //有单图、多图
                        if(!empty($info['pic_one'])) $img_one= config('storage.domain').$info['pic_one'][0]['key'];
                        if(!empty($info['pic_all'])) {
                            foreach ($info['pic_all'] as $file) {
                                $img_url=config('storage.domain').$file['key'];
                                $picall_url = $img_url . ',' . $picall_url;
                            }
                        }
                    }elseif($file){
                        //单图
                        $img_one= config('storage.domain').$info[0]['key'];
                    }else{
                        //多图
                        foreach ($info as $file) {
                            $img_url=config('storage.domain').$file['key'];
                            $picall_url = $img_url . ',' . $picall_url;
                        }
                    }
                }else{
                    $this->error($error,url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                }
            }else{
                $validate = config('upload_validate');
                //单图
                if (!empty($file)) {
                    $info = $file[0]->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                    if ($info) {
                        $img_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
                        //写入数据库
                        $data['uptime'] = time();
                        $data['filesize'] = $info->getSize();
                        $data['path'] = $img_url;
                        Db::name('plug_files')->insert($data);
                        $img_one = $img_url;
                    } else {
                        $this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                    }
                }
                //多图
                if (!empty($files)) {
                    foreach ($files as $file) {
                        $info = $file->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                        if ($info) {
                            $img_url = config('upload_path'). '/' . date('Y-m-d') . '/' . $info->getFilename();
                            //写入数据库
                            $data['uptime'] = time();
                            $data['filesize'] = $info->getSize();
                            $data['path'] = $img_url;
                            Db::name('plug_files')->insert($data);
                            $picall_url = $img_url . ',' . $picall_url;
                        } else {
                            $this->error($file->getError(), url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                        }
                    }
                }
            }
        }
        //获取文章属性
        $news_flag=input('post.news_flag/a');
        $flag=array();
        if(!empty($news_flag)){
            foreach ($news_flag as $v){
                $flag[]=$v;
            }
        }
        $flagdata=implode(',',$flag);
        $sl_data=array(
            'n_id'=>input('n_id'),
            'news_title'=>input('news_title'),
            'news_titleshort'=>input('news_titleshort',''),
            'news_columnid'=>input('news_columnid'),
            'news_flag'=>$flagdata,
            'news_zaddress'=>input('news_zaddress',''),
            'news_key'=>input('news_key',''),
            'news_tag'=>input('news_tag',''),
            'news_source'=>input('news_source',''),
            'news_pic_type'=>input('news_pic_type'),
            'news_pic_content'=>input('news_pic_content',''),
            'news_open'=>input('news_open',0),
            'news_scontent'=>input('news_scontent',''),
            'news_content'=>htmlspecialchars_decode(input('news_content')),
            'listorder'=>input('listorder',50,'intval'),
        );
        //图片字段处理
        if(!empty($img_one)){
            $sl_data['news_img']=$img_one;
        }
        $sl_data['news_pic_allurl']=$pic_oldlist.$picall_url;
        //根据栏目id,获取语言
        $news_l=Db::name('menu')->where('id',input('news_columnid'))->value('menu_l');
        $sl_data['news_l']=$news_l;
        //附加字段
        $showtime=input('showdate','');
        $news_extra['showdate']=($showtime=='')?time():strtotime($showtime);
        $sl_data['news_extra']=json_encode($news_extra);
        $rst=NewsModel::update($sl_data);
        if($rst!==false){
            $this->success('文章修改成功,返回列表页',url('admin/News/news_list'));
        }else{
            $this->error('文章修改失败',url('admin/News/news_list'));
        }
    }
}
