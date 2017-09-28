<?php
// +----------------------------------------------------------------------
// | ValidateClass.验证类
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/9/27
// +----------------------------------------------------------------------

namespace app\common\lib;

use think\File;
use think\Validate;

class ValidateBasic extends Validate
{

    /**
     * 验证是否是手机或者邮箱
     * @param string $value 要验证的字段
     * @param string $msg 提示信息
     * @param array $data 外加数据
     * @return string
     */
    public function verification($value, $msg = '', $data = [])
    {
        if (preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $value)) {
            $res = [
                'code' => 1,
                'type' => 'email',
                'msg' => '验证成功',
            ];
        } elseif (preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $value)) {
            $res = [
                'code' => 1,
                'type' => 'tel',
                'msg' => '验证成功',
            ];
        } else {
            $res = [
                'code' => 0,
                'type' => 'Unknown',
                'msg' => $msg ? $msg . '错误!' : '既不是手机也不是邮箱',
            ];
        }
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 验证是否手机号格式是否正确
     * @param string $value 传入的值
     * @param string $msg 提示语
     * @param array $data 其他验证数据
     * @return bool|string
     */
    protected function isPhone($value, $msg = '', $data = [])
    {
        if (preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $value)) {
            return true;
        }
        return $msg ?: '手机号格式错误';
    }

    /**
     * 验证数据长度
     * @access protected
     * @param mixed $value 字段值
     * @param mixed $rule 验证规则
     * @return bool
     */
    protected function length($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string)$value, 'utf-8');
        }

        if (strpos($rule, ',')) {
            // 长度区间
            list($min, $max) = explode(',', $rule);
            return $length >= $min && $length <= $max;
        } else {
            // 指定长度
            return $length == $rule;
        }
    }

    /**
     * 验证数据最大长度
     * @access protected
     * @param mixed $value 字段值
     * @param mixed $rule 验证规则
     * @return bool
     */
    protected function max($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string)$value, 'utf-8');
        }
        return $length <= $rule;
    }

    /**
     * 验证数据最小长度
     * @access protected
     * @param mixed $value 字段值
     * @param mixed $rule 验证规则
     * @return bool
     */
    protected function min($value, $rule)
    {
        if (is_array($value)) {
            $length = count($value);
        } elseif ($value instanceof File) {
            $length = $value->getSize();
        } else {
            $length = mb_strlen((string)$value, 'utf-8');
        }
        return $length >= $rule;
    }

}