<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/3
 * Time: 11:42
 */

namespace app\admin\model;


class HotelImg extends BaseModel
{
    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    public function cate()
    {
        return $this->belongsTo('HotelImgCategory','hotel_img_category_id','id');
    }
}