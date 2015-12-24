<?php
namespace Rbac\Controller;
use Think\Controller;
class AuthController extends Controller {
    public function _initialize()
    {
        //未输出任何html格式，设置编码为utf-8，防止乱码。
        header("Content-type:text/html;charset=utf-8");
        echo "Rbac模块下Controller\AuthContorller类中_initialize()方法调用成功<br />";
        echo "在该方法中进行权限的认证<br />";
    }
}
