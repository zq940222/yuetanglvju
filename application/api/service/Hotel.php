<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 15:19
 */

namespace app\api\service;

use app\api\model\Hotel as HotelModel;
use app\api\model\HotelFiltrate;
use app\api\model\HotelHotelFiltrate;
use app\api\model\HotelHotelStyle;
use app\lib\exception\HotelException;
use app\api\service\Distance as DistanceService;
use app\lib\exception\ParameterException;
use think\Db;

class Hotel
{
    /**
     * @desc 推荐酒店
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @return mixed
     */
    public static function getRecommendHotel($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = 0, $max_distance = 0, $hotel_style_id = 0)
    {
        $where = [];
        $where['is_recommend'] = ['=',1];
        $where['status'] = ['=',1];
        $where['city'] = ['=',$city];

        if ($max_price)
        {
            $where['min_price'] = ['between',[$min_price,$max_price]];
        }else{
            $where['min_price'] = ['>=',$min_price];
        }

        if ($lat && $lon && $max_distance){
            //以当前用户经纬度为中心,查询5000米内的其他用户
            $y = $max_distance / 110852; //纬度的范围
            $x = $max_distance / (111320*cos($lat)); //经度的范围
            $where['lat'] = ['between',[$lat-$y,$lat+$y]];
            $where['lon'] = ['between',[$lon-$x,$lon+$x]];
        }


        if ($hotel_style_id) {
            $ids2 = Db::name('hotel_hotel_filtrate')->where('hotel_filtrate_id','=',$hotel_style_id)->group('hotel_id')->column('hotel_id');
            if (isset($ids)){
                $ids = array_unique(array_merge($ids,$ids2));
            }else{
                $ids = $ids2;
            }
            $where['id'] = ['in',$ids];
        }
        $pagingData = HotelModel::with(['image','scenicArea','province','city','district'])
            ->where($where)
            ->paginate($size,true,['page'=>$page]);
        $resData = DistanceService::subhead($pagingData,$lat, $lon);
        return $resData;
    }

    /**
     * @desc 综合排序酒店
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @param int $min_price
     * @param null $max_price
     * @param null $max_distance
     * @param string $district
     * @param int $scenicAreaID
     * @param array $filtrateIDs
     * @param string $name
     * @return mixed
     * @throws HotelException
     * @throws \think\exception\DbException
     */
    public static function getHotel($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = null, $max_distance = null,$hotel_style_id = 0,$district='',$scenicAreaID=0,$filtrateIDs=[],$name='')
    {
        $where = [];
        if ($max_price)
        {
            $where['min_price'] = ['between',[$min_price,$max_price]];
        }else{
            $where['min_price'] = ['>',$min_price];
        }

        if ($lat && $lon && $max_distance){
            //以当前用户经纬度为中心,查询5000米内的其他用户
            $y = $max_distance / 110852; //纬度的范围
            $x = $max_distance / (111320*cos($lat)); //经度的范围
            $where['lat'] = ['between',[$lat-$y,$lat+$y]];
            $where['lon'] = ['between',[$lon-$x,$lon+$x]];
        }

        if ($hotel_style_id) {
            array_push($filtrateIDs,$hotel_style_id);
        }

        if ($district){
            $where['district'] = ['=',$district];
        }

        if ($scenicAreaID) {
            $where['scenic_area_id'] = ['=',$scenicAreaID];
        }

        if ($filtrateIDs) {
            $ids2 = Db::name('hotel_hotel_filtrate')->where('hotel_filtrate_id','in',$filtrateIDs)->group('hotel_id')->column('hotel_id');
            if (isset($ids)){
                $ids = array_unique(array_merge($ids,$ids2));
            }else{
                $ids = $ids2;
            }
            $where['id'] = ['in',$ids];
        }

        if ($name) {
            $where['name'] = ['like',"%$name%"];
        }

        $where['status'] = ['=',1];
        $where['city'] = ['=',$city];
        $pagingData = HotelModel::with(['image','scenicArea','province','city','district'])
            ->where($where)
            ->paginate($size,true,['page'=>$page]);

        $resData = DistanceService::subhead($pagingData,$lat, $lon);
        return $resData;
    }


    public static function getHotelByPrice($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = null, $max_distance = null,$hotel_style_id = 0,$district,$scenicAreaID,$filtrateIDs,$name)
    {
        $where = [];
        if ($max_price)
        {
            $where['min_price'] = ['between',[$min_price,$max_price]];
        }else{
            $where['min_price'] = ['>',$min_price];
        }

        if ($lat && $lon && $max_distance){
            //以当前用户经纬度为中心,查询5000米内的其他用户
            $y = $max_distance / 110852; //纬度的范围
            $x = $max_distance / (111320*cos($lat)); //经度的范围
            $where['lat'] = ['between',[$lat-$y,$lat+$y]];
            $where['lon'] = ['between',[$lon-$x,$lon+$x]];
        }

        if ($hotel_style_id) {
            array_push($filtrateIDs,$hotel_style_id);
        }

        if ($district){
            $where['district'] = ['=',$district];
        }

        if ($scenicAreaID) {
            $where['scenic_area_id'] = ['=',$scenicAreaID];
        }

        if ($filtrateIDs) {
            $ids2 = Db::name('hotel_hotel_filtrate')->where('hotel_filtrate_id','in',$filtrateIDs)->group('hotel_id')->column('hotel_id');
            if (isset($ids)){
                $ids = array_unique(array_merge($ids,$ids2));
            }else{
                $ids = $ids2;
            }
            $where['id'] = ['in',$ids];
        }

        if ($name) {
            $where['name'] = ['like',"%$name%"];
        }

        $where['status'] = ['=',1];
        $where['city'] = ['=',$city];

        $pagingData = HotelModel::with(['image','scenicArea','province','city','district'])
            ->order('min_price asc')
            ->where($where)
            ->paginate($size,true,['page'=>$page]);

        $resData = DistanceService::subhead($pagingData,$lat, $lon);
        return $resData;
    }

    public static function getHotelByScore($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = null, $max_distance = null,$hotel_style_id = 0,$district,$scenicAreaID,$filtrateIDs,$name)
    {
        $where = [];
        if ($max_price)
        {
            $where['min_price'] = ['between',[$min_price,$max_price]];
        }else{
            $where['min_price'] = ['>',$min_price];
        }

        if ($lat && $lon && $max_distance){
            //以当前用户经纬度为中心,查询5000米内的其他用户
            $y = $max_distance / 110852; //纬度的范围
            $x = $max_distance / (111320*cos($lat)); //经度的范围
            $where['lat'] = ['between',[$lat-$y,$lat+$y]];
            $where['lon'] = ['between',[$lon-$x,$lon+$x]];
        }

        if ($hotel_style_id) {
            array_push($filtrateIDs,$hotel_style_id);
        }

        if ($district){
            $where['district'] = ['=',$district];
        }

        if ($scenicAreaID) {
            $where['scenic_area_id'] = ['=',$scenicAreaID];
        }

        if ($filtrateIDs) {
            $ids2 = Db::name('hotel_hotel_filtrate')->where('hotel_filtrate_id','in',$filtrateIDs)->group('hotel_id')->column('hotel_id');
            if (isset($ids)){
                $ids = array_unique(array_merge($ids,$ids2));
            }else{
                $ids = $ids2;
            }
            $where['id'] = ['in',$ids];
        }

        if ($name) {
            $where['name'] = ['like',"%$name%"];
        }

        $where['status'] = ['=',1];
        $where['city'] = ['=',$city];

        $pagingData = HotelModel::with(['image','scenicArea','province','city','district'])
            ->order('avg_score desc')
            ->where($where)
            ->paginate($size,true,['page'=>$page]);

        $resData = DistanceService::subhead($pagingData,$lat, $lon);
        return $resData;
    }

    public static function getHotelByDistance($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = null, $max_distance = null, $hotel_style_id = 0, $district = '',$scenicAreaID = 0,$filtrateIDs = [], $name = '')
    {
        if (empty($lat) || empty($lon)) {
            throw new ParameterException([
                'msg' => '经纬度不能为空'
            ]);
        }

        $where = [];
        if ($max_price)
        {
            $where['min_price'] = ['between',[$min_price,$max_price]];
        }else{
            $where['min_price'] = ['>=',$min_price];
        }

        if ($lat && $lon && $max_distance){
            //以当前用户经纬度为中心,查询5000米内的其他用户
            $y = $max_distance / 110852; //纬度的范围
            $x = $max_distance / (111320*cos($lat)); //经度的范围
            $where['lat'] = ['between',[$lat-$y,$lat+$y]];
            $where['lon'] = ['between',[$lon-$x,$lon+$x]];
        }

        if ($hotel_style_id) {
            array_push($filtrateIDs,$hotel_style_id);
        }

        if ($district){
            $where['district'] = ['=',$district];
        }

        if ($scenicAreaID) {
            $where['scenic_area_id'] = ['=',$scenicAreaID];
        }

        if ($filtrateIDs) {
            $ids2 = Db::name('hotel_hotel_filtrate')->where('hotel_filtrate_id','in',$filtrateIDs)->group('hotel_id')->column('hotel_id');
            if (isset($ids)){
                $ids = array_unique(array_merge($ids,$ids2));
            }else{
                $ids = $ids2;
            }
            $where['id'] = ['in',$ids];
        }

        if ($name) {
            $where['name'] = ['like',"%$name%"];
        }

        $where['status'] = ['=',1];
        $where['city'] = ['=',$city];
        $data = HotelModel::with(['image','scenicArea','province','city','district'])
            ->where($where)
            ->select()
            ->toArray();

        $dataArray = DistanceService::range($lat,$lon,$data);
        $pagingDataArray = page_array($size,$page,$dataArray);

        $pagingData = ['per_page' => $size,'current_page' => $page,'data' => $pagingDataArray];

        return $pagingData;
    }

}