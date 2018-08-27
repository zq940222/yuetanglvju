<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/30
 * Time: 19:14
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\HotelAppointment;
use app\api\model\User as UserModel;
use app\api\model\UserAccountDetails;
use app\api\service\AliRefund;
use app\lib\enum\HotelAppointmentStatusEnum;
use think\Db;
use think\Exception;
use think\Log;
use app\api\service\WxRefund as WxRefundService;

class Refund extends BaseController
{
    /**
     * @desc 拼团过期退款
     */
    public function groupOvertimeRefund($orderIDs)
    {
        foreach ($orderIDs as $orderID)
        {
            $this->HotelOrderRefund($orderID);
        }
    }

    /**
     * @desc 订单退款
     * @param $orderID
     */
    public function HotelOrderRefund($orderID)
    {
        $HotelAppointmentModel = HotelAppointment::get($orderID);
        if ($HotelAppointmentModel->pay_channel == 1){//余额
            Db::startTrans();
            try{
                //修改用户余额
                $userModel = UserModel::get($HotelAppointmentModel->user_id);
                $userModel->setInc('balance',$HotelAppointmentModel->total_price);
                //修改订单状态
                $HotelAppointmentModel->status = HotelAppointmentStatusEnum::HasUnsubscribe;
                $HotelAppointmentModel->save();
                //修改账单记录
                UserAccountDetails::create([
                    'user_id' => $HotelAppointmentModel->user_id,
                    'type' => 2,
                    'money' => $HotelAppointmentModel->total_price,
                    'status' => 2
                ]);
                Db::commit();
                return true;
            }
            catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                return false;
            }

        }elseif ($HotelAppointmentModel->pay_channel == 2){//微信
            Db::startTrans();
            try{
                $wxRefundService = new WxRefundService();
                $result = $wxRefundService->wxRefund($HotelAppointmentModel->total_price,$HotelAppointmentModel->order_no);
                //修改订单状态
                if ($result['return_code'] == 'SUCCESS') {
                    $HotelAppointmentModel->status = HotelAppointmentStatusEnum::HasUnsubscribe;
                    $HotelAppointmentModel->save();
                }
                Db::commit();
                return true;
            }
            catch (Exception $ex){
                Db::rollback();
                Log::error($ex);
                return false;
            }

        }elseif ($HotelAppointmentModel->pay_channel == 3){//支付宝
            Db::startTrans();
            try{
                $aliRefundService = new AliRefund();
                $result = $aliRefundService->refund($HotelAppointmentModel->total_price,$HotelAppointmentModel->order_no);
                //修改订单状态
                if ($result) {
                    $HotelAppointmentModel->status = HotelAppointmentStatusEnum::HasUnsubscribe;
                    $HotelAppointmentModel->save();
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
    }
}