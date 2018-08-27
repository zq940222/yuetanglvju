<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/9
 * Time: 17:27
 */

namespace app\admin\controller;

use app\admin\model\Coupon as CouponModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Coupon extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $name  = input('param.name/s', '');
        $date  = input('param.date/s');

        $model = new CouponModel();
        $where = [];
        if ($name)
        {
            $where['name'] = ['like',"%$name%"];
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }
        $count = $model->where($where)
            ->count();
        $data = $model->where($where)
            ->order('create_time desc')
            ->page($page,$limit)
            ->select();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost())
        {
            $postData = request()->post();
            $date = $postData['date'];
            list($stime,$etime)=explode(' - ', $date);
            $couponModel = new CouponModel();
            $couponModel->name = $postData['name'];
            $couponModel->stime = strtotime($stime);
            $couponModel->etime = strtotime($etime);
            $couponModel->day = $postData['day'];
            $couponModel->money = $postData['money'];
            $couponModel->money_off = $postData['money_off'];
            $couponModel->save();
            return json(new SuccessMessage(['msg' => '提交成功']));
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        $couponModel = CouponModel::get($id);
        if (request()->isPost())
        {
            $postData = request()->post();
            $date = $postData['date'];
            list($stime,$etime)=explode(' - ', $date);
            $couponModel->name = $postData['name'];
            $couponModel->stime = strtotime($stime);
            $couponModel->etime = strtotime($etime);
            $couponModel->day = $postData['day'];
            $couponModel->money = $postData['money'];
            $couponModel->money_off = $postData['money_off'];
            $couponModel->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $this->assign('coupon',$couponModel);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = CouponModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}