<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/5/8
 * Time: 17:06
 */

namespace app\api\controller;

use app\api\service\Token as TokenService;
use think\Controller;
use traits\model\SoftDelete;

class BaseController extends Controller
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}