<?php
/*
 * 
 */
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller
{
    private $user;
    public function __construct() {
        parent::__construct();
        $this->user = M('User');
    }

    public function loginAction(){
        $url = U('signIn');
        $this->assign(url,$url);
        $this->display('login');
    }
    public function signInAction(){
        $name = I('post.name',0);
        $password = I('post.password',0);
        $miPassword = ao_mi($name,$password);
        $dbPassword = $this->user->field('password')->where('userid='."'$name'")->find();
        if($dbPassword[password]==null){
            $error = "该用户不存在";
            $this->assign(error,$error);
            $this->loginAction();
        }else{
            if($dbPassword[password] == $miPassword){
                session('userId',$name,1800);
                $url = U('Admin/Index/index');
                redirect_url($url);
                die();
            }
            else{
                $error = "密码错误";
                $this->assign(error,$error);
                $this->loginAction();
            }
        }
    }
    //退出登陆
    public function logoutAction(){
        session('userId',null); // 删除name
        $url = U('signIn');
        $this->assign(url,$url);
        $this->display('login');
    }
}