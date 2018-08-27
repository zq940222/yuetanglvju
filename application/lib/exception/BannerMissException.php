<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 19:20
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 400;
    public $msg = '请求Banner不存在';
    public $errorCode = 40000;
}