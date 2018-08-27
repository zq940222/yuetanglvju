<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/21
 * Time: 10:14
 */

namespace app\admin\model;


class HotelCrowdfunding extends BaseModel
{
    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}