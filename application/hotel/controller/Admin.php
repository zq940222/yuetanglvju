<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/2
 * Time: 14:34
 */

namespace app\hotel\controller;


use app\hotel\model\HotelAdmin;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;
use think\Session;

class Admin extends BaseController
{
    /**
     * @desc 修改密码
     * @return mixed|\think\response\Json
     * @throws AdminException
     */
    public function editPassword()
    {
        $username = session('admin.account');
        if (request()->isPost()) {
            $password = input('post.password/s','');
            $newPassword = input('post.new_password/s','');
            $admin = HotelAdmin::checkLogin($username,$password);
            if (!$admin) {
                throw new HotelAdminException(['msg' => '原密码错误']);
            }
            $admin->password = $newPassword;
            $admin->save();
            Session::destroy();
            return json(new SuccessMessage(['msg' => '修改成功']));
        }
        $this->assign('username',$username);
        return $this->fetch();
    }
}