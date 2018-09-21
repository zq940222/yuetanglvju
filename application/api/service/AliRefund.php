<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/7/31
 * Time: 9:45
 */

namespace app\api\service;

use think\Loader;

Loader::import('AliPay.aop.AopClient',EXTEND_PATH,'.php');
Loader::import('AliPay.aop.request.AlipayTradeRefundRequest', EXTEND_PATH, '.php');

class AliRefund
{
    public function refund($totalPrice, $outTradeNo)
    {
        $aop = new \AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = '2018070460542343';
        $aop->rsaPrivateKey = "MIIEogIBAAKCAQEAvQe3PWZIykbrxtLSWxGp/CS6fmem8sirOtnVNfwpkK5e6fi52aVC1DMGAYhZFzRUsLVOpIsn7jOXY9N4bDxLf098InSZichmeV4IlgjmxTE2YXWsYajNqqJoCmZm3ieed4MP3Gc1YdUSGv+b/cYqNlvOGQyhvkYUJBLWzJcwU9fgvhMXuJoQ7M5N2jmTBZcUg0j0F7jh0oU2hhYh5BPPdrng648ABbW9eMMSuBh2lPHOKs7+x3Lm/UFnNxrMam66q6gRyFR96UB6PdIDMEsmMYSQpq6jznDOFF0cUjl3FCsXMwbyq97LUOtbMdeI/6qLweE8uulyryABTeqxQqpQ/QIDAQABAoIBABh59UooAmjewgzeo4pTQTV69AMGHOH3BeT669avrhoj2fpl0HrUIVEkwjRUmWSdzBGNiH9Z3XPEjmfIrCEntYboneRAQNlMb6hreqUixe7mrmn0OLv0hZ0ApoQiOlOtwaEsAVPCsXDXjB6e1m4HyNN9E7S+o/rlTBpXriSTtxhD9LVbUx74vou1DB9DMXHgVGJa9IAunGUkw901zAUROeMiGiqTJrrq8Eqv+LVy7esfd8vJlcMNLGBbE5RtoY+iKnhP6F5bpqwL+nqRiR7UobQ9/TTComg2pkAlfPNCN6C+EFxVCcwoNqKprfJk26twq319JVEe9FguOt4QaMgyasECgYEA51bY2Ts3nRwImJcAKqhk+8IXUze7dEz6mi2iZI24AyJ+tSg2ohFGyIl0sLuD8Wx2VTVpEZb2Q87gtp1J4SzuOVEizECmNULuq110JPyahcg15tewiXpzdSuRgFe6LiQsjh4iBv1Of7dVz6XM8IFY0PEyo2sodwZQu6Mgdk2NI+kCgYEA0S5FRVvnJJUdE+sYkHRPiDuDHV8U4W6US1EZ/B6h0WLkjS7h1JIZEGX5/iZ2nP0exU3tgxoTEasLriReSIoyES1Ri1RGarADicbYPG8Bo9L2aQv+6Y1WBnfbUtSSYl6oSLFMwLAC6EM8WiF3URvIUdP7Mxi4boCcBDH2IN/me/UCgYBQX1Ltfe5fbirqYKPVLjYPZapW5ikBSfFS+YHO75G7vRNKexMoEVqHN4JMGInJqcYe6nR7gPhELK7ToyfUzJhjX3X4gol8PanP7aL5aq2Ax0M61TrnOJy+W4msjk4H09eK9Jsb1IueQaLVhqQB9t5VkUbnkcY4PAB2gEE5+M2NaQKBgAiUXUL7Af/+HbMzcU57dsefqUELJVAZuPtd2DL/DqQH6lfgFGMjmuORSy+hZDwMJbbx+0vlReLzoQcDdtqC0Irj0PRmAH1fusVr4nKYGvkdLf4g/9OUeHLLd7NuBJMETuKsYvmEPppIJ7GKrdolyZGRoDv4R5hAriV95xpyFIIBAoGAO9Y0ZTd46zSim9bXC4qyQgTT+aBheoiEhph6rydk2Dk+WXXFi8qp7Ko9LX9KReFRAV3avExnBWtulBBnzDRmzh6qiIn9SlsgVeH9Ym0bNcYrQaXHgNWVcsWgulWbaQ4iFnw3+ccPtcPM5AmYJnNTZ+CqUAuvRle+xI6Bno+wH30=";
        $aop->alipayrsaPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAudjeCjTYyDO6z2hHnUr+/R7Wz6PwpH20FTADbdgpUJu+3sC42jo/DZsSYem8UCgO8Q2lM0HzzcXgUOgJVwft8arr9pqCuc7JeqAR2G4JweQKJI81jFtTy8ZFT1n5YmjdSy3OOUyb235560FLT3+0HAuRlKV3+yGeA3zHC7ymRR2KGvCz2hkk+Tt2woP8w+j4pU0t+WQoaUllgtXg/SPlGJNKPMjxAqihK7le2Hlt6t5P+BdqKgxb50SGpiGETrnbZpGkOlHtJjrmqBgTRQbmLUmK7qDGSXiZmwfwLftPykmNwpb7wJL6a98NHDixB+WO6TPc8zanZEjgyWQiw5HEbQIDAQAB";
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->charset='UTF-8';
        $aop->format='json';
        $request = new \AlipayTradeRefundRequest ();
        $request->setBizContent("{" .
            "\"out_trade_no\":\"".$outTradeNo."\"," .
//            "\"trade_no\":\"2014112611001004680073956707\"," .
            "\"refund_amount\":".$totalPrice."," .
//            "\"refund_currency\":\"USD\"," .
            "\"refund_reason\":\"正常退款\"," .
//            "\"out_request_no\":\"HZ01RF001\"," .
            "\"operator_id\":\"OP001\"," .
            "\"store_id\":\"NJ_S_001\"," .
            "\"terminal_id\":\"NJ_T_001\"," .
//            "      \"goods_detail\":[{" .
//            "        \"goods_id\":\"apple-01\"," .
//            "\"alipay_goods_id\":\"20010001\"," .
//            "\"goods_name\":\"ipad\"," .
//            "\"quantity\":1," .
//            "\"price\":2000," .
//            "\"goods_category\":\"34543238\"," .
//            "\"body\":\"特价手机\"," .
//            "\"show_url\":\"http://www.alipay.com/xxx.jpg\"" .
//            "        }]," .
//            "      \"refund_royalty_parameters\":[{" .
//            "        \"royalty_type\":\"transfer\"," .
//            "\"trans_out\":\"2088101126765726\"," .
//            "\"trans_out_type\":\"userId\"," .
//            "\"trans_in_type\":\"userId\"," .
//            "\"trans_in\":\"2088101126708402\"," .
//            "\"amount\":0.1," .
//            "\"amount_percentage\":100," .
//            "\"desc\":\"分账给2088101126708402\"" .
//            "        }]" .
            "  }");
        $result = $aop->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode)&&$resultCode == 10000){
            return true;
        } else {
            return false;
        }
    }
}