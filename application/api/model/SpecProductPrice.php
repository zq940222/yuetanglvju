<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/6/22
 * Time: 17:33
 */

namespace app\api\model;


class SpecProductPrice extends BaseModel
{
    public function getImageAttr($value,$data)
    {
        if ($value)
        {
            return config('setting.img_prefix').$value;
        }
        else
        {
            $product = Product::get($data['product_id']);
            return $product->coverImage->url;
        }

    }
}