<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/26
 * Time: 17:04
 */

namespace app\api\service;


use app\api\model\Order;
use app\api\model\OrderProduct;
use app\api\model\Region;
use app\lib\exception\OrderException;

class PlaceOrder
{
    private $userNote;
    private $pay;
    private $order;
    private $userAddress;

    /**
     * PlaceOrder constructor.
     * @param Pay $pay
     */
    public function __construct(Pay $pay)
    {
        $this->pay = $pay;
        $this->order = new Order();
    }

    public function setUserNote($userNote)
    {
        $this->userNote = $userNote;
    }

    public function setUserAddress($userAddress)
    {
        $this->userAddress = $userAddress;
    }

    public function addNormalOrder()
    {
        $this->check();
        $this->addOrder();
        $this->addOrderGoods();
    }

    /**
     * 提交订单前检查
     */
    public function check()
    {
        $pay_points = $this->pay->getPayPoints();
        if ($pay_points) {
            $user = $this->pay->getUser();
            if ($pay_points > $user['integral']) {
                throw new OrderException(['msg' => '积分不足']);
            }
        }

    }

    /**
     * 插入订单表
     */
    private function addOrder()
    {
        $OrderLogic = new OrderLogic();
        $user = $this->pay->getUser();

        $orderData = [
            'order_no'         =>$OrderLogic->get_order_sn(), // 订单编号
            'user_id'          =>$user['id'], // 用户id
            'product_total_price'    =>$this->pay->getGoodsPrice(),//'商品价格',
            'shipping_price'   =>$this->pay->getShippingPrice(),//'物流价格',
//            'coupon_price'     =>$this->pay->getCouponPrice(),//'使用优惠券',
            'integral'         =>$this->pay->getPayPoints(), //'使用积分',
            'integral_money'   =>$this->pay->getIntegralMoney(),//'使用积分抵多少钱',
            'order_total_price'     =>$this->pay->getTotalAmount(),// 订单总额
            'pay_price'     =>$this->pay->getOrderAmount()//'应付款金额',
        ];
        if(!empty($this->userAddress)){
            $orderData['consignee'] = $this->userAddress['consignee'];// 收货人
            $orderData['province'] = $this->userAddress['province'];//'省份id',
            $orderData['city'] = $this->userAddress['city'];//'城市id',
            $orderData['district'] = $this->userAddress['district'];//'县',
            $orderData['detail_address'] = $this->userAddress['detail'];//'详细地址'
            $orderData['mobile'] = $this->userAddress['mobile'];//'手机',
        }
        if(!empty($this->userNote)){
            $orderData['user_remark'] = $this->userNote;// 用户下单备注
        }

        $this->order->data($orderData, true);
        $orderSaveResult = $this->order->save();
        if ($orderSaveResult === false) {
            throw new OrderException(['msg' => '添加订单失败']);
        }
    }

    /**
     * 插入订单商品表
     */
    private function addOrderGoods()
    {
        $payList = $this->pay->getPayList();
        $orderGoodsAllData = [];
        foreach ($payList as $payKey => $payItem) {
            $orderGoodsData['order_id'] = $this->order['id']; // 订单id
            $orderGoodsData['product_id'] = $payItem['product_id']; // 商品id
            $orderGoodsData['product_name'] = $payItem['product_name']; // 商品名称
            $orderGoodsData['product_num'] = $payItem['product_num']; // 购买数量
            $orderGoodsData['product_price'] = $payItem['product_price']; // 商品价               为照顾新手开发者们能看懂代码，此处每个字段加于详细注释
            $orderGoodsData['product_image'] = $payItem['product_image'];
            if (!empty($payItem['spec_key'])) {
                $orderGoodsData['spec_key'] = $payItem['spec_key']; // 商品规格
                $orderGoodsData['spec_key_name'] = $payItem['spec_key_name']; // 商品规格名称
            } else {
                $orderGoodsData['spec_key'] = ''; // 商品规格
                $orderGoodsData['spec_key_name'] = ''; // 商品规格名称
            }
            array_push($orderGoodsAllData, $orderGoodsData);
        }
        $OrderProduct = new OrderProduct();
        $OrderProduct->saveAll($orderGoodsAllData);
    }

    /**
     * 获取订单表数据
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
}