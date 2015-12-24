<?php
namespace WechatInterface\Controller;
class TestController{
    public function indexAction() {
        echo get_access_token();
    }
}