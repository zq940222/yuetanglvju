<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/11
 * Time: 10:23
 */

namespace app\api\model;


use traits\model\SoftDelete;

class Order extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function product()
    {
        return $this->hasMany('OrderProduct','order_id','id');
    }

    public function provinceName()
    {
        return $this->belongsTo('Region','province','id');
    }

    public function cityName()
    {
        return $this->belongsTo('Region','city','id');
    }

    public function districtName()
    {
        return $this->belongsTo('Region','district','id');
    }

    public function setSnapAddress($value)
    {
        return json($value);
    }

    public function getSnapAddress($value)
    {
        return json_decode($value,true);
    }

    public static function getProductOrder($uid, $status = 0, $page = 1, $size = 10)
    {
        $where = [];
        $where['user_id'] = ['=',$uid];
        $where['is_delete'] = ['=',0];
        if ($status) {
            $where['status'] = ['=',$status];
        }
        $pagingData = self::with(['product','provinceName','cityName','districtName'])
            ->order('create_time desc')
            ->where($where)
            ->paginate($size,true,['page' => $page]);
        foreach ($pagingData as &$value)
        {
            $value['province'] = $value->provinceName->name;
            $value['city'] = $value->cityName->name;
            $value['district'] = $value->districtName->name;
        }
        return $pagingData;
    }
}