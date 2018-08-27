<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/1
 * Time: 10:28
 */

namespace app\api\service;


use app\api\model\GroupList;
use app\api\model\HotelRoomType;

class HotelRoom
{
    public function getRoom($id)
    {
        $dataArray = HotelRoomType::with(['image','facility','facility.parent'])
            ->find($id);
        $groupList = $this->getGroupList($id);
        $dataArray['group_list'] = $groupList;
        return $dataArray;
    }

    private function getGroupList($id)
    {
        $groupList = GroupList::with(['groupHost','user'])
            ->where('room_id','=',$id)
            ->select();
        return $groupList;
    }

}