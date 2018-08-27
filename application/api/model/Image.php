<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 19:27
 */

namespace app\api\model;


class Image extends BaseModel
{
    protected $visible = ['url'];

    public function getUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
    //base64上传图片
    public function setUrlAttr($value)
    {
        $base64_img = $value;
        $temp_date = date('Ymd', time());
        $temp_dir = ROOT_PATH . 'public' . DS . 'uploads/'.$temp_date;


        if(!file_exists($temp_dir)){
            mkdir($temp_dir,0777);
        }

        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)){
            $type = $result[2];
            if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
                $saveName = 'upload'.date('YmdHis').mt_rand(1000,9999).'.'.$type;
                if(file_put_contents($temp_dir. DS .$saveName, base64_decode(str_replace($result[1], '', $base64_img)))){
                    return $temp_date. DS .$saveName;
                }
            }
        }
    }
}