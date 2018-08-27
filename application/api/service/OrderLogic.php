<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/27
 * Time: 17:50
 */

namespace app\api\service;


use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\SpecProductPrice;
use app\lib\exception\OrderException;

class OrderLogic
{
    //订单的商品列表,
    protected $oProducts;

    //真实的商品信息
    protected $products;

    /**
     * 获取订单 order_sn
     * @return string
     */
    public function get_order_sn()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
        $orderSn =
            $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(1000, 9999));
        return $orderSn;
    }

    public function checkOrderStock($orderID)
    {
        $oProducts = OrderProduct::where('order_id','=',$orderID)
            ->select();
        $this->oProducts = $oProducts;

        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct){
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'],$oProduct['spec_key'],$oProduct['product_num'],$this->products
            );
            if (!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    private function getProductStatus($oPID, $specKey, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'key' => '',
            'haveStock' => false,
            'count' => 0,
            'totalPrice' => 0
        ];

        for ($i=0;$i<count($products);$i++){
            if ($oPID == $products[$i]['product_id'] && $specKey == $products[$i]['key']){
                $pIndex = $i;
            }

        }

        if ($pIndex == -1) {
            throw new OrderException([
                'msg' => 'id为'.$oPID.'商品不存在,创建订单失败'
            ]);
        }
        else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['product_id'];
            $pStatus['key'] = $product['key'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($product['store'] - $oCount >= 0){
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    private function getProductsByOrder($oProducts)
    {
        $products = [];
        foreach ($oProducts as $item){
            if ($item['spec_key']) {
                $data = SpecProductPrice::where('product_id','=',$item['product_id'])
                    ->where('key','=',$item['spec_key'])
                    ->field('product_id,key,price,store')
                    ->find();
                array_push($products,$data);
            }else{
                $data = Product::get($item['product_id'])
                    ->visible(['id','store','min_price'])
                    ->toArray();
                $data['product_id'] = $data['id'];
                unset($data['id']);
                $data['price'] = $data['min_price'];
                unset($data['min_price']);
                $data['key'] = '';
                array_push($products,$data);
            }
        }

        return $products;
    }
}