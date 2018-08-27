<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/5
 * Time: 19:11
 */

namespace app\admin\controller;


use app\lib\exception\AdminException;
use think\Auth;
use think\Controller;
use think\Request;

class BaseController extends Controller
{
    protected $beforeActionList=[
        'auth'=>['only'=>'lists']
    ];

    public function _initialize()
    {
        // 判断用户是否登录
        if(!session('?admins')) {
            $this->redirect('login/index');
        }

    }

    public function auth()
    {
        $cName = Request::instance()->controller();
        $aName = Request::instance()->action();
        $name = $cName.'/'.$aName;
        $admin = session('admins');
        // 获取auth实例
        $auth = Auth::instance();
        // 检测权限
        if (!$auth->check($name, $admin['id'])) {// 第一个参数是规则名称,第二个参数是用户UID
            throw new AdminException(['msg' => '没有权限,请联系超级管理员']);
        }
    }

    public function change()
    {
        $id    = input('post.id/d', 0);
        $field = input('post.field/s', '');
        $model = input('post.model/s', '');
        $model = model($model);
        $info  = $model->find($id);
        $info->$field=!$info->getData($field);
        $info->save();

        return $info->$field;
    }
}