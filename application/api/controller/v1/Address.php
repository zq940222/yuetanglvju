<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/8
 * Time: 16:20
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Region;
use app\api\model\UserAddress;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\api\validate\AddressNew;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;


class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

    public function addressList()
    {
        $uid = TokenService::getCurrentUid();
//        $uid = 1;
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }
        $address_list = UserAddress::with(['province','city','district'])
            ->where(['user_id'=>$uid])
            ->order('is_default desc')
            ->select();
        return $address_list;
    }

    public function createAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();

        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));
        $dataArray['user_id'] = $uid;
        UserAddress::create($dataArray);

        return json(new SuccessMessage(),201);
    }

    public function getSingle($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $data = UserAddress::with(['province','city','district'])
            ->find($id);
        if (!$data) {
            throw new UserException([
                'msg' => '请求的用户地址不存在'
            ]);
        }
        return $data;
    }

    public function updateAddress($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $validate = new AddressNew();
        $validate->goCheck();

        $uid = TokenService::getCurrentUid();

        $dataArray = $validate->getDataByRule(input('put.'));
        $res = UserAddress::where('id','=',$id)
            ->where('user_id','=',$uid)
            ->update($dataArray);
        if (!$res){
            return json(new UserException(['msg' => '修改地址内容不正确哦']));
        }
        return (new SuccessMessage());
    }

    public function setDefault($id)
    {
        (new IDMustBePostiveInt())->goCheck();

        $uid = TokenService::getCurrentUid();

        UserAddress::where('user_id','=',$uid)
            ->update([
                'is_default' => 0
            ]);
        $res = UserAddress::where('id','=',$id)
            ->where('user_id','=',$uid)
            ->update([
                'is_default' => 1
            ]);
        if (!$res) {
            throw new UserException([
                'msg' => '设置默认地址失败'
            ]);
        }
        return (new SuccessMessage());
    }

    public function getRegion($parent_id = 100000)
    {
        $data = Region::getRegion($parent_id);
        return $data;
    }

    public function getCity()
    {
        $data = Region::getCity();
        return $data;
    }

    public function deleteAddress($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $addressModel = UserAddress::get($id);
        $uid = TokenService::getCurrentUid();
        if ($addressModel->user_id != $uid){
            return json(new UserException(['msg' => '你没有权限这么做']));
        }else{
            UserAddress::destroy($id);
            return json(new SuccessMessage(['msg' => '删除成功']));
        }

    }
}