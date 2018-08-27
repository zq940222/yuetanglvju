<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 15:25
 */

namespace app\api\model;


class HotelComment extends BaseModel
{
    protected $visible = ['avg_score','user','image','overall_score','content','check_in_time','create_time','room_type'];

    public function user()
    {
        return $this->belongsTo('User','user_id','id');
    }

    public function image()
    {
        return $this->belongsToMany('Image','HotelCommentImg','img_id','hotel_comment_id');
    }
}