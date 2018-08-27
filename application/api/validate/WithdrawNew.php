<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/10
 * Time: 17:12
 */

namespace app\api\validate;


class WithdrawNew extends BaseValidate
{
    protected $rule = [
        'ali_account' => 'require|isNotEmpty',
        'ali_account_num' => 'require|isNotEmpty',
        'money' => 'require|between:1,2000|number',
    ];

    protected $message = [
        'ali_account' => '支付宝昵称不能为空',
        'ali_account_num' => '支付宝账号不能为空',
        'money' => '金额必须在1到2000之间'
    ];
}