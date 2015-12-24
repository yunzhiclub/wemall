<?php

/* 
 * 梦云智工作室
 *   * 
 */
namespace Coupon\Model;
use Think\Model;
class CouponModel extends Model{
	private $state; //判断优惠券是否使用
        private $map = null ; //
        private $orderBy = null; //
        
        public function setState($state) {
            $this->state = $state;
        }

        public function setMap($map) {
            $this->map = $map;
        }

        public function setOrderBy($orderBy) {
            $this->orderBy = $orderBy;
        }

        	public function initAction(){
		$model = M('Coupon');
		//$data['coupon_state']='0';
		//$res = $model -> where($data) ->find();
		$sql = "SELECT * FROM `wemall_coupon` WHERE STATE = 0 ORDER BY ID DESC";
		$res= $model->query($sql);
		var_dump($res);
	}
	public function insert(){
		$model =M('Coupon');
		$data['coupon_state']='0';
		$res = $model ->data($data) ->add();
		echo $res;	
	}
        
        public function getCounts()
        {
            if($this->map == null)
            {
                $this->map = array();
            }
            return $this->where($this->map)->count();
        }
        
        public function freezeCouponById($id)
        {
            $map['id'] = $id;
            $map['is_freezed'] = 1;
            $this->save($map);
            return;
        }
        
        public function getCouponArrByMap($currentPage , $pageSize)
        {
            $p = $currentPage;
            if($this->map == null)
            {
                $this->map = array();
            }
            if($this->orderBy == null)
            {
                $this->orderBy = '';
            }
            return $this->where($this->map)->order($this->orderBy)->page($p, $pageSize)->select();
        }
        public function getCoupon($id) {
            $map['id'] = $id;
            $res = $this->where($map)->select();
            return $res;
        }
        /*
         * 查找某人的优惠券
         */
        public function search(){
            $openid = get_openid();
            $map['openid'] = $openid;
            $res = $this->where($map)->select();
            return $res;

        }
        
        /*
         *通过参数送券
         * @param openid 标识
         * @parma cover 面额（分）
         * @param $duration 有效期
         * @count 数量
         * return true 
         */
        public function sendCoupon($openid,$cover,$duration,$count)
        {
            $data['cover'] = $cover;
            $data['duration'] = $duration;
            $data['start_time'] = time();
            $data['end_time'] = time()+24*60*60*$duration;
            $data['openid'] = $openid;
            for($i=0;$i<$count;$i++)
            {
                $this->add($data);
            }
            return true;
        }
        /*
         * 通过OPENID获取未使用的优惠券信息
         * 排除已过期优惠券
         */
        public function getUnusedCouponsByOpenid($openid)
        {
            $map['openid'] = $openid;
            $map['state'] = 1;
            $map['end_time'] = array('egt',time());
            return $this->where($map)->select();
        }
        
        /*
         * 通过OPENID获取已使用的优惠券信息
         */
        public function getUsedCouponsByOpenid($openid)
        {
            $map['openid'] = $openid;
            $map['state'] = 0;
            $map['end_time'] = array('egt',time());
            return $this->where($map)->select();
        }
}
