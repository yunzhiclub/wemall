<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace ShoppingCart\Model;
use Think\Model;
class ShoppingCartModel extends Model{
    private $openid = null; //用户openid
    function setOpenid($openid) {
        $this->openid = $openid;
    }
    public function init($res){
         foreach ($res as $key => $value) {     
            $id['id']=$value ['product_number'];
            $country = D('goods');
           $tem[] = $country  ->where($id)->find(); 
        }
        
        for($i=0;$i<count($tem);$i++){
            $temp[$i] = array_merge($res[$i],$tem[$i]);
        }
        //取附件信息
        $attachmentInfo = D('Attachment/Attachment');
        //设置关键字：即哪个字段代表的附件ID
        $attachmentInfo->setKey('focus_pictures');
        //取出附件信息,信息进行替换后，返回
        $data = $attachmentInfo->selectInfo($temp); 
        //var_dump($data);
        
        $array = array();
        foreach ($data as $key =>$value){
            if (!in_array($value ['source'], $countryName,TRUE)){
                $countryName[]=$value['source'];    
            }
            $num = array_search($value[source], $countryName);
            $array[$num][] = $value ;
       }
       //遍历来源
       
       foreach($array as $key => $value)
       {
           //遍历物流方式
           $logistic = array();
           $keylogistic = $key;
           foreach($value as $key=>$value1)
           {
               if(!in_array($value1['logistics_mode'], $logistic,TRUE)){
                   $logistic[] = $value1 ['logistics_mode'];
               }
               $num1 = array_search($value1['logistics_mode'], $logistic);
               $array1[$keylogistic][$num1][] = $value1 ;
               
           }       
       }
       return $array1;
        
    }
    /*
     * 将用户在购物车的所有商品选出
     * @必要条件 openid
     * @return 当前openid在数据库中的信息
     */
    public function selectInfo()
    {
        $openid = $this->openid;
        if($openid == null )
        {
            return false;
        }
        $map = array();
        $map['openid'] = $openid;
        return $this->where($map)->select();
    }
    public function edit(){
       /* $this ->create();
        $result =$this ->add();
        if($result){
            return 'success';
        }  else {
            return 'fail';
        }
        * 
        */
        echo 'model';
    }
    public function delete() {
        $id = I('get.id');
        $this->where($id)->detele();
        return '1';
    }
    public function update(){
        $this->create();
        $this ->save();
        return '1';
    }

}

