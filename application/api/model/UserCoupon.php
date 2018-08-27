<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/9
 * Time: 14:37
 */

namespace app\api\model;


use traits\model\SoftDelete;

class UserCoupon extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}