<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/1
 * Time: 11:02
 */

namespace app\hotel\model;


class HotelRoomType extends BaseModel
{
    public function image()
    {
        return $this->belongsToMany('Image','HotelRoomTypeImg','img_id','hotel_room_type_id');
    }

    public function facility()
    {
        return $this->belongsToMany('RoomFacility','HotelRoomTypeRoomFacility','room_facility_id','hotel_room_type_id');
    }
}