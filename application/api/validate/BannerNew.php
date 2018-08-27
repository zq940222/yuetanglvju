<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 19:44
 */

namespace app\api\validate;


class BannerNew extends BaseValidate
{
    protected $rule = [
        'image' => 'require',
        'banner_id' => 'require|isPositiveInteger',
        'jump_address' => 'require|isNotEmpty',
        'key_word' => 'require'
    ];

    protected $message = [
        'image' => '图片上传失败',
        'banner_id' => 'BannerID为正整数',
        'jump_address' => '跳转地址不能为空',
        'key_word' => '关键词错误'
    ];
}