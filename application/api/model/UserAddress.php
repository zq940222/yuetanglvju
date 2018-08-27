<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/25
 * Time: 17:50
 */

namespace app\api\model;


use traits\model\SoftDelete;

class UserAddress extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

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
}