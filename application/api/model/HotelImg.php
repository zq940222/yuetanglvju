<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/11
 * Time: 16:25
 */

namespace app\api\model;



class HotelImg extends BaseModel
{
    protected $visible = ['image','category'];

    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    public function category()
    {
        return $this->belongsTo('HotelImgCategory','hotel_img_category_id','id');
    }
}