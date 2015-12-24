<?php
namespace Register\Controller;
use User\Controller\UserController;
use Register\Model\UcpaasModel;
use Register\Model\CustomerModel;
use Active\Model\ActiveModel;

class IndexController extends UserController   {
    public function _initialize(){
        $title = '会员注册';
        $this->assign('title', $title);
        $this->addCss('/theme/wemall/css/head.css');
        $this->addCss('/theme/wemall/css/reset.css');
        $this->addCss('/theme/wemall/css/style.css');
        $this->addCss('/theme/wemall/css/user.css');
        $this->addCss('/theme/wemall/css/xmapp.css');
        $this->addCss('/theme/wemall/css/order-list.css');
        $this->addCss('/theme/wemall/css/order-detial.css');
        $this->addJs('/theme/wemall/js/jquery.autocomplete.js');
        $this->addJs('/theme/wemall/js/jquery.lazyload.js');
        $this->addJs('/theme/wemall/js/jquery.touchScroll.js');
        $this->addJs('/theme/wemall/js/fbi.js');
        parent::_initialize();
    }
    public function indexAction(){
        $openid = get_openid();
        $name = I('get.nickname');
        $reg = M('customer');
        $map = array();
        $openid = $this->getOpenid();
        $this->assign('openid',$openid);
        $registeUrl= U('registe');
        $urlSend = U('sendMsg');
        $gotoUrl = U('User/UserCenter/index');
        $this->assign('gotoUrl',$gotoUrl);
        $this->assign('openid',$openid);
        $this->assign('urlSend',$urlSend);
        $this->assign('registeUrl',$registeUrl);
        $this->assign('cusid',"$cusid");
        $css = $this->fetch('indexCss');
        $js = $this->fetch('indexJs');
        $this->assign('css',$css);
        $this->assign('js',$js);
        $this->assign("YZBody",$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    
    /*
     * 发送验证码
     * 1.看上次发送时间上否在1分钟以内
     * 2.发送并缓存
     */
    public function sendMsgAction(){     
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
        session($openid.'checkCode',$authNum,300);
        session($openid.'lastTime',time(),60);
        
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
     * 用户提交注册action
     * @input openid 用户ID
     * @input checkcode 验证码
     * @return ture false
     * 1.接收验证码并验证是否过期
     * 1.1 有效则将用户状态更改为“注册用户”
     * 1.2 数据存用户的手机号及微信号
     * 2过期或验证码错语，返回false
     * 3 已是注册用户返回FALSE
     */
    public function registeAction()
    {
        $customer = new CustomerModel();
        $openId = I('get.openid','');
        $checkCode = I('get.checkcode','');
        $weixinId = I('get.weixinid','');
        if($openId == '' || $checkCode == '' || $weixinId == '')
        {
            return false;
        }
        
        //取用户信息
        $customerInfo = get_customer_info($openId);
        if($customerInfo == false)
        {        
            $customer->setOpenid($openId);
            $customer->addCustomer();
            $customerInfo = get_customer_info($openId);
        }
        $customerType = $customerInfo['subscribe_state'];
        
        //判断用户类型
        if((int)$customerType & 1 == 1)
        {
            echo "请务重复注册";
            return;
        }
        
        if($checkCode != session($openId . 'checkCode'))
        {
            echo "验证码错语";
            return;
        }
        
        if( time() - session($openId . 'lastTime') > 300 )
        {
            echo "验证码已过期，请重新获取";
            return;
        }
        
        //用户状态更新
        $customer->setWeixinId($weixinId);
        $customer->updateState();
        
        //调用关注送券活动
        $active = new ActiveModel();
        $active->setopenId($openId);
        $accessToken = get_access_token();
        $data = get_weichat_user_info($openId,$accessToken);//用出客户基本信息
        //
        $obj = get_postObj();
        if(isset($obj->EventKey))
        {
            $pcid = (int)$obj->EventKey;
        }
        else
        {
            $pcid = 0;
        }
        $data['parentid'] = $pcid; 
        $active->setParentId($pcid);
        $active->subscribe();
        //
        
        echo "success";
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
    
    
    function create($client_id,$client_pwd,$acc_id,$acc_token,$expire_time='') {
		
		//超时时间格式：20140605184430。不设置该参数的话默认从现在开始有效期两天
		if(empty($expire_time)) {
			//$expire_time = date('ymdHis',time()+3600*48);
			$expire_time = date('YmdHis',time()+3600*48);
			//echo $expire_time.PHP_EOL;
		}
		// 组合head信息
		$head_arr = array();
		$head_arr['Alg'] = 'HS256';
		$head_arr['Accid'] = $acc_id;
		$head_arr['Cnumber'] = $client_id;
		$head_arr['Expiretime'] = $expire_time;
		
		$head = json_encode($head_arr);
		// echo $head.PHP_EOL;
		
		// 组合body信息
		$body_arr = array();
		
		$body_arr['Accid'] = $acc_id;
		$body_arr['AccToken'] = $acc_token;
		$body_arr['Cnumber'] = $client_id;
		$body_arr['Cpwd'] = $client_pwd;
		$body_arr['Expiretime'] = $expire_time;
		
		$body = json_encode($body_arr);
		// echo $body.PHP_EOL;
		//HMAC+SHA256 认证方式。key为主账号的token
		//$body_bytes = hash_hmac( "sha224", utf8_encode( $body ), utf8_encode( $acc_token ),false );
		$hmac_arr ="sha256";
		$body_bytes = hash_hmac($hmac_arr , $body , $acc_token , true);
		// echo $body_bytes.PHP_EOL;
		//#base64编码
		$body_bytes = base64_encode($body_bytes);
		$head = base64_encode(utf8_encode($head));
		// 将SLC header，SLC pyload使用“.”进行连接，获得完整的SLC
		
		return $head.".".$body_bytes;
	}
        
}