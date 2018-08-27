<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 14:52
 */

namespace app\admin\controller;

use app\admin\model\RoomFacility as RoomFacilityModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class RoomFacility extends BaseController
{
    public function lists()
    {
        $facility = new RoomFacilityModel();
        $facilityList = $facility->getByList();
        $this->assign('facilityList',$facilityList);
        return $this->fetch();
    }

    public function add()
    {
        $facility = new RoomFacilityModel();
        if(request()->isPost()) {
            $post=request()->post();

            if($facility->save($post)) {
                return json(new SuccessMessage(['msg'=>'添加成功']));
            } else {
                throw new AdminException(['msg' => '添加失败']);
            }
        }
        $category=$facility->where('parent_id', 0)->select();
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function edit()
    {
        $id = input('param.id',0);
        if(request()->isPost()) {
            $name = input('post.name/s','');
            $sort = input('post.sort');
            $parent_id = input('post.parent_id');
            $facility = RoomFacilityModel::get($id);
            $facility->name = $name;
            $facility->sort = $sort;
            $facility->parent_id = $parent_id;
            $res = $facility->save();
            if($res) {
                return json(new SuccessMessage(['msg'=>'添加成功']));
            } else {
                throw new AdminException(['msg' => '添加失败']);
            }
        }
        $facility = RoomFacilityModel::get($id)->toArray();
        $this->assign('facility', $facility);
        $category=RoomFacilityModel::where('parent_id', 0)->select();
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function delete()
    {
        $id = input('param.id/d');
        $ids = RoomFacilityModel::getChildren($id);
        $res = RoomFacilityModel::destroy($ids);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }

}