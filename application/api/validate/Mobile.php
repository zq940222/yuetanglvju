<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/26
 * Time: 11:06
 */

namespace app\api\validate;


class Mobile extends BaseValidate
{
    protected $rule = [
        'mobile' => 'require|isMobile'
    ];

    protected $message = [
        'mobile' => '请输入正确的手机号'
    ];
}