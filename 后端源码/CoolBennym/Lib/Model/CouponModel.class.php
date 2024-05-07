<?php
class CouponModel extends CommonModel{
    protected $pk   = 'coupon_id';
    protected $tableName =  'coupon';
	
	
	//获取跑腿优惠券列表
	public function getRunningOrderCouponList($running_id,$uid){
		
		if(!$running = M('running')->where(array('running_id'=>$running_id,'closed'=>0,'status'=>0))->find()){
			return false;
		}
		
		$lists = M('CouponDownload')->where(array('user_id'=>$uid,'is_used'=>0))->order(array('create_time'=>'desc'))->select();
		if(!$lists){
			return false;
		}
		
		foreach($lists as $k => $val){
			$coupon = M('coupon')->where(array('coupon_id'=>$val['coupon_id'],'closed'=>0,'expire_date' => array('EGT', TODAY)))->find();
			if($running['school_id'] != $val['school_id']){
				unset($lists[$k]);
			}
			if($running['need_pay'] <=  $coupon['full_price']){
				unset($lists[$k]);
			}
	    }
	    current($lists);
	    return $lists;
	}
	
	
	//获取跑腿满减的价格
	public function getRunningOrderCouponPrice($running_id,$download_id){
		
		if(!$running = M('running')->where(array('running_id'=>$running_id,'closed'=>0))->find()){
			return false;
		}
		
		$download = M('CouponDownload')->where(array('download_id'=>$download_id))->find();
		$coupon = M('coupon')->where(array('coupon_id'=>$download['coupon_id'],'closed'=>0,'expire_date' => array('EGT', TODAY)))->find();
		if(!$coupon){
			return false;
		}
				
		$price = $running['need_pay'] - $coupon['full_price'];
		//满足2条件才返回
		if($running['need_pay'] > $coupon['full_price'] && $price >0){
			return $coupon['reduce_price'];	
		}else{
			return false;
		}		
		return false;		
	}
	
	
	
	//跑腿付款成功后修改优惠券状态
	public function changeRunningDownloadIdUsed($running_id){
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		if($running['download_coupon_id']){
			M('CouponDownload')->save(array('download_id'=>$running['download_coupon_id'],'is_used' => 1,'used_time' => time(),'used_ip' =>get_client_ip()));
		}
		return true;
	}
	
	
	
	
						
}
