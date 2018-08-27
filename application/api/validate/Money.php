<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/28
 * Time: 18:49
 */

namespace app\api\validate;


class Money extends BaseValidate
{
    protected $rule = [
        'money' => 'require|isNotEmpty|number'
    ];

    protected $message = [
        'money' => '请传正确金额'
    ];
}