<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/3
 * Time: 11:17
 */

namespace app\hotel\controller;


use app\admin\model\HotelImg;
use app\admin\model\HotelImgCategory;
use app\hotel\model\Image;
use app\lib\exception\AdminException;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;

class Photo extends BaseController
{
    public function lists()
    {
        $cate = HotelImgCategory::all();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');

        $category_id = input('param.category_id/d', 0);

        $admin = session('admin');
        $model = new HotelImg();

        $where['hotel_id'] = $admin['hotel_id'];

        if($category_id) {
            $where['hotel_img_category_id'] = $category_id;
        }

        $count  = $model->where($where)->count();
        $array  = $model->order('update_time desc')->where($where)->relation(['image','cate'])->page($page, $limit)->select();
        $data = [];
        foreach ($array as $value)
        {
            $data[] = [
                'id' => $value->id,
                'image' => '<img src="'.$value->image->url.'">',
                'cate' => $value->cate->name,
                'create_time' => $value->create_time
            ];
        }

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        $admin = session('admin');
        if (request()->isPost()) {
            $postData = request()->post();
            if (!$postData['image']) {
                throw new HotelAdminException(['msg' => '请上传图片']);
            }
            foreach ($postData['image'] as $value)
            {
                $imageModel = new Image();
                $imageModel->url = $value;
                $imageModel->save();
                $model = new HotelImg();
                $model->hotel_id = $admin['hotel_id'];
                $model->hotel_img_category_id = $postData['category_id'];
                $model->img_id = $imageModel->id;
                $model->save();
            }
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $cate = HotelImgCategory::all();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    public function edit($id)
    {
        $admin = session('admin');
        $photoModel = HotelImg::get($id);
        if ($photoModel->hotel_id != $admin['hotel_id']) {
            throw new HotelAdminException(['msg' => '你没有权限这么做']);
        }
        if (request()->isPost())
        {
            $postData = request()->post();
            if ($postData['image']) {
                $imageModel = new Image();
                $imageModel->url = $postData['image'];
                $imageModel->save();
                $photoModel->img_id = $imageModel->id;
            }

            $photoModel->hotel_id = $admin['hotel_id'];
            $photoModel->hotel_img_category_id = $postData['category_id'];
            $photoModel->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }

        $this->assign('photo',$photoModel);
        $cate = HotelImgCategory::all();
        $this->assign('cate',$cate);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = HotelImg::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new HotelAdminException(['msg' => '删除失败']);
        }
    }
}