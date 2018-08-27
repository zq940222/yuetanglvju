<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/1
 * Time: 10:02
 */

namespace app\api\model;


class GroupList extends BaseModel
{
    protected $visible = ['id','end_time','group_num','group_host.nickname','group_host.headimg','appointment','appointment.user','appointment.user.headimg','people_num'];

    public function groupHost()
    {
        return $this->belongsTo('User','group_host_id','id');
    }


    public function appointment()
    {
        return $this->hasMany('HotelAppointment','group_list_id','id')->where('status','in',[2,3,4]);
    }

    public static function getGroupLists($id)
    {
        $data = self::with(['groupHost','groupHost.headimg','appointment','appointment.user','appointment.user.headimg'])
            ->where('hotel_id','=',$id)
            ->where('status','=',1)
            ->select();
        $people_num = self::where('hotel_id','=',$id)
            ->where('status','=',1)
            ->sum('join_group_num');
        return ['people_num' => $people_num,'list' => $data];
    }

}