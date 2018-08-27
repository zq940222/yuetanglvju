<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 20:22
 */

namespace app\hotel\model;


use traits\model\SoftDelete;

class Hotel extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }

    public function scenicArea()
    {
        return $this->belongsTo('ScenicArea','scenic_area_id','id');
    }

    public function provinceName()
    {
        return $this->belongsTo('Region','province','id');
    }

    public function cityName()
    {
        return $this->belongsTo('Region','city','id');
    }

    public function districtName()
    {
        return $this->belongsTo('Region','district','id');
    }

    public function businessLicense()
    {
        return $this->belongsTo('Image','business_license_img_id','id');
    }

    public function filtrate()
    {
        return $this->belongsToMany('HotelFiltrate','HotelHotelFiltrate','hotel_filtrate_id','hotel_id');
    }
}