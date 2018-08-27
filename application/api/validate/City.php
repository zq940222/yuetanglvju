<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/9
 * Time: 15:13
 */

namespace app\api\validate;


class City extends BaseValidate
{
    protected $rule = [
        'city' => 'require|isNotEmpty'
    ];

    protected $message = [
        'city' => 'city不能为空'
    ];
}