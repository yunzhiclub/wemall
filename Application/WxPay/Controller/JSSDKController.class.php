<?php
/*
 *  JSSDK，用于动态生成JSSDK配置信息
 * 源于微信官方DEMO
 */
namespace WxPay\Controller;
class JSSDKController{
    private $code = null;//为了加密，需要传入值
    private $state = null;
    private $userToken = null;
    private $openid = null;
    function setCode($code) {
        $this->code = $code;
    }

    function setState($state) {
        $this->state = $state;
    }

    function setUserToken($userToken) {
        $this->userToken = $userToken;
    }
    public function setOpenid($openid) {
        $this->openid = $openid;       
    }
        
  public function __construct() {
    $this->appId = C('WECHAT_APPID');
    $this->appSecret = C('WECHAT_APPSECRET');
  }
/*
 * 获取api js签名包
 */
  public function getSignPackage() {
    //获取ticket，有缓存功能。
    $jsapiTicket = get_jsapi_ticket();
    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->_createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

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
        $signType = 'sha1';//加密方式为sha1
        $timeStamp = time();//时间戳
        $nonceStr = $this->_createNonceStr();
        //以下开始进行签名
        $url = get_current_url();
        $accessToken = $this->userToken;
        $string = 'accesstoken=' . $accessToken . '&appid=' . $appId . '&noncestr=' . $nonceStr . '&timestamp=' . $timeStamp . '&url=' . $url;       
        //echo "签名字符串" .$string . "<br />";
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
  private function _createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }  
}
