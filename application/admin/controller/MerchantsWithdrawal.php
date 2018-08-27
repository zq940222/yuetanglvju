<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/24
 * Time: 10:26
 */

namespace app\admin\controller;


use app\admin\model\Withdraw;
use app\admin\model\Hotel as HotelModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class MerchantsWithdrawal extends BaseController
{
    public function lists()
    {
        $hotel = HotelModel::all();
        $this->assign('hotel',$hotel);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $status = input('param.status/d');
        $hotelID  = input('param.hotel_id/d');
        $bankAccount = input('param.bank_account/s');
        $bankAccountNum  = input('param.bank_account_num/s');
        $date  = input('param.date/s');


        $where=[];
        if($status) {
            $where['status'] = $status;
        }else{
            $where['status'] = 1;
        }

        if($hotelID) {
            $where['hotel_id'] = $hotelID;
        }

        if($bankAccount) {
            $where['bank_account'] = $bankAccount;
        }

        if($bankAccountNum) {
            $where['bank_account_num']=$bankAccountNum;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $data  = Withdraw::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        $count = Withdraw::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $status = input('post.status/d');
            $remark = input('post.remark/s','');
            $model = Withdraw::get($id);
            $model->status = $status;
            $model->remark = $remark;
            $model->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $data = Withdraw::get($id,['hotel']);

        $this->assign('data',$data);
        return $this->fetch();
    }

    public function pass()
    {
        $id=input('param.id/a');
        $res = Withdraw::where('id','in',$id)->update(['status'=>2]);
        if($res) {
            return json(new SuccessMessage(['msg' => '审核通过']));
        } else {
            throw new AdminException(['msg' => '操作失败']);
        }
    }
}