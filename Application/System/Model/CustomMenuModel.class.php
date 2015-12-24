<?php
namespace System\Model;
use Think\Model;
class CustomMenuModel extends model{
    private $id = null;
    private $upload = null;
    public function setUpload($upload)
    {
        $this->upload = $upload;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function index()
    {
        $data = $this->select();
        $attachmentInfo = D('Attachment/Attachment');
        //设置关键字：即哪个字段代表的附件ID
        $attachmentInfo->setKey('reply_image');
        $attachmentInfo->setDefaultImageId(6);
        //取出附件信息,信息进行替换后，返回
        $data = $attachmentInfo->selectInfo($data);
        return $data;
    }
    public function fetchInfo()
    {
        $map['id'] = $this->id;
        $data = $this->where($map)->find();
        //实例化
        $attachmentInfo = D('Attachment/Attachment');
        //设置关键字：即哪个字段代表的附件ID
        $attachmentInfo->setKey('reply_image');
        //取出附件信息,信息进行替换后，返回
        $data = $attachmentInfo->findInfo($data);
        return $data;
    }
    //更新操作
    public function update()
    {
        //存在附件则进行附件上传，并取附件关键字
        if(count($_FILES))
        {
            $upload = $this->upload;
            $upload->exts = array('jpg','gif','png','jpeg');
            $info = $upload->upload();
            if($info)
            {
                $saveInfo = D('Attachment/Attachment');
                $saveInfo->setInfo($info);
                $id = $saveInfo->saveInfo();
                if(is_array($id))
                {
                    $_POST['reply_image'] = $id[0];
                }
            }
        }
        $this->create();
        $this->save();   
    }
    /*
     * 获取微信菜单的数据集
     * @return 目录树
     */
    public function fetchMenuList()
    {
        $menuList = $this->order('sort')->select();
        $menuList = list_to_tree($menuList);
        return $menuList;
    }
   
}