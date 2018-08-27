<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/24
 * Time: 11:40
 */

namespace app\admin\model;


class Withdraw extends BaseModel
{
    public function hotel()
    {
        return $this->belongsTo('Hotel','hotel_id','id');
    }
}