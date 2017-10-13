<?php
// +----------------------------------------------------------------------
// | Cat. 文章分类管理
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/13
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Cat as catModel;
use app\admin\model\Module;

class Cat extends Base
{
    //分类列表
    public function index()
    {
        $model = new catModel();
        $list = $model->order('cat_id', 'desc')->paginate(15);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch();
    }

    public function add()
    {
        $module = new Module();
        $moduleList = $module->cache(3600)->column('module_name', 'id');
        if ($this->request->isPost()) {
            //处理图片
            $file = request()->file('file0');
            $imgInfo = $this->fileUpload($file, [], '/admin/cat/index.html');
            //构建数组
            $data = array(
                'cat_name' => input('cat_name'),
                'cid' => input('cid'),
                'cat_intro' => input('cat_intro'),
                'status' => htmlspecialchars_decode(input('status')),
                'cat_img' => $imgInfo['img_one'],
            );
            $model = new catModel();
            if ($model->plus($data)) {
                $this->success('添加成功', '/admin/cat/index.html');
            } else {
                $this->error('添加失败');
            }
        }
        $this->assign('moduleList', $moduleList);
        return $this->fetch();
    }

    public function edit()
    {
        return $this->fetch();
    }

    public function del()
    {
        return $this->fetch();
    }
}