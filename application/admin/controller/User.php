<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/20
 * Time: 9:12
 */

namespace app\admin\controller;

use app\admin\model\Image;
use app\admin\model\User as UserModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class User extends BaseController
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
        $userModel  = new UserModel;
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

        $count  = $userModel->where($where)->count();
        $data  = $userModel->order('create_time desc')->where($where)->page($page, $limit)->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if(request()->isPost()) {
            $model=new UserModel();
            $post=request()->only(['nickname', 'mobile', 'gender','img']);
            $res = UserModel::withTrashed()->where('mobile',$post['mobile'])->find();
            if ($res) {
                throw new AdminException(['msg' => '该手机号已被注册']);
            }
            if ($post['img']) {
                $image = new Image();
                $image->url = $post['img'];
                $image->save();
                $post['img_id'] = $image->id;
            }
            unset($post['img']);

            if($model->save($post)) {
                return $this->success('添加成功', 'lists');
            } else {
                return $this->error('添加失败');
            }
        }
        return $this->fetch();
    }

    public function detail($id)
    {
        $user = UserModel::with(['image'])
            ->find($id);

        $this->assign('user',$user);
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()){
            $nickname = input('post.nickname/s','');
            $img = input('post.img');
            $gender = input('post.gender');

            $user = UserModel::get($id);
            $user->nickname = $nickname;
            $user->gender = $gender;
            if ($img) {
                $image = new Image();
                $image->url = $img;
                $image->save();
                $user->img_id = $image->id;
            }
            $user->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $user = UserModel::get($id,['image']);
        $this->assign('user',$user);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = UserModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}