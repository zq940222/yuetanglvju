<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/3
 * Time: 14:28
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    const UnPay = 1;

    const UnDelivery = 2;

    const UnComment = 3;

    const Finished = 4;

    const Canceled = 5;

    const ApplyUnsubscribe  = 6;

    const Unsubscribe = 7;

    const HasUnsubscribe = 8;

    const Failed = -1;
}