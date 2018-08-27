<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 9:22
 */

namespace app\lib\exception;


class AdminException extends BaseException
{
    public $code = 404;
    public $msg = '请求失败';
    public $errorCode = 10000;
}