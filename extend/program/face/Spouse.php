<?php
// +----------------------------------------------------------------------
// | Spouse.夫妻相
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.abc3210.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: wjf <admin@abc3210.com> 2017/9/26
// +----------------------------------------------------------------------

namespace program\face;

class Spouse
{
    public function __construct($url)
    {
        $faceObj = new FacePlusPlus();
        $detect = $faceObj->face_detect($url);
        if (!empty($detect->error)) {
            exit(json_encode(['code' => $detect->error_code, 'msg' => '获取数据失败'], JSON_UNESCAPED_UNICODE));
        }
        $numbers = isset($detect->face) ? count($detect->face) : 0;
        if (($detect->face[0]->attribute->gender->value != $detect->face[1]->attribute->gender->value) && $numbers == 2) {
            $compare = $faceObj->recognition_compare($detect->face[0]->face_id, $detect->face[1]->face_id);
            $result = $this->getCoupleComment($compare->component_similarity->eye, $compare->component_similarity->mouth, $compare->component_similarity->nose, $compare->component_similarity->eyebrow, $compare->similarity);
            return $result;
        } else {
            return "似乎不是一男一女，无法测试夫妻相";
        }
    }

    public function getCoupleComment($eye, $mouth, $nose, $eyebrow, $similarity)
    {
        $index = round(($eye + $mouth + $nose + $eyebrow) / 4);
        if ($index < 40) {
            $comment = "花好月圆";
        } else if ($index < 50) {
            $comment = "相濡以沫";
        } else if ($index < 60) {
            $comment = "情真意切";
        } else if ($index < 70) {
            $comment = "郎才女貌";
        } else if ($index < 80) {
            $comment = "心心相印";
        } else if ($index < 90) {
            $comment = "浓情蜜意";
        } else {
            $comment = "海盟山誓";
        }
        return "【夫妻相指数】\n得分：" . $index . "\n评语：" . $comment;
    }
}