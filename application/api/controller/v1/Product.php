<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/1
 * Time: 15:09
 */

namespace app\api\controller\v1;


use app\api\model\ProductComment;
use app\api\service\Attention;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\PagingParameter;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;
use app\lib\exception\SuccessMessage;
use think\Request;

class Product
{
    public function getRecommendProducts($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $recommendProducts = ProductModel::with(['coverImage'])
            ->order('update_time desc')
            ->where('is_recommend','=',1)
            ->where('status','=',1)
            ->paginate($size, true, ['page' => $page]);
        if ($recommendProducts->isEmpty()) {
            throw new ProductException();
        }
        return $recommendProducts;
    }

    public function getProducts($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $keyword = Request::instance()->get('keyword/s','');
        $cateID = Request::instance()->get('cate_id/d',0);
        $products = ProductModel::getProducts($page,$size,$keyword,$cateID);
        return $products;
    }

    public function getProductsBySalesVolume($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $keyword = Request::instance()->get('keyword/s','');
        $cateID = Request::instance()->get('cate_id/d',0);
        $products = ProductModel::getProductsBySalesVolume($page,$size,$keyword,$cateID);
        return $products;
    }

    public function getProductsByPriceAsc($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $keyword = Request::instance()->get('keyword/s','');
        $cateID = Request::instance()->get('cate_id/d',0);
        $products = ProductModel::getProductsByPriceAsc($page,$size,$keyword,$cateID);
        return $products;
    }

    public function getProductsByPriceDesc($page = 1, $size = 10)
    {
        (new PagingParameter())->goCheck();
        $keyword = Request::instance()->get('keyword/s','');
        $cateID = Request::instance()->get('cate_id/d',0);
        $products = ProductModel::getProductsByPriceDesc($page,$size,$keyword,$cateID);
        return $products;
    }

    public function getDetail($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $data = ProductModel::getDetail($id);
        return $data;
    }

    public function getProductComment($id, $page = 1, $size = 10)
    {
        (new IDMustBePostiveInt())->goCheck();
        (new PagingParameter())->goCheck();
        $data = ProductComment::with(['user','user.headimg','image'])
            ->order('create_time desc')
            ->where('product_id','=',$id)
            ->paginate($size,true,['page' => $page]);
        foreach ($data as &$value)
        {
            if (!$value['user']['headimg'])
            {
                $value['user']['headimg'] = '';
            }
        }
        return $data;
    }

    public function clickAttention($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $result = Attention::changeProductAttention($id);
        if (!$result) {
            throw new ProductException([
                'msg' => '商品收藏失败,请稍后再试'
            ]);
        }
        return (new SuccessMessage());
    }
}