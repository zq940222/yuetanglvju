<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/17
 * Time: 11:52
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class Banner extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function item()
    {
        return $this->hasMany('BannerItem','banner_id','id');
    }
}