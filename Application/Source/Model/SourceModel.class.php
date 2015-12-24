<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Source\Model;
use Think\Model;
class SourceModel extends Model {
    private $upload = null;
    public function setUpload($upload)
    {
        $this->upload = $upload;
    }
    public function init(){
        $res = $this ->select();
        return $res;
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
                    $_POST['attchment_id'] = $id[0];
                }
            }
        }
        $this->create();
        $res = $this->save(); 
        return $res;
        
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
                    $_POST['attachment_id'] = $id[0];
                }
            }
        }
        $this->create();
        $res = $this->add(); 
        return $res;
        
    }
    public function info(){
        $model = M('attachment');
        $res = $model ->order('id desc') ->find();
        $id =$res['id'];
        return  intval($id,10)+1;
        
    }
    public function infoNum(){
        $res = $this ->order('id desc') ->find();
        $id =$res['id'];
        return  intval($id,10)+1;
        
    }
    
    /*
     * 输入$arr
     * 输出拼接后的
     */
    public function getInfoById($data , $key , $keyRes)
    {
        foreach($data as $k => $v)
        {
            $map['id'] = $v[$key];
            $res = $this->where($map)->find();
            $data[$k][$keyRes] = $res;
        }
        return $data;
    }
}