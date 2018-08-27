<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/8
 * Time: 15:47
 */

namespace app\api\controller\v1;

use app\api\model\CustomerService;
use app\api\model\HotelCrowdfunding;
use app\api\model\Image;
use app\api\model\Recharge;
use app\api\model\User as UserModel;
use app\api\model\UserAccountDetails;
use app\api\model\UserCoupon;
use app\api\model\UserWithdraw;
use app\api\service\Token as TokenService;
use app\api\validate\Money;
use app\api\validate\WithdrawNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Db;
use think\Exception;
use think\Log;

class User
{
    public function mine()
    {
        $uid = TokenService::getCurrentUid();
        $user = UserModel::with(['headimg','third'])->find($uid);
        if (!$user->headimg)
        {
            $user->headimg = '';
        }else{
            $user->headimg = $user->headimg->url;
        }
        $bind_wx = 0;
        if ($user->third)
        {
            $bind_wx = 1;
        }
        $user['bind_wx'] = $bind_wx;
        return $user;
    }


    public function edit()
    {
        $nickname = input('post.nickname/s','');
        $gender = input('post.gender/d',0);
        $headimg = input('post.headimg','');
        $uid = TokenService::getCurrentUid();
        $userModel = UserModel::get($uid);
        if ($headimg) {
            $imageModel = new Image();
            $imageModel->url = $headimg;
            $imageModel->save();
            $userModel->img_id = $imageModel->id;
        }
        $userModel->nickname = $nickname;
        $userModel->gender = $gender;
        $userModel->save();
        return json(new SuccessMessage(['msg' => '编辑成功']));
    }

    public function attention()
    {
        $uid = TokenService::getCurrentUid();
        $user = UserModel::with(['hotelAttention','hotelAttention.image','productAttention','productAttention.coverImage'])->find($uid);
        return $user;
    }

    public function coupon()
    {
        $status = 1;
        $uid = TokenService::getCurrentUid();
        $coupon = UserCoupon::where('user_id','=',$uid)
            ->where('status','=',$status)
            ->select();
        return $coupon;
    }

    public function accountDetail($page = 1,$size = 10)
    {
        $uid = TokenService::getCurrentUid();
        $status = 2;
        $detail = UserAccountDetails::where('status','=',$status)
            ->where('user_id','=',$uid)
            ->paginate($size,true,['page'=> $page]);
        return $detail;
    }

    public function crowdFunding()
    {
        $data = HotelCrowdfunding::with(['image'])->find();
        return $data;
    }

    public function customerService()
    {
        $customer = CustomerService::find();
        return $customer;
    }

    public function recharge($money)
    {
        (new Money())->goCheck();
        $uid = TokenService::getCurrentUid();
        $res = Recharge::recharge($uid,$money);
        return $res;
    }

    public function withdraw()
    {
        $validate = new WithdrawNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $postData = $validate->getDataByRule(input('post.'));
        $userModel = UserModel::get($uid);
        if ($userModel->balance < $postData['money'])
        {
            throw new UserException(['msg' => '余额不足']);
        }
        Db::startTrans();
        try{
            //添加记录
            $model = UserAccountDetails::create([
                'user_id' => $uid,
                'type' => 1,
                'money' => $postData['money']
            ]);
            $userModel->setDec('balance',$postData['money']);
            $postData['user_id'] = $uid;
            $postData['nickname'] = $userModel->nickname;
            $postData['account_details_id'] = $model->id;
            UserWithdraw::create($postData);
            Db::commit();
            return json(new SuccessMessage(['msg' => '申请成功']));
        }
        catch (Exception $ex)
        {
            Db::rollback();
            Log::error($ex);
            throw new UserException(['msg' => '操作异常']);
        }
    }
}