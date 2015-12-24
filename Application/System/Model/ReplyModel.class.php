<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace System\Model;
use Think\Model;

class ReplyModel extends Model{
    private $upload = null;
    public function setUpload($upload)
    {
        $this->upload = $upload;
    }
//    public function info(){
//        $res = $this->count()+1;
//        return $res;
//    }
    public function all(){
        return $this->find();
    }

    public function appendS()
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
                    $_POST['picUrl'] = $id[0];
                }
            }
        }
        $this->create();
        $res = $this->save(); 
        return $res;
        
    }
}
