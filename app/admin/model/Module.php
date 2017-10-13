<?php
// +----------------------------------------------------------------------
// | Module.模块类
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/28
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;

class Module extends BaseModel
{
    //主键
    protected $pk = 'id';
    //自动完成
    protected $auto = [
        'update_time' => NOW_TIME
    ];
    //类型转换
    protected $type = [
        'module_status' => 'integer',
        'update_time' => 'integer',
    ];

    /**
     * Author: wjf <1937832819@qq.com>
     */
    protected static function init()
    {
        //更新模块内容后清楚缓存
        self::event('after_update', function ($object) {
            cache('module_info/id=' . $object->id, null);
        });
    }

    /**
     * 获取当前应用模块
     * @param $value
     * @param $data
     * @return mixed
     */
    public function getModuleStatusNameAttr($value, $data)
    {
        $module_status = [0 => '未使用', 1 => '正在使用'];
        return $module_status[$data['module_status']];
    }

    /**
     * 添加编辑操作
     * @param $data
     * @return bool|mixed
     */
    public function plus($data)
    {
        if (empty($data)) {
            $this->error = '数据不能为空';
            return false;
        }
        if ($this->save($data) === false) {
            return false;
        }
        return $this->id;
    }

    /**
     * 修改启用模块
     * @param $id
     * @return bool
     */
    public function sel_module($id)
    {
        $info = self::get($id);
        if (empty($info)) {
            $this->error = '该模块不存在';
            return false;
        } elseif ($info['module_status'] == 1) {
            $this->error = '已选择此模块';
            return false;
        } elseif (!in_array($info['module_name'], self::module_list())) {
            $this->error = '不存在此模块';
        }
        $give = self::where('module_status', 1)->update(['module_status' => 0]);
        if ($give === false) {
            $this->error = '更换模块失败';
        }
        $info->module_status = 1;
        if ($info->save() === false) {
            return false;
        }
        //设置默认模块
        $default_module = ['default_module' => $info->module_name];
        if (sys_config_setbyarr($default_module) === false) {
            return false;
        }
        return $info->id;
    }

    /**
     * 删除
     * @return bool
     */
    public function rm()
    {
        return $this->delete() !== false ? true : false;
    }

    /**
     * 获取模块标识
     * Author: wjf <1937832819@qq.com>
     * @param string $path
     * @return array
     */
    public static function module_list($path = APP_PATH)
    {
        $moduleList = cache('module_list' . $path);
        if ($moduleList) return $moduleList;
        //过滤目录
        $exclude = ['admin', 'common', 'cron', 'install', 'lang', 'wechat'];
        //获取目录名称
        $list = list_file($path, '*', GLOB_ONLYDIR);

        $moduleList = [];
        foreach ($list as $k => $module) {
            if (!in_array($module['filename'], $exclude)) {
                array_push($moduleList, $module['filename']);
            }
        }
        unset($list);
        cache('module_list' . $path, $moduleList);
        return $moduleList;
    }

    /**
     * 获取模块信息
     * Author: wjf <1937832819@qq.com>
     * @param $id
     * @return mixed|null|static
     */
    public static function module_info($id)
    {
        $info = cache('module_info/id=' . $id);
        if ($info) return $info;
        $info = self::get($id);
        cache('module_info/id=' . $id);
        return $info;
    }
}