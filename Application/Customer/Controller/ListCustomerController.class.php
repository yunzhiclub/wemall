<?php
/*
 * 获取用户列表,返回openId的数组。
 * 根据OPENID，取信息进行更新或是添加。
 */
namespace Customer\Controller;
use Admin\Controller\AdminController;
class ListCustomerController extends AdminController{
    public function indexAction(){
        //取出openId数据组
        $accessToken = get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $accessToken;
        $jsonData = http_get_data($url);
        $data = json_decode($jsonData);
        $openids = $data->data->openid;
        //取出openid对应的其它信息
        $userInfo = array();
        foreach ($openids as $key => $value)
        {
            $userInfo[] = get_weichat_user_info($value, $accessToken);
        }
        $customer = D('Customer');
        $customer->addUserInfo($userInfo);
        $this->success('列表获取完成',U('Index/index'),2);
    }
}