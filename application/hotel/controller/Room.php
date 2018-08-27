<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/1
 * Time: 10:57
 */

namespace app\hotel\controller;


use app\hotel\model\HotelFiltrate;
use app\hotel\model\HotelRoomType;
use app\hotel\model\Image;
use app\hotel\model\RoomFacility;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;
use think\Db;

class Room extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $admin = session('admin');
        $rooms = HotelRoomType::where('hotel_id','=',$admin['hotel_id'])
            ->page($page,$limit)
            ->select();
        $count = HotelRoomType::where('hotel_id','=',$admin['hotel_id'])
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$rooms]);
    }

    public function add()
    {
        $admin = session('admin');
        if (request()->isPost()) {
            $postData = request()->post();
            $roomModel = new HotelRoomType();
            if (!$postData['image']) {
                throw new HotelAdminException(['msg' => '请至少传一张图片']);
            }
            $roomModel->hotel_id = $admin['hotel_id'];
            $roomModel->room_type = $postData['room_type'];
            $roomModel->price = $postData['price'];
            $isGroup = input('post.is_group/d',0);
            $roomModel->is_group = $isGroup;
            if ($isGroup){
                $roomModel->group_price = $postData['group_price'];
            }else{
                $roomModel->group_price = null;
            }

            $roomModel->stock = $postData['stock'];
            $roomModel->room_remark = $postData['remark'];
            $roomModel->refund_rule = $postData['refund_rule'];
            $roomModel->regulation = $postData['regulation'];
            $roomModel->save();
            $imageArray = [];
            foreach ($postData['image'] as $key => $value) {
                $imageArray[$key]['url'] = $value;
            }
            $roomModel->image()->saveAll($imageArray);
            $facilityArray = [];
            foreach ($postData['facility_id'] as $key => $value) {
                $facilityArray[$key]['room_facility_id'] = $value;
                $facilityArray[$key]['hotel_room_type_id'] = $roomModel->id;
            }
            Db::name('HotelRoomTypeRoomFacility')->insertAll($facilityArray);
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $facility = RoomFacility::getByTree();
        $this->assign('facility',$facility);
        $pID = HotelFiltrate::where('name','=','房型')->find();
        $roomType = HotelFiltrate::where('parent_id','=',$pID['id'])->select();
        $this->assign('room_type',$roomType);
        return $this->fetch();
    }

    public function detail($id)
    {
        $admin = session('admin');
        $room = HotelRoomType::get($id,['image','facility','facility.parentCate']);
        if ($room->hotel_id != $admin['hotel_id']) {
            throw new HotelAdminException(['msg' => '你没有权限这么做']);
        }
        $this->assign('room',$room);
        return $this->fetch();
    }

    public function edit($id)
    {
        $admin = session('admin');
        $roomModel = HotelRoomType::get($id,['image']);
        if ($roomModel->hotel_id != $admin['hotel_id']) {
            throw new HotelAdminException(['msg' => '你没有权限这么做']);
        }
        if (request()->isPost()) {
            $postData = request()->post();

            $roomModel->hotel_id = $admin['hotel_id'];
            $roomModel->room_type = $postData['room_type'];
            $roomModel->price = $postData['price'];
            $isGroup = input('post.is_group/d',0);
            $roomModel->is_group = $isGroup;
            if ($isGroup){
                $roomModel->group_price = $postData['group_price'];
            }else{
                $roomModel->group_price = null;
            }

            $roomModel->stock = $postData['stock'];
            $roomModel->room_remark = $postData['remark'];
            $roomModel->refund_rule = $postData['refund_rule'];
            $roomModel->regulation = $postData['regulation'];
            $roomModel->save();
            if ($postData['image']) {
                $imageArray = [];
                foreach ($postData['image'] as $key => $value) {
                    $imageArray[$key]['url'] = $value;
                }
                $roomModel->image()->saveAll($imageArray);
            }

            $facilityArray = [];
            foreach ($postData['facility_id'] as $key => $value) {
                $facilityArray[$key]['room_facility_id'] = $value;
                $facilityArray[$key]['hotel_room_type_id'] = $roomModel->id;
            }
            Db::name('HotelRoomTypeRoomFacility')->where('hotel_room_type_id','=',$id)->delete();
            Db::name('HotelRoomTypeRoomFacility')->insertAll($facilityArray);
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $roomFacilityIDs = Db::name('HotelRoomTypeRoomFacility')->where('hotel_room_type_id','=',$id)->column('room_facility_id');
        $this->assign('room_facility_ids',$roomFacilityIDs);
        $this->assign('room',$roomModel);
        $facility = RoomFacility::getByTree();
        $this->assign('facility',$facility);
        $pID = HotelFiltrate::where('name','=','房型')->find();
        $roomType = HotelFiltrate::where('parent_id','=',$pID['id'])->select();
        $this->assign('room_type',$roomType);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = HotelRoomType::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new HotelAdminException(['msg' => '删除失败']);
        }
    }

    public function del_img($id)
    {
        Image::destroy($id);
        Db::name('HotelRoomTypeImg')->where('img_id','=',$id)->delete();
        return json(new SuccessMessage(['msg' => '已删除']));
    }
}