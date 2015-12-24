<?php
//验证微信接口程序
namespace WechatInterface\Controller;
class ValidController{
    public function __construct() {

    }
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //valid signature , option
        if($this->_checkSignature()){
        	echo $echoStr;
        	return;
        }
    } 
    private function _checkSignature()
    {
        // you must define TOKEN by yourself        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];      		
        $token = C('WECHAT_TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
                return true;
        }else{
                return false;
        }
    }
}