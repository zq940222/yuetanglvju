<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/7
 * Time: 16:10
 */

namespace app\admin\model;


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