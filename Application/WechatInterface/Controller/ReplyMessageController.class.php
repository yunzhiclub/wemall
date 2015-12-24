<?php
/*
 * 微信回复消息.
 * $text 回复文本 string
 * $news二维数组，每个数组元素中存储包含以下字段的信息
 * title 标题
 * description 描述
 * picurl 图片地址
 * url链接地址
 */
namespace WechatInterface\Controller;
class ReplyMessageController extends WechatController{
    private $news = null;//图文消息
    private $text = null;//文字消息
    function setNews($news) {
        $this->news = $news;
    }
    function setText($text)
    {
        $this->text = $text;
    }

        //回复文字消息
    public function replyTextMessage()
    {
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
        $contentStr = ($this->text == null ? '没有要显示的文字信息' : $this->text );
        $resultStr = sprintf($textTpl, $this->postObj->FromUserName, $this->postObj->ToUserName, $time, $msgType, $contentStr);
        echo $resultStr;        
    }
    //回复图片消息
    public function replyImageMessage()
    {
        $textTpl = ' 
                    <xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                    <MediaId><![CDATA[media_id]]></MediaId>
                    </Image>
                    </xml> ';
      
    }
    //回复图文消息
    public function replyNewsMessage(){
        $tpl =  T('ReplyMessage/replyNewsMessage');
        $this->assign('article',$this->news);
        $this->display($tpl);       
    }
    //回复挑转链接
    public function replyUrlMessage()
    {
        $tpl = T('ReplyMessage/replyUrlMessage');
        $this->assign('url','www.baidu.com');
        $this->display($tpl);
    }
}
