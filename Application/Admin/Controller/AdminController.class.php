<?php
/* 
 * 梦云智工作室 www.yunzhi.club
 *  Author:Email: * 
 */
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller
{
    private $css;//调用CSS
    private $js;//调用JS
    private $head;//添加的head头信息
    protected $upload;//上传类
    protected $page;//分页类
    function addCss($css) {
        $this->css[] = $css;
    }
    function addJs($js) {
        $this->js[] = $js;
    }
    function addHead($head)
    {
        $this->head[] =$head;
    }
    public function __construct() {
        parent::__construct();
        $this->css = array();
        $this->js = array();
        $this->head = array();
        $this->upload = new \Think\Upload();
        $this->upload->maxSize = 5000000;//附件最大为5M
        $this->upload->exts = array('jpg','gif','png','jpeg');//允许上传的类型
        //相对于入口文件下的/upload的路径,upload路径在think基类中进行了定义
        $path = './Attachment/';
        $this->upload->savePath = $path;
        $this->upload->autosub = true;//启用子目录
        $this->upload->subName = array('date','Ymd');//设置子目录命名规则
        $this->saveName = array('uniqid','');//文件命名规则
        $this->page = new PageController();
        
        $userId = session('userId');
        if($userId == null || $userId == false)
        {
            $url = U('Admin/Login/login');
            redirect($url);
            die();
        }
        else
        {
            session('userId',$userId,1800);
        }   
    }
    protected function _initialize(){
        $this->css[] =  '/theme/Akira/css/bootstrap.min.css' ;
        $this->css[] = '/theme/Akira/css/bootstrap-responsive.min.css' ;    
        $this->css[] =  '/theme/Akira/css/site.css';
        $this->js[] = '/js/jquery1.11.2.js';
        $this->js[] = '/js/adminMain.js';
        $this->js[] = '/js/jquery.validate.js';
        $this->js[] = '/js/jquery.metadata.js';
        $this->js[] = '/js/jquery.validate.messages_cn.js';
        
        $this->css = addRootPath($this->css);//为每个元素增加应用根路径
        $this->js = addRootPath($this->js);//
        //取主菜单
        $menu = new MenuListController();
        $mainMenu = $menu->fetchMainMenu();
        $subMenu = $menu->fetchSubMenu();
        $currentMenuInfo = $menu->getCurrentMenuInfo();
        $title = $currentMenuInfo['title'] . ' Power By yunzhi.club';
        $this->assign('YZHead',$this->head);
        $this->assign('YZCss',$this->css);
        $this->assign('YZJs',$this->js);
        $this->assign('YZTitle',$title);
        $this->assign('YZMenu',$mainMenu);
        $this->assign('YZLeft',$subMenu);
        define("YZ_TEMPLATE",T('Template@Admin/index'));
    }
}