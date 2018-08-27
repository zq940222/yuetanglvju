<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/26
 * Time: 14:15
 */

namespace app\api\model;


class FreightRegion extends BaseModel
{
    public function freightConfig()
    {
        return $this->belongsTo('FreightConfig','config_id','id');
    }
}