<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/11
 * Time: 11:32
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\UserCoupon;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Db;
use app\api\service\Token as TokenService;
use app\api\model\Coupon as CouponModel;

class Coupon extends BaseController
{
    public function popup()
    {
        $uid = TokenService::getCurrentUid();
        $ids = Db::name('CouponUser')->where('user_id','=',$uid)->column('coupon_id');
        $couponModel = CouponModel::where('id','not in',$ids)
            ->where('status','=',1)
            ->where('stime','<=',time())
            ->where('etime','>',time()-24*60*60)
            ->select();
        return $couponModel;
    }

    public function getCoupon($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $uid = TokenService::getCurrentUid();
        $res = Db::name('CouponUser')
            ->where('user_id','=',$uid)
            ->where('coupon_id','=',$id)
            ->find();
        if ($res)
        {
            throw new UserException(['msg' => '你已经领取过该优惠券了哦']);
        }
        Db::name('CouponUser')->insert([
            'user_id' => $uid,
            'coupon_id' => $id
        ]);
        $couponModel = CouponModel::get($id);
        $userCouponModel = new UserCoupon();
        $userCouponModel->user_id = $uid;
        $userCouponModel->money = $couponModel->money;
        $userCouponModel->money_off = $couponModel->money_off;
        $userCouponModel->start_time = date('Y-m-d',time());
        $userCouponModel->end_time = date('Y-m-d',strtotime('+' .$couponModel->day.'day'));
        $userCouponModel->name = $couponModel->name;
        $userCouponModel->save();

        return json(new SuccessMessage(['msg' => '领取成功']));
    }
}