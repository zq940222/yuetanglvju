<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/10
 * Time: 9:25
 */

namespace app\admin\controller;


use app\admin\model\HotelFiltrate;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Filtrate extends BaseController
{
    public function lists()
    {
        $filtrate = new HotelFiltrate();
        $cateList = $filtrate->getFiltrateByList();
        $this->assign('cateList',$cateList);
        return $this->fetch();
    }

    public function add()
    {
        $filtrate = new HotelFiltrate();
        if(request()->isPost()) {
            $post=request()->post();

            if($filtrate->save($post)) {
                return json(new SuccessMessage(['msg'=>'添加成功']));
            } else {
                throw new AdminException(['msg' => '添加失败']);
            }
        }
        $category=$filtrate->where('parent_id', 0)->select();
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
            $filtrate = HotelFiltrate::get($id);
            $filtrate->name = $name;
            $filtrate->sort = $sort;
            $filtrate->parent_id = $parent_id;
            $res = $filtrate->save();
            if($res) {
                return json(new SuccessMessage(['msg'=>'添加成功']));
            } else {
                throw new AdminException(['msg' => '添加失败']);
            }
        }
        $filtrate = HotelFiltrate::get($id)->toArray();
        $this->assign('filtrate', $filtrate);
        $category=HotelFiltrate::where('parent_id', 0)->select();
        $this->assign('category', $category);
        return $this->fetch();
    }

    public function delete()
    {
        $id = input('param.id/d');
        $ids = HotelFiltrate::getChildren($id);
        $res = HotelFiltrate::destroy($ids);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}