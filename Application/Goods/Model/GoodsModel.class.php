<?php
/* 
 * 梦云智工作室
 *   * 
 */
namespace Goods\Model;
use Think\Model;
use Home\Model\AttachmentModel;
class GoodsModel extends Model{
    private $goodsId;
    protected $_scope = array(
            'under'=>array(
                'where'=>array('state'=>0),
                'field'=>'id,name,purchasing_price,sales_volume,reorder',
            ), 'on'=>array(
                'where'=>array('state'=>1),
                'field'=>'id,name,purchasing_price,sales_volume,reorder',
            )
        );
    public function getGoodsId() {
        return $this->goodsId;
    }

    public function setGoodsId($goodsId) {
        $this->goodsId = $goodsId;
    }
    //商品添加
    public function insert($picture){
        $id = $this->_getGoodsNumber();
        $data = $this->create();
        $data['id'] = $id;
        $data['focus_pictures'] = $picture;
        $data['international_price'] = $this->_huansuan($data['international_price']);
        $data['purchasing_price'] = $this->_huansuan($data['purchasing_price']);
        $data['domestic_transportation_expenses'] = $this->_huansuan($data['domestic_transportation_expenses']);
        $data['discount_amount'] = $this->_huansuan($data['discount_amount']);
        $data['add_time'] = time();
        $this->add($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    //查看架上商品
    public function onShelevesView(){       
        $res = $this->scope('on')->order('add_time desc')->select();
        return $res;
    }
    //查看架下商品
    public function underShelevesView(){
        $res = $this->scope('under')->order('add_time desc')->select();
        return $res;
    }
    //获取商品信息
    public function getGoodsInf(){
        $res = $this->where('id = '.$this->goodsId)->find();
        $pictureId = $res['focus_pictures'];
        if($pictureId != null){
            $attchmentId = explode(',', $pictureId);
            $attchmentModel = new AttachmentModel();
            foreach($attchmentId as $value) {
                $attchmentModel->setId($value);
                $relpath[] = $attchmentModel->getAttchmentPath();
            }
        $res['picturePath'] = $relpath;
        }
        return $res;
    }
    //更改商品信息
    public function update($picture){
        $data = $this->create();
        if(I('post.id') == '')//如果未传ID值，则插入数据
            $hello = $this->add();
        else
            $data['focus_pictures'] = $picture;
            $data['international_price'] = $this->_huansuan($data['international_price']);
            $data['purchasing_price'] = $this->_huansuan($data['purchasing_price']);
            $data['domestic_transportation_expenses'] = $this->_huansuan($data['domestic_transportation_expenses']);
            $data['discount_amount'] = $this->_huansuan($data['discount_amount']);
            $this->save($data);
        $resData['status'] = 'success';
        $resData['msg'] = ' 操作成功';
        return $resData;
    }
    public function getHomeFreight($id) {
        $id = "1";
        $map['id'] = $id;
        $res = $this->where($map)->order('domestic_transportation_expenses DESC')->field('domestic_transportation_expenses')->find();
        return $res; 
    }
    //把分换算成元，乘以100以存库；
    private function _huansuan($price){
        $price1 = str_replace(',','',$price);//去除大的数字金额里的逗号
        return $price1 * 100;
    }
    //得到当前的商品的id
    private function _getGoodsNumber(){
        //获取数据库中最大的id;
        $maxId = $this->max('id');
        //获取当前日期；拼接成前边日期六位字符
        $deta = date("ymd",time());
        $str1 = substr($maxId,2,2);
        $str2 = substr($deta,2,2);
        if($str1 == $str2){
            $id = $maxId + 1;
        }else {
            if($str1 < str2){
                $id = $deta."001";
            }
        }
        return $id;
    }
}
