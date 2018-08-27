<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/10
 * Time: 18:20
 */

namespace app\admin\controller;

use app\admin\model\UserWithdraw as UserWithdrawModel;
use app\api\model\UserAccountDetails;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Log;

class UserDeposit extends BaseController
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
            $where['status'] = ['in',[2,3]];
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
        Db::startTrans();
        try{
            $model = UserWithdrawModel::get($id);
            $model->status = 3;
            $model->save();
            //TODO:: 刷新记录
            $accountDetailsModel = UserAccountDetails::get($model->account_details_id);
            $accountDetailsModel->status = 2;
            $accountDetailsModel->save();
            Db::commit();
            return json(new SuccessMessage(['msg' => '已转账']));
        }
        catch (Exception $ex)
        {
            Db::rollback();
            Log::error($ex);
            throw new AdminException(['msg' => '操作异常']);
        }

    }
}