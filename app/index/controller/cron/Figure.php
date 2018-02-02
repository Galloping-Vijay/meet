<?php
// +----------------------------------------------------------------------
// | FigureClean.清理斗图图片
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <1937832819@qq.com> 2018/2/2
// +----------------------------------------------------------------------

namespace app\index\controller\cron;

use app\common\lib\Download;

class Figure
{
    public function index()
    {
        //获取路径
        $dir = Download::handle()->path();
        $this->delFile($dir);
        return '执行完成';

    }

    /**
     * @param $dirName
     * @return bool
     */
    public function delFile($dirName)
    {
        if (file_exists($dirName) && $handle = opendir($dirName)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (file_exists($dirName . '/' . $item) && is_dir($dirName . '/' . $item)) {
                        $this->delFile($dirName . '/' . $item);
                    } else {
                        if (unlink($dirName . '/' . $item)) {
                            return true;
                        }
                    }
                }
            }
            closedir($handle);
        }
    }
}