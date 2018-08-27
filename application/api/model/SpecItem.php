<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/23
 * Time: 10:08
 */

namespace app\api\model;


class SpecItem extends BaseModel
{
    public function spec()
    {
        return $this->belongsTo('Spec','spec_id','id');
    }
}