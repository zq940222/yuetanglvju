<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/19
 * Time: 15:26
 */

namespace app\api\model;


class BookingStatistics extends BaseModel
{
    public static function getRoomSurplus($checkInTime, $checkOutTime, $roomID)
    {
        $date = [];
        for ($i = strtotime($checkInTime); $i < strtotime($checkOutTime); $i += 86400) {
            $date[] = date("Y-m-d",$i);
        }

        $surplusAmount = self::where('room_id','=',$roomID)
            ->where('date','in',$date)
            ->find();
        if ($surplusAmount)
        {
            $surplusAmount = self::where('room_id','=',$roomID)
                ->where('date','in',$date)
                ->min('surplus_amount');
        }else{
            $room = HotelRoomType::get($roomID);
            $totalNum = $room['stock'];
            $surplusAmount = $totalNum;
        }
        return $surplusAmount;
    }

}