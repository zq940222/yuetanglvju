<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/25
 * Time: 11:00
 */

namespace app\api\validate;


class AddToCart extends BaseValidate
{
    protected $rule = [
        'product_id' => 'require|isPositiveInteger',
        'item_id' => 'require',
        'product_num' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'product_id' => '产品ID不正确哦',
        'item_id' => '规则ID不正确哦',
        'product_num' => '产品数量不正确'
    ];
}