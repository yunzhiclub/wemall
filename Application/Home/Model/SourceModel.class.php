<?php

/*
 * 梦云智工作室
 *   * 
 */

/**
 * Description of SourceModel
 *
 * @author J
 */
namespace Home\Model;
use Think\Model;

class SourceModel extends Model {
    //put your code here
    public function getSource($map){
        $res = $this ->where($map) ->find();
        $attachmentInfo = D('Attachment/Attachment');
        //设置关键字：即哪个字段代表的附件ID
        $attachmentInfo->setKey('attachment_id');
        //取出附件信息,信息进行替换后，返回
        $att = $attachmentInfo->findInfo($res);
        return $att;
    }
}
