<?php
/*
 * 微信接口.用于响应用户的请求
 * indexAction()微信服务器传输总接口
 * 按msgType不同,定义不同的回复方法.
 */
namespace WechatInterface\Controller;
class IndexController extends WechatController{
    public function indexAction(){
//        $valid = new ValidController();
//        $valid->valid();
        //获取微信传输信息
        $postObj = $this->postObj;
        $responseMsg = A('ResponseMsg');
        if($postObj->MsgType == 'text')
        {
            //调用回复消息实例化方法 
           // $responseMsg->responseTextMsg();
        }
        elseif($postObj->MsgType == 'event')
        {
            $responseMsg = A('ResponseEvent');
            //菜单事件
            if($postObj->Event == 'CLICK')
            {
                $responseMsg->responseClickMsg();
            }
            /*
             * 关注事件,向用户表写库.
             * 注意:用户在前台界面注册时,写入了相关的文件.
             */
            elseif($postObj->Event == 'subscribe')
            {
                $responseEvent = A('ResponseEvent');
                $responseEvent->responseSubscribeMsg();
            }
            //取消关注事件
            elseif($postObj->Event == 'unsubscribe')
            {
                $responseEvent = A('ResponseEvent');
                $responseEvent->responseUnsubscribeMsg();
            }
            elseif($postObj->Event == 'SCAN')
            {
                $responseEvent = A('ResponseEvent');
                $responseEvent->responseScanMsg();
            }
        }
    }
}