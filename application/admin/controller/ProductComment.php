<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 15:14
 */

namespace app\admin\controller;

use app\admin\model\Product as ProductModel;
use app\admin\model\Product;
use app\admin\model\ProductComment as ProductCommentModel;
use app\admin\model\Order as OrderModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class ProductComment extends BaseController
{
    public function lists()
    {
        $product = ProductModel::all();
        $this->assign('product',$product);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $orderNo  = input('param.orderNo/s','');
        $productID  = input('param.product_id/d',0);
        $date  = input('param.date/s');

        $where=[];
        if ($orderNo) {
            $order = OrderModel::where('order_no','=',$orderNo)->find();
            $where['id'] = $order['id'];
        }

        if ($productID) {
            $where['product_id'] = $productID;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $model = new ProductCommentModel();
        $count  = $model->where($where)->count();
        $data  = $model->order('create_time desc')->where($where)->relation(['product'])->page($page, $limit)->select();
        foreach ($data as &$value) {
            $value['product_name'] = $value->product->title;
        }
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail($id)
    {
        $model = new ProductCommentModel();

        $data = $model->with(['product','user','image'])
            ->find($id);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = ProductCommentModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}