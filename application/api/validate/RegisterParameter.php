<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/10
 * Time: 11:53
 */

namespace app\api\validate;


class RegisterParameter extends BaseValidate
{
    protected $rule = [
        'account' => 'require|length:6,32',
        'password' => 'require|length:6,18',
        'repassword' => 'require|confirm:password'
    ];

    protected $message = [
        'account' => '账号必须是6到32位字符',
        'password' => '密码必须是6到18位字符',
        'repassword' => '两次密码不一致'
    ];
}