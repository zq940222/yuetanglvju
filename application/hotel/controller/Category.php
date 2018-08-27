<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/1
 * Time: 9:51
 */

namespace app\hotel\controller;


use app\hotel\model\Hotel;
use app\hotel\model\HotelFiltrate;
use app\lib\exception\HotelAdminException;
use app\lib\exception\SuccessMessage;
use think\Db;
use think\Exception;
use think\Log;

class Category extends BaseController
{
    public function lists()
    {
        $admin = session('admin');
        $hotelModel = Hotel::get($admin['hotel_id'],['filtrate','filtrate.parentCate']);
        $category = $hotelModel->filtrate;
        $this->assign('category',$category);
        return $this->fetch();
    }

    public function edit()
    {
        $admin = session('admin');
        if (request()->isPost()) {
            $postData = input('filtrate_id/a',[]);
            $addData = [];
            foreach ($postData as $value)
            {
                $addData[] = [
                    'hotel_filtrate_id' => $value,
                    'hotel_id' => $admin['hotel_id']
                ];
            }
            Db::startTrans();
            try{
                Db::name('HotelHotelFiltrate')->where('hotel_id','=',$admin['hotel_id'])->delete();
                Db::name('HotelHotelFiltrate')->insertAll($addData);
                Db::commit();
                return json(new SuccessMessage(['msg' => '编辑成功']));
            }
            catch (Exception $ex){
                Db::rollback();
                Log::error($ex);
                throw new HotelAdminException(['msg' => '编辑失败']);
            }

        }
        $categoryIDs = Db::name('HotelHotelFiltrate')->where('hotel_id','=',$admin['hotel_id'])->column('hotel_filtrate_id');
        $this->assign('category_ids',$categoryIDs);
        $filtrate = HotelFiltrate::getByTree();
        $this->assign('filtrate',$filtrate);
        return $this->fetch();
    }

}