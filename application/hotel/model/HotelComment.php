<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 14:34
 */

namespace app\hotel\model;


use traits\model\SoftDelete;

class HotelComment extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function hotel()
    {
        return $this->belongsTo('Hotel','hotel_id','id');
    }

    public function user()
    {
        return $this->belongsTo('User','user_id','id');
    }

    public function image()
    {
        return $this->belongsToMany('Image','HotelCommentImg','img_id','hotel_comment_id');
    }
}