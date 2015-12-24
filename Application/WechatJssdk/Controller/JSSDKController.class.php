<?php
/*
 * JSSDK，用于动态生成JSSDK配置信息
 * 源于微信官方DEMO
 */
namespace WechatJssdk\Controller;
class JSSDKController{
  public function __construct($appId, $appSecret) {
    $this->appId = C('WECHAT_APPID');
    $this->appSecret = C('WECHAT_APPSECRET');
  }
/*
 * 获取签名包
 */
  public function getSignPackage() {
    $jsapiTicket = get_jsapi_ticket();//获取ticket
    $url = get_current_url();//获取当前url
    $timestamp = time();
    $nonceStr = $this->_createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=".$jsapiTicket."&noncestr=".$nonceStr."&timestamp=".$timestamp."&url=".$url;
    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }
    /*
     * 获取收货地址参数
     */  
    public function getAddressPackage()
    {
        $appId = $this->appId;//appID
        $scope = 'jsapi_address';//说明获取编辑地址权限
        $signType = 'SHA1';//加密方式为sha1
        $timeStamp = time();//时间戳
        $nonceStr = $this->_createNonceStr();
        //以下开始进行签名
        $url = get_current_url();
        $accessToken = get_access_token();
        $string = 'accesstoken=' . $accessToken . '&appid=' . $appId . '&noncestr=' . $nonceStr . '&timestamp=' . $timeStamp . '&url=' . $url;
        $addrSign = SHA1($string);
        $addressPage = array(
            'appId'     =>      $appId,
            'scope'     =>      $scope,
            'signType'  =>      $signType,
            'addrSign'  =>      $addrSign,
            'timeStamp' =>      $timeStamp,
            'nonceStr'  =>      $nonceStr
        );
        return $addressPage;
    }
/*
 * 获取16位的随机字符串
 */
  private function _createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }  
  
}
