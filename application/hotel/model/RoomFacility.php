<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/1
 * Time: 15:28
 */

namespace app\hotel\model;


class RoomFacility extends BaseModel
{
    public function parentCate()
    {
        return $this->belongsTo('RoomFacility','parent_id','id');
    }

    public static function getByTree()
    {
        $cateList=self::select();
        $array=[];
        foreach($cateList as $item) {
            $array[]=$item->toArray();
        }
        $cateTree=self::toTree($array);

        return $cateTree;
    }

    private static function toTree($category, $parent_id=0)
    {
        $array=[];
        foreach($category as $item) {
            if($item['parent_id']==$parent_id) {
                $children=self::toTree($category, $item['id']);
                $item['children']=$children;
                $array[]=$item;
            }
        }
        return $array;
    }
}