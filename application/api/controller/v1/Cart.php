<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/25
 * Time: 10:20
 */

namespace app\api\controller\v1;

use app\api\model\Region;
use app\api\model\UserAddress;
use app\api\service\CartLogic;
use app\api\service\GoodsLogic;
use app\api\service\Pay;
use app\api\service\PlaceOrder;
use app\api\validate\AddToCart;
use app\lib\exception\CartException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use app\api\service\Token as TokenSevice;
use app\api\model\User as UserModel;

class Cart
{
    public $user_id = 0;

    /**
     * 初始化函数
     */
    public function _initialize()
    {
        parent::_initialize();
        $uid = TokenSevice::getCurrentUid();
        $this->user_id = $uid;
    }

    public function cartList()
    {
        $cartLogic = new CartLogic();
        $carts = $cartLogic->getCartList();
        return $carts;
    }

    public function addProductToCart()
    {
        $validate = new AddToCart();
        $validate->goCheck();

        $post = $validate->getDataByRule(input('post.'));

        $cartLogic = new CartLogic();
        $cartLogic->setProductModel($post['product_id']);
        if($post['item_id']){
            $cartLogic->setSpecProductPriceModel($post['item_id'],$post['product_id']);
        }
        $cartLogic->setProductBuyNum($post['product_num']);
        $cartLogic->addGoodsToCart();
        return json(new SuccessMessage(['msg' => '添加购物车成功']));
    }

    /**
     * @desc 改变购物车商品数量
     * @return \think\response\Json
     * @throws CartException
     */
    public function changeNum()
    {
        $cart = input('cart/a',[]);
        if (empty($cart)) {
            throw new CartException(['msg' => '请选择要更改的商品']);
        }
        $cartLogic = new CartLogic();
        $cartLogic->changeNum($cart['id'],$cart['product_num']);
        return json(new SuccessMessage());
    }

    /**
     * 删除购物车商品
     */
    public function delete()
    {
        $cart_ids = input('cart_ids/a',[]);
        $cartLogic = new CartLogic();
        $result = $cartLogic->delete($cart_ids);
        if($result){
            return json(new SuccessMessage(['msg' => '删除成功']));
        }else{
            throw new CartException(['msg' => '删除失败']);
        }
    }

    /**
     * ajax 获取订单商品价格 或者提交 订单
     */
    public function cart3()
    {
        $address_id         = input("address_id/d"); //  收货地址id
//        $coupon_id          = input("coupon_id/d"); //  优惠券id
        $pay_points         = input("pay_points/d",0); //  使用积分
        $user_note          = input("user_note",''); // 用户留言
        $products           = input("products/a");
        if(!$address_id)
        {
            throw new CartException(['msg'=>'请先填写收货人信息']);
        } // 返回结果状态
        $address = UserAddress::get($address_id);
        $pay = new Pay();
        Db::startTrans();
        try {
            $pay->payGoodsList($products);
            $pay->delivery($address['district']);
//            $pay->useCouponById($coupon_id);
            $pay->usePayPoints($pay_points);
            // 提交订单
            $placeOrder = new PlaceOrder($pay);
            $placeOrder->setUserAddress($address);
            $placeOrder->setUserNote($user_note);
            $placeOrder->addNormalOrder();
            $pay->clearCart();
            $order = $placeOrder->getOrder();
            $orderID = $order->id;
            $create_time = $order->create_time;
            $orderNo = $order->order_no;
            // 用户积分结算
            if ($pay_points)
            {
                $userModel = UserModel::get($this->user_id);
                $userModel->setDec('integral',$pay_points);
            }
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];

        } catch (Exception $ex) {
            Db::rollback();
            throw $ex;
        }
    }

    public function getFreight()
    {
        $productsArr = input('param.product_arr/a',[]);
        $address_id = input('param.address_id/d',0);
        $address = UserAddress::get($address_id);
        $district_id = $address['district'];
        $GoodsLogic = new GoodsLogic();
        $shippingPrice = $GoodsLogic->getFreight($productsArr, $district_id);
        return ['shipping_price' => $shippingPrice];
    }
}