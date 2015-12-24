<?php
/*
 * 附件管理，根据THINKPHP传入的$INFO值，将$info的值加入到附件表
 * auto:panjie
 * 
 */
namespace Attachment\Model;
use Think\Model;
class AttachmentModel extends Model{
    private $info = null;//数据存储信息,二维数组
    private $key = null;//代表图片关键字的健值
    private $defaultImageId = null;//默认的图片ID
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        parent::__construct($name, $tablePrefix, $connection);
        $this->key = 'attachment_id';
        $this->defaultImageId = 9;
    }
    public function setDefaultImageId($defaultImageId)
    {
        $this->defaultImageId = $defaultImageId;
    }
    public function setInfo($info)
    {
        $this->info = $info;
    }
    public function setKey($key)
    {
        $this->key = $key;
    }
    
    public function getInfoById($attachmentId)
    {
        $map['id'] = $attachmentId;
        $res = $this->where($map)->find();
        if(!$res)
        {
            $map['id'] = $this->defaultImageId;
            $res = $this->where($map)->find();
        }
        //url字符串拼接
        if(substr($res['savepath'],0,1) == '.')
        {
            $savePath = substr($res['savepath'],1,strlen($res['savepath'])-1);
        }
        $res['url'] = add_root_path( C('UPLOAD_ROOT_PATH') . $savePath . $res['savename'] );
        return $res;            
    }
    
    public function saveInfo($info = null)
    {
        //判断是否传入了info值
        if($info == null)
        {
            $info = $this->info;
        }
        if($info == null)
        {
            return FALSE;
        }
        else
        {
            $res = array();
            foreach($this->info as $value)
            {
                $this->data($value)->add();
                $lastItem = $this->order('id desc')->find();
                $res[] = $lastItem['id'];
            }          
        }
        return $res;
    }
    //取单条信息
    //$info一组数组
    //return 拼接后的二维数据
    public function findInfo($info = null)
    {
        if($info == null)
        {
            $info = $this->info;
        }
        if($info == null)
        {
            return FALSE;
        }
        //进行数组拼接后传出
        else
        {
            if($this->_fetchInfo($info))
            {
                $info[$this->key] = $this->_fetchInfo($info);
            }
            return $info;
        }
    }
    /*
     * 取多条数据,将附件的信息进行替换
     * @info 进行添加数据的数组
     * @return拼接好的多维数据
     */
    public function selectInfo($info = null)
    {
        if($info == null)
        {
            $info = $this->info;
        }
        if($info == null)
        {
            return FALSE;
        }
        //进行数组拼接后传出
        else
        {
            foreach($info as $key => $value)
            {
                $info[$key][$this->key] = $this->_fetchInfo($value);
            }
            return $info;
        }
    }
    //添加附件信息
    private function _fetchInfo($value)
    {
        //不存在设定的关键字,即直接返回原值
        if(!isset($value[$this->key]))
        {
            return FALSE;
        }
        else
        {
            $map['id'] = $value[$this->key];
            $res = $this->where($map)->find();
            if(!$res)
            {
                $map['id'] = $this->defaultImageId;
                $res = $this->where($map)->find();
            }
            //url字符串拼接
            if(substr($res['savepath'],0,1) == '.')
            {
                $savePath = substr($res['savepath'],1,strlen($res['savepath'])-1);
            }
            $res['url'] = add_root_path( C('UPLOAD_ROOT_PATH') . $savePath . $res['savename'] );
            return $res;
        }
    }
}