<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/7
 * Time: 14:59
 */

namespace app\api\model;


class ProductComment extends BaseModel
{
    protected $hidden = ['id','username','mobile','product_id','update_time','delete_time'];

    public function user()
    {
        return $this->belongsTo('User','user_id','id')->field('id,nickname,img_id');
    }

    public function image()
    {
        return $this->belongsToMany('Image','ProductCommentImg','img_id','product_comment_id');
    }
}