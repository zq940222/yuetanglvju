<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/26
 * Time: 10:46
 */

namespace app\api\service;


use app\lib\exception\MobileVerificationException;
use think\Session;

class VerificationCode
{
    public static function generateVerificationCode($mobile)
    {
        $code = self::generateCode(6);
        Session::set('mobile',$mobile);
        Session::set('code',$code);
        self::sendCode($mobile,$code);
    }

    public static function generateCode($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }

    public static function sendCode($mobile,$code)
    {
        $result = SMS::sendSms($mobile,$code);
        return $result;
    }

    public static function checkCode($mobile,$code)
    {
        if ($mobile != Session::get('mobile')){
            throw new MobileVerificationException([
                'msg' => '手机号错误'
            ]);
        }
        if ($code != Session::get('code')) {
            throw new MobileVerificationException();
        }
        return true;
    }
}