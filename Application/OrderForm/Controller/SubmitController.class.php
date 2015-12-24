<?php
/*
 * 用户在购物车选择后的提交订单界面
 */
namespace OrderForm\Controller;
use User\Controller\UserController;
use System\Model\ConfigModel;
use OrderForm\Model\LogisticsModel;
use OrderForm\Model\SourceModel;
use OrderForm\Model\ShoppingCartModel;
use CustomerAddress\Model\CustomerAddressModel;
use Reply\Model\ReplyModel;
class SubmitController extends UserController
{
    public function _initialize() { 
        $title = "立即支付";
        $this->assign($title);
        $this->addCss('/theme/wemall/css/head_foot.css');
        $this->addCss('/theme/wemall/css/order-detial.css');
        $this->addCss('/theme/wemall/css/reset.css');
        $this->addCss('/theme/wemall/css/xmapp.css');
        parent::_initialize();
    }
    
    
    
    /*
     * 立即支付触发事件
     */
    public function indexAction()
    {
//        // 判断当前URL是否传入了code和state值 
        $checkBrower = C('WECHAT_BROWER');
        if($checkBrower == true)
        {
            $code = I('get.code','defaultCode');
            $state = I('get.state','');
            //无授权code，则跳转获取
            if($code == 'defaultCode' || $state == '' || $code == session('code'))
            {
                goto_auth();
                return;
            } 
            //通过微信返回的CODE值，获取用户信息
            $userInfo = get_user_info($code);
            $userToken = $userInfo['access_token'];
            $this->openid = $userInfo['openid'];
            session('openid',$this->openid);
            session('openidTime',time());
            
//            //判断用户权限，未注册跳转到提示页面
//            $customerInfo = get_customer_info($this->openid);
//            $customerType = $customerInfo['subscribe_state'];
//            if($customerType == 0)
//            {
////                转到注册界面
////                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
////                $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
////                $url = U('Register/Index/index');
//                //转至提示如果成为分销商的界面
//                $id = 1;
//                $reply = new ReplyModel();
//                $reply->setId($id);
//                $replyInfo = $reply->getInfoById();
//                redirect($replyInfo['url']);
//            }
            session('code',$code);
            //获取调用收货地址配置信息
            //获取调用微信JSSDK签名信息
            $jssdk = new JSSDKController();
            $jssdk->setCode($code);
            $jssdk->setState($state);
            $jssdk->setUserToken($userToken);
    //        echo 'pay中当前code值' . $code . "<br />";
    //        echo 'pay中当前state值' . $state . "<br />";
    //        echo 'pay中当前userToken值' . $userToken . '<br />';
            $addressPackage = $jssdk->getAddressPackage();      
            $signPackage = $jssdk->getSignPackage();
        }
        
        $openid = get_openid();
        $this->assign('openid',$openid);
        $title = '确认订单';
        $this->assign('title',$title);
         //获取get信息,去除html过滤.数据类型为JSON格式的字符串
        $ids = I('get.ids','');
        //字符串转为数组
        $this->assign('ids',$ids);
        $ids = explode("-",$ids);
        $shoppingCart = new ShoppingCartModel();
        $counts = $shoppingCart->getCounts($ids,$openid);     
        $this->assign('counts',implode('-',$counts));
        
        //取用户收货地址信息
        $address = new CustomerAddressModel();
        $address->setOpenid($openid);
        $customerAddress = $address->getLastUseAdderss();
        $this->assign('address',$customerAddress);
        
        //设置GET收货地址管理的URL
        $customerAddress['chooseAddressUrl'] = U('Address/chooseAddress');
        $this->assign('address',$customerAddress);
        
        //取收货地址信息HTML
        $this->assign('indexAddress',$this->fetch('indexAddress'));
        

        /* 进行数据处理,添加商品详情，按不同来源、物流方式进行分组，并将分组后数组的信息返回 */
        $data = $this->dataAccess($ids,$counts);     
        $product = $data[0];
        $totalInfo = $data[1];
        
        //传用户id,优惠券表里取对应的优惠券      
        $couponM = D('Coupon');
        $coupon = $couponM->getCoupon($openid);
        
        $freightM = D('System/Config');
        $freight = $freightM->freightCount();//取运费的五个字段
        $this->assign('freight',$freight);
        
        //给出收货地址URL信息
        $data['addUrl'] = U('OrderForm/Address/add?openid=' . $openid);
        $data['editUrl'] = U('OrderForm/Address/edit?openid=' . $openid);
        $data['saveAddressUrl'] = U('OrderForm/Address/save');        
        $data['uploadImageUrl'] = U('OrderForm/Address/uploadImage');
        $data['updateAddressUrl'] = U('OrderForm/Address/updateAddress');
        $data['deleteUrl'] = U('OrderForm/Address/delete');
        $this->assign('data',$data);
        
        //给出可以定制的header信息
        $this->assign('header',$this->fetch('indexHeader'));
        
        
        $this->assign('coupon',$coupon);
        $this->assign('total',$totalInfo);
        //地址下面的条纹图片路径
        $guoduUrl = add_root_path('/theme/wemall/images/guodu.png');
        $this->assign('guoduUrl',$guoduUrl);
        $this->assign('css',$this->fetch('indexCss'));
        $this->assign('signPackage',$signPackage);
        $this->assign('addressPackage',$addressPackage);
        $updateUrl = U('Index/payNow');
        $this->assign('url',$updateUrl);
       
        
        
        $wxJs = $this->fetch('wxJs');
        $this->assign('wxJs',$wxJs);
        $js = $this->fetch('js');
        $this->assign('js',$js);
        $this->assign('product',$product);
        
        //送入首页URL
        $indexUrl = U('Home/Index/index');
        $this->assign('indexUrl',$indexUrl);
        
        //定制底部菜单
        $this->assign('footer',$this->fetch('footer'));
        $this->assign('YZBody',$this->fetch('index')); 
        $this->display(YZ_TEMPLATE);
    }   
    
    /* 取出商品的ID和数量，并按商品的来源和物流方式进行分组
     * 返回值：array(0,1,2,3
     * 数组中，每个元素代表一种来源的一种物流方式
     *  */
    public function dataAccess($ids,$counts)
    {
        if(count($counts) != count($ids))
        {
            return false;
        }
        $goodsM = D('Goods');
        $product = $goodsM->getProduct($ids,$counts);//根据商品id获取商品对应的信息       
        $attachmentinfo = D('Attachment/Attachment');//取附件表里面的置换图片url
        $attachmentinfo->setKey('focus_pictures');
        $product = $attachmentinfo->selectinfo($product);
        foreach ($product as $key => $value) {
            $product[$key]['product_quantity'] = ($counts[$key]== '' || $counts[$key]== 0)? 1 : $counts[$key] ;//加上商品数量这个字段
        }        
        $product = group_by_key($product, 'source');//商品按国家分类
        foreach($product as $key => $value)
        {
            $product[$key] = group_by_key($value, 'logistics_mode');//商品按物流方式分类
        }
        $i = 0;
        $total['totalFee'] = 0; //商品总价值
        $total['totalCount'] = 0;//商品总数量 
        $total['totalLogisticFee'] = 0;//商品总运费
        $total['totalAmount'] = 0;//商品总优惠金额
        $total['totalPayable'] = 0;//商品总应付金额
        $total['totalDirectFee'] = 0; //直销返点
        $total['totallineFee'] = 0;//线销返点
        $total['totalCustomsFee'] = 0;//总关税
        
        $logistic = new ConfigModel(); //运费配置
        $logisticM = new LogisticsModel();//物流方式
        $sourceM = new SourceModel();//来源
        foreach($product as $key => $value) //$key 来源
        {             
            foreach($value as $ke => $va)  //$ke 物流方式
            {
                $weightTotal = 0; //商品总重量
                $countTotal = 0;//商品总数量
                $feeTotal = 0; //商品总金额
                $maxAmount = 0; //商品最大优惠金额
                $maxLogisFee = 0; //最大国内运费
                $directTotal = 0; //直销返点
                $lineTotal = 0; //线销返点
                $customsTotal = 0; //关税
                foreach($va as $k => $v)
                {
                    $count = $v['product_quantity'];
                    $weightTotal += $v['weight']*$count;
                    $countTotal += $count;
                    $feeTotal += $count*$v['purchasing_price']; 
                    $directTotal += floor($v['purchasing_price']*$v['direct_selling_commission']/100)*$count;
                    $lineTotal += floor($v['purchasing_price']*$v['line_selling_commission']/100)*$count;           
                    //$customsTotal += $count*$v['customs'];不计算关税
                    if($v['discount_amount'] > $maxAmount)
                    {
                        $maxAmount = $v['discount_amount'];
                        $amountWay = $v['discount_way'];
                    }
                    if($v['domestic_transportation_expenses'] > $maxLogisFee)
                    {
                        $maxLogisFee = $v['domestic_transportation_expenses'];
                    }   
                    $res[$i]['goods'][] = $v; //商品信息
                }               
                $res[$i]['weightTotal'] = $weightTotal; 
                $res[$i]['countTotal'] = $countTotal;
                $res[$i]['feeTotal'] = $feeTotal;
                $res[$i]['maxAmount'] = $maxAmount;
                $res[$i]['maxLogisFee'] = $maxLogisFee;
                $res[$i]['directTotal'] = $directTotal; //直销返点
                $res[$i]['lineTotal'] = $lineTotal;
                $res[$i]['amountWay'] = $amountWay;//优惠政策  
                $res[$i]['customsTotal'] = $customsTotal;
                
                
                /*按物流方式，计算国内国际运费 */
                if($ke == 1)
                {
                    $internationLogisticFee = $logistic->getFee($weightTotal);
                    $domesticLogisticFee = 0;
                }
                else
                {
                    $internationLogisticFee = 0;
                    $domesticLogisticFee = $maxLogisFee;
                }       
                $logisticFee = $domesticLogisticFee + $internationLogisticFee;
                $res[$i]['internationLogisticFee'] = $internationLogisticFee;
                $res[$i]['domesticLogisticFee'] = $domesticLogisticFee;
                $res[$i]['logisticFee'] = $logisticFee;
                
                //取来源与物流方式
                $res[$i]['source'] = $sourceM->getInfoById($key); //来源
                $res[$i]['logisticType'] = $logisticM->getInfoByMode($ke);//物流方式
                
                //应付总金额 = 商品总价格－最大优惠金额+物流费用+总关税
                $res[$i]['payable'] = $feeTotal-$maxAmount+$logisticFee+$customsTotal;
                               
                /* 商品总信息 */
                $total['totalFee'] += $feeTotal; //商品总价值
                $total['totalCount'] += $countTotal;//商品总数量 
                $total['totalLogisticFee'] += $logisticFee;//商品总运费
                $total['totalAmount'] += $maxAmount;//商品总优惠金额
                $total['totalPayable'] += $res[$i]['payable']; //商品总应付金额
                $total['totalDirectFee'] += $res[$i]['directTotal']; //直销返点
                $total['totallineFee'] += $res[$i]['lineTotal'];//线销返点
                $total['totalCustomsFee'] += $res[$i]['customsTotal'];//关税               
                $i++;
            }           
        }
        //最低付款１分
        $total['totalPayable'] = ($total['totalPayable'] > 0)?$total['totalPayable']:1;
        $data[] = $res;
        $data[] = $total;
        return $data;
    }
    
    public function testAction()
    {
        $this->display('indexAddress');
    }
}

