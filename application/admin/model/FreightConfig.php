<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/18
 * Time: 11:38
 */

namespace app\admin\model;


class FreightConfig extends BaseModel
{
    public function freightRegion()
    {
        return $this->hasMany('FreightRegion', 'config_id', 'id');
    }
}