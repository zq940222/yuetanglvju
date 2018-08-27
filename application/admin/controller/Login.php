<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/5
 * Time: 19:38
 */

namespace app\admin\controller;


use app\admin\model\Admin;
use app\lib\exception\AdminException;
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
            $info = Admin::checkLogin($username, $password);
            if($info) {
                $admin=$info->toArray();
                session('admins', $admin);
                return json(new SuccessMessage(['msg' => '登录成功']));
            } else {
                throw new AdminException([
                    'code' => 400,
                    'msg' => '账号或密码错误'
                ]);
            }
        }
    }

    public function logout()
    {
        session('admins', null);
        $this->redirect('login/index');
    }

    public function _empty()
    {
        $this->redirect('login/index');
    }
}