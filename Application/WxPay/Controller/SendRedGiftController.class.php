<?php
/*
 * 发送红包类
 */
namespace WxPay\Controller;
use WxPay\Controller\WxPayClientPubController;
use SetRedpacket\Model\SetRedpacketModel;
use WechatRedpacket\Model\WechatRedpacketModel;
class SendRedGiftController extends WxPayClientPubController
{
    private $openid; 
    private $minValue;//红包发放最小值
    private $maxValue;//红包发放最大值
    private $wishing;//祝福的话
    private $actName; //活动名称
    private $remake;//备注
    private $nickName;
    private $sendName;
    function __construct() {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
        //设置curl超时时间
        $this->curl_timeout = 30;
    }
    
    /*
     * 为新老用户发送红包
     */
    public function sendRedGiftForCustomer($newOpenid,$oldOpenid)
    {
        //取出配置信息
        $SetRedpacketModel = new SetRedpacketModel();
        $config = $SetRedpacketModel->init();
        
        //老用户发红包
        if($config['old_state'] == true && $oldOpenid != '' )
        {
            $WechatRedpacketModel = new WechatRedpacketModel();
            $count = $WechatRedpacketModel->getCountsByOpenid($oldOpenid);
            if($count <= $config['old_max_num'] )
            {
                $this->send($config['send_name'], $config['send_name'], $oldOpenid, $config['old_min_value'], $config['old_max_value'],  trim($config['wishing']), $config['act_name'], $config['remark']);
            }
        }

        //新用户发红包
        if($config['new_state'] == true)
        {
            //判断是否直接关注
            if($oldOpenid == '')
            {
                if( $config['attention_give'] == false)
                {
                    return;
                }
            }
            
            //按规则发放红包
            $this->send($config['send_name'], $config['send_name'], $newOpenid, $config['new_min_value'], $config['new_max_value'],  trim($config['wishing']), $config['act_name'], $config['remark']);
        }
    }
    
    
    public function send($nickName,$sendName,$openid,$minValue,$maxValue,$wishing,$actName,$remark)
    {
        //发送红包
        $this->nickName = $nickName;
        $this->sendName = $sendName;
        $this->openid = $openid;
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        $this->wishing = $wishing;
        $this->remake = $remark;
        $this->actName = $actName;
        $sendValue = rand($minValue, $maxValue);
        $xmlData = $this->createXml($sendValue);
        $returnStr = xml_to_array($this->postXmlSSLCurl($xmlData,$this->url));
        
        //正确返回信息，写返回状态SUCCESS，写库
        if(isset($returnStr['return_code']) && $returnStr['return_code'] == "SUCCESS" &&$returnStr['result_code'] == "SUCCESS"  )
        {
            $WechatRedpacketModel = new WechatRedpacketModel();
            $WechatRedpacketModel->addData($nickName,$sendName,$openid,$minValue,$maxValue,$sendValue,$wishing,$actName,$remark,$returnStr);
        }
    }
    
     /**
     * 生成接口参数xml
     */
    function createXml($sendValue) {
        $this->parameters["nonce_str"]    = $this->createNoncestr();//随机字符串
        $this->parameters['mch_billno']   = C("WXPAY_MCHID").date('Ymd').rand(1000000000, 9999999999);
        $this->parameters["mch_id"]       = C("WXPAY_MCHID");//商户号
        $this->parameters['wxappid']      = C("WECHAT_APPID");//公众账号ID
        $this->parameters['nick_name']    = $this->nickName;
        $this->parameters['send_name']    = $this->sendName;
        $this->parameters['re_openid']    = $this->openid;//发给谁
        $this->parameters['total_amount'] = $sendValue;//付款金额
        $this->parameters['min_value']    = $sendValue;//最小红包
        $this->parameters['max_value']    = $sendValue;//最大红包
        $this->parameters['total_num']    = '1';//最大红包
        $this->parameters['wishing']      = $this->wishing;//最大红包
        $this->parameters['client_ip']    = get_server_ip();//最大红包
        $this->parameters['act_name']     = $this->actName;//最大红包
        $this->parameters['remark']       = $this->remake;//最大红包
        $this->parameters["sign"]         = $this->getSign($this->parameters);//签名
        return $this->arrayToXml($this->parameters);
    }
    
    /**
     *     作用：获取结果，使用证书通信
     */
    function getResult() {
        $this->postXmlSSL();
        $this->result = $this->xmlToArray($this->response);
        return $this->result;
    }
}

