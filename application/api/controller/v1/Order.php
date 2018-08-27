<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/1
 * Time: 14:43
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\GroupList;
use app\api\model\HotelAppointment;
use app\api\model\HotelComment;
use app\api\model\Order as OrderModel;
use app\api\model\ProductComment;
use app\api\model\User as UserModel;
use app\api\service\HotelAppointment as HotelAppointmentService;
use app\api\service\Token;
use app\api\validate\AppointmentHotel;
use app\api\validate\HotelCommentNew;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\PagingParameter;
use app\lib\enum\HotelAppointmentStatusEnum;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Log;


class Order extends BaseController
{
    public function getHotelAppointment($status = 0, $page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingHotelAppointments = HotelAppointment::getHotelAppointment($uid, $status, $page, $size);

        return $pagingHotelAppointments;
    }

    public function HotelAppointmentDetail($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $detail = HotelAppointment::get($id);
        if (!$detail) {
            throw new OrderException();
        }
        return $detail;
    }

    public function placeHotelAppointment()
    {
        $validate = new AppointmentHotel();
        $validate->goCheck();
        $dataArray = $validate->getDataByRule(input('post.'));

        $uid = Token::getCurrentUid();
        $order = new HotelAppointmentService();
        $status = $order->place($uid, $dataArray);
        return $status;
    }

    public function hotelComment()
    {
        $validate = new HotelCommentNew();
        $validate->goCheck();

        $param = $validate->getDataByRule(input('post.'));
        $image_name = input('image_name/a');
        $uid = Token::getCurrentUid();
        $order = HotelAppointment::get($param['order_id']);
        //新增评论
        $hotelComment = new HotelComment();
        $hotelComment->user_id = $uid;
        $hotelComment->order_id = $order->id;
        $hotelComment->overall_score = $param['overall_score'];
        $hotelComment->location_score = $param['location_score'];
        $hotelComment->service_score = $param['service_score'];
        $hotelComment->sanitation_score = $param['sanitation_score'];
        $hotelComment->facility_score = $param['facility_score'];
        $hotelComment->content = $param['content'];
        $hotelComment->room_type = $order['room_type'];
        $hotelComment->check_in_time = $order['check_in_time'];
        $hotelComment->hotel_id = $order['hotel_id'];
        $hotelComment->save();
        //新增评论图片
        if ($image_name) {
            $data = [];
            foreach ($image_name as $key => $item) {
                $data[$key]['url'] = $item;
            }
            $hotelComment->image()->saveAll($data);
        }
        //修改酒店评论总分数,平均分和评论数量
        $hotel = \app\api\model\Hotel::get($order['hotel_id']);
        $hotel->setInc('comment_num',1);
        $hotel->setInc('total_score',$param['overall_score']);
        $hotel->avg_score = round(($hotel->total_score/$hotel->comment_num),1);
        $hotel->save();
        //修改订单状态
        $order->status = HotelAppointmentStatusEnum::Used;
        $order->save();

        return json(new SuccessMessage(['msg' => '评论成功']));
    }

    public function getProductOrder($status = 0, $page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingProductOrders = OrderModel::getProductOrder($uid, $status, $page, $size);
        return $pagingProductOrders;
    }

    public function productOrderDetail($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $detail = OrderModel::with(['product','provinceName','cityName','districtName'])
            ->find($id);
        $detail['province'] = $detail->provinceName->name;
        $detail['city'] = $detail->cityName->name;
        $detail['district'] = $detail->districtName->name;
        if (!$detail) {
            throw new OrderException();
        }
        return $detail;
    }

    public function productComment()
    {
        $order_id = input('order_id');
        if (!$order_id) {
            throw new OrderException(['msg' => '订单不存在']);
        }
        $params = input('comments/a');
        $uid = Token::getCurrentUid();
        foreach ($params as $key => $value) {
            $productComment = new ProductComment();

            $productComment->user_id = $uid;
            $productComment->order_id = $order_id;
            $productComment->product_id = $value['product_id'];
            $productComment->score = $value['score'];
            $productComment->content = $value['content'];
            $productComment->save();
            //评论图片
            $image_name = $value['image_name'];
            if ($image_name) {
                $data = [];
                foreach ($image_name as $key => $item) {
                    $data[$key]['url'] = $item;
                }
                $productComment->image()->saveAll($data);
            }
            //修改产品评论数量
            $product = \app\api\model\Product::get($value['product_id']);
            $product->setInc('comment_num',1);
            $product->save();
        }

        //修改订单状态
        $order = \app\api\model\Order::get($order_id);
        $order->status = OrderStatusEnum::Finished;
        $order->save();
        return json(new SuccessMessage(['msg' => '评论成功']));
    }

    //取消超时订单
    public function overtime()
    {
        Db::startTrans();
        try{
            $groupListModel = new GroupList();
            $ids = $groupListModel->where('status','=',1)
                ->where('end_time','<=',time())
                ->column('id');
            $groupListModel->where('status','=',1)
                ->where('end_time','<=',time())
                ->update(['status'=>3]);
            $orderIDs = HotelAppointment::where('group_list_id','in',$ids)
                ->where('status','=',2)
                ->column('id');
            if ($orderIDs)
            {
                //TODO: 退款
                $refund = new Refund();
                $refund->groupOvertimeRefund($orderIDs);
                HotelAppointment::where('group_list_id','in',$ids)
                    ->update(['status'=> HotelAppointmentStatusEnum::Canceled]);
            }
            Db::commit();
            return true;
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            return false;
        }

    }

    public function getRefund()
    {
        $uid = Token::getCurrentUid();
        $refund = new \app\api\service\Refund();
        $result = $refund->refundList($uid);
        return $result;
    }

    public function appointmentCancel($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $orderModel = HotelAppointment::get($id);
        if ($orderModel->user_id != $uid || $orderModel->status != HotelAppointmentStatusEnum::UnPay)
        {
            return json(new OrderException(['msg' => '你没有权限这么做哦']));
        }
        else
        {
            $orderModel->status = HotelAppointmentStatusEnum::Canceled;
            $orderModel->save();
            return json(new SuccessMessage(['msg' => '已取消']));
        }

    }

    public function orderCancel($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $orderModel = OrderModel::get($id);
        if ($orderModel->user_id != $uid || $orderModel->status != OrderStatusEnum::UnPay)
        {
            return json(new OrderException(['msg' => '你没有权限这么做哦']));
        }
        else
        {
            $orderModel->status = OrderStatusEnum::Canceled;
            $orderModel->save();
            //用户积分结算
            if ($orderModel->integral != 0)
            {
                $userModel = UserModel::get($uid);
                $userModel->setInc('integral',$orderModel->integral);
            }
            return json(new SuccessMessage(['msg' => '已取消']));
        }

    }

    public function appointmentDelete($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $orderModel = HotelAppointment::get($id);
        if ($orderModel->user_id != $uid)
        {
            return json(new OrderException(['msg' => '你没有权限这么做哦']));
        }
        else
        {
            $orderModel->is_delete = 1;
            $orderModel->save();
            return json(new SuccessMessage(['msg' => '已删除']));
        }
    }

    public function receipt($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $orderModel = OrderModel::get($id);
        if ($orderModel->status != OrderStatusEnum::UnDelivery || $orderModel->user_id != $uid)
        {
            return json(new OrderException(['msg' => '你没有权限这么做哦']));
        }
        else
        {
            $orderModel->status = OrderStatusEnum::UnComment;
            $orderModel->save();
            return json(new SuccessMessage(['msg' => '操作成功']));
        }
    }

    public function orderDelete($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $uid = Token::getCurrentUid();
        $orderModel = OrderModel::get($id);
        if ($orderModel->user_id != $uid )
        {
            return json(new OrderException(['msg' => '你没有权限这么做哦']));
        }
        else
        {
            $orderModel->is_delete = 1;
            $orderModel->save();
            return json(new SuccessMessage(['msg' => '已删除']));
        }
    }
}