<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 15:23
 */

namespace app\api\model;


class Hotel extends BaseModel
{

    protected $hidden = ['img_id','scenic_area_id','business_license_img_id','is_recommend','status','create_time'];

    public function province()
    {
        return $this->belongsTo('Region','province','id');
    }

    public function city()
    {
        return $this->belongsTo('Region','city','id');
    }

    public function district()
    {
        return $this->belongsTo('Region','district','id');
    }

    public function scenicArea()
    {
        return $this->belongsTo('scenic_area','scenic_area_id','id');
    }

    public function comments()
    {
        return $this->hasMany('HotelComment','hotel_id','id');
    }

    public function filtrate()
    {
        return $this->belongsToMany('HotelFiltrate','HotelHotelFiltrate','hotel_filtrate_id','hotel_id');
    }

    public function room()
    {
        return $this->belongsToMany('HotelFiltrate','HotelRoomType','room_type_id','hotel_id');
    }

    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }

}