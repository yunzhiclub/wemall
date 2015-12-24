<?php
/*
 * 收货地址管理
 */
namespace OrderForm\Controller;
use Think\Controller;
use CustomerAddress\Model\CustomerAddressModel;
use OrderForm\Model\AttachmentModel;
class AddressController extends Controller
{
    private $maxAddressCount = null;
    public function __construct() {
        parent::__construct();
        $this->maxAddressCount = 10;
        $addressCss = $this->fetch(T("OrderForm@Address/addressCss"));
        $this->assign('addressCss',$addressCss);
        $addressJs = $this->fetch('addressJs');
        $this->assign('addressJs',$addressJs);
    }
    
    /*
     * 添加收货地址
     */
    public function addAction()
    {
        $openid = I('get.openid');
        $this->assign('openid',$openid);
      
        $this->display("addressEdit");
    }
    

    public function  chooseAddressAction()
    {
        //取OPENID，并送入模板
        $openid = I('get.openid');
        $this->assign('openid',$openid);
        
        //取用户收货地址信息
        $address = new CustomerAddressModel();
        $address->setOpenid($openid);
        $addressList = $address->getAddress();
        $this->assign('address',$addressList);
        
        //计算用户还可以添加的数量
        $count = count($addressList);
        $allowAddCount = $this->maxAddressCount - count($addressList);
        $data['allowAddressNo'] = $allowAddCount;
        $this->assign('data',$data);
        
        $tpl = T("OrderForm@Address/address");
        $this->display($tpl);
    }
    
    /*
     * 编辑收货地址
     */
    public function editAction()
    {
        $id = I('get.id');
        
        
        $address = new CustomerAddressModel();
        $address->setId($id);
        $addressINfo = $address->getAddress();
        $this->assign('address',$addressINfo);
        
        $this->display("addressEdit");
    }
    /*
     * 删除收货地址
     */
    public function deleteAction()
    {
        $id = I('get.id','');
        if($id == '')
        {
            echo "false";
        }
        $address = new CustomerAddressModel();
        $address->deleteDataById($id);
        echo "success";
        
    }
    
    /*
     * 保存（新增、更新）收货地址
     */
    public function saveAction()
    {
        $id = I('get.id','');
        $openid = I('get.openid');
        if($id == '' && $openid == '')
        {
            return false;
        }
        
        $data = I('get.');
        $address = new CustomerAddressModel();
        $address->setPostData($data);
        $address->savePostAddress();
    }
    
    /*
     * 更新默认收货地址
     * 并返回收货地址名细
     */
    public function updateAddressAction()
    {
        $id = I('get.id','');
        if($id == '')
        {
            return false;
        }
        
        //更新信息
        $address = new CustomerAddressModel();
        $data['id'] = $id;
        $address->setPostData($data);
        $address->savePostAddress();
        
        //取地址信息
        $address->setId($id);
        $addressInfo = $address->getAddress();
        $this->assign('address',$addressInfo);
        
        $tpl = T("OrderForm@Submit/indexAddress");
        echo $this->fetch($tpl);
    }
    
    /*
     * 上传微信服务器图片至本地服务器
     * 返回图片ID
     */
    public function uploadImageAction()
    {
        $serverId = I('get.serverId','');
        $openid = I('get.openid','');
        if($serverId == '' || $openid == '')
        {
            return false;
        }
        
        //接收身份证信息并抓取上传服务器，返回身份证附件ID
        $attModel = new AttachmentModel();
        $attModel->setOpenid($openid);
        $attId = $attModel->getAndUploadWxImage($serverId);
        echo $attId;
    }
}