<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/4/26
 * Time: 10:53
 */

namespace app\api\service;


use app\api\model\Third;
use app\api\model\User;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;

class WxAuth extends Token
{
    public function bind($unionid)
    {
        $uid = Token::getCurrentUid();
        Third::where('user_id','=',$uid)->delete();
        Third::create([
            'user_id' => $uid,
            'platform' => 'wechat',
            'unionid' => $unionid,
            'status' => 1
        ]);
        return true;
    }

    public function grantToken($unionid)
    {
        $thirdModel = Third::where('unionid','=',$unionid)
            ->where('status','=',1)
            ->where('platform','=','wechat')
            ->find();

        if ($thirdModel) {
            $uid = $thirdModel->user_id;
        }else{
            throw new TokenException(['code' => 402,'msg' => '需要绑定手机号']);
        }
        $user = User::find($uid);
        $cachedValue = $this->prepareCachedValue($user);
        $token = $this->saveToCache($cachedValue);
        return  $token;
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

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}