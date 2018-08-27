<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 11:58
 */

namespace app\admin\model;


class VersionUpgrade extends BaseModel
{
    public function getTypeAttr($value)
    {
        $arr = [
            '未知',
            '不强制升级',
            '强制升级'
        ];
        return $arr[$value];
    }
}