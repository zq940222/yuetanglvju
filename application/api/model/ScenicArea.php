<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/10
 * Time: 15:28
 */

namespace app\api\model;


class ScenicArea extends BaseModel
{
    protected $hidden = ['province','city','create_time','update_time','delete_time'];

    public function city()
    {
        return $this->belongsTo('Region','city','id');
    }
}