<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/20
 * Time: 11:52
 */

namespace app\admin\controller;

use app\admin\model\User as UserModel;

class Black extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $nickname  = input('param.nickname/s', '');
        $gender  = input('param.gender/d', -1);
        $mobile  = input('param.mobile', '');
        $where=[];
        if($nickname) {
            $where['nickname']=['like', '%'.$nickname.'%'];
        }

        if($gender>-1) {
            $where['gender']=$gender;
        }

        if($mobile) {
            $where['mobile']=$mobile;
        }

        $count  = UserModel::onlyTrashed()->where($where)->count();
        $data  = UserModel::onlyTrashed()->order('update_time desc')->where($where)->page($page, $limit)->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail($id)
    {
        $user = UserModel::onlyTrashed()
            ->with(['image'])
            ->find($id);

        $this->assign('user',$user);
        return $this->fetch();
    }

    public function restore()
    {
        $id=input('param.id/d');
        $user = UserModel::withTrashed()
            ->find($id);
        $user->delete_time = null;
        $res = $user->save();
        if($res) {
            return $this->success('还原成功');
        } else {
            return $this->error('还原失败');
        }
    }
}