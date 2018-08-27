<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 11:45
 */

namespace app\lib\exception;


class UploadException extends BaseException
{
    public $code = 400;
    public $msg = '上传失败';
    public $errorCode = 10006;
}