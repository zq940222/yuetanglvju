<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/25
 * Time: 15:04
 */

namespace app\api\service;


use app\api\model\Cart;
use app\api\model\Product;
use app\api\model\SpecProductPrice;
use app\lib\exception\CartException;

class CartLogic
{
    protected $product;//商品模型
    protected $specProductPrice;//商品规格模型
    protected $productBuyNum;//购买的商品数量
    protected $user_id;//user_id

    public function __construct()
    {
        $uid = Token::getCurrentUid();
        $this->setUserId($uid);
    }

    /**
     * @param int $selected|是否被用户勾选中的 0 为全部 1为选中  一般没有查询不选中的商品情况
     * 获取用户的购物车列表
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getCartList(){
        $cart = new Cart();
        // 如果用户已经登录则按照用户id查询
        $cartWhere['user_id'] = $this->user_id;
        $cartList = $cart->with(['product','product.coverImage'])->where($cartWhere)->select();  // 获取购物车商品
        $cartCheckAfterList = $this->checkCartList($cartList);
        return $cartCheckAfterList;
    }

    /**
     * 检查购物车数据是否满足库存购买
     * @param $cartList
     */
    public function checkStockCartList($cartList)
    {
        foreach ($cartList as $cartKey => $cartVal) {
            if ($cartVal->product_num > $cartVal->store) {
                throw new CartException(['msg' => $cartVal->product_name . '购买数量不能大于' . $cartVal->store]);
            }
        }
    }

    /**
     * 过滤掉无效的购物车商品
     * @param $cartList
     */
    public function checkCartList($cartList){
        foreach($cartList as $cartKey=>$cart){
            //商品不存在或者已经下架
            if(empty($cart['product']) || $cart['product']['status'] != 1 || $cart['product_num'] == 0){
                $cart->delete();
                unset($cartList[$cartKey]);
                continue;
            }

        }
        return $cartList;
    }
    /**
     * 包含一个商品模型
     * @param $product_id
     * @throws \think\exception\DbException
     */
    public function setProductModel($product_id)
    {
        if($product_id > 0){
            $productModel = new Product();
            $this->product = $productModel::get($product_id);
        }
    }
    /**
     * 包含一个商品规格模型
     * @param $item_id
     * @param $product_id
     * @throws CartException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setSpecProductPriceModel($item_id,$product_id)
    {
        if($item_id > 0){
            $specProductPriceModel = new SpecProductPrice();
            $this->specProductPrice = $specProductPriceModel->find($item_id);
            if ($this->specProductPrice->product_id != $product_id) {
                throw new CartException(['msg' => '规格有误']);
            }
        }
    }

    /**
     * 设置用户ID
     * @param $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * 设置购买的商品数量
     * @param $productBuyNum
     * */
    public function setProductBuyNum($productBuyNum)
    {
        $this->productBuyNum = $productBuyNum;
    }

    /**
     * modify ：addCart
     * @return array
     */
    public function addGoodsToCart()
    {
        if(empty($this->product)){
            throw new CartException([
                'msg' => '添加的产品不能为空'
            ]);
        }

        $specGoodsPriceCount = SpecProductPrice::where("product_id", $this->product['id'])->count();
        if(empty($this->specProductPrice) && !empty($specGoodsPriceCount)){
            throw new CartException([
                'msg' => '必须传入规格'
            ]);
        }
        $result = $this->addNormalCart();

        return $result;
    }

    /**
     * 购物车添加普通商品
     * @return array
     */
    private function addNormalCart(){
        if(empty($this->specProductPrice)){
            $price =  $this->product['min_price'];
            $store_count =  $this->product['store'];
        }else{
            //如果有规格价格，就使用规格价格，否则使用本店价。
            $price = $this->specProductPrice['price'];
            $store_count = $this->specProductPrice['store'];
        }
        // 查询购物车是否已经存在这商品
        $userCartGoods = Cart::get(['user_id'=>$this->user_id,'product_id'=>$this->product['id'],'spec_key'=>($this->specProductPrice['key'] ?: '')]);

        // 如果该商品已经存在购物车
        if ($userCartGoods) {
            $userWantGoodsNum = $this->productBuyNum + $userCartGoods['product_num'];//本次要购买的数量加上购物车的本身存在的数量

            if($userWantGoodsNum > 200){
                $userWantGoodsNum = 200;
            }
            if($userWantGoodsNum > $store_count){
                throw new CartException(['msg' => '商品不足']);
            }
            $cartResult = $userCartGoods->save(['product_num' => $userWantGoodsNum,'product_price'=>$price]);
        }else{
            //如果该商品没有存在购物车
            if($this->productBuyNum > $store_count){
                throw new CartException(['msg' => '商品不足']);
            }

            $cartAddData = array(
                'user_id' => $this->user_id,   // 用户id
                'product_id' => $this->product['id'],   // 商品id
                'product_name' => $this->product['title'],   // 商品名称
                'product_price' => $price,  // 原价
                'product_num' => $this->productBuyNum, // 购买数量
            );
            if($this->specProductPrice){
                $cartAddData['item_id'] = $this->specProductPrice['id'];
                $cartAddData['spec_key'] = $this->specProductPrice['key'];
                $cartAddData['spec_key_name'] = $this->specProductPrice['key_name']; // 规格 key_name
            }
            $cartResult = Cart::create($cartAddData);
        }
        if (!$cartResult) {
            throw new CartException(['msg' => '加入购物车失败']);
        }else{
            return true;
        }

    }

    /**
     * 更改购物车的商品数量
     * @param $cart_id|购物车id
     * @param $product_num|商品数量
     * @return array
     */
    public function changeNum($cart_id, $product_num){
        $Cart = new Cart();
        $cart = $Cart->find($cart_id);
        $cart->product_num = $product_num;
        $cart->save();
        return true;
    }
    /**
     * 删除购物车商品
     * @param array $cart_ids
     * @return int
     */
    public function delete($cart_ids = []){
        $cartWhere['user_id'] = $this->user_id;
        $delete = Cart::where($cartWhere)->where('id','IN',$cart_ids)->delete();
        if ($delete) {
            return true;
        }else{
            return false;
        }
    }
}