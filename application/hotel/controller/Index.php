<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 19:56
 */

namespace app\hotel\controller;


class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }
}