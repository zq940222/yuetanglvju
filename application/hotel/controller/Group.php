<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 9:31
 */

namespace app\hotel\controller;


use app\hotel\model\Hotel;
use app\hotel\model\HotelRoomType;

class Group extends BaseController
{
    public function lists()
    {
        $admin = session('admin');
        $hotelModel = Hotel::get($admin['hotel_id']);
        $groupNum = $hotelModel->group_num;
        $roomModel = HotelRoomType::where('hotel_id','=',$admin['hotel_id'])
            ->select();
        $this->assign('group_num',$groupNum);
        $this->assign('room',$roomModel);
        return $this->fetch();
    }
}