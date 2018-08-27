<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/10
 * Time: 17:13
 */

namespace app\admin\controller;


use app\admin\model\FreightTemplate;
use app\admin\model\Image;
use app\admin\model\Product;
use app\admin\model\ProductCategory;
use app\admin\model\ProductType;
use app\admin\model\Spec;
use app\admin\model\SpecItem;
use app\admin\model\SpecProductPrice;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Goods extends BaseController
{
    public function lists()
    {
        $cateModel= new ProductCategory();
        $cateList= $cateModel->getCategoryByList();
        $this->assign('category', $cateList);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');

        $title  = input('param.title/s', '');
        $category_id = input('param.category_id/s', 0);
        $status  = input('param.status/s', 'close');

        $model  = new Product();

        $where=[];
        if($title) {
            $where['title'] = ['like', '%'.$title.'%'];
        }

        if($category_id) {
            $ids = ProductCategory::getChildren($category_id);
            $where['category_id'] = ['in',$ids];
        }

        if(is_numeric($status)) {
            $where['status'] = $status;
        }

        $count  = $model->where($where)->count();
        $array  = $model->order('create_time desc')->where($where)->relation(['category','coverImg'])->page($page, $limit)->select();
        $data=[];
        foreach($array as $item) {
            $goods=$item->toArray();
            $goods['category_name']=$goods['category']['name'];
            $goods['image']='<img src="'.$goods['coverImg']['url'].'" width="40">';
            $data[]=$goods;
        }
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if(request()->isPost()) {
            $model = new Product();
            $title = input('post.title/s','');
            $subhead = input('post.subhead/s','');
            $brand = input('post.brand/s','');
            $categoryID = input('post.category_id/d',0);
            $coverImg = input('post.cover_img/s','');
            $detailImg = input('post.detail_image/a',[]);
            $store = input('post.store/d',0);
            $min_price = input('post.min_price/d',0);
            $status = input('post.status/d',0);
            $isRecommend = input('post.is_recommend/d',0);
            $isFreeShipping = input('post.is_free_shipping/d',0);
            $templateID = input('post.template_id/d',0);
            $image = input('post.image/a',[]);
            $imageModel = new Image();
            $imageModel->url = $coverImg;
            $imageModel->save();

            $model->title = $title;
            $model->subhead = $subhead;
            $model->brand = $brand;
            $model->category_id = $categoryID;
            $model->store = $store;
            $model->min_price = $min_price;
            $model->status = $status;
            $model->is_recommend = $isRecommend;

            $model->template_id = $templateID;
            if (!$templateID)
            {
                $isFreeShipping = 1;
            }
            $model->is_free_shipping = $isFreeShipping;
            $model->cover_img_id = $imageModel->id;
            $model->save();

            $detailImgArray = [];
            foreach ($detailImg as $key => $value) {
                $detailImgArray[$key]['url'] = $value;
            }
            $model->productDetail()->saveAll($detailImgArray);
            $imageArray = [];
            foreach ($image as $key => $value) {
                $imageArray[$key]['url'] = $value;
            }
            $model->image()->saveAll($imageArray);
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $model= new ProductCategory();
        $category=$model->getCategoryByList();
        $this->assign('category', $category);

        $tempModel = new FreightTemplate();
        $tempList = $tempModel->select();
        $this->assign('tempList',$tempList);
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $model = Product::get($id);
            $title = input('post.title/s','');
            $subhead = input('post.subhead/s','');
            $brand = input('post.brand/s','');
            $categoryID = input('post.category_id/d',0);
            $coverImg = input('post.cover_img/s','');
            $detailImg = input('post.detail_image/a',[]);
            $store = input('post.store/d',0);
            $min_price = input('post.min_price/s',0);
            $status = input('post.status/d',0);
            $isRecommend = input('post.is_recommend/d',0);
            $isFreeShipping = input('post.is_free_shipping/d',0);
            $templateID = input('post.template_id/d',0);
            $image = input('post.image/a',[]);

            if ($status == 1)
            {
                $res = SpecProductPrice::where('product_id','=',$id)->find();
                if (!$res)
                {
                    throw new AdminException(['msg' => '请编辑商品模型信息再上架商品']);
                }
            }
            $model->title = $title;
            $model->subhead = $subhead;
            $model->brand = $brand;
            $model->category_id = $categoryID;
            $model->store = $store;
            $model->min_price = $min_price;
            $model->status = $status;
            $model->is_recommend = $isRecommend;
            $model->is_free_shipping = $isFreeShipping;
            $model->template_id = $templateID;
            if ($coverImg) {
                $imageModel = new Image();
                $imageModel->url = $coverImg;
                $imageModel->save();
                $model->cover_img_id = $imageModel->id;
            }
            $model->save();
            if ($detailImg) {
                $detailImgArray = [];
                foreach ($detailImg as $key => $value) {
                    $detailImgArray[$key]['url'] = $value;
                }
                $model->productDetail()->saveAll($detailImgArray);
            }
            if ($image) {
                $imageArray = [];
                foreach ($image as $key => $value) {
                    $imageArray[$key]['url'] = $value;
                }
                $model->image()->saveAll($imageArray);
            }
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $product = Product::get($id,['coverImg','image','productDetail'])->toArray();
        $this->assign('product',$product);
        $model = new ProductCategory();
        $category = $model->getCategoryByList();
        $this->assign('category', $category);

        $tempModel = new FreightTemplate();
        $tempList = $tempModel->select();
        $this->assign('tempList',$tempList);
        return $this->fetch();
    }

    public function putaway()
    {
        $id = input('post.id/d',0);
        $info  = model('product')->find($id);
        if ($info->status == 0)
        {
            $res = SpecProductPrice::where('product_id','=',$id)->find();
            if (!$res)
            {
                throw new AdminException(['msg' => '请编辑商品模型信息再上架商品']);
            }
        }
        $info->status = !$info->getData('status');
        $info->save();
        return $info->status;
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = Product::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }

    public function del_img($id)
    {
        Image::destroy($id);
        return json(new SuccessMessage(['msg' => '已删除']));
    }

    public function model($id)
    {
        if (request()->isPost()) {
            $productTypeID = input('post.type/d',0);
            $items = input('post.item/a',[]);

            foreach ($items as $key => $value) {
                if ($value['store'] == 0) {
                    unset($items[$key]);
                }else{
                    $items[$key]['key'] = $key;
                    $items[$key]['key_name'] = $this->getKeyNameByKey($key);
                    if (!$value['image'])
                    {
                        unset($items[$key]['image']);
                    }
                }
            }
            $product = Product::get($id);
            $product->product_type_id = $productTypeID;
            $product->save();
            $product->specProductPrice()->delete();
            $product->specProductPrice()->saveAll($items);
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $product = Product::get($id);
        $this->assign('product',$product);
        $spec = Spec::with(['item'])
            ->where('type_id','=',$product['product_type_id'])->select();
        $this->assign('spec',$spec);
        $productType = ProductType::all();
        $this->assign('product_type',$productType);
        return $this->fetch();
    }
    /**
     * 动态获取商品规格输入框 根据不同的数据返回不同的输入框
     */
    public function ajaxGetSpecInput(){
        $ProductService = new \app\admin\service\Product();
        $product_id = input('product_id/d',0);
        $str = $ProductService->getSpecInput($product_id ,input('post.spec_arr/a',[[]]));
        return $str;
    }
    /**
     * 动态获取商品规格选择框 根据不同的数据返回不同的选择框
     */
    public function ajaxGetSpecSelect(){
        $product_id = input('product_id/d',0);
        $specList = model('Spec')->where("type_id",input('get.spec_type/d'))->select();
        foreach($specList as $k => $v)
            $specList[$k]['spec_item'] = model('SpecItem')->where("spec_id",$v['id'])->field('id,item')->select(); // 获取规格项

        $items_id = model('SpecProductPrice')->where('product_id',$product_id)->column("GROUP_CONCAT(`key` SEPARATOR '_') AS items_id");
        $items_ids = explode('_', $items_id[0]);
        $this->assign('items_ids',$items_ids);
        $this->assign('specList',$specList);
        return $this->fetch('ajax_spec_select');
    }

    private function getKeyNameByKey($key)
    {
        $keyArray = explode('_',$key);
        $data = SpecItem::all($keyArray,['spec']);
        $keyName = '';
        foreach ($data as $v) {
            $keyName .= $v['spec']['name'];
            $keyName .= ':';
            $keyName .= $v['item'];
            $keyName .= ' ';
        }
        return $keyName;
    }
}