<?php
/*
 * 微信公众平台菜单管理。
 * author:panjie
 * 数据表：custom_menu
 */
namespace System\Controller;
use Admin\Controller\AdminController;
class CustomerMenuController extends AdminController{
    //浏览，初始化
    public function indexAction(){
        $data = D('CustomMenu')->index();
        $sendMenuUrl = U('sendMenu');
        $deleteMenuUrl = U('deleteMenu');
        //增加修改的URL信息
        foreach($data as $key => $value)
        {
            $data[$key]['actionUrl']['edit'] = U('CustomerMenu/edit?id=' . $value['id']);
        }
        $this->assign('deleteMenuUrl',$deleteMenuUrl);
        $this->assign('sendMenuUrl',$sendMenuUrl);
        $this->assign('data', $data);
        $this->assign('YZRight',$this->fetch());
        $this->display(YZ_TEMPLATE);
    }
    public function editAction(){
        $id = I('get.id','');
        if($id == '')
        {
            //未获取到ID信息
        }
        else
        {
            $CustomMenu = D('CustomMenu');
            $CustomMenu->setId($id);
            $data = $CustomMenu->fetchInfo();            
        }
        $keyFlag = time().rand(100,999) ;//临时关键字，用于标识添加界面信息
        $this->assign('keyFlag',$keyFlag);
        $this->assign('getUrl',U('System/CustomerMenu/update?keyFlag=' . $keyFlag)); 
        $this->assign('actionUrl',U('System/CustomerMenu/update'));
        $this->assign('data',$data);
        $this->assign('YZRight',$this->fetch(T('System@CustomerMenu/edit')));
        $this->display(YZ_TEMPLATE);
    }
    public function updateAction()
    {
        $customMenu = D('CustomMenu');
        //涉及到附件上传，传入upload对象
        $customMenu->setUpload($this->upload);
        $customMenu->update();
        $this->success('操作成功','index');
    }
    //生成微信菜单
    public function sendMenuAction()
    {
        $CustomMenu = D('CustomMenu');
        $menuList = $CustomMenu->fetchMenuList();
        $wechatMenu = array(); //微信菜单的数组，用于转为 json
        //进行微信菜单的拼接
        foreach($menuList as $key => $value)
        {
            //如果存在子菜单，说明为目录
            if(array_key_exists('_child',$value))
            {
                $subButton = array();
                foreach($value['_child'] as $k => $v)
                {
                    //进行微信菜单格式的转换
                    $subButton[] = $this->_to_wechat_button($v);
                }
                $wechatMenu['button'][] = array("name" => $value['title'],'sub_button' => $subButton);
            }
            //无子菜单，则设置值
            else
            {
                $wechatMenu['button'][] = $this->_to_wechat_button($value);
            }
        }
        $json = urldecode(json_encode($this->_url_encode($wechatMenu)));
        $accessToken = get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $accessToken;
        $res = json_decode( http_post_json($url, $json) );
        if($res->errmsg == 'ok')
        {
            $this->success('菜单生成成功，新菜单将在12小时后生效，重新关注后可立即查看效果',U('CustomerMenu/index'),2);
        }
        else
        {
            $this->error('操作失败，错误代码:' . $res->errcode . $res->errmsg , U('CustomerMenu/index'),30);
        }
        
    }
    /*
     * 删除微信菜单
     */
    public function deleteMenuAction()
    {
        $accessToken = get_access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=' . $accessToken;
        $res = http_get_data($url);
        $this->success("删除成功",U('CustomerMenu/index'),2);
    }
    //格式化数组，使其符合微信菜单的生成格式
    //@return 数组
    private function _to_wechat_button($arr)
    {
        switch ($arr['type'])
        {
            case 'click':
                $menuKey = 'key';
                $menuValue = 'keyword';
                break;
            case 'view':
                $menuKey = 'url';
                $menuValue = 'url';
                break;
            default:
                break;
        }
        return array('type'=>$arr['type'],'name'=>$arr['title'],$menuKey=>$arr[$menuValue]);
    }
    /*
     * url进行code 转换
     */
    private function _url_encode($str) {  
        if(is_array($str)) {  
            foreach($str as $key=>$value) {  
                $str[urlencode($key)] = $this->_url_encode($value);  
            }  
        } else {  
            $str = urlencode($str);  
        }  

        return $str;  
    } 
}
