<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/30
 * Time: 14:37
 */

namespace app\api\service;


use think\Db;

class Attention
{
    public static function changeHotelAttention($hotelID)
    {
        $userID = Token::getCurrentUid();
        $result = Db::name('UserHotelAttention')
            ->where('user_id','=',$userID)
            ->where('hotel_id','=',$hotelID)
            ->find();
        if ($result) {
            $res = Db::name('UserHotelAttention')
                ->where('user_id','=',$userID)
                ->where('hotel_id','=',$hotelID)
                ->delete();
        }else{
            $res =Db::name('UserHotelAttention')
                ->insert([
                    'user_id' => $userID,
                    'hotel_id' => $hotelID
                ]);
        }
        if ($res) {
            return true;
        }else{
            return false;
        }
    }

    public static function changeProductAttention($productID)
    {
        $userID = Token::getCurrentUid();
        $result = Db::name('UserProductAttention')
            ->where('user_id','=',$userID)
            ->where('product_id','=',$productID)
            ->find();
        if ($result) {
            $res = Db::name('UserProductAttention')
                ->where('user_id','=',$userID)
                ->where('product_id','=',$productID)
                ->delete();
        }else{
            $res = Db::name('UserProductAttention')
                ->insert([
                    'user_id' => $userID,
                    'product_id' => $productID
                ]);
        }
        if ($res) {
            return true;
        }else{
            return false;
        }
    }
}