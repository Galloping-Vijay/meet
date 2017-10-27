<?php
// +----------------------------------------------------------------------
// | Sys.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/26
// +----------------------------------------------------------------------

namespace app\blog\controller\admin;

use app\admin\model\Module;
use app\common\controller\AdminBase;
use app\blog\model\AuthRule;
use think\Cache;

class Sys extends AdminBase
{
    /**
     * 跳转到权限列表
     */
    public function index(){
        $this->redirect(MODULE_NAME.'/admin.sys/admin_rule_list');
    }
    /**
     * 权限(后台菜单)列表
     */
    public function admin_rule_list()
    {
        $pid = input('pid', 0);
        $level = input('level', 0);
        $id_str = input('id', 'pid');
        $admin_rule = AuthRule::where('pid', $pid)->order('sort')->select();
        $admin_rule_all = AuthRule::order('sort')->select();
        $arr = menu_left($admin_rule, 'id', 'pid', '─', $pid, $level, $level * 20);
        $arr_all = menu_left($admin_rule_all, 'id', 'pid', '─', 0, $level, $level * 20);
        $this->assign('admin_rule', $arr);
        $this->assign('admin_rule_all', $arr_all);
        $this->assign('pid', $id_str);
        if (request()->isAjax()) {
            return $this->fetch('ajax_admin_rule_list');
        } else {
            return $this->fetch();
        }
    }

    /**
     * 权限(后台菜单)添加
     */
    public function admin_rule_add()
    {
        $pid = input('pid', 0);
        //全部规则
        $admin_rule_all = AuthRule::order('sort')->select();
        $arr = menu_left($admin_rule_all);
        $this->assign('admin_rule', $arr);
        $this->assign('pid', $pid);
        return $this->fetch();
    }

    /**
     * 权限(后台菜单)添加操作
     */
    public function admin_rule_runadd()
    {
        if (!request()->isAjax()) {
            $this->error('提交方式不正确', url('blog/admin.sys/admin_rule_list'));
        } else {
            $authRule = new AuthRule();
            $pid = $authRule->where(array('id' => input('pid')))->field('level')->find();
            $level = $pid['level'] + 1;
            $name = input('name');
            $name = $authRule->check_name($name, $level);
            if ($name) {
                $sldata = array(
                    'name' => $name,
                    'title' => input('title'),
                    'module_id' => Module::module_id('blog'),
                    'status' => input('status', 0, 'intval'),
                    'sort' => input('sort', 50, 'intval'),
                    'pid' => input('pid'),
                    'notcheck' => input('notcheck', 0, 'intval'),
                    'addtime' => time(),
                    'css' => input('css', ''),
                    'level' => $level,
                );
                $authRule->insert($sldata);
                Cache::clear();
                $this->success('权限添加成功', url('blog/admin.sys/admin_rule_list'), 1);
            } else {
                $this->error('控制器或方法不存在,或提交格式不规范', url('blog/admin.sys/admin_rule_list'));
            }
        }
    }

    /**
     * 权限(后台菜单)显示/隐藏
     */
    public function admin_rule_state()
    {
        $id = input('x');
        $statusone = AuthRule::where(array('id' => $id))->value('status');//判断当前状态情况
        if ($statusone == 1) {
            $statedata = array('status' => 0);
            AuthRule::where(array('id' => $id))->setField($statedata);
            Cache::clear();
            $this->success('状态禁止');
        } else {
            $statedata = array('status' => 1);
            AuthRule::where(array('id' => $id))->setField($statedata);
            Cache::clear();
            $this->success('状态开启');
        }
    }

    /**
     * 权限(后台菜单)检测/不检测
     */
    public function admin_rule_notcheck()
    {
        $id = input('x');
        $statusone = AuthRule::where(array('id' => $id))->value('notcheck');//判断当前状态情况
        if ($statusone == 1) {
            $statedata = array('notcheck' => 0);
            AuthRule::where(array('id' => $id))->setField($statedata);
            Cache::clear();
            $this->success('检测');
        } else {
            $statedata = array('notcheck' => 1);
            AuthRule::where(array('id' => $id))->setField($statedata);
            Cache::clear();
            $this->success('不检测');
        }
    }

    /**
     * 权限(后台菜单)排序
     */
    public function admin_rule_order()
    {
        if (!request()->isAjax()) {
            $this->error('提交方式不正确', url('blog/admin.sys/admin_rule_list'));
        } else {
            foreach ($_POST as $id => $sort) {
                AuthRule::where(array('id' => $id))->setField('sort', $sort);
            }
            Cache::clear();
            $this->success('排序更新成功', url('blog/admin.sys/admin_rule_list'));
        }
    }

    /**
     * 权限(后台菜单)编辑
     */
    public function admin_rule_edit()
    {
        //全部规则
        $admin_rule_all = AuthRule::order('sort')->select();
        $arr = menu_left($admin_rule_all);
        $this->assign('admin_rule', $arr);
        //待编辑规则
        $admin_rule = AuthRule::where(array('id' => input('id')))->find();
        $this->assign('rule', $admin_rule);
        return $this->fetch();
    }

    /**
     * 权限(后台菜单)通过复制添加
     */
    public function admin_rule_copy()
    {
        //全部规则
        $admin_rule_all = AuthRule::order('sort')->select();
        $arr = menu_left($admin_rule_all);
        $this->assign('admin_rule', $arr);
        //待编辑规则
        $admin_rule = AuthRule::where(array('id' => input('id')))->find();
        $this->assign('rule', $admin_rule);
        return $this->fetch();
    }

    /**
     * 权限(后台菜单)编辑操作
     */
    public function admin_rule_runedit()
    {
        if (!request()->isAjax()) {
            $this->error('提交方式不正确', url('blog/admin.sys/admin_rule_list'));
        } else {
            $name = input('name');
            $old_pid = input('old_pid');
            $old_level = input('old_level', 0, 'intval');
            $pid = input('pid');
            $level_diff = 0;
            $authRule = new AuthRule();
            //判断是否更改了pid
            if ($pid != $old_pid) {
                $level = $authRule->where('id', $pid)->value('level') + 1;
                $level_diff = ($level > $old_level) ? ($level - $old_level) : ($old_level - $level);
            } else {
                $level = $old_level;
            }
            $name = $authRule->check_name($name, $level);
            if ($name) {
                $sldata = array(
                    'id' => input('id', 1, 'intval'),
                    'name' => $name,
                    'module_id' => Module::module_id('blog'),
                    'title' => input('title'),
                    'status' => input('status', 0, 'intval'),
                    'notcheck' => input('notcheck', 0, 'intval'),
                    'pid' => input('pid', 0, 'intval'),
                    'css' => input('css'),
                    'sort' => input('sort'),
                    'level' => $level
                );
                $rst = $authRule->update($sldata);
                if ($rst !== false) {
                    if ($pid != $old_pid) {
                        //更新子孙级菜单的level
                        $auth_rule = $authRule->order('sort')->select();
                        $tree = new \Tree();
                        $tree->init($auth_rule, ['parentid' => 'pid']);
                        $ids = $tree->get_childs($auth_rule, $sldata['id'], true, false);
                        if ($ids) {
                            if ($level > $old_level) {
                                $authRule->where('id', 'in', $ids)->setInc('level', $level_diff);
                            } else {
                                $authRule->where('id', 'in', $ids)->setDec('level', $level_diff);
                            }
                        }
                    }
                    Cache::clear();
                    $this->success('权限修改成功', url('blog/admin.sys/admin_rule_list'));
                } else {
                    $this->error('权限修改失败', url('blog/admin.sys/admin_rule_list'));
                }
            } else {
                $this->error('控制器或方法不存在,或提交格式不规范', url('blog/admin.sys/admin_rule_list'));
            }
        }
    }

    /**
     * 权限(后台菜单)删除
     */
    public function admin_rule_del()
    {
        $pid = input('id');
        $arr = AuthRule::select();
        $tree = new \Tree();
        $tree->init($arr, ['parentid' => 'pid']);
        $arrTree = $tree->get_childs($arr, $pid, true, true);
        if ($arrTree) {
            $rst = AuthRule::where('id', 'in', $arrTree)->delete();
            if ($rst !== false) {
                Cache::clear();
                $this->success('权限删除成功', url('blog/admin.sys/admin_rule_list'));
            } else {
                $this->error('权限删除失败', url('blog/admin.sys/admin_rule_list'));
            }
        } else {
            $this->error('权限删除失败', url('blog/admin.sys/admin_rule_list'));
        }
    }
}