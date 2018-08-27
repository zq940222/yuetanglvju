<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 20:18
 */

namespace app\hotel\controller;


use app\hotel\model\Hotel;
use app\hotel\model\Image;
use app\hotel\model\Region;
use app\hotel\model\ScenicArea;
use app\lib\exception\SuccessMessage;

class Info extends BaseController
{
    public function lists()
    {
        $admin = session('admin');
        $hotelModel = Hotel::get($admin['hotel_id']);
        $this->assign('hotel',$hotelModel);
        return $this->fetch();
    }

    public function edit()
    {
        $admin = session('admin');
        $hotelModel = Hotel::get($admin['hotel_id']);
        if (request()->isPost()) {
            $postData = request()->post();
            if ($postData['image']) {
                $imageModel = new Image();
                $imageModel->url = $postData['image'];
                $imageModel->save();
                $hotelModel->img_id = $imageModel->id;
            }
            $hotelModel->name = $postData['name'];
            $hotelModel->province = $postData['province'];
            $hotelModel->city = $postData['city'];
            $hotelModel->district = $postData['district'];
            $hotelModel->detail_address = $postData['detail_address'];
            $hotelModel->scenic_area_id = $postData['scenic_area_id'];
            $hotelModel->remark = $postData['remark'];
            $hotelModel->phone = $postData['phone'];
            $hotelModel->min_price = $postData['min_price'];
            $hotelModel->group_num = $postData['group_num'];
            if ($postData['city'] && $postData['district'] && $postData['detail_address'])
            {
                $cityName = Region::get($postData['city']);
                $districtName = Region::get($postData['district']);
                $result = getLatLng($districtName['name'].$postData['detail_address'],$cityName['name']);
                $hotelModel->lat = $result['lat'];
                $hotelModel->lon = $result['lng'];
            }
            $hotelModel->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $region = Region::getRegion();
        $this->assign('region',$region);
        $city = Region::getRegion($hotelModel['province'])->toArray();
        $this->assign('city',$city);
        $district = Region::getRegion($hotelModel['city'])->toArray();
        $this->assign('district',$district);
        $scenicArea = ScenicArea::all();
        $this->assign('scenic_area',$scenicArea);
        $this->assign('hotel',$hotelModel);
        return $this->fetch();
    }
}