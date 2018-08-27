<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/17
 * Time: 11:53
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class BannerItem extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function banner()
    {
        return $this->belongsTo('Banner','banner_id','id');
    }

    public function image()
    {
        return $this->belongsTo('Image','img_id','id');
    }
}