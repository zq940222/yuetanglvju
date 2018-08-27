<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/28
 * Time: 17:00
 */

namespace app\api\model;


class HotelRoomType extends BaseModel
{
    protected $hidden = ['hotel_id','create_time','update_time','delete_time'];

    public function image()
    {
        return $this->belongsToMany('Image','HotelRoomTypeImg','img_id','hotel_room_type_id');
    }

//    public function roomType()
//    {
//        return $this->belongsTo('HotelFiltrate','room_type_id','id');
//    }

    public function facility()
    {
        return $this->belongsToMany('RoomFacility','HotelRoomTypeRoomFacility','room_facility_id','hotel_room_type_id');
    }

    public function hotel()
    {
        return $this->belongsTo('Hotel','hotel_id','id');
    }

    public static function getRoom($id, $checkInTime, $checkOutTime)
    {
        $room = self::with(['image','facility','facility.parent','hotel','hotel.image'])
            ->find($id)
            ->toArray();
        $room['surplus_amount'] = BookingStatistics::getRoomSurplus($checkInTime,$checkOutTime,$id);
        return $room;
    }
}