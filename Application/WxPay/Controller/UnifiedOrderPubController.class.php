<?php
/**
 * 统一支付接口类
 */
namespace WxPay\Controller;
use WxPay\Model\OrderRelationModel;
class UnifiedOrderPubController extends WxPayClientPubController{
    public function __construct() 
    {
        //设置接口链接
        $this->url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //设置curl超时时间
        $this->curl_timeout = C('WXPAY_CURL_TIMEOUT');
    }
	
    /**
     * 生成接口参数xml
     */
    public function createXml()
    {
        try
        {
            //检测必填参数
            if($this->parameters["out_trade_no"] == null) 
            {
                throw new SDKRuntimeExceptionController("缺少统一支付接口必填参数out_trade_no！"."<br>");
            }elseif($this->parameters["body"] == null){
                throw new SDKRuntimeExceptionController("缺少统一支付接口必填参数body！"."<br>");
            }elseif ($this->parameters["total_fee"] == null ) {
                throw new SDKRuntimeExceptionController("缺少统一支付接口必填参数total_fee！"."<br>");
            }elseif ($this->parameters["notify_url"] == null) {
                throw new SDKRuntimeExceptionController("缺少统一支付接口必填参数notify_url！"."<br>");
            }elseif ($this->parameters["trade_type"] == null) {
                throw new SDKRuntimeExceptionController("缺少统一支付接口必填参数trade_type！"."<br>");
            }elseif ($this->parameters["trade_type"] == "JSAPI" && $this->parameters["openid"] == NULL){
                throw new SDKRuntimeExceptionController("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
            }
            $this->parameters["appid"] = C('WECHAT_APPID');//公众账号ID
            $this->parameters["mch_id"] = C('WXPAY_MCHID');//商户号
            $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip	    
            $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters);//签名
            return  $this->arrayToXml($this->parameters);
        }catch (SDKRuntimeExceptionController $e)
        {
            die($e->errorMessage());
        }
    }

    /**
     * 获取prepay_id
     * @orderNo订单号，或是交易号out_trade_no
     * @ruturn 如果库里有，证明
     */
    public function getPrepayId()
    {
        $orderNo = $this->parameters["out_trade_no"];
        $orderForm = new OrderRelationModel();
        $orderForm->setPayId($orderNo);
        $prepay_id = $orderForm->getPrepayId();
        $data = array();
        if($prepay_id == FALSE)
        {
            $this->postXml();
            $this->result = $this->xmlToArray($this->response);
            if($this->result['result_code'] == 'FAIL')
            {
                $data['state'] = 'error';
                $data['msg'] = $this->result['err_code_des'];
                return $data;
            }
            elseif($this->result['return_code'] == 'FAIL')
            {
                $data['state'] = 'error';
                $data['msg'] = $this->result['return_msg'];
                return $data;
            }
            $prepay_id = $this->result["prepay_id"];
            $orderForm->savePrepayId($prepay_id);
        }
        $data['state'] = 'success';
        $data['prepay_id'] = $prepay_id;
        return $data;
    }
}