<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/25
 * Time: 15:15
 */

namespace app\lib\exception;


class CartException extends BaseException
{
    public $code = 400;
    public $msg = '添加购物车失败';
    public $errorCode = 10000;
}