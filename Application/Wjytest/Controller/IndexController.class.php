<?php
namespace Wjytest\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
        header("Content-type:text/html;charset=utf-8");
        $rebeta = M('rebate')->select();  
        $this->assign('rebate',$rebeta);
        $template = T('Wjytest@index');
        $this->display($template);
    }
}