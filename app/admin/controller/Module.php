<?php
// +----------------------------------------------------------------------
// | Module.模块控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/28
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Module as ModuleModel;

class Module extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //模块列表
    public function lists()
    {
        $module = ModuleModel::all();
        $this->assign('module', $module);
        return $this->fetch();
    }

    //添加模块
    public function add()
    {
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim');
            $data['module_name'] = strtolower($data['module_name']);
            $model = new ModuleModel;
            $moduleList = $model::module_list();
            if (!in_array($data['module_name'], $moduleList)) {
                $this->error('不存在此模块!');
            }
            if ($model->plus($data)) {
                $this->success('操作成功', Url('admin/Module/lists'));
            } else {
                $this->error($model->getError() ?: '操作失败');
            }
        }
        return $this->fetch();
    }

    //编辑模块
    public function edit()
    {
        $id = input('id');
        $info = ModuleModel::get($id);
        if (empty($info)) {
            $this->error('该信息不存在');
        }
        if ($this->request->isPost()) {
            $data = input('post.', [], 'trim');
            $data['module_name'] = strtolower($data['module_name']);
            $moduleList = $info::module_list();
            if (!in_array($data['module_name'], $moduleList)) {
                $this->error('不存在此模块!');
            }
            if ($info->plus($data)) {
                $this->success('操作成功', Url('admin/Module/lists'));
            } else {
                $this->error($info->getError() ?: '操作失败');
            }
        }
        $this->assign('info', $info);
        return $this->fetch();
    }

    //修改主题
    public function sel()
    {
        $id = input('id');
        $model = new ModuleModel;
        if ($model->sel_module($id)) {
            $this->success('操作成功', Url('admin/Module/lists'));
        } else {
            $this->error('操作失败');
        }
    }

    //删除模块
    public function del()
    {
        $id = input('id');
        $info = ModuleModel::get($id);
        if (empty($info)) {
            $this->error('该信息不存在');
        }
        if ($info->rm()) {
            $this->success('删除成功', Url('admin/Module/lists'));
        } else {
            $this->error($info->getError() ?: '删除失败');
        }
    }
}