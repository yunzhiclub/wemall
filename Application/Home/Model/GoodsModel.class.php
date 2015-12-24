<?php

/*
 * 梦云智工作室
 *   * 
 */

namespace Home\Model;
use Think\Model;
use Home\Model\AttachmentModel;

/**
 * Description of Goods
 *
 * @author XINGYANNIAN
 */
class GoodsModel extends Model {
    private $goodsId;
    private $page;
    private $pageSize;
    private $goodsName;
    public function __construct() {
        parent::__construct();
        $this->pageSize = 5;//默认单页显示条数
        $this->page = 1;//默认首页
    }
    public function getPageSize() {
        return $this->pageSize;
    }

    public function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }
    public function getPage() {
        return $this->page;
    }

    public function setPage($page) {
        $this->page = $page;
    }

        public function getGoodsId() {
        return $this->goodsId;
    }

    public function setGoodsId($goodsId) {
        $this->goodsId = $goodsId;
    }
    public function getGoodsInfo(){
        $res = $this->where('id = '.$this->goodsId)->select();
        return $res;
    }
    public function getGoodsList(){
        $res = $this->where('state=1')->order('reorder desc')->page($this->page, $this->pageSize)->select();
        return $res;
    }//获取商品列表
    public function getDetailGoodsList() {
        $res = $this->getGoodsList();
        $attachmentModel = new AttachmentModel();
        foreach ($res as $key => $value) {
            $focusPictures = $value['focus_pictures'];
            $postion = strpos($focusPictures, ',');//第一个逗号出现的位置,如果不存在返回false
            if ($postion === false) {
                $attchmentId = $focusPictures;
            }  
            else {
                $length = $postion + 1;//要截取的长度
                $attchmentId = substr($focusPictures, 0, $length);
            }            
            $attachmentModel->setId($attchmentId);
            $res[$key]['image_path'] = $attachmentModel->getAttchmentPath();            
        }
        return $res;
    }//获取带缩略图的列表
    public function getCount(){
        $num = $this->where('state=1')->count();
        return $num;
    }// 获取记录总数
    public function getName() {
        return $this->goodsName;
    }

    public function setName($goodsName) {
        $this->goodsName = $goodsName;
    }

    public function goodsList() {
        $name = '%'.$this->goodsName.'%';
        $map['name'] = array('like',$name);
        $map['state'] = 1;
        $res = $this->where($map)->order('reorder desc')->page($this->page, $this->pageSize)->select();
        $attachmentModel = new AttachmentModel();
        foreach ($res as $key => $value) {
            $focusPictures = $value['focus_pictures'];
            $postion = strpos($focusPictures, ',');//第一个逗号出现的位置,如果不存在返回false
            if ($postion === false) {
                $attchmentId = $focusPictures;
            }  
            else {
                $length = $postion + 1;//要截取的长度
                $attchmentId = substr($focusPictures, 0, $length);
            }            
            $attachmentModel->setId($attchmentId);
            $res[$key]['image_path'] = $attachmentModel->getAttchmentPath();            
        }
        return $res;
    }//通过名字模糊查询
    public function likeNameCount() {
        $name = '%'.$this->goodsName.'%';
        $map['name'] = array('like',$name);
        $map['state'] = 1;
        $num = $this->where($map)->count();
        return $num;
    }//统计模糊条件的总数
    

}
