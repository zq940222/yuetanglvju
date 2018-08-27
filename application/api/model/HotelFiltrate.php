<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/10
 * Time: 20:44
 */

namespace app\api\model;

use traits\model\SoftDelete;

class HotelFiltrate extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $visible = ['id','name','children','parent_id'];

    public static function getHotelFiltrateByTree()
    {
        $cateList=self::order('sort asc')
            ->select();
        $array=[];
        foreach($cateList as $item) {
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
}