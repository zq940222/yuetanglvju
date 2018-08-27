<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/18
 * Time: 9:31
 */

namespace app\hotel\model;


class GroupList extends BaseModel
{
    public function appointment()
    {
        return $this->hasMany('HotelAppointment','group_list_id','id')->where('status','in',[2,3,4]);
    }

    public function groupHost()
    {
        return $this->belongsTo('User','group_host_id','id');
    }

    public function getEndTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    public function getStatusAttr($value)
    {
        $array = [
            '未知',
            '未完成',
            '已完成',
            '已超时'
        ];
        return $array[$value];
    }

}