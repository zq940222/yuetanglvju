<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 14:10
 */

namespace app\hotel\controller;

use app\hotel\model\Withdraw as WithdrawModel;

class Deposit extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $status = input('param.status/d');
        $bankAccount = input('param.bank_account/s');
        $bankAccountNum  = input('param.bank_account_num/s');
        $date  = input('param.date/s');

        $admin = session('admin');
        $where['hotel_id'] = $admin['hotel_id'];

        if($status) {
            $where['status'] = $status;
        }else{
            $where['status'] = ['in',[2,3]];
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

        $data  = WithdrawModel::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        $count = WithdrawModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }
}