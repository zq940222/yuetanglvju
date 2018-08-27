<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/17
 * Time: 16:00
 */

namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use app\admin\model\AuthGroup;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Session;

class Admin extends BaseController
{

    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $keywords = input('keywords/s');

        $where = [];
        if($keywords){
            $where['username'] = ['like',"%$keywords%"];
        }

        $data  = AdminModel::with(['authGroup'])
            ->order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        foreach ($data as $key => $value)
        {
            $role = '';
            foreach ($value['authGroup'] as $v)
            {
                $role .= $v['title'];
                $role .= ',';
            }
            $data[$key]['role'] = $role;
        }
        $count = AdminModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost())
        {
            $username = input('post.username/s','');
            $password = input('post.password/s','');
            $role = input('post.role/a',0);
            $adminModel = new AdminModel();

            $adminModel->username = $username;
            $adminModel->password = $password;
            $adminModel->save();

            if ($role) {
                $insertData = [];
                foreach ($role as $v)
                {
                    $insertData[] = [
                        'uid' => $adminModel->id,
                        'group_id' => $v
                    ];
                }
                Db::name('auth_group_access')->insertAll($insertData);
            }
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $role = AuthGroup::all();
        $this->assign('role',$role);
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $username = input('post.username/s','');
            $role = input('post.role/a',0);
            $adminModel = AdminModel::get($id);

            $adminModel->username = $username;
            $adminModel->save();

            if ($role) {
                Db::name('auth_group_access')->where('uid',$id)->delete();
                $insertData = [];
                foreach ($role as $v)
                {
                    $insertData[] = [
                        'uid' => $adminModel->id,
                        'group_id' => $v
                    ];
                }
                Db::name('auth_group_access')->insertAll($insertData);
            }
        }
        $admin = AdminModel::get($id);
        $groupIDs = Db::name('auth_group_access')->where('uid',$id)->column('group_id');
        $admin['group'] = $groupIDs;
        $this->assign('admin',$admin);
        $role = AuthGroup::all();
        $this->assign('role',$role);
        return $this->fetch();
    }

    /**
     * @desc 修改密码
     * @return mixed|\think\response\Json
     * @throws AdminException
     */
    public function editPassword()
    {
        $username = session('admins.username');
        if (request()->isPost()) {
            $password = input('post.password/s','');
            $newPassword = input('post.new_password/s','');
            $admin = AdminModel::checkLogin($username,$password);
            if (!$admin) {
                throw new AdminException(['msg' => '原密码错误']);
            }
            $admin->password = $newPassword;
            $admin->save();
            Session::destroy();
            return json(new SuccessMessage(['msg' => '修改成功']));
        }
        return $this->fetch();
    }
}