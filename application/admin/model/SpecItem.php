<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/12
 * Time: 14:25
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class SpecItem extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function spec()
    {
        return $this->belongsTo('Spec','spec_id','id');
    }
}