<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/11
 * Time: 16:59
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class ProductType extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
}