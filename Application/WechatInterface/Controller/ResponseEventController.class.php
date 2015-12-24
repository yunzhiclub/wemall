<?php
//接收用户事件后回复
namespace WechatInterface\Controller;
use Reply\Model\ReplyModel; //关注回复
use Attachment\Model\AttachmentModel; //附件
use Config\Model\ConfigModel; //配置类
use Coupon\Model\CouponModel; //优惠券类
use Active\Model\ActiveModel; //注册送券活动
use OrderRelation\Model\OrderRelationModel; //支付订单表
use Customer\Model\CustomerModel;//客户表
use Rebate\Model\RebateModel; //系数表
use WxPay\Controller\SendRedGiftController;
class ResponseEventController extends WechatController{
    public function responseClickMsg()
    {
        $cutomMenu = D('CustomMenu');
        //响应用户点击菜单事件
        //响应点击微商场事件
        if($this->postObj->EventKey == 'mall')
        {
            $ReplyMessage = A('ReplyMessage');
            $ReplyMessage->replyUrlMessage();           
            return;
        }
        //响应我的业绩事件
        elseif($this->postObj->EventKey == 'soreDetail')
        {
            //获取当前用户的当前业绩
            $currentAchievement = $this->_getCurrentCustomerCurrentAchievement();
            
            //拼接传给用户的文字信息
            $text = "您的当前业绩\n";
            $text .= "直销业绩：". format_money($currentAchievement[direct][_totalGoodsFee]) . "\n";
            $text .= "直销佣金基数：". format_money($currentAchievement[direct][_totalDirectFee]) . "\n";
            $text .= "直销佣金系数：". $currentAchievement[_direct_radio][direct_ratio] . "\n";
            $directFee = floor($currentAchievement[direct][_totalDirectFee]*$currentAchievement[_direct_radio][direct_ratio]);
            $text .= "直销佣金：". format_money($directFee) . "\n";
            $text .= "\n";
            $text .= "下线业绩：". format_money($currentAchievement[line][_totalGoodsFee]) . "\n";
            $text .= "下线佣金基数：". format_money($currentAchievement[line][_totalLineFee]) . "\n";
            $text .= "下线佣金系数：". $currentAchievement[_line_radio][line_ratio] . "\n";
            $lineFee = floor($currentAchievement[line][_totalLineFee]*$currentAchievement[_line_radio][line_ratio]);
            $text .= "下线佣金：". format_money($lineFee) . "\n";
            $text .= "\n";
            $feeTatol =  $directFee + $lineFee ;
            $text .= "总佣金：". format_money($feeTatol) . "元\n";
                     
            $replyMessage = A('ReplyMessage');
            $replyMessage->setText($text);
            $replyMessage->replyTextMessage();
            
        }
        elseif($this->postObj->EventKey == 'lineSore')
        {
            //获取当前用户的当前业绩
            $currentAchievement = $this->_getCurrentCustomerCurrentAchievement();
            $text = '';
            foreach($currentAchievement['line']['_detail'] as $key => $value)
            {
                $text .= "下线昵称:".$value['_customer']['nickname']."\n";
                $text .= "销售业绩:" . format_money($value['_totalGoodsFee']) . "\n";
                $text .= "贡献佣金:" . format_money($value['_totalLineFee']) . "\n";
                $text .= "\n";
            }
            $replyMessage = A('ReplyMessage');
            $replyMessage->setText($text);
            $replyMessage->replyTextMessage();
        }
        //响应我的二维码事件
        elseif($this->postObj->EventKey == 'viewCode')
        {
//                $replyMessage = A('ReplyMessage');
//                $openId = (string)$this->postObj->FromUserName;
//                $customerInfo = get_customer_info($openId);
//                $text = '您的邀请码是：' . $customerInfo['customer_code'];
//                $replyMessage->setText($text);
//                $replyMessage->replyTextMessage();
            $message = A('ReplyQRCode');
            $message->setActionName(1);//1为永久方式获取，0为临时获取
            $message->replyCode();
            return;
        }
        //分销加盟join,分销政策policy,关于我们aboutUs，联系店小二contactUs
        elseif((string)$this->postObj->EventKey == 'join' || (string)$this->postObj->EventKey == 'policy' || (string)$this->postObj->EventKey == 'aboutUs' || (string)$this->postObj->EventKey == 'contactUs')
        {
            $cutomMenu->setEventKey((string)$this->postObj->EventKey);
            $message = $cutomMenu->findInfo();
            //文件类型
            if($message['reply_type'] == 'text')
            {
                $replyMessage = A('ReplyMessage');
                $replyMessage->setText($message['reply_content']);
                $replyMessage->replyTextMessage();
            }
            //图文类型
            elseif($message['reply_type'] == 'news')
            {
                $replyMessage = A('ReplyMessage');
                $data['title'] = $message['reply_title'];
                $data['description'] = $message['reply_content'];
                $data['url'] = $message['reply_url'];
                $data['picurl'] = add_host($message['reply_image']['url']);
                $news[] = $data;
                $replyMessage->setNews($news);
                $replyMessage->replyNewsMessage();
            }
            //其它
            else
            { 
                $replyMessage = A('ReplyMessage');
                $replyMessage->setText('功能暂未实现');
                $replyMessage->replyTextMessage();
            }
            return;
        }
        else
        {
            $ReplyMessage = A('ReplyMessage');
            $ReplyMessage->setText('功能暂未实现');
            $ReplyMessage->replyTextMessage();
            return;
        }
            
    }
    
    /*
     * 响应用户关注事件
     * 1.将用户信息存表
     * 2.取用户的邀请码，没有则置0，为系统邀请
     * 3.查看用户是否以前关注过。
     * 3.1未关注，则取活动信息，发放优惠券.
     * 4.更新用户信息（查看原始上级是否为系统，如果为系统，则进行上下级的更新）
     */
    public function responseSubscribeMsg()
    {
        //个性化二维码 
        if(isset($this->postObj->EventKey))
        {
            $eventKey = (string)$this->postObj->EventKey;
            $pcid = substr($eventKey, 8);
        }
        else
        {
            $pcid = 0;
        }
        //取用户的基本信息
        $openid = (string)$this->postObj->FromUserName;
        $accessToken = get_access_token();
        $data = get_weichat_user_info($openid,$accessToken);//用出客户基本信息
        
        //添加或更新用户信息
        $customerInfo = get_customer_info($openid);
        $customer = new \Customer\Model\CustomerModel();
        $data['subcribe_time'] = time();

        if($customerInfo == FALSE)
        {
            //进行注册送券活动
            $this->_sentCouponForNewUser($openid,$pcid);
            
            //取上线用户信息
            $oldCustomerInfo = $customer->getCustomerInfoById($pcid);
            if($oldCustomerInfo != false)
            {
                $oldOpendid = $oldCustomerInfo['openid'];
            }
            else
            {
                $oldOpendid = '';
            }
            //进行注册送红包活动
            $SendRedGiftController = new SendRedGiftController();
            $SendRedGiftController->sendRedGiftForCustomer($openid,$oldOpendid);

            //加上邀请码
            $code = $customer->checkCode();
            $data['parentid'] = $pcid;
            $data['customer_code'] =$code ; 

        }else{
            if($customerInfo['parentid'] == 0)
            {
                $data['parentid'] = $pcid;
            }
        }
        $userInfo[] = $data;
        $customer->addUserInfo($userInfo);

        $reply = new ReplyModel();
        $id = 1;
        $reply->setId($id);
        $message = $reply->getInfoById();
        
        //获取附件信息
        $attch = new AttachmentModel();
        $key = 'picurl';
        $attch->setKey($key);
        $attch->setInfo($message);
        $message = $attch->findInfo();
        
        
        if($message['news_type'] == 0)
        {
            $replyMessage = A('ReplyMessage');
            $replyMessage->setText($message['description']);
            $replyMessage->replyTextMessage();
        }
        //图文类型
        elseif($message['news_type'] == 1)
        {
            $replyMessage = A('ReplyMessage');
            $data['title'] = $message['title'];
            $data['description'] = $message['description'];
            $data['url'] = $message['url'];
            $data['picurl'] = add_host($message[$key]['url']);
            $news[] = $data;
            $replyMessage->setNews($news);
            $replyMessage->replyNewsMessage();
        }
        //其它
        else
        { 
            $replyMessage = A('ReplyMessage');
            $replyMessage->setText('系统错误');
            $replyMessage->replyTextMessage();
        }
        return;
    }
    
    /*
     * 响应用户扫描个性化二维码事件
     */
    public function responseScanMsg()
    {
        $eventKey = (string)$this->postObj->EventKey;
        $this->postObj->EventKey = 'qrscene_' . $eventKey;
        $this->responseSubscribeMsg();
    }
    /*
     * 响应用户取消关注事件
     * 用户取消关注，将用户状态至0.
     */
    public function responseUnsubscribeMsg()
    {
        $openid = (string)$this->postObj->FromUserName;
        $data['openid'] = $openid;
        $data['subscribe'] = 0;
        $customer = D('Customer/Customer');
        $customer->updateState($data);
    }
    private function _replace($word){
        $res = strpos($word,"\n");
        $temp = substr($word,0,$res)."\n".substr($word,$res+2);
        return $temp;
    }
    
    //注册送券活动
    public function _sentCouponForNewUser($openid,$parentId)
    {
        $active = new ActiveModel();
        $coupon = new CouponModel();
        if($parentId != 0)
        {
            $customer = new CustomerModel();
            $parentCustomer = $customer->getCustomerInfoById($parentId);
            if($parentCustomer != false)
            {
                $parentOpenid = $parentCustomer['openid'];
                $activeOld = $active->getOldCustomerSendCouponInfo();
                if($activeOld['state'] == 0)
                {
                    $cover = $activeOld['cover']; //面额
                    $duration = $activeOld['duration'];
                    $count = $activeOld['count'];
                    $coupon->sendCoupon($parentOpenid,$cover,$duration,$count);
                }
            }            
        }     
        $activeNew = $active->getNewCustomerSendCouponInfo();
        if($activeNew['state'] == 0)
        {
            $cover = $activeNew['cover']; //面额
            $duration = $activeNew['duration'];
            $count = $activeNew['count'];
            $coupon->sendCoupon($openid,$cover,$duration,$count);
        }
    }
    
    /*
     * 获取当前用户的当前业绩
     */
    private function _getCurrentCustomerCurrentAchievement()
    {
        /* 取用户基本信息**** */
        $openid = (string)$this->postObj->FromUserName;

        //获取当前周期的开始结束日期
        $config = new ConfigModel();
        $times = $config->getBeginTimeAndEndTime();
        $beginTime = $times['endTime'];
        $endTime = time();

        //取当期业绩信息中直销业绩
        $relationForm = new OrderRelationModel();
        $currentAchievement['direct'] = $relationForm->getAchievementByOpenid($openid,$beginTime,$endTime);

        //取出上线为自已的所有用户的业绩
        $customer = new CustomerModel();
        $openids = $customer->getLineOpenids($openid);
        $currentAchievement['line'] = $relationForm->getAchievementByOpenid($openids,$beginTime,$endTime);

        //添加直销,线销系数
        $rebate = new RebateModel();

        //取直销系数
        $dataArr[0] = $currentAchievement['direct'];
        $key = '_totalGoodsFee';
        $keyReturn = '_direct_radio';
        $dataArr = $rebate->getRatioByMoney($dataArr, $key, $keyReturn);
        $currentAchievement['_direct_radio'] = $dataArr[0][$keyReturn];

        //取线销系数
        $dataArr[0] = $currentAchievement['line'];
        $keyReturn = '_line_radio';
        $dataArr = $rebate->getRatioByMoney($dataArr, $key, $keyReturn);
        $currentAchievement['_line_radio'] = $dataArr[0][$keyReturn];
        return $currentAchievement;
    }
}
