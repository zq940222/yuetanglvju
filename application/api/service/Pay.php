<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/26
 * Time: 10:23
 */

namespace app\api\service;


use app\api\model\Cart;
use app\api\model\Product;
use app\api\model\SpecProductPrice;
use app\api\model\User;
use app\api\model\UserCoupon;
use app\lib\exception\CartException;
use app\lib\exception\PayException;

class Pay
{
    protected $payList;
    protected $userId;
    protected $user;
    protected $cartIds = [];

    private $totalAmount = 0;//订单总价
    private $orderAmount = 0;//应付金额
    private $shippingPrice = 0;//物流费
    private $goodsPrice = 0;//商品总价
    private $totalNum = 0;// 商品总共数量
    private $integralMoney = 0;//积分抵消金额
    private $payPoints = 0;//使用积分
    private $couponPrice = 0;//优惠券抵消金额

    private $couponId;

    public function __construct()
    {
        $uid = Token::getCurrentUid();
        $this->userId = $uid;
        $this->user = User::get($uid);
    }

    public function clearCart()
    {
        Cart::where('id','IN',$this->cartIds)->delete();
    }

    /**
     * 计算购买商品表的商品
     * @param $goods_list
     */
    public function payGoodsList($products_list)
    {
        for ($i = 0; $i < count($products_list); $i++) {
            $item_id = $products_list[$i]['item_id'];
            $product_id = $products_list[$i]['product_id'];
            $product_num = $products_list[$i]['product_num'];
            $cart_id = $products_list[$i]['cart_id'];
            array_push($this->cartIds,$cart_id);
            $productModel = new Product();
            $product = $productModel->get($product_id,['coverImage']);
            $specProductPriceModel = new SpecProductPrice();
            $spec_key = '';
            $spec_key_name = '';
            if($item_id){
                $specProductPrice = $specProductPriceModel->find($item_id);
                if ($specProductPrice->product_id != $product_id) {
                    throw new PayException(['msg' => '规格不正确哟']);
                }
                $price = $specProductPrice['price'];
                $store_count = $specProductPrice['store'];
                $spec_key = $specProductPrice['key'];
                $spec_key_name = $specProductPrice['key_name'];
            }else{
                $specProductPrice = $specProductPriceModel->where('product_id',$product_id)->find();
                if ($specProductPrice){
                    throw new PayException(['msg' => '必须传入规格']);
                }

                $price = $product['min_price'];
                $store_count = $product['store'];
            }
            if ($product_num > $store_count) {
                throw new PayException(['msg' => $product['title'].'库存不足']);
            }
            $products_list[$i]['product_price'] = $price;
            $products_list[$i]['product_name'] = $product['title'];
            $products_list[$i]['spec_key'] = $spec_key;
            $products_list[$i]['spec_key_name'] = $spec_key_name;
            $products_list[$i]['product_image'] = $product->coverImage->url;
        }

        $this->payList = $products_list;
        $this->Calculation();
    }

    /**
     * 初始化计算
     */
    private function Calculation()
    {
        $goodsListCount = count($this->payList);
        for ($payCursor = 0; $payCursor < $goodsListCount; $payCursor++) {
            $this->payList[$payCursor]['goods_fee'] = $this->payList[$payCursor]['product_num'] * $this->payList[$payCursor]['product_price'];    // 小计
            $this->goodsPrice += $this->payList[$payCursor]['goods_fee']; // 商品总价

            $this->totalNum += $this->payList[$payCursor]['product_num'];
        }
        $this->orderAmount = $this->goodsPrice;
        $this->totalAmount = $this->goodsPrice;
    }

    /**
     * 配送
     * @param $district_id
     */
    public function delivery($district_id){
        if(empty($district_id)){
            throw new CartException(['msg'=>'请填写收货信息']);
        }
        $GoodsLogic = new GoodsLogic();

        $this->shippingPrice = $GoodsLogic->getFreight($this->payList, $district_id);
        $this->orderAmount = $this->orderAmount + $this->shippingPrice;
        $this->totalAmount = $this->totalAmount + $this->shippingPrice;

    }
    /**
     * 使用优惠券
     * @param $coupon_id
     */
    public function useCouponById($coupon_id){
        if($coupon_id > 0){
            $couponList = new UserCoupon();
            $userCoupon = $couponList->where(['user_id'=>$this->user['user_id'],'id'=>$coupon_id])->find();
            if($userCoupon){
                $this->couponId = $coupon_id;
                $this->couponPrice = $userCoupon['money'];
                $this->orderAmount = $this->orderAmount - $this->couponPrice;
            }
        }
    }

    /**
     * 使用积分
     * @param $pay_points
     */
    public function usePayPoints($pay_points)
    {
        $this->payPoints = $pay_points;
        $user = $this->user;
        $point_rate = 1; //兑换比例
        if ($pay_points > $user['integral']) {
            throw new CartException(['msg' => '积分不足']);
        }

        if($pay_points > 0 && $this->orderAmount > 0){
            $this->integralMoney = $point_rate * $pay_points;
            $this->orderAmount = $this->orderAmount - $this->integralMoney;
        }
    }

    /**
     * 获取用户
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * 商品总价
     * @return int
     */
    public function getGoodsPrice()
    {
        return $this->goodsPrice;
    }

    /**
     * 获取物流费
     * @return int
     */
    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }

    /**
     * 获取实际上使用的积分
     * @return float|int
     */
    public function getPayPoints()
    {
        return $this->payPoints;
    }

    /**
     * 获取实际上使用的积分抵扣金额
     * @return float
     */
    public function getIntegralMoney(){
        return $this->integralMoney;
    }

    /**
     * 获取订单总价
     * @return int
     */
    public function getTotalAmount()
    {
        return number_format($this->totalAmount, 2, '.', '');
    }

    /**
     * 获取订单应付金额
     * @return int
     */
    public function getOrderAmount()
    {
        return number_format($this->orderAmount, 2, '.', '');
    }

    public function getPayList()
    {
        return $this->payList;
    }

}