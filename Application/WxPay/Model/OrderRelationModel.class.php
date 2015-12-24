<?php
/*
 * 支付订单表
 * 存微信返回的支付信息
 * 或后台管理员提交的支付信息
 * 前期支付类型为微信H5支付及后台管理员支付
 * H5支付类型存为JSAPI
 * 后台管理员手动支付为OTHER
 */
namespace WxPay\Model;
use Think\Model;
class OrderRelationModel extends Model
{
    private $payId = null;//支付id
        
    public function setPayId($payId)
    {
        $this->payId = $payId;
    }
    /*
     * 获取当前预支付订单下的prepayId信息
     */
    public function getPrepayId()
    {
        if($this->payId == null)
        {
            return false;
        }
        $map['id'] = $this->payId;
        $res = $this->where($map)->find();
        if($res == false)
        {
            return false;
        }
        else
        {
            $preparId = $res['prepar_id'];
        }
        if($preparId == '')
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    /*
     * 更新prepareID信息
     */
    public function savePrepayId($prepay_id)
    {
        if($this->payId == null)
        {
            return false;
        }
        $map['id'] = $this->payId;
        $map['prepay_id'] = $prepay_id;
        $this->data($map)->save();
    }
    /*
     * 进行微信支付
     * 状态为已支付且支付类型为JSPAI
     * 则直接跳出
     * 1.判断result_code为success，而且ID=out_trade_no
     */
    public function saveInfo($data)
    {
        $map['id'] = $data['out_trade_no'];
        $res = $this->where($map)->find();
        if($res['result_code'] == 'SUCCESS')
        {
            return false;
        }
        else
        {
            $data['id'] = $data['out_trade_no'];   
            $data['payed_time'] = time();
            //更改支付状态
            $data['is_payed'] = 1;
            $this->data($data)->save();          
        }
        return true;
    }
    /*
     * 获取当前支付订单号下的信息
     * @param payid string 支付号ID
     * @param openid string 预支付人openid
     * @return array(state(1成功、0失败),msg(失败信息),res(订单支付信息);
     * 订单状态第1位，为1代表已微信支付 为0为非微信支付
     */
    public function chcekPayid($payId,$openid)
    {
        if($payId == null || $openid == null)
        {
            $resArr['state'] = 0;
            $resArr['msg'] = '输入信息有误';
        }
        else {
            $map['id'] = $payId;
//            $map['openid'] = $openid;
            $res = $this->where($map)->find();
            if($res == false)
            {
                $resArr['state'] = 0;
                $resArr['msg'] = '支付信息不存在';          
            }
            else
            {
                $isPayed= $res['is_payed'];
                if($isPayed == 1)
                {
                    $resArr['state'] = 0;
                    $resArr['msg'] = '订单已支付';     
                }
                else
                {
                    $resArr['state'] = 1;
                    $resArr['res'] = $res;
                    
                }
            }
        }    
        return $resArr;
        
    }

}
