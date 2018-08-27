<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/10
 * Time: 16:52
 */

namespace app\admin\controller;

use app\admin\model\UserWithdraw as UserWithdrawModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class UserWithdraw extends BaseController
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
        $aliAccount = input('param.ali_account/s');
        $aliAccountNum  = input('param.ali_account_num/s');
        $date  = input('param.date/s');


        $where=[];
        if($status) {
            $where['status'] = $status;
        }else{
            $where['status'] = 1;
        }

        if($aliAccount) {
            $where['ali_account'] = $aliAccount;
        }

        if($aliAccountNum) {
            $where['ali_account_num']=$aliAccountNum;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $data  = UserWithdrawModel::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        $count = UserWithdrawModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $status = input('post.status/d');
            $remark = input('post.remark/s','');
            $model = UserWithdrawModel::get($id);
            $model->status = $status;
            $model->remark = $remark;
            $model->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $data = UserWithdrawModel::get($id);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function pass()
    {
        $id=input('param.id/a');
        $res = UserWithdrawModel::where('id','in',$id)->update(['status'=>2]);
        if($res) {
            return json(new SuccessMessage(['msg' => '审核通过']));
        } else {
            throw new AdminException(['msg' => '操作失败']);
        }
    }
}