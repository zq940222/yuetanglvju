<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 14:17
 */

namespace app\api\service;


class Distance
{
    /**
     * @desc 拼接副标题
     * @param $list
     * @param $u_lat
     * @param $u_lon
     * @return mixed
     */
    public static function subhead($list, $u_lat = null, $u_lon = null)
    {
        /*
        *u_lat 用户纬度
        *u_lon 用户经度
        *list sql语句
        */
        if (!empty($u_lat) && !empty($u_lon)) {
            foreach ($list as $key => $row) {
                $distance = self::nearby_distance($u_lat, $u_lon, $row['lat'], $row['lon']);
                $distance = round($distance,1);
                $list[$key]['km'] = $distance;
            }
        }else{
            foreach ($list as $key => $row) {
                $list[$key]['km'] = 0;
            }
        }
        return $list;
    }

    /**
     * @desc 根据距离排序
     * @param $u_lat
     * @param $u_lon
     * @param $list
     * @return array|bool
     */
    public static function range($u_lat,$u_lon,$list){
        /*
        *u_lat 用户纬度
        *u_lon 用户经度
        *list sql语句
        */
        if(!empty($u_lat) && !empty($u_lon)){
            foreach ($list as $row) {
                $row['km'] = self::nearby_distance($u_lat, $u_lon, $row['lat'], $row['lon']);
                $row['km'] = round($row['km'], 1);
                $res[] = $row;
            }
            if (!empty($res)) {
                foreach ($res as $user) {
                    $ages[] = $user['km'];
                }
                array_multisort($ages, SORT_ASC, $res);
                return $res;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    //计算经纬度两点之间的距离
    public static function nearby_distance($lat1, $lon1, $lat2, $lon2) {
        $EARTH_RADIUS = 6378.137;
        $radLat1 = self::rad($lat1);
        $radLat2 = self::rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = self::rad($lon1) - self::rad($lon2);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s1 = $s * $EARTH_RADIUS;
        $s2 = round($s1 * 10000) / 10000;
        return $s2;
    }

    private static function rad($d) {
        return $d * 3.1415926535898 / 180.0;
    }
}