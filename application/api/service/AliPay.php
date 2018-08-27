<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/25
 * Time: 17:01
 */

namespace app\api\service;

use app\api\model\Order;
use app\api\model\Recharge;
use app\api\model\User;
use app\lib\enum\HotelAppointmentStatusEnum;
use app\lib\enum\OrderStatusEnum;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use app\api\service\HotelAppointment as HotelAppointmentService;
use app\api\model\HotelAppointment as HotelAppointmentModel;

Loader::import('AliPay.aop.AopClient', EXTEND_PATH, '.php');
Loader::import('AliPay.aop.request.AlipayTradeAppPayRequest', EXTEND_PATH, '.php');

class AliPay
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
        $notifyUrl = config('secure.alipay_hotel_back_url');
        return $this->makeAliPreOrder($status['orderPrice'], $this->orderNO,$notifyUrl);
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
        $orderModel = Order::get($this->orderID);
        $notifyUrl = config('secure.alipay_product_back_url');
        return $this->makeAliPreOrder($orderModel->pay_price, $this->orderNO,$notifyUrl);
    }

    //充值
    public function recharge()
    {
        $notifyUrl = config('secure.alipay_recharge_back_url');
        $RechargeModel = Recharge::get($this->orderID)->toArray();
        $this->orderNO = $RechargeModel['order_no'];
        return $this->makeAliPreOrder($RechargeModel['total_price'], $this->orderNO,$notifyUrl);
    }

    private function checkProductOrderValid()
    {
        $order = Order::get($this->orderID);
        $uid = Token::getCurrentUid();
        $userModel = User::get($uid);
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

        if ($order->integral > $userModel->integral)
        {
            throw new OrderException(['msg' => '积分已被消费,请重新下单']);
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

    public function makeAliPreOrder($totalAmount, $outTradeNo,$notifyUrl, $subject = '深兰悦棠旅居', $body = '深兰悦棠旅居', $timeoutExpress = '30m', $product_code = 'QUICK_MSECURITY_PAY')
    {
        //测试数据
        $totalAmount = 0.01;
        $aop = new \AopClient();
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = "2018070460542343";
        $aop->rsaPrivateKey = "MIIEogIBAAKCAQEAvQe3PWZIykbrxtLSWxGp/CS6fmem8sirOtnVNfwpkK5e6fi52aVC1DMGAYhZFzRUsLVOpIsn7jOXY9N4bDxLf098InSZichmeV4IlgjmxTE2YXWsYajNqqJoCmZm3ieed4MP3Gc1YdUSGv+b/cYqNlvOGQyhvkYUJBLWzJcwU9fgvhMXuJoQ7M5N2jmTBZcUg0j0F7jh0oU2hhYh5BPPdrng648ABbW9eMMSuBh2lPHOKs7+x3Lm/UFnNxrMam66q6gRyFR96UB6PdIDMEsmMYSQpq6jznDOFF0cUjl3FCsXMwbyq97LUOtbMdeI/6qLweE8uulyryABTeqxQqpQ/QIDAQABAoIBABh59UooAmjewgzeo4pTQTV69AMGHOH3BeT669avrhoj2fpl0HrUIVEkwjRUmWSdzBGNiH9Z3XPEjmfIrCEntYboneRAQNlMb6hreqUixe7mrmn0OLv0hZ0ApoQiOlOtwaEsAVPCsXDXjB6e1m4HyNN9E7S+o/rlTBpXriSTtxhD9LVbUx74vou1DB9DMXHgVGJa9IAunGUkw901zAUROeMiGiqTJrrq8Eqv+LVy7esfd8vJlcMNLGBbE5RtoY+iKnhP6F5bpqwL+nqRiR7UobQ9/TTComg2pkAlfPNCN6C+EFxVCcwoNqKprfJk26twq319JVEe9FguOt4QaMgyasECgYEA51bY2Ts3nRwImJcAKqhk+8IXUze7dEz6mi2iZI24AyJ+tSg2ohFGyIl0sLuD8Wx2VTVpEZb2Q87gtp1J4SzuOVEizECmNULuq110JPyahcg15tewiXpzdSuRgFe6LiQsjh4iBv1Of7dVz6XM8IFY0PEyo2sodwZQu6Mgdk2NI+kCgYEA0S5FRVvnJJUdE+sYkHRPiDuDHV8U4W6US1EZ/B6h0WLkjS7h1JIZEGX5/iZ2nP0exU3tgxoTEasLriReSIoyES1Ri1RGarADicbYPG8Bo9L2aQv+6Y1WBnfbUtSSYl6oSLFMwLAC6EM8WiF3URvIUdP7Mxi4boCcBDH2IN/me/UCgYBQX1Ltfe5fbirqYKPVLjYPZapW5ikBSfFS+YHO75G7vRNKexMoEVqHN4JMGInJqcYe6nR7gPhELK7ToyfUzJhjX3X4gol8PanP7aL5aq2Ax0M61TrnOJy+W4msjk4H09eK9Jsb1IueQaLVhqQB9t5VkUbnkcY4PAB2gEE5+M2NaQKBgAiUXUL7Af/+HbMzcU57dsefqUELJVAZuPtd2DL/DqQH6lfgFGMjmuORSy+hZDwMJbbx+0vlReLzoQcDdtqC0Irj0PRmAH1fusVr4nKYGvkdLf4g/9OUeHLLd7NuBJMETuKsYvmEPppIJ7GKrdolyZGRoDv4R5hAriV95xpyFIIBAoGAO9Y0ZTd46zSim9bXC4qyQgTT+aBheoiEhph6rydk2Dk+WXXFi8qp7Ko9LX9KReFRAV3avExnBWtulBBnzDRmzh6qiIn9SlsgVeH9Ym0bNcYrQaXHgNWVcsWgulWbaQ4iFnw3+ccPtcPM5AmYJnNTZ+CqUAuvRle+xI6Bno+wH30=";
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAudjeCjTYyDO6z2hHnUr+/R7Wz6PwpH20FTADbdgpUJu+3sC42jo/DZsSYem8UCgO8Q2lM0HzzcXgUOgJVwft8arr9pqCuc7JeqAR2G4JweQKJI81jFtTy8ZFT1n5YmjdSy3OOUyb235560FLT3+0HAuRlKV3+yGeA3zHC7ymRR2KGvCz2hkk+Tt2woP8w+j4pU0t+WQoaUllgtXg/SPlGJNKPMjxAqihK7le2Hlt6t5P+BdqKgxb50SGpiGETrnbZpGkOlHtJjrmqBgTRQbmLUmK7qDGSXiZmwfwLftPykmNwpb7wJL6a98NHDixB+WO6TPc8zanZEjgyWQiw5HEbQIDAQAB";
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay

        $request = new \AlipayTradeAppPayRequest();
//SDK已经封装掉了公共参数，这里只需要传入业务参数
//        $bizcontent = "{\"body\":\"我是测试数据\","
//            . "\"subject\": \"App支付测试\","
//            . "\"out_trade_no\": \"2017012501\","
//            . "\"timeout_express\": \"30m\","
//            . "\"total_amount\": \"0.01\","
//            . "\"product_code\":\"QUICK_MSECURITY_PAY\""
//            . "}";
        $bizcontent = "{\"body\":\"".$body."\","
            . "\"subject\": \"".$subject."\","
            . "\"out_trade_no\": \"".$outTradeNo."\","
            . "\"timeout_express\": \"".$timeoutExpress."\","
            . "\"total_amount\": \"".$totalAmount."\","
            . "\"product_code\":\"".$product_code."\""
            . "}";
        $request->setNotifyUrl($notifyUrl);
        $request->setBizContent($bizcontent);
//这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);
//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        return $response;//就是orderString 可以直接给客户端请求，无需再做处理。
    }
}