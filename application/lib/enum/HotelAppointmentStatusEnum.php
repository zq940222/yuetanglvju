<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/3
 * Time: 14:28
 */

namespace app\lib\enum;


class HotelAppointmentStatusEnum
{
    const UnPay = 1;

    const UnShare = 2;

    const UnUse = 3;

    const UnComment = 4;

    const Used = 5;

    const Canceled = 6;

    const ApplyUnsubscribe  = 7;

    const Unsubscribe = 8;

    const HasUnsubscribe = 9;

    const Failed = -1;
}