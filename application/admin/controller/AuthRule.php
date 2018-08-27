<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/23
 * Time: 14:38
 */

namespace app\admin\controller;

use app\admin\model\AuthRule as AuthRuleModel;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class AuthRule extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $keyword = input('param.keyword/s','');
        $model  = new AuthRuleModel();
        $where = [];
        if ($keyword) {
            $where['title|name'] = ['like',"%$keyword%"];
        }
        $count  = $model->where($where)->count();
        $data  = $model->order('create_time desc')->where($where)->page($page, $limit)->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost()) {
            $title = input('post.title/s','');
            $name = input('post.name/s','');
            $model = new AuthRuleModel();
            $model->title = $title;
            $model->name = $name;
            $model->save();
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()) {
            $title = input('post.title/s','');
            $name = input('post.name/s','');
            $model = AuthRuleModel::get($id);
            $model->title = $title;
            $model->name = $name;
            $model->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $rule = AuthRuleModel::get($id);
        $this->assign('rule',$rule);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = AuthRuleModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }
}