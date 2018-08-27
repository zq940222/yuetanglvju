<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/12
 * Time: 14:24
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class Spec extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function item()
    {
        return $this->hasMany('SpecItem','spec_id','id');
    }

    public function productType()
    {
        return $this->belongsTo('ProductType','type_id','id');
    }
}