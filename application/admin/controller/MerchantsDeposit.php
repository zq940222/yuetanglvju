<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/24
 * Time: 16:05
 */

namespace app\admin\controller;

use app\admin\model\Hotel as HotelModel;
use app\admin\model\Withdraw;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Log;

class MerchantsDeposit extends BaseController
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
            $where['status'] = ['in',[2,3]];
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
        Db::startTrans();
        try{
            $model = Withdraw::get($id);
            $model->status = 3;
            $model->save();
            $hotelModel = HotelModel::get($model->hotel_id);
            $hotelModel->setInc('withdrawed',$model->money);
            Db::commit();
            return json(new SuccessMessage(['msg' => '已转账']));
        }
        catch (Exception $ex)
        {
            Db::rollback();
            Log::error($ex);
            throw new AdminException(['msg' => '操作失败']);
        }

    }
}