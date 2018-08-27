<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/17
 * Time: 16:25
 */

namespace app\api\service;


class HotelSearch
{
    public static function getHotel($city, $page = 1, $size = 10, $lat = null, $lon = null)
    {
        $where = [];
        if ($array['city']){
            $where['city'] = $array['city'];
        }
        if ($array['min_price'] && $array['max_price']){
            $where['min_price'] = ['between',[$array['min_price'],$array['max_price']]];
        }
        if ($array['min_distance'] && $array['max_distance']){
            $where['min_distance'] = ['between',[$array['min_distance'],$array['max_distance']]];
        }
    }
}