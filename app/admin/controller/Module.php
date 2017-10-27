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
use app\common\lib\ValidateBasic;

class Module extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //模块列表
    public function lists()
    {
        $ModuleModel = new ModuleModel();
        $module = $ModuleModel->paginate(15);
        $page = $module->render();
        $this->assign('module', $module);
        $this->assign('page', $page);
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
            $rule = [
                ['module_name', 'require|unique:module', '模块标识必须|模块标识必须唯一'],
            ];
            $validate = new ValidateBasic($rule);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
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
            $rule = [
                ['module_name', 'require|unique:module', '模块标识必须|模块标识必须唯一'],
            ];
            $validate = new ValidateBasic($rule);
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
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