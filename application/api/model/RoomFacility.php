<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/10
 * Time: 20:44
 */

namespace app\api\model;


class RoomFacility extends BaseModel
{
    protected $visible = ['id','name','parent'];

    public function parent()
    {
        return $this->belongsTo('RoomFacility','parent_id','id');
    }

}