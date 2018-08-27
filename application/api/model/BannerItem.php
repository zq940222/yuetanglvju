<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 19:11
 */

namespace app\api\model;

use traits\model\SoftDelete;

class BannerItem extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected $hidden = ['id','img_id','banner_id','create_time','update_time','delete_time'];

    public function img()
    {
        return $this->belongsTo('Image','img_id','id');
    }

}