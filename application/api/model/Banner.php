<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 19:10
 */

namespace app\api\model;


use traits\model\SoftDelete;

class Banner extends BaseModel
{
    use SoftDelete;

    protected $hidden = ['create_time','update_time','delete_time'];

    public function items()
    {
        return $this->hasMany('BannerItem','banner_id','id');
    }

    public static function getBannerByID($id)
    {
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }

}