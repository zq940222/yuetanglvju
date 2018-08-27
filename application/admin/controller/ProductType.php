<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/12
 * Time: 11:50
 */

namespace app\admin\controller;

use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class ProductType extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');

        $count = model('ProductType')->count();
        $data = model('ProductType')->order('create_time desc')
            ->page($page,$limit)
            ->select();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost()) {
            $name = input('post.name/s','');
            $productType = new \app\admin\model\ProductType();
            $productType->name = $name;
            $productType->save();
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        $productType = model('ProductType')->find($id);
        if (request()->isPost()) {
            $name = input('post.name/s','');
            $productType->name = $name;
            $productType->save();
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $this->assign('productType',$productType);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = \app\admin\model\ProductType::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}