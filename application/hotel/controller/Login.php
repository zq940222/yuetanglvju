<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 19:05
 */

namespace app\hotel\controller;


use app\hotel\model\HotelAdmin;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;
use think\Controller;

class Login extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        if(request()->isPost()) {
            $username=input('post.username/s', '');
            $password=input('post.password/s', '');
            $info = HotelAdmin::checkLogin($username, $password);
            if($info) {
                $admin=$info->toArray();
                session('admin', $admin);
                return json(new SuccessMessage(['msg' => '登录成功']));
            } else {
                throw new HotelAdminException([
                    'code' => 400,
                    'msg' => '账号或密码错误'
                ]);
            }
        }
    }

    public function logout()
    {
        session('admin', null);
        $this->redirect('login/index');
    }

    public function _empty()
    {
        $this->redirect('login/index');
    }
}