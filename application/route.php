<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::post('api/:version/upload','api/:version.Upload/upload');

Route::get('api/:version/banner/:id','api/:version.Banner/getBanner',[], ['id'=>'\d+']);

Route::group('api/:version/hotel',function(){
    Route::get('recommend','api/:version.Hotel/getRecommendHotel');
    Route::get('comprehensive','api/:version.Hotel/getHotel');
    Route::get('price','api/:version.Hotel/getHotelByPrice');
    Route::get('score','api/:version.Hotel/getHotelByScore');
    Route::get('distance','api/:version.Hotel/getHotelByDistance');
    Route::get(':id','api/:version.Hotel/getHotelSingle',[],['id'=>'\d+']);
});

Route::get('api/:version/hotel/style','api/:version.Hotel/getHotelStyle');

Route::get('api/:version/scenic_area/by_city', 'api/:version.Hotel/getScenicArea');

Route::get('api/:version/district/by_city', 'api/:version.Hotel/getDistrict');

Route::get('api/:version/hotel/filtrate','api/:version.Hotel/getHotelFiltrate');

//发送验证码
Route::post('api/:version/send_code','api/:version.VerificationCode/sendCode');
//用户手机号登录 自动注册
Route::post('api/:version/token/by_mobile','api/:version.Token/getTokenByMobile');

Route::post('api/:version/token/by_wx','api/:version.Token/getTokenByWx');

Route::post('api/:version/token/bind_wx','api/:version.Token/bindWx');

Route::get('api/:version/hotel/room','api/:version.Hotel/getHotelRoom');

Route::get('api/:version/hotel/image/:id','api/:version.Hotel/getHotelImage',[],['id'=>'\d+']);

Route::get('api/:version/hotel/comment/:id','api/:version.Hotel/getComment',[],['id'=>'\d+']);

Route::post('api/:version/hotel/comment','api/:version.Order/hotelComment');
//关注
Route::put('api/:version/hotel/attention/:id','api/:version.Hotel/clickAttention',[],['id'=>'\d+']);

Route::get('api/:version/room/:id','api/:version.Hotel/getRoom',[],['id'=>'\d+']);

Route::get('api/:version/hotel/group_list/:id','api/:version.Hotel/getGroupLists',[],['id' => '\d+']);

Route::post('api/:version/hotel/appointment','api/:version.Order/placeHotelAppointment');
//取消预订
Route::post('api/:version/appointment/cancel/:id','api/:version.Order/appointmentCancel');
//删除预订
Route::delete('api/:version/appointment/:id','api/:version.Order/appointmentDelete');
//产品
Route::get('api/:version/product/recommend','api/:version.Product/getRecommendProducts');

Route::get('api/:version/product/category','api/:version.Category/getAllCategories');

Route::group('api/:version/product',function (){
    Route::get('comprehensive','api/:version.Product/getProducts');
    Route::get('sales_volume','api/:version.Product/getProductsBySalesVolume');
    Route::get('price_asc','api/:version.Product/getProductsByPriceAsc');
    Route::get('price_desc','api/:version.Product/getProductsByPriceDesc');
});

Route::get('api/:version/product/detail/:id','api/:version.Product/getDetail',[],['id'=>'\d+']);

Route::get('api/:version/product/comment/:id','api/:version.Product/getProductComment',[],['id'=>'\d+']);

Route::put('api/:version/product/attention/:id','api/:version.Product/clickAttention',[],['id'=>'\d+']);

Route::get('api/:version/address/list','api/:version.Address/addressList');
Route::post('api/:version/address','api/:version.Address/createAddress');
//Route::get('api/:version/address/:id','api/:version.Address/getSingle',[],['id'=> '\d+']);
Route::put('api/:version/address/edit','api/:version.Address/updateAddress');
Route::put('api/:version/address/default','api/:version.Address/setDefault');
Route::delete('api/:version/address/:id','api/:version.Address/deleteAddress');
Route::get('api/:version/address/region','api/:version.Address/getRegion');
Route::get('api/:version/address/city','api/:version.Address/getCity');

Route::get('api/:version/mine','api/:version.User/mine');
Route::get('api/:version/attention','api/:version.User/attention');
Route::post('api/:version/personal/edit','api/:version.User/edit');
Route::get('api/:version/coupon','api/:version.User/coupon');
Route::get('api/:version/order/hotel','api/:version.Order/getHotelAppointment');
Route::get('api/:version/hotel_order_detail/:id','api/:version.Order/HotelAppointmentDetail');
Route::get('api/:version/product_order_detail/:id','api/:version.Order/productOrderDetail');
Route::get('api/:version/order/product','api/:version.Order/getProductOrder');
Route::get('api/:version/account/detail','api/:version.User/accountDetail');

Route::get('api/:version/crowd/funding','api/:version.User/crowdFunding');
Route::get('api/:version/customer/service','api/:version.User/customerService');

Route::get('api/:version/cart/list','api/:version.Cart/cartList');
Route::post('api/:version/cart/add','api/:version.Cart/addProductToCart');
Route::put('api/:version/cart/change','api/:version.Cart/changeNum');
Route::delete('api/:version/cart/delete','api/:version.Cart/delete');

Route::get('api/:version/cart/address','api/:version.Cart/getAddress');

Route::post('api/:version/order/place','api/:version.Cart/cart3');
//取消产品订单
Route::post('api/:version/order/cancel/:id','api/:version.Order/orderCancel');
//删除产品订单
Route::delete('api/:version/order/:id','api/:version.Order/orderDelete');
//确认收货
Route::put('api/:version/confirm/receipt/:id','api/:version.Order/receipt');
//产品订单评价
Route::post('api/:version/product/comment','api/:version.Order/productComment');
//支付宝支付
Route::post('api/:version/alipay/hotel','api/:version.AliPay/getHotelPreOrder');
Route::post('api/:version/alipay/product','api/:version.AliPay/getProductPreOrder');
Route::post('api/:version/alipay/recharge','api/:version.AliPay/getRechargePreOrder');

Route::post('api/:version/alipay_notify/hotel','api/:version.Notify/hotelAliNotify');
Route::post('api/:version/alipay_notify/product','api/:version.Notify/productAliNotify');
Route::post('api/:version/alipay_notify/recharge','api/:version.Notify/rechargeAliNotify');
//微信支付
Route::post('api/:version/wxpay/hotel','api/:version.WxPay/getHotelPreOrder');
Route::post('api/:version/wxpay/product','api/:version.WxPay/getProductPreOrder');
Route::post('api/:version/wxpay/recharge','api/:version.WxPay/getRechargePreOrder');

Route::post('api/:version/wxpay_notify/notify','api/:version.Notify/receiveNotify');
//充值
Route::post('api/:version/recharge','api/:version.User/recharge');
//用户提现
Route::post('api/:version/withdraw','api/:version.User/withdraw');
//获取运费
Route::any('api/:version/freight','api/:version.Cart/getFreight');
//APP版本升级
Route::get('api/:version/version/upgrade','api/:version.Version/upgrade');
//定时检查拼团订单
Route::any('api/:version/group/overtime','api/:version.Order/overtime');
//定时过期优惠券处理
Route::any('api/:version/coupon/overtime','api/:version.Crontab/couponOvertime');
//定时处理未及时付款的订单
Route::any('api/:version/order/overtime','api/:version.Crontab/orderOvertime');
//退款/售后
Route::get('api/:version/refund','api/:version.Order/getRefund');
//弹窗优惠券
Route::get('api/:version/popup','api/:version.Coupon/popup');
//领取优惠券
Route::post('api/:version/get_coupon/:id','api/:version.Coupon/getCoupon');


