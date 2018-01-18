<?php
// +----------------------------------------------------------------------
// | ClassBook.通讯录
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/1/17
// +----------------------------------------------------------------------

namespace app\web\controller\admin;

use app\common\controller\AdminBase;
use app\web\model\ClassBooks;

class Classbook extends AdminBase
{
    /**
     * 列表首页
     * @return mixed
     */
    public function index()
    {
        $model = new ClassBooks();
        $list = $model->paginate(3);
        // 获取分页显示
        $page = $list->render();
        // 模板变量赋值
        $this->assign('list', $list);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()) {
            $model = new ClassBooks();
            $data = $this->request->param();
            //处理图片
            $file = request()->file('file0');
            if ($file) {
                $imgInfo = $this->fileUpload($file, [], '/web/admin.Classbook/add');
                $data['pic'] = $imgInfo['img_one'];
            }
            if ($model->allowField(true)->save($data)) {
                $this->success('添加成功', Url('/web/admin.Classbook/index'));
            } else {
                $this->error('添加失败');
            }
        }
        return $this->fetch();
    }

    public function edit()
    {
        if (request()->isPost() || request()->isAjax()) {
            $data = $this->request->param();
            $info = ClassBooks::get($data['id']);
            if ($info['pic'] != $data['pic']) {
                //处理图片
                $file = request()->file('file0');
                if ($file) {
                    $imgInfo = $this->fileUpload($file, [], '/web/admin.Classbook/add');
                    $data['pic'] = $imgInfo['img_one'];
                }
            }
            if ($info->allowField(true)->save($data)) {
                $this->success('操作成功', Url('/web/admin.Classbook/index'));
            } else {
                $this->error('操作失败');
            }
        }
        $id = $this->request->param();
        $info = ClassBooks::get($id);
        $this->assign('info', $info);
        return $this->fetch();
    }

    public function del()
    {
        $info = ClassBooks::get(input('id'));
        if ($info->delete() === false) {
            $this->error("删除失败！", url('/web/admin.Classbook/index'));
        } else {
            $this->success('操作成功', Url('/web/admin.Classbook/index'));
        }
    }
}