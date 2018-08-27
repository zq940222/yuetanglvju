<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/25
 * Time: 15:11
 */

namespace app\admin\controller;

use app\admin\model\Expend as ExpendModel;

class Expend extends BaseController
{
    public function lists()
    {
        $admin = \app\admin\model\Admin::all();
        $this->assign('admin',$admin);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $adminID  = input('param.admin_id/d');
        $date  = input('param.date/s');

        $where=[];

        if($adminID) {
            $where['admin_id'] = $adminID;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $data  = ExpendModel::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select();
        $count = ExpendModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }
}