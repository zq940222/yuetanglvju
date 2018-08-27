<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/28
 * Time: 15:47
 */

namespace app\admin\controller;

use app\api\model\Banner as BannerModel;
use app\api\model\BannerItem;
use app\api\validate\BannerNew;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\BannerMissException;

class Banner
{
    public function getBannerAll()
    {
        $bannerAll = BannerModel::getBannerAll();
        if (!$bannerAll) {
            throw new BannerMissException();
        }
        return $bannerAll;
    }

    public function getBannerItem($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $banner = BannerItem::getBannerItemByID($id);
        if (!$banner) {
            throw new BannerMissException();
        }
        return $banner;
    }

    public function addBannerItem()
    {
        $validate = new BannerNew();
        $validate->goCheck();

        $dataArray = $validate->getDataByRule(input('post.'));

        if($dataArray['image']) {
            $imageID = Upload::move($dataArray['image']);
            $dataArray['img_id'] = $imageID;
            unset($dataArray['image']);
        }

        BannerItem::create($dataArray);

        return new SuccessMessage();
    }

    public function editBannerItem()
    {
        (new IDMustBePostiveInt())->goCheck();

        $validate = new BannerNew();
        $validate->goCheck();

        $dataArray = $validate->getDataByRule(input('post.'));
        $id = Request::instance()->put('id/d');
        if($dataArray['image']) {
            $imageID = Upload::move($dataArray['image']);
            $dataArray['img_id'] = $imageID;
            unset($dataArray['image']);
        }

        BannerItem::save($dataArray,$id);

        return new SuccessMessage();
    }

    public function deleteBannerItem()
    {
        (new IDCollection())->goCheck();

        $ids = Request::instance()->delete('ids/a');
        BannerItem::destroy($ids);

        return new SuccessMessage([
            'code' => 204
        ]);
    }
}