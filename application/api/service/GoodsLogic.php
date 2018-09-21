<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/22
 * Time: 17:20
 */

namespace app\api\service;

use app\api\model\Product;
use app\api\model\Spec;
use app\api\model\SpecProductPrice;
use think\Db;

class GoodsLogic
{

    /**
     * @desc 获取商品规格
     * @param $products_id|商品id
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_spec($products_id)
    {
        //商品规格 价钱 库存表 找出 所有 规格项id
        $keys = SpecProductPrice::where("product_id", $products_id)->column("GROUP_CONCAT(`key` ORDER BY store desc SEPARATOR '_') ");
        $filter_spec = array();
        $keys = str_replace('_', ',', $keys)[0];

        if ($keys) {
            $filter_spec = Spec::hasWhere('item',['id'=>['in',$keys]])
                ->order('id asc')
                ->with([
                'item' => function($query) use ($keys){
                    $query->where('id','in',$keys);
                }
            ])->select();
        }
        return $filter_spec;
    }

    /**
     * 根据配送地址获取多个商品的运费
     * @param $productsArr
     * @param $region_id
     * @return int
     */
    public function getFreight($productsArr, $region_id)
    {
        $Products = new Product();
        $freightLogic = new FreightLogic();
        $freightLogic->setRegionId($region_id);
        $products_ids = array_column($productsArr, 'product_id');
        $productsList = $Products->field('id,volume,weight,template_id,is_free_shipping')->where('id', 'IN', $products_ids)->select()->toArray();
        foreach ($productsArr as $cartKey => $cartVal) {
            foreach ($productsList as $productsKey => $productsVal) {
                if ($cartVal['product_id'] == $productsVal['id']) {
                    $productsArr[$cartKey]['volume'] = $productsVal['volume'];
                    $productsArr[$cartKey]['weight'] = $productsVal['weight'];
                    $productsArr[$cartKey]['template_id'] = $productsVal['template_id'];
                    $productsArr[$cartKey]['is_free_shipping'] = $productsVal['is_free_shipping'];
                }
            }
        }
        $template_list = [];
        foreach ($productsArr as $productsKey => $productsVal) {
            $template_list[$productsVal['template_id']][] = $productsVal;
        }
        $freight = 0;
        foreach ($template_list as $templateVal => $productsArr) {
            $temp['template_id'] = $templateVal;
            $temp['total_volume'] = 0;
            $temp['total_weight'] = 0;
            $temp['product_num'] = 0;
            foreach ($productsArr as $productsKey => $productsVal) {
                $temp['total_volume'] += $productsVal['volume'] * $productsVal['product_num'];
                $temp['total_weight'] += $productsVal['weight'] * $productsVal['product_num'];
                $temp['product_num'] += $productsVal['product_num'];
                $temp['is_free_shipping'] = $productsVal['is_free_shipping'];
            }
            $freightLogic->setproductModel($temp);
            $freightLogic->setproductNum($temp['product_num']);
            $freightLogic->doCalculation();
            $freight += $freightLogic->getFreight();
            unset($temp);
        }
        return $freight;
    }
}