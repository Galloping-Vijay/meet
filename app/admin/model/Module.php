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
        //新增模块后增加初始菜单
        self::event('after_insert', function ($object) {
            $authRule = new AuthRule();
            $data = [
                'module_id' => $object->id,
                'name' => $object->module_name . '/admin.sys/index',
                'title' => '后台菜单',
                'status' => 1,
                'type' => 1,
                'css' => 'fa-desktop',
                'pid' => 0,
                'level' => 1,
                'addtime' => NOW_TIME
            ];
            $authRule->save($data);
        });
        self::event('after_write', function ($object) {
            //更新模块内容后清楚缓存
            cache('module_info/id=' . $object->id, null);
            cache('moduleAll', null);
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
        $module_status = [0 => '隐藏', 1 => '显示'];
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

    /**
     * 获取模块
     * @param bool $iscache 是否缓存
     * @param array $where 条件
     * @param array $order 排序
     * @return array|mixed
     */
    public function module_all($iscache = true, $where = [], $order = ['id' => 'desc'])
    {
        $list = cache('moduleAll');
        if (empty($list) || $iscache == false) {
            $list = self::where($where)->order($order)->column('id,module_name,module_title,module_url,module_status', 'id');
            cache('moduleAll', serialize($list));

            return $list;
        }
        return unserialize($list);
    }

    /**
     * 获取模块id
     * @param null $name
     * @param bool $iscache
     * @return bool|mixed
     */
    public static function module_id($name = null, $iscache = true)
    {
        if (!isset($name) || empty($name)) {
            return false;
        }
        $one = cache('module_' . $name);
        if (empty($one) || $iscache == false) {
            $one = self::where('module_name', $name)->value('id');
            cache('module_' . $name);
        }
        return $one;
    }
}