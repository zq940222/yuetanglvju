<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/10
 * Time: 19:51
 */

namespace app\admin\model;


class FreightTemplate extends BaseModel
{
    public function freightConfig()
    {
        return $this->hasMany('FreightConfig', 'template_id', 'id');
    }
}