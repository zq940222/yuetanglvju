<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 15:06
 */

namespace app\admin\service;

use think\Loader;

Loader::import('GETUI.IGt', EXTEND_PATH, '.Push.php');

class PushMessage
{
    public function pushMessageToApp($title = '', $content = ''){
        $igt = new \IGeTui(config('getui.HOST'),config('getui.APPKEY'),config('getui.MASTERSECRET'));
        $template = $this->IGtTransmissionTemplateDemo($title,$content);
        //基于应用消息体
        $message = new \IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);
        $appIdList=array(config('getui.APPID'));
        $message->set_appIdList($appIdList);
        $rep = $igt->pushMessageToApp($message);
        return $rep;
    }

    public function IGtTransmissionTemplateDemo($title, $content){
        $template =  new \IGtTransmissionTemplate();
        $template->set_appId(config('getui.APPID'));//应用appid
        $template->set_appkey(config('getui.APPKEY'));//应用appkey
        $template->set_transmissionType(1);//透传消息类型
        $template->set_transmissionContent($content);//透传内容
        //APN高级推送
        $apn = new \IGtAPNPayload();
        $alertmsg=new \DictionaryAlertMsg();
        $alertmsg->body= $content;
        $alertmsg->title=$title;
        $apn->alertMsg=$alertmsg;
        $apn->badge=0;
        $apn->contentAvailable=1;
        $apn->category="ACTIONABLE";
        $template->set_apnInfo($apn);
        return $template;
    }
}