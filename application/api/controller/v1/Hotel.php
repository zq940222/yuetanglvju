<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 14:38
 */

namespace app\api\controller\v1;


use app\api\model\BookingStatistics;
use app\api\model\GroupList;
use app\api\model\Hotel as HotelModel;
use app\api\model\HotelFiltrate;
use app\api\model\HotelImgCategory;
use app\api\model\HotelRoomType;
use app\api\model\HotelStyle;
use app\api\model\Region;
use app\api\model\ScenicArea;
use app\api\service\Attention;
use app\api\service\Hotel as HotelService;
use app\api\service\HotelComment;
use app\api\validate\City;
use app\api\validate\GetRoom;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\PagingParameter;
use app\lib\exception\HotelException;
use app\lib\exception\SuccessMessage;
use think\Cache;
use think\Db;
use think\Request;

class Hotel
{
    /**
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @param int $min_price
     * @param int $max_price
     * @param int $max_distance
     * @param int $hotel_style_id
     * @return mixed
     * @throws HotelException
     * @throws \app\lib\exception\ParameterException
     */
    public function getRecommendHotel($city, $page = 1, $size = 10, $lat = null, $lon = null, $min_price = 0, $max_price = 0, $max_distance = 0, $hotel_style_id = 0)
    {
        // 有经纬度则显示距离 ,没有则显示位置
        (new PagingParameter())->goCheck();
        (new City())->goCheck();
        $where = [];
        $where['level_type'] = ['=',2];
        $where['name|id'] = ['=',$city];
        $city = model('Region')->where($where)->find();
        $hotelData = HotelService::getRecommendHotel($city['id'], $page, $size, $lat, $lon,$min_price, $max_price, $max_distance, $hotel_style_id);

        return $hotelData;

    }

    /**
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @param int $min_price
     * @param null $max_price
     * @param null $max_distance
     * @param int $hotel_style_id
     * @return mixed
     * @throws HotelException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\exception\DbException
     */
    public function getHotel($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = null, $max_distance = null, $hotel_style_id = 0)
    {
        // 有经纬度则显示距离 ,没有则显示位置
        (new PagingParameter())->goCheck();
        (new City())->goCheck();
        $where['level_type'] = ['=',2];
        $where['name|id'] = ['=',$city];
        $city = model('Region')->where($where)->find();

        $district = input('param.district/s','');
        $scenicAreaID = input('param.scenic_area_id/d',0);
        $filtrateIDs = input('param.filtrate_ids/s','');
        if ($filtrateIDs) {
            $filtrateIDs = explode(',',$filtrateIDs);
        }
        $name = input('param.name/s','');
        $hotelData = HotelService::getHotel($city['id'], $page, $size, $lat, $lon,$min_price,$max_price,$max_distance,$hotel_style_id,$district,$scenicAreaID,$filtrateIDs,$name);

        return $hotelData;

    }

    /**
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @param int $min_price
     * @param null $max_price
     * @param null $max_distance
     * @param int $hotel_style_id
     * @return mixed
     * @throws HotelException
     * @throws \app\lib\exception\ParameterException
     */
    public function getHotelByPrice($city, $page = 1, $size = 10, $lat = null, $lon = null, $min_price = 0, $max_price = null, $max_distance = null,$hotel_style_id = 0)
    {
        (new PagingParameter())->goCheck();
        (new City())->goCheck();
        $where['level_type'] = ['=',2];
        $where['name|id'] = ['=',$city];
        $city = model('Region')->where($where)->find();
        $district = input('param.district/s','');
        $scenicAreaID = input('param.scenic_area_id/d',0);
        $filtrateIDs = input('param.filtrate_ids/a',[]);
        $name = input('param.name/s','');
        $hotelData = HotelService::getHotelByPrice($city['id'], $page, $size, $lat, $lon,$min_price,$max_price,$max_distance,$hotel_style_id,$district,$scenicAreaID,$filtrateIDs,$name);

        return $hotelData;
    }

    /**
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @param int $min_price
     * @param null $max_price
     * @param null $max_distance
     * @param int $hotel_style_id
     * @return mixed
     * @throws HotelException
     * @throws \app\lib\exception\ParameterException
     */
    public function getHotelByScore($city, $page = 1, $size = 10, $lat = null, $lon = null,$min_price = 0, $max_price = null, $max_distance = null,$hotel_style_id = 0)
    {
        (new PagingParameter())->goCheck();
        (new City())->goCheck();

        $where['level_type'] = ['=',2];
        $where['name|id'] = ['=',$city];
        $city = model('Region')->where($where)->find();

        $district = input('param.district/s','');
        $scenicAreaID = input('param.scenic_area_id/d',0);
        $filtrateIDs = input('param.filtrate_ids/a',[]);
        $name = input('param.name/s','');
        $hotelData = HotelService::getHotelByScore($city['id'], $page, $size, $lat, $lon,$min_price,$max_price,$max_distance,$hotel_style_id, $district,$scenicAreaID,$filtrateIDs,$name);

        return $hotelData;
    }

    /**
     * @param $city
     * @param int $page
     * @param int $size
     * @param null $lat
     * @param null $lon
     * @param int $min_price
     * @param null $max_price
     * @param null $max_distance
     * @param int $hotel_style_id
     * @return array
     * @throws HotelException
     * @throws \app\lib\exception\ParameterException
     */
    public function getHotelByDistance($city, $page = 1, $size = 10, $lat = null, $lon = null, $min_price = 0, $max_price = null, $max_distance = null,$hotel_style_id = 0)
    {
        (new PagingParameter())->goCheck();
        (new City())->goCheck();

        $where['level_type'] = ['=',2];
        $where['name|id'] = ['=',$city];
        $city = model('Region')->where($where)->find();

        $district = input('param.district/s','');
        $scenicAreaID = input('param.scenic_area_id/d',0);
        $filtrateIDs = input('param.filtrate_ids/a',[]);
        $name = input('param.name/s','');
        $hotelData = HotelService::getHotelByDistance($city['id'], $page, $size, $lat, $lon,$min_price,$max_price,$max_distance,$hotel_style_id, $district,$scenicAreaID,$filtrateIDs,$name);

        return $hotelData;
    }

    public function getHotelStyle()
    {
        $pID = HotelFiltrate::where('name','=',"类型")->find();
        $style = HotelFiltrate::where('parent_id','=',$pID['id'])->field('id,name')->select();
        return $style;
    }

    public function getScenicArea($city)
    {
        (new City())->goCheck();
        $data = ScenicArea::hasWhere('city',['name'=>['like',"%$city%"]])
            ->select();
        return $data;
    }

    public function getDistrict($city)
    {
        (new City())->goCheck();
        $cityData = Region::where('name','=',$city)
            ->where('level_type','=',2)->find();
        $data = Region::where('parent_id','=',$cityData['id'])->select();
        return $data;
    }

    public function getHotelFiltrate()
    {
        $data = HotelFiltrate::getHotelFiltrateByTree();
        return $data;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws HotelException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHotelSingle($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $data = HotelModel::with(['image','province','city','district'])->find($id);
        //查询收藏
        $is_attention = 0;
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if ($vars) {
            if (!is_array($vars)) {
                $vars = json_decode($vars,true);
            }
            $user_id = $vars['uid'];
            $attention = Db::name('user_hotel_attention')
                ->where('user_id','=',$user_id)
                ->where('hotel_id','=',$id)
                ->find();
            if ($attention) {
                $is_attention = 1;
            }
        }
        $data['avg_score'] = HotelComment::hotelScore($id);
        $data['is_attention'] = $is_attention;
        if (!$data) {
            throw new HotelException([
                'msg' => '请求酒店不存在'
            ]);
        }
        return $data;
    }

    /**
     * @param $hotel_id
     * @param $check_in_time
     * @param $check_out_time
     * @return array
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getHotelRoom($hotel_id, $check_in_time, $check_out_time)
    {
        (new GetRoom())->goCheck();

        $rooms = HotelRoomType::with(['image'])
            ->where('hotel_id','=',$hotel_id)
            ->select()
            ->toArray();
        foreach ($rooms as $key => &$value) {
            $value['surplus_amount'] = BookingStatistics::getRoomSurplus($check_in_time,$check_out_time,$value['id']);
        }

        return $rooms;
    }

    /**
     * @param $id
     * @return false|static[]
     * @throws \app\lib\exception\ParameterException
     */
    public function getHotelImage($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $data = HotelImgCategory::getHotelImg($id);
        return $data;
    }

    /**
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRoom($id,$check_in_time, $check_out_time)
    {
        (new IDMustBePostiveInt())->goCheck();
        $data = HotelRoomType::getRoom($id,$check_in_time,$check_out_time);
        return $data;
    }

    /**
     * @desc 酒店拼团列表
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getGroupLists($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $groupLists = GroupList::getGroupLists($id);

        return $groupLists;
    }

    /**
     * @desc 酒店评论
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getComment($id, $page = 1, $size = 10)
    {
        (new IDMustBePostiveInt())->goCheck();
        $data = HotelComment::hotelComment($id, $page, $size);
        return $data;
    }

    /**
     * @param $id
     * @return SuccessMessage
     * @throws HotelException
     */
    public function clickAttention($id)
    {
        //检查登录
        //检查是否关注
        $result = Attention::changeHotelAttention($id);
        if (!$result) {
            throw new HotelException([
                '收藏酒店失败,请稍后再试'
            ]);
        }
        return (new SuccessMessage());
    }
}