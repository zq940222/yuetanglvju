<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/11
 * Time: 9:11
 */

namespace app\api\service;


class Category
{
    public static function getCategoryByList($list)
    {
        $array=[];
        foreach($list as $item) {
            $array[]=$item->toArray();
        }
        $cateList=self::toList($array);

        return $cateList;
    }

    public static function getCategoryByTree($list)
    {
        $array=[];
        foreach($list as $item) {
            $array[]=$item->toArray();
        }
        $cateTree=self::toTree($array);

        return $cateTree;
    }

    public static function toTree($category, $parent_id=0)
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

    public static function toList($category, $parent_id=0)
    {
        $array=[];
        foreach($category as $item) {
            if($item['parent_id']==$parent_id) {
                $array[]=$item;
                $children=self::toList($category, $item['id']);
                $array=array_merge($array, $children);
            }
        }
        return $array;
    }
}