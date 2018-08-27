<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/25
 * Time: 10:24
 */

namespace app\api\model;


class Cart extends BaseModel
{
    protected $hidden = ['user_id','create_time','update_time','delete_time'];

    public function product()
    {
        return $this->belongsTo('Product','product_id','id');
    }
}