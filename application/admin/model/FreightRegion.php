<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/18
 * Time: 14:20
 */

namespace app\admin\model;


class FreightRegion extends BaseModel
{
    public function region()
    {
        return $this->hasOne('region', 'id', 'region_id');
    }
    public function freightConfig()
    {
        return $this->hasOne('FreightConfig', 'id', 'config_id');
    }
}