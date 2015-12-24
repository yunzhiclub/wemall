<?php

namespace User\Controller;

use User\Controller\UserController;
use User\Model\ChangephoneModel;
use User\Model\UcpaasModel;
use Customer\Model\CustomerModel;
use Achievement\Model\AchievementModel;

/**
 * Description of viewController
 * 前台修改手机号
 * @author DENGHAOYANG
 */
class ChangePhoneController extends UserController {
    
    public function _initialize() {
        $title = '修改个人信息';
        $this->assign('title',$title);
        parent::_initialize();

    }
    //初始化修改手机号界面
    public function indexAction() {
        $openid = get_openid();
        $customer = new CustomerModel;
        $customerInfo = $customer->getCustomerInfo($openid);
        
        $gotoUrl = U('User/UserCenter/index');
        $this->assign('gotoUrl',$gotoUrl);
        
        $url = U('change');
        $this->assign('changeUrl',$url);
        $this->assign(number,$customerInfo['phone_number']);
        $this->assign('account',$customerInfo['account']);
        
        $this->assign('openid',$openid);
        $js = $this->fetch('indexJs');
        $this->assign('js',$js);
        
        $css = $this->fetch('indexCss');
        $this->assign('css',$css);
        
        $this->assign('YZBody',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    
    public function changOkAction(){
        $customer = new CustomerModel;
        $resData = $customer->changPhone();
        $url = U('User/UserCenter/index');
        if($resData['status'] == 'success')
        {
            $this->success('修改成功',$url,2);
        }
        else
        {
            $this->error('修改失败',$url);
        }
        
    }

    //调用云之讯模板 发送验证码功能
     /*
     * 发送验证码
     * 1.看上次发送时间上否在1分钟以内
     * 2.发送并缓存
     */
    public function sendNewMsgAction(){     
        $openid = I('get.openid','');
        $phone = I('get.phone','');
        if($openid == '' || $phone == '')
        {
            return false;
        }
        //session($openid.'lastTime',time(),1);
        $lastTime = session($openid . 'lastTime');
        if(time() - $lastTime < 60 )
        {
            return false;
        }     
        
        //随机生成4位验证码
        $authNum = $this->_creatRandNum(4);
             
        //缓存信息
        session($openid.'new_Code',$authNum,300);
        session($openid.'new_lastTime',time(),60);
        
        //初始化发送短信模板
        $ucpass = new UcpaasModel();  
        $ucpass->setPhone($phone);
        $ucpass->setText($authNum);
        $state = $ucpass->sendMsg();
        if($state === true)
        {
            echo "success";
        }
        else
        {
            echo $state;
        }
    }
    public function sendOldMsgAction(){     
        $openid = I('get.openid','');
        $phone = I('get.phone','');
        if($openid == '' || $phone == '')
        {
            return false;
        }
        //session($openid.'lastTime',time(),1);
        $lastTime = session($openid . 'lastTime');
        if(time() - $lastTime < 60 )
        {
            return false;
        }     
        
        //随机生成4位验证码
        $authNum = $this->_creatRandNum(4);
             
        //缓存信息
        session($openid.'old_Code',$authNum,300);
        session($openid.'old_lastTime',time(),60);
        
        //初始化发送短信模板
        $ucpass = new UcpaasModel();  
        $ucpass->setPhone($phone);
        $ucpass->setText($authNum);
        $state = $ucpass->sendMsg();
        if($state === true)
        {
            echo "success";
        }
        else
        {
            echo $state;
        }
    }
    
    /*
     * 生成count位数的随机数
     */
    private function _creatRandNum($count)
    {
        srand((double)microtime()*1000000);//create a random number feed.
        $ychar="0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,U,V,W,X,Y,Z";
        $list=explode(",",$ychar);
        for($i=0;$i<$count;$i++){
            $randnum=rand(0,33); // 10+26;
            $authnum.=$list[$randnum];
        }
        return $authnum;
    }
     /*
     * 修改手机号动作
     */
    public function changeAction(){
        $openId = I('get.openid','');
        $phonenumber = I('get.newphone');
        $account = I('get.account','');
        $map = array();
        $map['openid'] = $openId;
        $data = array();
        $data['phone_number'] = $phonenumber;
        $data['account'] = $account;
        $cus = M('customer');
        $cus->where($map)->save($data);
        $string = "success";
        echo $string;       
    }
}       
