<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/23
 * Time: 11:29
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class AuthRule extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}