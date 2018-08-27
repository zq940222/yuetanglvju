<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/21
 * Time: 16:36
 */

namespace app\admin\controller;


use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Role extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $model=model('AuthGroup');
        $count  = $model->count();
        $data  = $model->page($page, $limit)->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost()) {
            $title = input('post.title/s','');
            $intro = input('post.intro/s','');
            $rules = input('post.rule/a',[]);

            $model = new AuthGroup();
            $model->title = $title;
            $model->intro = $intro;
            $model->rules = $rules;
            $model->save();
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $rule = AuthRule::all();
        $this->assign('rule',$rule);
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $title = input('post.title/s','');
            $intro = input('post.intro/s','');
            $rules = input('post.rule/a',[]);

            $model = AuthGroup::get($id);
            $model->title = $title;
            $model->intro = $intro;
            $model->rules = $rules;
            $model->save();
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        $group = AuthGroup::get($id);
        $this->assign('group',$group);
        $rule = AuthRule::all();
        $this->assign('rule',$rule);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = AuthGroup::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}