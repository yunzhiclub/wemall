<?php
namespace Coupon\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
           $coupon = D('Coupon');
	   $coupon -> initAction();
	 //  $coupon -> insert();
    }
}