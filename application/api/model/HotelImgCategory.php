<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/11
 * Time: 17:10
 */

namespace app\api\model;

class HotelImgCategory extends BaseModel
{

    public static function getHotelImg($id)
    {
        $category = self::all();
        foreach ($category as $key => &$value)
        {
            $images = HotelImg::with(['image'])
                ->where('hotel_id','=',$id)
                ->where('hotel_img_category_id','=',$value['id'])
                ->select();
            $value['image'] = $images;
        }
        return $category;
    }
}