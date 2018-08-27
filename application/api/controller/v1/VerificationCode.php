<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/26
 * Time: 10:59
 */

namespace app\api\controller\v1;

use app\api\service\VerificationCode as VerificationCodeService;
use app\api\validate\Mobile;
use app\lib\exception\SuccessMessage;

class VerificationCode
{
    public function sendCode($mobile = '')
    {
        (new Mobile())->goCheck();

        VerificationCodeService::generateVerificationCode($mobile);
        return json(new SuccessMessage([
            'msg' => '发送成功'
        ]),201);
    }
}