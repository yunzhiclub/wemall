<?php
namespace Lf\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
        $lf = $this->fetch('ordermange');
        $this->show($lf);
       $this->display();
    }
}