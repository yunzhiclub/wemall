<?php
//接收用户文字消息后回复
namespace WechatInterface\Controller;
class ResponseMsgController extends WechatController{
    //响应text事件
    public function responseTextMsg()
    {
       $replyMessage = A('ReplyMessage');
       $replyMessage->replyTextMessage();
    }
    //响应event事件,点击后反馈信息
    public function responseClickMsg()
    {
        $postObj = $this->postObj;
        $fromUserName = $postObj->FromUserName;
        $toUserName = $postObj->ToUserName;
        $msgType = 'text';
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        $msgType = "text";
        //获取微信服务器的相关参数
        $oauth = C('WECHAT_OAUTH');
        $appid = C('WECHAT_APPID');
        $redirectUrl = 'http://'.C('MALL_DOMAIN');
        $redirectUrl = urlencode($redirectUrl);
        $scode = 'snsapi_userinfo';
        $state = '123';
        $contentStr = sprintf($oauth,$appid,$redirectUrl,$scode,$state);
        $resultStr = sprintf($textTpl, $fromUserName, $toUserName, $time, $msgType, $contentStr);
        echo $resultStr;
    }
}
