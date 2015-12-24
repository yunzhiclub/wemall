<?php
/**
 * Description of WechatRedpacketModel
 *
 * @author xlj
 */
namespace WechatRedpacket\Model;
use Think\Model;
class WechatRedpacketModel extends Model {
    
    public function getCountsByOpenid($openid)
    {
        $map['re_openid'] = $openid;
        return $this->where($map)->count();
    }
    
    /*
     * 新增数据
     */
    public function addData($nickName,$sendName,$openid,$minValue,$maxValue,$sendValue,$wishing,$actName,$remark,$returnStr)
    {
        $data = array();
        $data['re_openid'] = $openid;
        $data['total_amount'] = $sendValue;
        $data['min_value'] = $minValue;
        $data['max_value'] = $maxValue;
        $data['total_num'] = 1;
        $data['wishing'] = $wishing;
        $data['client_ip'] = get_client_ip();
        $data['act_name'] = $actName;
        $data['remark'] = $remark;
        $data['return_code'] = $returnStr['return_code'];
        $data['return_msg'] = $returnStr['return_msg'];
        $data['result_code'] = $returnStr['result_code'];
        $data['mch_billno'] = $returnStr['mch_billno'];
        $data['wxappid'] = $returnStr['wxappid'];
        $data['send_time'] = $returnStr['send_time'];
        $data['send_listid'] = $returnStr['send_listid'];
        $this->data($data)->add();
        return; 
    }
}
