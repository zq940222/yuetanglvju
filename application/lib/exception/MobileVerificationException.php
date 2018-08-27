<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/26
 * Time: 16:10
 */

namespace app\lib\exception;


class MobileVerificationException extends BaseException
{
    public $code = 400;
    public $msg = '验证码错误';
    public $errorCode = 10000;
}