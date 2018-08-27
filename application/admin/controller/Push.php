<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 15:05
 */

namespace app\admin\controller;


use app\admin\model\PushMessage;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Push extends BaseController
{
    public function lists()
    {
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $title  = input('param.title/s', '');
        $status  = input('param.status/s', 'close');
        $date  = input('param.date/s');

        $model = new PushMessage();
        $where = [];
        if ($title)
        {
            $where['title'] = ['like',"%$title%"];
        }

        if (is_numeric($status))
        {
            $where['status'] = $status;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }
        $count = $model->where($where)
            ->count();
        $data = $model->where($where)
            ->order('create_time desc')
            ->page($page,$limit)
            ->select();

        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function add()
    {
        if (request()->isPost())
        {
            $postData = request()->post();
            $model = new PushMessage();
            $model->title = $postData['title'];
            $model->content = $postData['content'];
            $model->save();
            $pushMessageService = new \app\admin\service\PushMessage();
            $res = $pushMessageService->pushMessageToApp($postData['title'],$postData['content']);
            if ($res['result'] == 'ok')
            {
                $model->status = 1;
                $model->save();
                return json(new SuccessMessage(['msg' => '发布成功']));
            }
            else
            {
                $model->status = 2;
                $model->save();
                throw new AdminException(['msg' => '发布失败']);
            }

        }

        return $this->fetch();
    }


}