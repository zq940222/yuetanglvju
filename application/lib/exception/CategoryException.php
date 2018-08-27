<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/4
 * Time: 9:59
 */

namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 403;
    public $msg = '请求分类不存在';
    public $errorCode = 50000;
}