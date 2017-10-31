<?php
// +----------------------------------------------------------------------
// | OssExport.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2017/10/31
// +----------------------------------------------------------------------

namespace app\common\lib;

class OssExport
{
    //生成文件名
    private $object = null;
    //队列key
    private $redisKey = '';
    //追加后的指针
    private $appendId = 0;
    //oss bucket
    private $bucket = '';
    //最后一次错误信息
    public $error = '';

    public function __construct($name)
    {
        $this->redisKey = "_temp_export_{$name}" . '管理员信息';
    }

    /**
     * 初始化
     * @param $name
     * @return $this
     */
    public static function init($name)
    {
        static $_instance = [];
        if (!isset($_instance[$name])) {
            $_instance[$name] = new self($name);
        }
        return $_instance[$name];
    }

    /**
     * 设置/获取object名称
     * @param string $name
     * @return $this|null
     */
    public function object($name = '')
    {
        if ($name) {
            $this->object = "temp/export/{$name}";
        } else {
            return $this->object;
        }
        return $this;
    }

    /**
     * 追加内容
     * @param $content 字符串内容
     * @return mixed
     */
    public function appendObject($content)
    {
        return $this->redis()->rPush($this->redisKey, $content);
    }

    /**
     * 清除
     * @return bool
     */
    public function clearContent()
    {
        $this->redis()->delete($this->redisKey);
        return true;
    }

    /**
     * 检查object文件是否存在
     * @return mixed
     */
    public function isObject()
    {
        return service('Upload')->doesObjectExist($this->bucket, $this->object);
    }

    /**
     * 设置追加内容文件的最后指针位置
     * @param int $appendId
     * @return $this|int
     */
    public function appendId($appendId = 0)
    {
        if ($appendId) {
            $this->appendId = $appendId;
        } else {
            return $this->appendId;
        }
        return $this;
    }

    /**
     * 生成文档
     * @return bool|int 为布尔值时代表结束,false代表错误,true全部生成完毕
     */
    public function generate()
    {
        if (empty($this->object)) {
            $this->error = 'object 为空';
            return false;
        }
        //默认OSS对应的bucket
        $upload = service('Upload');
        $data = '';
        for ($i = 0; $i <= 100; $i++) {
            $val = $this->redis()->lPop($this->redisKey);
            if (empty($val)) {
                continue;
            }
            $data .= $val;
        }
        if (empty($data)) {
            $this->clearContent();
            return true;
        }
        $this->appendId = $upload->appendObject($this->bucket, $this->object, $data, $this->appendId);
        return $this->appendId;
    }

    //获取生成的文档下载地址
    public function fileUrl()
    {
        return "http://img.d1xz.net/{$this->object}";
    }

    //redis对象
    private function redis()
    {
        return redis()->handler();
    }
}