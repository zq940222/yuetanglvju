<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 11:56
 */

namespace app\admin\controller;


use app\admin\model\VersionUpgrade;
use app\lib\exception\SuccessMessage;

class Upgrade extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');

        $model = new VersionUpgrade();
        $data  = $model->order('create_time DESC')
            ->page($page,$limit)
            ->select();
        $count = $model->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost()){
            $post = input('post.');
            $model = new VersionUpgrade();
            $model->allowField(true)->save($post);
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()) {

        }
        $model = VersionUpgrade::get($id);
        $this->assign('version',$model);
        return $this->fetch();
    }
}