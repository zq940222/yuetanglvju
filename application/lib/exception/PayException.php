<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/25
 * Time: 15:15
 */

namespace app\lib\exception;


class PayException extends BaseException
{
    public $code = 400;
    public $msg = '提交订单失败';
    public $errorCode = 80000;
}