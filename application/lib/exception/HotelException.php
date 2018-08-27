<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 15:49
 */

namespace app\lib\exception;


class HotelException extends BaseException
{
    public $code = 404;
    public $msg = '没有找到相关酒店信息';
    public $errorCode = 70001;
}