<?php
// +----------------------------------------------------------------------
// | AuthRule.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/26
// +----------------------------------------------------------------------

namespace app\blog\model;

use app\admin\model\Module;
use app\common\model\BaseModel;

class AuthRule extends BaseModel
{
    //主键
    protected $pk = 'id';
    //自动完成
    protected $auto = [
        'addtime' => NOW_TIME,
    ];
    //插入时自动完成
    protected $insert = [];
    //更新时自动完成
    protected $update = [];
    //类型转换
    protected $type = [
        'sort' => 'integer',
        'addtime' => 'integer',
        'level' => 'integer',
        'pid' => 'integer',
    ];
    //自动验证
    protected $validate = [];

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function module()
    {
        return $this->belongsTo('\\app\\admin\\model\\Module', 'module_id', 'id');
    }

    // 定义全局的查询范围
    protected function base($query)
    {
        $module_id = cache('module_blog_id');
        if (!isset($module_id) || empty($module_id)) {
            $module_id = Module::where('module_name', 'blog')->value('id');
            cache('module_blog_id', $module_id);
        }
        $query->where('module_id', $module_id);
    }

    /**
     * 菜单检查是否有效
     * @param int
     * @return string 返回''表示无效,否则返回正确的name字符串
     */
    public static function check_name($name, $level = 1)
    {
        $module = 'blog';
        $nameF = str_replace('.', '/', $name);
        $arr = explode('/', $nameF);
        $count = count($arr);
        $rst = '';
        if ($level == 1) {
            if ($count > 1) {
                $module = strtolower($arr[0]);
                $controller = ucfirst($arr[2]);
            } else {
                $controller = ucfirst($name);
            }
            if (has_controller($module, $controller)) {
                $rst = $module . '/admin.' . $controller;
            }
        } elseif ($level == 2) {
            $rst = $name;
        } else {
            $module = strtolower($arr[0]);
            $controller = ucfirst($arr[2]);
            $action = $arr[3];
            if ($action) {
                //判断$action是否含?
                $arr = explode('?', $action);
                $_action = (count($arr) == 1) ? $action : $arr[0];
                if (has_action($module, $controller, $_action) == 2) {
                    $rst = $module . '/admin.' . $controller . '/' . $action;
                }
            }
        }
        return $rst;
    }
}