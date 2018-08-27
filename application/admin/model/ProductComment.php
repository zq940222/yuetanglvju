<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/16
 * Time: 15:13
 */

namespace app\admin\model;


use traits\model\SoftDelete;

class ProductComment extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function product()
    {
        return $this->belongsTo('Product','product_id','id');
    }

    public function user()
    {
        return $this->belongsTo('User','user_id','id');
    }

    public function image()
    {
        return $this->belongsToMany('Image','ProductCommentImg','img_id','product_comment_id');
    }
}