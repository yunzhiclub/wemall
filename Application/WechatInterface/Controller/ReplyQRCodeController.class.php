<?php
//回复用户带参数的二维码
namespace WechatInterface\Controller;
class ReplyQRCodeController extends WechatController{
   private $actionName = null;//生成二维码的方式 
   public function __construct() {
       parent::__construct();
       $this->actionName = 'QR_SCENE';//默认为临时方式
   }
   public function setActionName($actionName) {
       //如果设置为1，则采用永久方式获取.其它值则使用临时方式获取
       if($actionName == 1)
       {
           $this->actionName = 'QR_LIMIT_SCENE';
       }
   }
   /*
    * 回复用户个性二维码
    * 1.取出当前用户ID
    * 将ID值做为参数传入
    * 获取包括此ID的个性二维码
    * 将此图片输出给用户
    */
   public function replyCode()
   {
        $openId = (string)$this->postObj->FromUserName;
        $customerInfo = get_customer_info($openId);
        $customerId = $customerInfo['id'];
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s';
        $accessToken = get_access_token();
        $url = sprintf($url,$accessToken);
        $data['expire_seconds'] = 1800;
        $data['action_name'] = $this->actionName;
        $data['action_info'] = array('scene' => array('scene_id' => (int)$customerId));
        $data = json_encode($data);
//                $replyMessage = A('ReplyMessage');
//            $replyMessage->setText('传入的JSON数据为' . $data);
//            $replyMessage->replyTextMessage();    
//            return;
        $res = http_post_json($url,$data); 
        $res = json_decode($res);
        $ticket = $res->ticket;
        $ticket = urlencode($ticket);
        $replyMessage = A('ReplyMessage');
        $picUrl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
        $url = $picUrl;
        $title = '您的专属二维码';
        $description = '其它用户通过扫描该二维码关注我们，将自动成为您的朋友。';
        $news[] = array('title' => $title, 'description' => $description , 'picurl' => $picUrl , 'url' =>$url);
        $replyMessage->setNews($news);
        $replyMessage->replyNewsMessage();
   }

}
