<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 11:44
 */

namespace app\admin\controller;


use app\admin\model\Setting;
use app\lib\exception\SuccessMessage;

class Integral extends BaseController
{
    public function lists()
    {
        $data = Setting::find();
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function edit($id)
    {
        $model = Setting::get($id);
        if (request()->isPost()) {
            $integralRatio = input('post.integral_ratio');
            $royaltyRatio = input('post.royalty_ratio');
            $model->integral_ratio = $integralRatio;
            $model->royalty_ratio = $royaltyRatio;
            $model->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $this->assign('data',$model);
        return $this->fetch();
    }
}