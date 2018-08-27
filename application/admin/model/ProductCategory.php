<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/10
 * Time: 17:23
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class ProductCategory extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    public function getCategoryByTree()
    {
        $cateList=$this->select();
        $array=[];
        foreach($cateList as $item) {
            $array[]=$item->toArray();
        }
        $cateTree=$this->toTree($array);

        return $cateTree;
    }

    public function toTree($category, $parent_id=0)
    {
        $array=[];
        foreach($category as $item) {
            if($item['parent_id']==$parent_id) {
                $children=$this->toTree($category, $item['id']);
                $item['children']=$children;
                $array[]=$item;
            }
        }
        return $array;
    }

    public function getCategoryByList()
    {
        $cateList=$this->with(['image'])->select();
        $array=[];
        foreach($cateList as $item) {
            $array[]=$item->toArray();
        }
        $cateList=$this->toList($array);

        return $cateList;
    }

    public function toList($category, $parent_id=0)
    {
        $array=[];
        foreach($category as $item) {
            if($item['parent_id']==$parent_id) {
                $array[]=$item;
                $children=$this->toList($category, $item['id']);
                $array=array_merge($array, $children);
            }
        }
        return $array;
    }

    public static function getChildren($id)
    {
        $ids = [$id];
        $array = self::select();
        foreach ($array as $value){
            if ($value['parent_id'] == $id){
                $ids = array_merge($ids,self::getChildren($value['id']));
            }
        }
        return $ids;
    }

}