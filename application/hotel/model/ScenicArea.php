<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 20:40
 */

namespace app\hotel\model;


use traits\model\SoftDelete;

class ScenicArea extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function regionProv()
    {
        return $this->belongsTo('Region','province','id');
    }

    public function regionCity()
    {
        return $this->belongsTo('Region','city','id');
    }
}