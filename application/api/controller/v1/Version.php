<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 10:48
 */

namespace app\api\controller\v1;

use app\api\model\VersionUpgrade;

class Version
{
    public function upgrade()
    {
        $versionModel = VersionUpgrade::order('create_time desc')
            ->where('status','=',1)
            ->find();
        return $versionModel;
    }
}