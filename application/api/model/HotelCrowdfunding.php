<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/2
 * Time: 16:25
 */

namespace app\api\model;


class HotelCrowdfunding extends BaseModel
{
    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}