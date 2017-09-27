<?php
// +----------------------------------------------------------------------
// | Queue.列队操作
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.meetoyou.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <184521508.com> 2017/9/27
// +----------------------------------------------------------------------

namespace app\common\lib;

use think\Exception;
use think\Log;

class Queue
{
    /**
     * 驱动实例
     * @var Object
     */
    protected $handler;
    /**
     * 错误信息
     * @var string
     */
    protected $error = '';
    /**
     * 队列名称
     * @var string
     */
    protected $name = '';

    public function __construct()
    {
        $this->setDriver(redis());
    }

    /**
     * 获取一个队列操作对象
     * @param string $name 队列名称
     * @return Queue
     */
    public static function init($name)
    {
        static $handler;
        if ($name && isset($handler[$name])) {
            $object = $handler[$name];
        } else {
            $object = $handler[$name] = new self();
            $object->name($name);
        }
        return $object;
    }

    /**
     * 获取最后一次错误信息
     * @return string 错误信息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置/获取 队列名称
     * @param null|string $name
     * @return $this|string
     */
    public function name($name = null)
    {
        if (is_null($name)) {
            return $this->name;
        } else {
            $this->name = $name;
        }
        return $this;
    }

    /**
     * 设置驱动
     * @param object $driver 基于TP实现缓存驱动接口对象
     * @return $this
     */
    public function setDriver($driver)
    {
        if (!is_object($driver)) {
            $this->error = '缓存驱动错误';
            return false;
        }
        $this->handler = $driver;
        return $this;
    }

    /**
     * 获取队列载体对象
     * @return mixed
     */
    public function handler()
    {
        return $this->handler->handler();
    }

    /**
     * 删除/清空队列
     * @return mixed
     */
    public function rm()
    {
        return $this->handler()->delete($this->name());
    }

    /**
     * 获取当前队列长度
     * @return int
     */
    public function length()
    {
        return $this->handler()->LLEN($this->name());
    }

    /**
     * 把数据压入当前队列结尾
     * @param string|array|object $data
     * @return bool
     */
    public function push($data)
    {
        try {
            foreach (func_get_args() as $value) {
                $this->handler()->rPush($this->name(), serialize($value));
            }
            return true;
        } catch (Exception $e) {
            Log::record(
                [
                    'tips' => '压入队列尾失败(enterQueue)',
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ],
                'error'
            );
            $this->error = '压入队列失败';
        }
        return false;
    }

    /**
     * 把数据压入当前队列开头
     * @param string|array|object $data
     * @return bool
     */
    public function lPush($data)
    {
        try {
            foreach (func_get_args() as $value) {
                $this->handler()->lPush($this->name(), serialize($value));
            }
            return true;
        } catch (Exception $e) {
            Log::record(
                [
                    'tips' => '压入队列头失败(enterQueue)',
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ],
                'error'
            );
            $this->error = '压入队列失败';
        }
        return false;
    }

    /**
     * 移出并获取列表的N条数据
     * @param string $queueName 队列名称
     * @param int $limit 数量
     * @return array
     */
    public function getData($limit = 100)
    {
        $dataList = [];
        try {
            $empty = 0;
            for ($i = 0; $i < $limit; $i++) {
                if ($empty > 10) {
                    break;
                }
                $data = $this->handler()->lPop($this->name());
                if (empty($data)) {
                    $empty++;
                    continue;
                }
                $dataList[] = unserialize($data);
            }
        } catch (Exception $e) {
            Log::record(
                [
                    'tips' => '从队列获取失败(getData)',
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ],
                'error'
            );
            $this->error = '从队列获取失败';
            return false;
        }
        return $dataList;
    }
}