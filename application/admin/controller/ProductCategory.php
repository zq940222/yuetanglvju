<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/11
 * Time: 17:39
 */

namespace app\admin\controller;

use app\admin\model\Image;
use app\admin\model\Product;
use app\admin\model\ProductCategory as ProductCategoryModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class ProductCategory extends BaseController
{
    public function lists()
    {
        $category = new ProductCategoryModel;
        $cateList = $category->getCategoryByList();
        $this->assign('cateList',$cateList);
        return $this->fetch();
    }

    public function add()
    {
        if(request()->isPost()) {
            $cateModel= new ProductCategoryModel();
            $post=request()->post();
            if ($post['image']) {
                $imageModel = new Image();
                $imageModel->url = $post['image'];
                $imageModel->save();
                $post['img_id'] = $imageModel->id;
            }
            unset($post['image']);
            unset($post['file']);
            if($cateModel->save($post)) {
                return json(new SuccessMessage(['msg'=>'添加成功']));
            } else {
                throw new AdminException(['msg' => '添加失败']);
            }
        }
        $cateModel= new ProductCategoryModel();
        $cateList= $cateModel->getCategoryByList();
        $this->assign('category', $cateList);
        return $this->fetch();
    }

    public function edit($id)
    {
        if(request()->isPost()) {
            $name = input('post.name/s','');
            $sort = input('post.sort');
            $parent_id = input('post.parent_id');
            $grade = input('post.grade',1);
            $image = input('post.image/s','');
            $id = input('post.id/d',0);
            $cate = ProductCategoryModel::get($id);
            $cate->name = $name;
            $cate->sort = $sort;
            $cate->grade = $grade;
            $cate->parent_id = $parent_id;
            if ($image) {
                $imageModel = new Image();
                $imageModel->url = $image;
                $imageModel->save();
                $cate->img_id = $imageModel->id;
            }
            $res = $cate->save();
            if($res) {
                return json(new SuccessMessage(['msg'=>'编辑成功']));
            } else {
                throw new AdminException(['msg' => '编辑失败']);
            }
        }
        $cateModel= new ProductCategoryModel();
        $category = $cateModel->find($id);
        $this->assign('category',$category);
        $cateList= $cateModel->where('grade',$category['grade']-1)->select();
        $this->assign('cateList', $cateList);
        return $this->fetch();
    }

    public function delete()
    {
        $id = input('param.id/d');
        $ids = ProductCategoryModel::getChildren($id);
        $data = Product::where('category_id','in',$ids)
            ->find();
        if ($data)
        {
            throw new AdminException(['msg' => '该分类下有商品,禁止删除该分类']);
        }
        $res = ProductCategoryModel::destroy($ids);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}