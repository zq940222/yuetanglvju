<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $data = model('VersionUpgrade')->order('create_time desc')
            ->where('status','=',1)
            ->find();
        $this->assign('data',$data);
        return $this->fetch();
    }
}
