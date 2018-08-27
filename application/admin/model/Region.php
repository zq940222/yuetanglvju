<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 16:47
 */

namespace app\admin\model;


class Region extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time'];

    public static function getRegion($parentId=100000)
    {
        $data=self::field('id,short_name,name')
            ->where('parent_id', $parentId)
            ->select();
        return $data;
    }
}