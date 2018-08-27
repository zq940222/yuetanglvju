<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 19:08
 */

namespace app\lib\exception;


class HotelAdminException extends BaseException
{
    public $code = 404;
    public $msg = '请求失败';
    public $errorCode = 10000;
}