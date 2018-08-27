<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/29
 * Time: 10:09
 */

namespace app\api\service;

use app\api\model\Hotel as HotelModel;
use app\api\model\HotelComment as HotelCommentModel;
use app\lib\exception\HotelException;

class HotelComment
{

    public static function hotelScore($hotel_id)
    {
        $hotel = HotelModel::find($hotel_id);
        return $hotel['avg_score'];
    }

    public static function hotelComment($id,$page,$size)
    {
        $dataArray = HotelCommentModel::with(['user','user.headimg','image'])
            ->where('hotel_id','=',$id)
            ->paginate($size,true,['page'=>$page]);
        foreach ($dataArray as &$value)
        {
            if (!$value->user->headimg)
            {
                $value->user->headimg = '';
            }
        }
        return $dataArray;
    }
}