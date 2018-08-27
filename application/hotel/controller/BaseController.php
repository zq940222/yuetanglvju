<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 19:04
 */

namespace app\hotel\controller;


use think\Controller;

class BaseController extends Controller
{
    public function _initialize()
    {
        // 判断用户是否登录
        if(!session('?admin')) {
            $this->redirect('login/index');
        }

    }
}