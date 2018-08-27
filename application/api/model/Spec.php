<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/22
 * Time: 17:00
 */

namespace app\api\model;


class Spec extends BaseModel
{
    public function item()
    {
        return $this->hasMany('SpecItem','spec_id','id');
    }
}