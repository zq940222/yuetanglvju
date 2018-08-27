<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 16:30
 */

namespace app\admin\model;


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

    public function businessLicense()
    {
        return $this->belongsTo('Image','business_license_img_id','id');
    }

    public function admin()
    {
        return $this->hasOne('HotelAdmin','hotel_id','id');
    }
}