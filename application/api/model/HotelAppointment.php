<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/9
 * Time: 16:24
 */

namespace app\api\model;


use traits\model\SoftDelete;

class HotelAppointment extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function hotel()
    {
        return $this->belongsTo('Hotel','hotel_id','id');
    }

    public function user()
    {
        return $this->belongsTo('User','user_id','id');
    }

    public function setOccupantNameAttr($value)
    {
        return json_encode($value);
    }

    public function getOccupantNameAttr($value)
    {
        return json_decode($value,true);
    }

    public static function getHotelAppointment($uid,$status = 0,$page = 1,$size = 10)
    {
        $where = [];
        $where['user_id'] = ['=',$uid];
        $where['is_delete'] = ['=',0];
        if ($status) {
            $where['status'] = ['=',$status];
        }
        $pagingData = self::order('create_time desc')
            ->where($where)
            ->paginate($size,true,['page' => $page]);
        return $pagingData;
    }

}