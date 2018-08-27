<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/4/26
 * Time: 12:26
 */

namespace app\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg = 'wechat unknown error';
    public $errorCode = 999;
}