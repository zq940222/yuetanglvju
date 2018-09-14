<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/3
 * Time: 16:05
 */

namespace app\api\service;

use app\api\model\Order;
use app\api\model\Recharge;
use app\lib\enum\HotelAppointmentStatusEnum;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use app\api\service\HotelAppointment as HotelAppointmentService;
use app\api\model\HotelAppointment as HotelAppointmentModel;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class WxPay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID) {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    public function hotelPay()
    {
        $this->checkOrderValid();
        $orderService = new HotelAppointmentService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice'], $this->orderNO, $type = 'hotel');
    }

    public function productPay()
    {
        $this->checkProductOrderValid();
        //产品订单支付
        $orderService = new OrderLogic();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice'], $this->orderNO, $type = 'product');
    }

    //充值
    public function recharge()
    {
        $RechargeModel = Recharge::get($this->orderID)->toArray();
        $this->orderNO = $RechargeModel['order_no'];
        return $this->makeWxPreOrder($RechargeModel['total_price'], $this->orderNO, $type = 'recharge');
    }

    private function checkProductOrderValid()
    {
        $order = Order::get($this->orderID);
        if (!$order) {
            throw new OrderException();
        }
        if (!Token::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != OrderStatusEnum::UnPay) {
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

    private function checkOrderValid()
    {
        $order = HotelAppointmentModel::get($this->orderID);
        if (!$order) {
            throw new OrderException();
        }
        if (!Token::isValidOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if ($order->status != HotelAppointmentStatusEnum::UnPay) {
            throw new OrderException([
                'msg' => '订单已支付过啦',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

    private function makeWxPreOrder($totalPrice, $orderNo, $type)
    {
//        $totalPrice = 0.01;
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($orderNo);
        $wxOrderData->SetTrade_type('APP');
        $wxOrderData->SetTotal_fee($totalPrice * 100);
        $wxOrderData->SetBody('悦棠旅居');
        $wxOrderData->SetNotify_url(config('secure.wx_pay_back_url'));
        $wxOrderData->SetAttach($type);
        return $this->getPaySignature($wxOrderData);
    }

    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
        }
        //prepay_id
//        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function sign($wxOrder)
    {
        $data['appid'] = 'wx8bd4d67ff9f63a53';
        $data['partnerid'] = '1510735831';
        $data['prepayid'] = $wxOrder['prepay_id'];
        $data['package'] = 'Sign=WXPay';
        $rand = md5(time().mt_rand(0,1000));
        $data['noncestr'] = $rand;
        $data['timestamp'] = time();
        $data['sign'] = $this->MakeSign( $data );
        return $data;

    }

    /**
     * 生成签名
     * @return 签名
     */
    public function MakeSign($params)
    {
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = trim($string . "&key=w1FkPs9SNC4GTi1P8IatsD2I7jOCyBwf");
        //var_dump($string);die;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;

    }

    /**
     * 将参数拼接为url: key=value&key=value
     * @param  $params
     * @return string
     */
    public function ToUrlParams($params)
    {
        $string = '';
        if (!empty($params)) {
            $array = array();
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }
}