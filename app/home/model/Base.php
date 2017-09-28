<?php
// +----------------------------------------------------------------------
// | Base.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/18
// +----------------------------------------------------------------------

namespace app\home\model;

use think\Db;
use think\Model;

class Base extends Model
{

    // 字段类型或者格式转换
    protected $type = [
        'update_time' => 'integer',
        'create_time' => 'integer',
    ];

    /**
     * 取得数据库的表信息
     * @access public
     * @return array
     */
    public function getTables()
    {
        $sql = 'SHOW TABLES ';
        $result = Db::query($sql);
        $info = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    /**
     * 获取当前字段信息
     * @access public
     * @param string $tableName 数据表名,不带表前缀
     * @return array
     */
    public function getTablesFields($tableName)
    {
        if (!$this->tableExists($tableName)) {
            return false;
        }
        return Db::getFields(\think\Config::get('database.prefix') . $tableName) ?: false;
    }

    /**
     * 检查字段是否存在
     * $table 不带表前缀
     */
    public function fieldExists($table, $field)
    {
        $fields = (array)$this->getTablesFields($table);
        return array_key_exists($field, $fields);
    }

    /**
     * 检查表是否存在
     * @param $table 数据表名,不带表前缀
     * @return bool
     */
    public function tableExists($table)
    {
        if (empty($table)) {
            return false;
        }
        $tables = $this->getTables();
        return in_array(\think\Config::get('database.prefix') . $table, $tables) ? true : false;
    }
}