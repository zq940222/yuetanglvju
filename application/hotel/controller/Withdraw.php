<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 10:16
 */

namespace app\hotel\controller;

use app\hotel\model\Hotel;
use app\hotel\model\Withdraw as WithdrawModel;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Log;

class Withdraw extends BaseController
{
    public function lists()
    {
        $admin = session('admin');
        $hotel = Hotel::get($admin['hotel_id']);
        $this->assign('can_withdraw',$hotel->can_withdraw);
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
            $where['status'] = 1;
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

    public function add()
    {
        $admin = session('admin');
        $hotel = Hotel::get($admin['hotel_id']);
        if (request()->isPost()) {
            $postData = request()->post();
            if ($postData['money'] < 10)
            {
                throw new HotelAdminException(['msg' => '提现金额最少为10']);
            }
            if ($postData['money'] > $hotel->can_withdraw)
            {
                throw new HotelAdminException(['msg' => '提现金额不能大于可提现金额']);
            }
            Db::startTrans();
            try{
                $withdrawModel = new WithdrawModel();
                $withdrawModel->hotel_id = $hotel->id;
                $withdrawModel->hotel_name = $hotel->name;
                $withdrawModel->money = $postData['money'];
                $withdrawModel->bank_name = $postData['bank_name'];
                $withdrawModel->bank_account_num = $postData['bank_account_num'];
                $withdrawModel->bank_account = $postData['bank_account'];
                $withdrawModel->save();
                $hotel->setDec('can_withdraw',$postData['money']);
                Db::commit();
                return json(new SuccessMessage(['msg' => '申请成功']));
            }
            catch (Exception $ex) {
                Db::rollback();
                Log::error($ex);
                throw new HotelAdminException(['msg' => '申请失败']);
            }
        }
        $this->assign('can_withdraw',$hotel->can_withdraw);
        return $this->fetch();

    }

    public function edit($id)
    {
        $withdrawModel = WithdrawModel::get($id);
        $admin = session('admin');
        if ($withdrawModel->hotel_id != $admin['hotel_id'])
        {
            throw new HotelAdminException(['msg' => '你没有权限这么做']);
        }
        if (request()->isPost())
        {
            $postData = request()->post();
            $withdrawModel->bank_name = $postData['bank_name'];
            $withdrawModel->bank_account_num = $postData['bank_account_num'];
            $withdrawModel->bank_account = $postData['bank_account'];
            $withdrawModel->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));

        }
        $this->assign('withdraw',$withdrawModel);
        return $this->fetch();
    }

    public function delete()
    {
        $admin = session('admin');
        $id=input('param.id/d');
        $withdrawModel = WithdrawModel::get($id);
        if ($withdrawModel->status != 1) {
            throw new HotelAdminException(['msg' => '不可以这样操作']);
        }
        if ($withdrawModel->hotel_id != $admin['hotel_id']) {
            throw new HotelAdminException(['msg' => '你没有权限这样做']);
        }
        Db::startTrans();
        try{
            $hotelModel = Hotel::get($admin['hotel_id']);
            $hotelModel->setInc('can_withdraw',$withdrawModel->money);
            WithdrawModel::destroy($id);
            Db::commit();
            return json(new SuccessMessage(['msg' => '删除成功']));
        }
        catch (Exception $ex){
            Db::rollback();
            Log::error($ex);
            throw new HotelAdminException(['msg' => '删除失败']);
        }

    }
}