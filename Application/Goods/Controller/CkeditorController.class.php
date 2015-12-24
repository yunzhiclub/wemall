<?php

/* 
 * 梦云智工作室
 *   * 
 */

namespace Goods\Controller;
use Admin\Controller\AdminController;
class CkeditorController extends AdminController{
    public function _initialize() {
        $this->addCss('/theme/default/ckeditor/sample.css');
        $this->addJs('/theme/default/ckeditor/sample.js');
        $this->addJs('/theme/default/ckeditor/ckeditor.js');
        parent::_initialize();
    }
    public function indexAction(){
        $appid = "wx9c03184589bf587a";
        $appsecret = "c2d72aef86fc223103bf52ad4c48db74";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        $access_token = $jsoninfo["access_token"];
        var_dump($access_token);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
}
