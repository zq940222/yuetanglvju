<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/24
 * Time: 11:40
 */

namespace app\hotel\model;


use traits\model\SoftDelete;

class Withdraw extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function hotel()
    {
        return $this->belongsTo('Hotel','hotel_id','id');
    }
}