<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/5
 * Time: 19:22
 */

namespace app\admin\controller;


class Index extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }
}