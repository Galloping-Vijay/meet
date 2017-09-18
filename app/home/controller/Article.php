<?php
// +----------------------------------------------------------------------
// | Article. 前台文章操作方法
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/18
// +----------------------------------------------------------------------

namespace app\home\controller;

use think\Db;

class Article extends Base
{
    public function newsAdd()
    {
        if (empty(session('user'))) {
            $this->error('请先登录!', url('/'));
        }
        $news_columnid = input('news_columnid', 0, 'intval');
        $menu_text = Db::name('menu')->where('parentid', 16)->cache(3600)->select();
        $this->assign('menu', $menu_text);
        $source = Db::name('source')->select();
        $this->assign('news_columnid', $news_columnid);
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
        $img_one = '';
        $picall_url = '';
        $file = request()->file('pic_one');
        $files = request()->file('pic_all');
        if ($file || $files) {
            if (config('storage.storage_open')) {
                //七牛
                $upload = \Qiniu::instance();
                $info = $upload->upload();
                $error = $upload->getError();
                if ($info) {
                    if ($file && $files) {
                        //有单图、多图
                        if (!empty($info['pic_one'])) $img_one = config('storage.domain') . $info['pic_one'][0]['key'];
                        if (!empty($info['pic_all'])) {
                            foreach ($info['pic_all'] as $file) {
                                $img_url = config('storage.domain') . $file['key'];
                                $picall_url = $img_url . ',' . $picall_url;
                            }
                        }
                    } elseif ($file) {
                        //单图
                        $img_one = config('storage.domain') . $info[0]['key'];
                    } else {
                        //多图
                        foreach ($info as $file) {
                            $img_url = config('storage.domain') . $file['key'];
                            $picall_url = $img_url . ',' . $picall_url;
                        }
                    }
                } else {
                    $this->error($error, url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                }
            } else {
                $validate = config('upload_validate');
                //单图
                if ($file) {
                    $info = $file[0]->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                    if ($info) {
                        $img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
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
                            $img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
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

        $sl_data = array(
            'news_title' => input('news_title'),
            'news_columnid' => input('news_columnid'),
            'news_key' => input('news_key', ''),
            'news_tag' => input('news_tag', ''),
            'news_source' => input('news_source', ''),
            'news_pic_type' => input('news_pic_type'),
            'news_pic_content' => input('news_pic_content', ''),
            'news_pic_allurl' => $picall_url,//多图路径
            'news_img' => $img_one,//封面图片路径
            'news_open' => input('news_open', 0),
            'news_scontent' => input('news_scontent', ''),
            'news_content' => htmlspecialchars_decode(input('news_content')),
            'news_auto' => session('user.member_list_nickname'),
            'news_time' => time(),
        );
        //根据栏目id,获取语言
        $news_l = Db::name('menu')->where('id', input('news_columnid'))->value('menu_l');
        $sl_data['news_l'] = $news_l;
        //附加字段
        $showtime = input('showdate', '');
        $news_extra['showdate'] = ($showtime == '') ? time() : strtotime($showtime);
        $sl_data['news_extra'] = json_encode($news_extra);
        //改到这里 下面的模型要建一个在home
        \app\admin\model\News::create($sl_data);
        $continue = input('continue', 0, 'intval');
        if ($continue) {
            $this->success('文章添加成功,继续发布', url('admin/News/news_add', ['news_columnid' => input('news_columnid')]));
        } else {
            $this->success('文章添加成功,返回列表页', url('admin/News/news_list'));
        }
    }

    /**
     * 编辑显示
     */
    public function news_edit()
    {
        $n_id = input('n_id');
        if (empty($n_id)) {
            $this->error('参数错误', url('admin/News/news_list'));
        }
        $news_list = NewsModel::get($n_id);
        $news_extra = json_decode($news_list['news_extra'], true);
        $news_extra['showdate'] = ($news_extra['showdate'] == '') ? $news_list['news_time'] : $news_extra['showdate'];
        //多图字符串转换成数组
        $text = $news_list['news_pic_allurl'];
        $pic_list = array_filter(explode(",", $text));
        $this->assign('pic_list', $pic_list);
        //栏目数据
        $menu_text = menu_text($this->lang);
        $this->assign('menu', $menu_text);
        $diyflag = Db::name('diyflag')->select();
        $source = Db::name('source')->select();//来源
        $this->assign('source', $source);
        $this->assign('news_extra', $news_extra);
        $this->assign('diyflag', $diyflag);
        $this->assign('news_list', $news_list);
        return $this->fetch();
    }

    /**
     * 编辑操作
     */
    public function news_runedit()
    {
        if (!request()->isAjax()) {
            $this->error('提交方式不正确', url('admin/News/news_list'));
        }
        //获取图片上传后路径
        $pic_oldlist = input('pic_oldlist');//老多图字符串
        $img_one = '';
        $picall_url = '';
        $file = request()->file('pic_one');
        $files = request()->file('pic_all');
        //上传处理
        if ($file || $files) {
            if (config('storage.storage_open')) {
                //七牛
                $upload = \Qiniu::instance();
                $info = $upload->upload();
                $error = $upload->getError();
                if ($info) {
                    if ($file && $files) {
                        //有单图、多图
                        if (!empty($info['pic_one'])) $img_one = config('storage.domain') . $info['pic_one'][0]['key'];
                        if (!empty($info['pic_all'])) {
                            foreach ($info['pic_all'] as $file) {
                                $img_url = config('storage.domain') . $file['key'];
                                $picall_url = $img_url . ',' . $picall_url;
                            }
                        }
                    } elseif ($file) {
                        //单图
                        $img_one = config('storage.domain') . $info[0]['key'];
                    } else {
                        //多图
                        foreach ($info as $file) {
                            $img_url = config('storage.domain') . $file['key'];
                            $picall_url = $img_url . ',' . $picall_url;
                        }
                    }
                } else {
                    $this->error($error, url('admin/News/news_list'));//否则就是上传错误，显示错误原因
                }
            } else {
                $validate = config('upload_validate');
                //单图
                if (!empty($file)) {
                    $info = $file[0]->validate($validate)->rule('uniqid')->move(ROOT_PATH . config('upload_path') . DS . date('Y-m-d'));
                    if ($info) {
                        $img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
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
                            $img_url = config('upload_path') . '/' . date('Y-m-d') . '/' . $info->getFilename();
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
        $news_flag = input('post.news_flag/a');
        $flag = array();
        if (!empty($news_flag)) {
            foreach ($news_flag as $v) {
                $flag[] = $v;
            }
        }
        $flagdata = implode(',', $flag);
        $sl_data = array(
            'n_id' => input('n_id'),
            'news_title' => input('news_title'),
            'news_titleshort' => input('news_titleshort', ''),
            'news_columnid' => input('news_columnid'),
            'news_flag' => $flagdata,
            'news_zaddress' => input('news_zaddress', ''),
            'news_key' => input('news_key', ''),
            'news_tag' => input('news_tag', ''),
            'news_source' => input('news_source', ''),
            'news_pic_type' => input('news_pic_type'),
            'news_pic_content' => input('news_pic_content', ''),
            'news_open' => input('news_open', 0),
            'news_scontent' => input('news_scontent', ''),
            'news_content' => htmlspecialchars_decode(input('news_content')),
            'listorder' => input('listorder', 50, 'intval'),
        );
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
        $rst = \app\admin\model\News::update($sl_data);
        if ($rst !== false) {
            $this->success('文章修改成功,返回列表页', url('admin/News/news_list'));
        } else {
            $this->error('文章修改失败', url('admin/News/news_list'));
        }
    }
}