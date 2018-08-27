<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/13
 * Time: 10:31
 */

namespace app\api\model;


class Region extends BaseModel
{
    protected $visible = ['id','name'];

    public static function getRegion($parentId=100000)
    {
        $data= self::where('parent_id', $parentId)->select();
        return $data;
    }

    public static function getCity()
    {
        $cityListByFirst=self::field('id, short_name, name, left(pinyin, 1) first')->where('level_type', 2)->where('pinyin','<>','')->order('first asc')->select();

        $cityListByFirst=self::toTree($cityListByFirst);

        return $cityListByFirst;
    }

    private static function toTree($array)
    {
        $tree=[];
        foreach($array as $item) {
            $tree[$item['first']][]=$item->toArray();
        }
        $data = [];
        foreach ($tree as $key => $value) {
            $da = [];
            $da['initial'] = $key;
            $da['list'] = $value;
            array_push($data,$da);
        }
        return $data;
    }
}