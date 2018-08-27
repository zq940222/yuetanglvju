<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/17
 * Time: 11:50
 */

namespace app\admin\controller;


use app\admin\model\BannerItem;
use app\admin\model\Image;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Advert extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }


    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');

        $model  = model('BannerItem');


        $count  = $model->count();
        $array  = $model->with(['banner','image'])->page($page, $limit)->select();
        $data=[];
        foreach($array as $item) {
            $ad=$item->toArray();
            $ad['banner_name']=$ad['banner']['name'];
            $data[]=$ad;
        }

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add() {
        if(request()->isPost()) {
            $banner = new BannerItem();
            $image = input('post.image/s','');
            $href = input('post.href/s','');
            $keyword = input('post.key_word','');
            $banner_id = input('post.banner_id/d',0);
            if (!$image) {
                throw new AdminException(['msg' => '请上传图片']);
            }
            $imageModel = new Image();
            $imageModel->url = $image;
            $imageModel->save();
            $banner->img_id = $imageModel->id;
            $banner->banner_id = $banner_id;
            $banner->href = $href;
            $banner->key_word = $keyword;
            $banner->save();
            return json(new SuccessMessage(['msg' => '添加成功']));
        }

        $banner = model('banner')->select();
        $this->assign('banner', $banner);
        return $this->fetch();
    }

    public function edit($id) {
        (new IDMustBePostiveInt())->goCheck();

        $data=model('BannerItem')->find($id);
        if(request()->isPost()) {
            $image = input('post.image/s','');
            $href = input('post.href/s','');
            $keyword = input('post.key_word','');
            $banner_id = input('post.banner_id/d',0);
            if ($image) {
                $imageModel = new Image();
                $imageModel->url = $image;
                $imageModel->save();
                $data->img_id = $imageModel->id;
            }
            $data->banner_id = $banner_id;
            $data->href = $href;
            $data->key_word = $keyword;
            $data->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }

        $this->assign('data', $data);
        $banner = model('banner')->select();
        $this->assign('banner', $banner);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = BannerItem::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}