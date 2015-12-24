<?php
/* 
 * 梦云智工作室
 *  用于获取微信服务器推送的消息，并进行处理。所以响应微信类，需要继承此函数 * 
 */
namespace WechatInterface\Controller;
use Think\Controller;
class WechatController extends Controller
{
    protected $postObj;
    public function _initialize()
    {
        //获取微信服务器传递的数值，储存为对象。
        $this->postObj = get_postObj();
        $this->assign('toUserName',$this->postObj->FromUserName);
        $this->assign('fromUserName',$this->postObj->ToUserName);
    }
}

