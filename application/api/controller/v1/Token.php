<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/12
 * Time: 11:58
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\service\WxAuth;
use app\api\validate\Mobile;
use app\api\validate\TokenGet;
use app\lib\exception\SuccessMessage;

class Token
{
    public function getTokenByMobile($mobile = '', $code = '',$unionid = '')
    {
        (new Mobile())->goCheck();

        $ut = new UserToken();
        $token = $ut->get($mobile,$code,$unionid);
        return [
            'token' => $token
        ];
    }

    public function getTokenByWx($unionid = '')
    {
        $wxAuth = new WxAuth();
        $token = $wxAuth->grantToken($unionid);
        return [
            'token' => $token
        ];
    }

    public function bindWx($unionid = '')
    {
        (new TokenGet())->goCheck();
        $wxAuth = new WxAuth();
        $wxAuth->bind($unionid);
        return (new SuccessMessage());
    }

}