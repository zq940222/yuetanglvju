<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/4
 * Time: 9:56
 */

namespace app\api\model;


use traits\model\SoftDelete;

class ProductCategory extends BaseModel
{
    use SoftDelete;

    protected $hidden = ['delete_time','update_time','create_time'];

    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    public static function getCategoryByTree()
    {
        $cateList=self::with('img')
            ->order('sort asc')
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