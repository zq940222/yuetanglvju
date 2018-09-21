<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/12
 * Time: 14:17
 */

namespace app\admin\controller;


use app\admin\model\Spec;
use app\admin\model\SpecItem;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class ProductSpec extends BaseController
{
    public function lists()
    {
        $typeList = model('ProductType')->select();
        $this->assign('typeList',$typeList);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $productType = input('param.type_id/d',0);

        $where = [];
        if ($productType) {
            $where['type_id'] = ['=',$productType];
        }
        $count = Spec::where($where)
            ->count();
        $data = Spec::with(['productType','item'])
            ->order('update_time desc')
            ->where($where)
            ->page($page,$limit)
            ->select()
            ->toArray();
        foreach ($data as &$value) {
            $value['type'] = $value['product_type']['name'];
            $arr = [];
            foreach ($value['item'] as $v) {
                $arr[] = $v['item'];
            }
            $value['item'] = implode(',',$arr);
        }
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost()) {
            $name = input('post.name/s','');
            $typeID = input('post.type_id/d',0);
            $items = input('post.items','');
            $items = explode("\n",$items);
            $specItem = [];
            foreach ($items as $v) {
                $specItem[] = ['item'=> $v];
            }
            $productSpec = new Spec();
            $productSpec->name = $name;
            $productSpec->type_id = $typeID;
            $productSpec->save();
            $productSpec->item()->saveAll($specItem);
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $typeList = model('ProductType')->select();
        $this->assign('typeList',$typeList);
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $name = input('post.name/s','');
            $typeID = input('post.type_id/d',0);
            $items = input('post.items','');
            $items = explode("\n",$items);
            $specItems = SpecItem::where('spec_id',$id)->column('item');

            $specItemAdd = array_diff($items,$specItems);
            $specItmeDelete = array_diff($specItems,$items);

            $specItem = [];
            foreach ($specItemAdd as $v) {
                $specItem[] = ['item'=> $v];
            }
            $productSpec = Spec::get($id);
            $productSpec->name = $name;
            $productSpec->type_id = $typeID;
            $productSpec->save();
            $productSpec->item()->where('item','in',$specItmeDelete)->delete();
            $productSpec->item()->saveAll($specItem);
            $productSpec->afterSave($id);
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $spec = Spec::with(['item'])
            ->find($id);
        $specArray = [];
        foreach ($spec['item'] as $value) {
            $specArray[] = $value['item'];
        }
        $spec['items'] = implode("\n",$specArray);
        $this->assign('spec',$spec);
        $typeList = model('ProductType')->select();
        $this->assign('typeList',$typeList);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = Spec::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}