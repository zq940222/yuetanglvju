<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/6
 * Time: 16:29
 */

namespace app\admin\controller;


use app\admin\model\Hotel as HotelModel;
use app\admin\model\HotelAdmin;
use app\admin\model\Region;
use app\admin\model\ScenicArea;
use app\lib\exception\AdminException;
use app\lib\exception\SuccessMessage;

class Hotel extends BaseController
{

    public function lists()
    {
        $region = Region::getRegion();
        $this->assign('region',$region);
        return $this->fetch();
    }

    public function page()
    {
        $page   = input('param.page/d', 1);
        $limit  = input('param.limit/d');
        $province = input('param.province/d');
        $city  = input('param.city/d');
        $district = input('param.district/d');
        $name  = input('param.name/s', '');
        $date  = input('param.date/s');


        $where=[];
        if($name) {
            $where['name'] = ['like', '%'.$name.'%'];
        }

        if($province) {
            $where['province'] = $province;
        }

        if($city) {
            $where['city'] = $city;
        }

        if($district) {
            $where['district']=$district;
        }

        if($date) {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $data  = HotelModel::order('create_time DESC')
            ->where($where)
            ->page($page,$limit)
            ->select()
            ->toArray();
        $count = HotelModel::where($where)
            ->count();
        return json(['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data]);
    }

    public function detail($id)
    {
        $hotel = HotelModel::with(['image','province','city','district','businessLicense','scenicArea'])
            ->find($id)
            ->toArray();
        //TODO :: 审核
        if (request()->isPost()){
            $hotel = HotelModel::with(['image','province','city','district','businessLicense','scenicArea'])
                ->find($id);
            $postData = request()->post();
            if ($postData['status'] == 1)
            {
                if (!$hotel->image)
                {
                    throw new AdminException(['msg' => '请联系商家上传封面图片']);
                }
                if (!$hotel->businessLicense)
                {
                    throw new AdminException(['msg' => '请联系卖家上传营业执照']);
                }

            }
            $hotel->status = $postData['status'];
            $hotel->save();
            return json(new SuccessMessage(['msg' => '操作成功']));
        }
        $this->assign('hotel',$hotel);
        return $this->fetch();
    }

    public function add()
    {
        if (request()->isPost()){
            $post = input('post.');
            $res = HotelAdmin::where('account','=',$post['account'])->find();
            if ($res) {
                throw new AdminException(['msg' => '该账户已存在']);
            }
            $hotel = new HotelModel();
            $hotel->name = $post['name'];
            $hotel->save();

            $hotel->admin() ->save([
                'account' => $post['account'],
                'password' => $post['password']
            ]);
            return json(new SuccessMessage(['msg' => '添加成功']));
        }
        return $this->fetch();
    }

    public function edit($id)
    {
        if (request()->isPost()){
            $name = input('post.name/s','');
            $minPrice = input('post.min_price');
            $province = input('post.province');
            $city = input('post.city');
            $district = input('post.district');
            $detailAddress = input('post.detail_address/s','');
            $remark = input('post.remark/s','');
            $scenicAreaID = input('post.scenic_area/d',0);
            $phone = input('post.phone/s');
            $groupNum = input('post.group_num/d',2);

            $hotel = HotelModel::get($id);
            $hotel->name = $name;
            $hotel->min_price = $minPrice;
            $hotel->province = $province;
            $hotel->city = $city;
            $hotel->district = $district;
            $hotel->detail_address = $detailAddress;
            $hotel->remark = $remark;
            $hotel->scenic_area_id = $scenicAreaID;
            $hotel->phone = $phone;
            $hotel->group_num = $groupNum;
            if ($city && $district && $detailAddress)
            {
                $cityName = Region::get($city);
                $districtName = Region::get($district);
                $result = getLatLng($districtName['name'].$detailAddress,$cityName['name']);
                $hotel->lat = $result['lat'];
                $hotel->lon = $result['lng'];
            }
            $hotel->save();
            return json(new SuccessMessage(['msg' => '编辑成功']));
        }
        $hotel = HotelModel::get($id,['image','businessLicense'])->toArray();
        $this->assign('hotel',$hotel);
        $region = Region::getRegion();
        $this->assign('region',$region);
        $city = Region::getRegion($hotel['province'])->toArray();
        $this->assign('city',$city);
        $district = Region::getRegion($hotel['city'])->toArray();
        $this->assign('district',$district);
        $scenicArea = ScenicArea::all();
        $this->assign('scenic_area',$scenicArea);
        return $this->fetch();
    }

    public function delete()
    {
        $id=input('param.id/a');
        $res = HotelModel::destroy($id);
        if($res) {
            return json(new SuccessMessage(['msg' => '删除成功']));
        } else {
            throw new AdminException(['msg' => '删除失败']);
        }
    }

    public function getRegion()
    {
        $id = input('post.id');
        $region = Region::getRegion($id);
        return $region;
    }
}