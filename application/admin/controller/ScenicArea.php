<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/20
 * Time: 14:22
 */

namespace app\admin\controller;

use app\admin\model\Region;
use app\admin\model\ScenicArea as ScenicAreaModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class ScenicArea extends BaseController
{
    public function lists()
    {
        $region = Region::getRegion();
        $this->assign('region',$region);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $province = input('param.province/d');
        $city  = input('param.city/d');
        $name  = input('param.name/s', '');


        $where=[];
        if($name) {
            $where['name'] = ['like', '%'.$name.'%'];
        }

        if($province) {
            $where['province'] = $province;
        }

        if($city) {
            $where['city'] = $city;
        }

        $data  = ScenicAreaModel::with(['regionProv','regionCity'])
            ->order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        foreach ($data as &$value)
        {
            $value['province'] = $value['regionProv']['name'];
            $value['city'] = $value['regionCity']['name'];
        }
        $count = ScenicAreaModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost()){
            $name = input('post.name/s','');
            $province = input('post.province/s','');
            $city = input('post.city/s','');
            $ScenicAreaModel = new ScenicAreaModel();
            $ScenicAreaModel->name = $name;
            $ScenicAreaModel->province = $province;
            $ScenicAreaModel->city = $city;
            $ScenicAreaModel->save();

            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $region = Region::getRegion();
        $this->assign('region',$region);
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()){
            $name = input('post.name/s','');
            $province = input('post.province/s','');
            $city = input('post.city/s','');

            $ScenicArea = ScenicAreaModel::get($id);
            $ScenicArea->name = $name;
            $ScenicArea->province = $province;
            $ScenicArea->city = $city;
            $ScenicArea->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $ScenicArea = ScenicAreaModel::get($id);
        $this->assign('scenic_area',$ScenicArea);
        $region = Region::getRegion();
        $this->assign('region',$region);
        $city = Region::getRegion($ScenicArea['province']);
        $this->assign('city',$city);

        return $this->fetch();
    }

    public function getRegion()
    {
        $id = input('post.id');
        $region = Region::getRegion($id);
        return $region;
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = ScenicAreaModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}