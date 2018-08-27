<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/10
 * Time: 17:27
 */

namespace app\admin\model;


class Product extends BaseModel
{
    public function category()
    {
        return $this->belongsTo('ProductCategory','category_id','id');
    }

    public function coverImg()
    {
        return $this->belongsTo('Image','cover_img_id','id');
    }

    public function image()
    {
        return $this->belongsToMany('Image','ProductImg','img_id','product_id');
    }

    public function productDetail()
    {
        return $this->belongsToMany('Image','ProductDetailImg','img_id','product_id')->order('sort asc');
    }

    public function specProductPrice()
    {
        return $this->hasMany('SpecProductPrice','product_id','id');
    }
}