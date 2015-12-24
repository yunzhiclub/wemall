<?php
/*
 * 用于接收微信支付的回调值
 * 接收后存入数据库
 * 更新相关订单状态
 */
namespace WxPay\Controller;
use Think\Controller;
use WxPay\Model\OrderRelationModel;
use WxPay\Model\OrderFormModel;
class InterfaceController extends Controller{
    public function indexAction()
    {
        //不进行任何的过滤，获取XML数据,并转化为数组
        $xml = I('globals.HTTP_RAW_POST_DATA','',false);
        if($xml == '')
        {
            return;
        }    
        
        $postArr = xml_to_array($xml);
        /*
         * 验证签名的有效性,证明是微信官方发回的文件
         * 很重要
         * 不然会有服务欺骗
         * 验证成功,返回success
         * 不成功,直接跳出
         */
        $sign = $postArr['sign'];
        unset($postArr['sign']);
        if( $sign == get_wechat_sign($postArr) )
        {
            echo 'SUCCESS';
        }
        else
        {
            return;
        }
        /*
         * 依据PAYID进行订单状态的更新操作.
         * 1.更新支付ID表
         * 2.更新订单表
         */
        $orderRelation = new OrderRelationModel();
        $res = $orderRelation->saveInfo($postArr);
        //返回值为false，证明数据重复提交，直接退出
        if($res == false)
        {
            return;
        }
        $orderForm = new OrderFormModel();
        $orderForm->setPostArr($postArr);
        $orderForm->saveInfo();
        return;
    }
}
