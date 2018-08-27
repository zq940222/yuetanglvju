<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/8
 * Time: 10:15
 */

namespace app\api\service;


use app\api\model\Third;
use app\api\model\User;
use app\lib\exception\TokenException;

class UserToken extends Token
{
    public function get($mobile,$code,$unionid='')
    {
//        VerificationCode::checkCode($mobile,$code);

        //检查该手机号用户是否存在
        $user = User::where('mobile','=',$mobile)
            ->find();
        if (!$user) {
            $data = [
                'mobile' => $mobile,
                'nickname' => 'm'.$mobile
            ];
            $user = User::create($data);
        }
        if ($unionid)
        {
            $thirdModel = new Third();
            $thirdModel->unionid = $unionid;
            $thirdModel->platform = 'wechat';
            $thirdModel->user_id = $user->id;
            $thirdModel->status = 1;
            $thirdModel->save();
        }
        return $this->grantToken($user);
    }

    private function grantToken($user)
    {
        $cachedValue = $this->prepareCachedValue($user);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
//        $expire_in = config('setting.token_expire_in');

        $request = cache($key, $value);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($user){
        $cachedValue['uid'] = $user['id'];
        return $cachedValue;
    }

}