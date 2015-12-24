<?php
//微信用户模块，进行微信用户的判断及认证。
/*
 * 所以前台模块将继承此模块。
 * 实现：指加入文件头
 * 自动进行是否微信BROWER验证
 * 自动进行客户验证
 * 非关注用户自动进行授权界面跳转
 */
namespace User\Controller;
use Think\Controller;
class UserController extends Controller
{
    protected $code = null;//微信验证返回码
    private $accessToken = null;//微信返回accessToken
    private $refreshToken = null;//微信返回refreshToken
    private $openId = null;//用户openId
    private $state = null;//设置返回状态
    private $userToken = null;//用户token
    private $css = null;//引用CSS
    private $js = null;//引用cs
    private $head = null;//引用文件头部
    public function __construct() {
        parent::__construct();
        $this->css = array();
        $this->js = array();
        $this->head = array();
        /* 初始化upload类 */
        $this->upload = new \Think\Upload();
        $this->upload->maxSize = 5000000;//附件最大为5M
        $this->upload->exts = array('jpg','gif','png','jpeg');//允许上传的类型
        //相对于入口文件下的/upload的路径,upload路径在think基类中进行了定义
        $path = './Customer/';
        $this->upload->savePath = $path;
        $this->upload->autosub = true;//启用子目录
        $this->upload->subName = array('date','Ymd');//设置子目录命名规则
        $this->saveName = array('uniqid','');//文件命名规则
    }
    function addCss($css) {
        $this->css[] = $css;
    }

    function addJs($js) {
        $this->js[] = $js;
    }
    function addHead($head)
    {
        $this->head[] = $head;
    }
    //获取用户OPENID，将跳转
    function getOpenid()
    {
        $openid = session('openid');
        if($openid ==  null)
        {
            $this->code = I('get.code','');   
            $this->state = I('get.state','');
            //如果存在code，证明是二次访问。
            if( ($this->code != '') && ($this->code != null) && ($this->code != S('code') ))
            {
                S('code',$this->code,300);//缓存300秒
                $user = get_user_token($this->code);//获取有户token相关信息
                if($user['openid'] != null)
                {
                    session('openid',$user['openid']); 
                    session('userToken',$user['access_token']);
                    session('openidTime',time());
                    $key = $user['openid'] . "Token";
                    S($key , $this['userToken'] , $user['expires_in']);
                }
                else
                {
                    var_dump($user);
                    throw_exception('获取用户信息失败');                    
                }
            }
            else //其它条件为首次访问，跳转至授权界面
            { 
                goto_auth();
                die();
            }
            
        }
        else
        {
            return $openid;
        }
    }
    
    protected function _initialize(){
//        $this->css[] = '/theme/default/pstyle/main.css' ;
//        $this->js[] = '/js/jquery1.11.2.js';
//        $this->css[] = '/theme/wemall/css/foot.css';
//        $this->css[] = '/theme/wemall/css/global_nav.css';
//        $this->css[] = '/theme/wemall/css/head.css';
//        $this->css[] = '/theme/wemall/css/shopping-cart.css';
//        $this->css[] = '/theme/wemall/css/reset.css';
//        $this->css[] = '/theme/wemall/css/xmapp.css';
//        $this->css[] = '/theme/wemall/css/order-detial.css';
//        $this->css[] = '/theme/wemall/css/footer.css';
//        $this->css[] = '/theme/wemall/css/head.css';
//        $this->css[] = '/theme/wemall/css/home.css';
//        $this->js[] = '/theme/wemall/js/jquery.min.js';
//        $this->js[] = '/theme/wemall/js/bootstrap.min.js';
//        $this->css[] = '/theme/wemall/bootstrap/css/bootstrap.min.css';
        $this->css[] = '/theme/wemall/css/Supernice.min.css';
        $this->js[] = '/theme/wemall/js/dropload.min.js';
        $this->js[] = '/theme/wemall/js/affix.js';
        $this->js[] = '/js/jquery.showLoading.js';
        $this->css[] = '/theme/wemall/css/showLoading.css';
        $this->css[] = '/theme/wemall/css/style.css';
        $this->css = addRootPath($this->css);//为每个元素增加应用根路径
        $this->js = addRootPath($this->js);
        $this->assign('YZCss',$this->css);
        $this->assign('YZJs',$this->js);
        $this->assign('YZHead',$this->head);
        $indexUrl = U('Home/Index/index');
        $userCenterUrl = U('User/UserCenter/index');
        $shoppingCartUrl = U('ShoppingCart/Index/index');
        $this->assign('userCenterUrl',$userCenterUrl);
        $this->assign('shoppingCartUrl',$shoppingCartUrl);
        $this->assign('indexUrl',$indexUrl);
        $this->assign('menuTop',$this->fetch(T('User@User/menuTop')));
        if(!(MODULE_NAME == 'Home' && ACTION_NAME=='index' && CONTROLLER_NAME == 'Index'))
        {
            $this->assign("header",$this->fetch(T('User@User/header')));
        }
        $this->assign('footer',$this->fetch(T('User@User/footer')));
        define("YZ_TEMPLATE",T('Template@Index/xiaomi'));
        //判断是否微信浏览器，如果否则终止程序，提示用户需要在微信浏览器中访问
        //        $wechatBrower = C('WECHAT_BROWER');        
//        if( $wechatBrower === FALSE )
//        {
//            $this->openId = C('WXPAY_OPENID');
//            session ('openid',$this->openId);
//            session('openidTime',time());
//             return;
//             //echo "<div>未开启微信浏览器验证，当前OPENID：".I('session.openId')."</div>";
//             
//        }
        if(!isWeixinBrowser())
        {
            die("请在微信浏览器中访问.如果你是开发者，请在模块的配置文件中加入'WECHAT_BROWER'     =>FALSE");
        }
    }
      public function useradd() {
         $user = M('user');
         $user->create();
         $user->add();
         
    }
}
