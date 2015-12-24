<?php
namespace Orderform\Model;
use Think\Model;
use Think\Upload;
class AttachmentModel extends Model
{
    private $openid;
    public function setOpenid($openid) {
        $this->openid = $openid;
    }

    /*
     * 获取微信服务器上传的图片
     * 1.取得当前access_token()
     * 2.下载图片
     * 3.将图片信息存入附件表
     * 4.上传图片
     */
    public function getAndUploadWxImage($mediaId)
    {
        $access_token = get_access_token(); 
        //下载图片
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaId";
        $fileInfo = $this->downloadWeixinFile($url);
        return $this->saveWeixinFile($fileInfo);     
    }
    
    function downloadWeixinFile($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);    
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package)); 
        return $imageAll;
    }
    /*
     * 存文件
     * 1.存数据库，返回ID
     * 2.将ID做为文件名
     * 3.按openid分文件夹上传附件
     */
    function saveWeixinFile($fileInfo)
    {
        $filecontent = $fileInfo["body"];
        $header = $fileInfo["header"];
        $documentRoot = I('server.DOCUMENT_ROOT');
        $savePath = "./Customer/" . $this->openid . "/";
        $saveName = uniqid();
        
        //更新数据库，取出做为filename
        $data['openid'] = $this->openid;
        $data['upload_time'] = time();
        $data['type'] = $header['content_type'];
        
        //未正确接收到图片信息，返回FALSE
        if( $data['type'] == "text/plain")
        {
            return false;
        }
        $data['ext'] = substr($header['content_type'],strrpos($header['content_type'],'/')+1);
        $data['savepath'] = $savePath;
        $data['savename'] = $saveName . '.' . $data['ext'];
        $data['md5'] = md5($filecontent);
        $data['sha1'] = sha1($filecontent);
        $this->data($data)->add();
        $id = $this->getLastInsID();  
        
        //上传文件
        $savePath = $documentRoot . add_root_path("/Uploads" . substr($savePath, 1));
        if (!is_dir($savePath)) 
        {
            mkdir($savePath); // 如果不存在则创建
        }
        $fileName = $savePath . $data['savename']; 
        $local_file = fopen($fileName, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
        $attId = $id;
        return $attId;
    }
}