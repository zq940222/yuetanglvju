<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/21
 * Time: 14:28
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class AuthGroup extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function setRulesAttr($value)
    {
        return implode(',',$value);
    }

    public function getRulesAttr($value)
    {
        return explode(',',$value);
    }
}