<?php
/**
 * Description of GoodsDetailsController
 *商品详情页面
 * @author XINGYANNIAN
 */
namespace Goods\Controller;
use User\Controller\UserController;
use Goods\Model\GoodsModel;
use Goods\Model\CustomerModel;
use Goods\Model\ShoppingCartModel;
use System\Model\ConfigModel;
use Logistics\Model\LogisticsModel;
use Home\Model\SourceModel;
class GoodsDetailController extends UserController {
    private $config_buy_max;
    private $config_buy_min;
    public function __construct() {
        parent::__construct();
        $config = M('Config');
        $this->config_buy_max = $config->where('id=67')->getField('value');
        $this->config_buy_min = $config->where('id=68')->getField('value');
    }
    public function _initialize() {    
        $this->assign('title','商品详情');
        $this->addCss('/theme/slider/swiper.min.css');
        $this->addJs('/js/slider/slider.js');
        parent::_initialize();
    }
    public function indexAction() {
        $customerInfo = get_customer_info(get_openid());
        $this->assign('customerType', $customerInfo['subscribe_state']);
        $this->assign('config_buy_max',  $this->config_buy_max);
        $this->assign('config_buy_min',  $this->config_buy_min);
        $this->assign('payNowUrl',U('OrderForm/Submit/index'));
        $addGoodsToCartUrl = U('Goods/GoodsDetail/add');
        $this->assign('addGoodsToCartUrl',$addGoodsToCartUrl);
        $this->assign('footer','');
        
        $goods = new GoodsModel();
        $goodsId = I('get.goodsId');
        $goods->setGoodsId($goodsId);
        $res = $goods->getGoodsInf();
        if($res == false || $res['state'] != 1)
        {
            $this->assign('js',$this->fetch('indexJs'));
            $this->assign('css',$this->fetch('indexCss'));
            $detail2 = $this->fetch('error');
            $this->assign('YZBody',$detail2);
            $this->display(YZ_TEMPLATE);
            return;
        }
        //计算运费
//        if($res['logistics_mode'] == 0)
//        {
//            $logistic = new ConfigModel(); //运费配置
//            $res['internation_transportation_expenses'] = $logistic->getFee($weightTotal);
//        }
//        
        //获取来源国信息
        $source = new SourceModel();
        $map['id']  = $res['source'];
        $res['source'] = $source  ->getSource($map);
        $res['source']['icon_url'] = __ROOT__."/".$res['source']['icon_url'];
       
        
        
        
        $logistic = new LogisticsModel();
        $key = 'logistics_mode';
        $data[0] = $res;
        $data = $logistic->getLogisticInfo($data , $key);
        $res = $data[0];
        
        $detail = $res;
        
        
//        var_dump($detail);
        $this->assign('goods',$detail);
        $this->assign('js',$this->fetch('indexJs'));
        $this->assign('css',$this->fetch('indexCss'));
        $detail2 = $this->fetch('index');
        $this->assign('YZBody',$detail2);
        $this->display(YZ_TEMPLATE);
       
    }
   
    
    /*
     * 接收商品详情添加到购物车事件
     * 1.判断是否有该商品
     * 1.1有则更新数据
     * 1.2无则添加数据
     * 2.判断是否超出商品购买最大最小值。
     * 2.1未超出直接存
     * 2.2超出将商品最大最小值重置为系统设置后存
     */
    public function addAction() {
        $this->assign('config_buy_max',  $this->config_buy_max);
        $this->assign('config_buy_min',  $this->config_buy_min);
        $goodsId = I('get.goodId');//获取商品编号
        $goodsNum = I('get.count');//获取购买数量
        $openId = get_openid();//获取客户openId
        $shoppingCart = new ShoppingCartModel();
        $shoppingCart->setOpenid($openId);
        $shoppingCart->setGoodsId($goodsId);
        $shoppingCart->setGoodsNum($goodsNum);
        $shoppingCart->addGoodsToCart();
    }//添加商品到数据库
}