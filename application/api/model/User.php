<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/22
 * Time: 10:50
 */

namespace app\api\model;


class User extends BaseModel
{
    protected $hidden = ['id','username','email','password','img_id','create_time','update_time','delete_time'];

    public function third()
    {
        return $this->hasMany('Third','user_id','id');
    }

    public function headimg()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    public function hotelAttention()
    {
        return $this->belongsToMany('Hotel','UserHotelAttention','hotel_id','user_id');
    }

    public function productAttention()
    {
        return $this->belongsToMany('Product','UserProductAttention','product_id','user_id');
    }
}