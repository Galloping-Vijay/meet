<?php
// +----------------------------------------------------------------------
// | Download.
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/2/2
// +----------------------------------------------------------------------

namespace app\common\lib;

class Download
{
    /**
     * 实例
     * @var null
     */
    protected static $handle = null;

    /**
     * 获取操作句柄
     * @return Download|null
     */
    public static function handle()
    {
        if (is_null(self::$handle)) {
            self::$handle = new self();
        }
        return self::$handle;
    }

    /**
     * @param $url
     * @return string
     */
    public function downloadImage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        return $this->saveAsImage($url, $file, $this->path());
    }

    /**
     * @param $url
     * @param $file
     * @param $path
     * @return string
     */
    private function saveAsImage($url, $file, $path)
    {
        $filename = pathinfo($url, PATHINFO_BASENAME);
        $imgPath = $path . $filename;
        $resource = fopen($imgPath, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return $imgPath;
    }

    /**
     * @return string
     */
    public function path()
    {
        $dir = ROOT_PATH . config('upload_path') . '/figure/';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }
}