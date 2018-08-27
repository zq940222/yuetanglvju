<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/21
 * Time: 10:12
 */

namespace app\admin\controller;


use app\admin\model\HotelCrowdfunding;
use app\admin\model\Image;
use app\lib\exception\SuccessMessage;

class Crowdfunding extends BaseController
{
    public function lists()
    {
        $crowdfunding = HotelCrowdfunding::with(['image'])->find();
        $this->assign('crowdfunding',$crowdfunding);
        return $this->fetch();
    }

    public function edit($id)
    {

        $crowdfunding = HotelCrowdfunding::with(['image'])->find($id);
        if (request()->isPost())
        {
            $postData = request()->post();
            if ($postData['image'])
            {
                $image = new Image();
                $image->url = $postData['image'];
                $image->save();
                $crowdfunding->img_id = $image->id;
            }
            $crowdfunding->content = $postData['content'];
            $crowdfunding->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $this->assign('crowdfunding',$crowdfunding);
        return $this->fetch();
    }
}