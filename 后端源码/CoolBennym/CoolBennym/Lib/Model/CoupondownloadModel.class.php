<?php
class CoupondownloadModel extends CommonModel{
    protected $pk   = 'download_id';
    protected $tableName =  'coupon_download';
    
	public function getError(){
        return $this->error;
    }
	
	
	public function updateCouponOrder($log_id,$id){       
        $logs = M('payment_logs')->where(array('log_id'=>$log_id))->find();//支付日志记录
		$res = M('coupon_download')->where(array('id'=>$id))->save(array('status'=>1,'closed'=>0,'money'=>$logs['need_pay']));//更新状态
        return true;
    }
	
	
	
    public function getCode(){       
        $i=0;
        while(true){
            $i++;
            $code = rand_string(8,1);
            $data = $this->find(array('where'=>array('code'=>$code)));
            if(empty($data)) return $code;
            if($i > 10) return $code;//CODE 做了唯一索引，如果大于10 我们也跳出循环以免更多资源消耗
        }
        
    }
	
	
	//发放红包
	public function deliver($coupon_id,$school_id,$user_id,$num){
		if(!$num){
           $num = 1;
        }
		$school = M('running_school')->find($school_id);
		if(empty($school)){
			$this->error = '学校不存在';
			return false;
		}
		$users = M('users')->find($user_id);
		if(empty($users)){
			$this->error = '会员不存在';
			return false;
		}
		$coupon = M('coupon')->where(array('coupon_id'=>$coupon_id))->find();
		if(empty($coupon)){
			$this->error = '红包不存在';
			return false;
		}
		
		if($coupon['expire_date'] < TODAY){
			$this->error = '红包已经过期';
			return false;
        }
        if($coupon['num'] <= 0){
			$this->error = '红包的数量已经被发放完毕';
			return false;
        }
		
        if($coupon['limit_num']){
            $download = M('coupon_download')->where(array('coupon_id' =>$coupon_id,'user_id'=>$user_id))->count();
            if($download+1 > $coupon['limit_num']){
				$this->error = '您已经超过下载该优惠券的限制';
				return false;
            }
        }
		

        $code = D('Coupondownload')->getCode();
        $data = array(
            'user_id' => $user_id,
            'school_id' => $school_id,
            'coupon_id' => $coupon_id,
            'create_time' => time(),
			'money' => $coupon['reduce_price'],
            'mobile' => $users['mobile'],
            'create_ip' => get_client_ip(),
            'code' => $code,
        );
		
        if($download_id = M('coupon_download')->add($data)){
            D('Coupon')->updateCount($coupon_id,'downloads');
            D('Coupon')->updateCount($coupon_id,'num',-1);
			return true;
        }
		return true;
	}
	
	
	
	//删除过期红包
	public function deleteCouponDownload($shop_id,$user_id){
		$list = M('CouponDownload')->where(array('is_used'=>0))->select();
		foreach($list as $k => $val){
			$coupon= M('Coupon')->where(array('coupon_id'=>$val['coupon_id']))->find();
			if($coupon['expire_date'] <= TODAY){
				M('CouponDownload')->where(array('download_id'=>$val['download_id']))->save(array('is_used'=>1));
			}
		}
		return true;
	}
	
	
	 //检测状态通用
	 public function check_coupondownload_state($download_id,$uid){ 
	    if(!($detail = D('Coupondownload')->find($download_id))){
			$this->error = '没有该优惠券';
			return false;
        }
        if($detail['user_id'] != $uid){
           $this->error = '请不要非法操作';
			return false;
        }
		if($detail['is_used'] != 0){
            $this->error = '该优惠券属于不可消费的状态';
			return false;
        }
		$coupon = D('Coupon')->find($detail['coupon_id']);
		if($coupon['expire_date'] < TODAY) {
			$this->error = '该优惠券已经过期';
			return false;
        }
       return true; 
	 
	 }
	 
	 
	
    
    public function CallDataForMat($items){
        if(empty($items)) return array();
        $obj = D('Coupon');        
        $coupon_ids = array();
        foreach($items as $k=>$val){
            $coupon_ids[$val['coupon_id']] = $val['coupon_id'];
        }       
        $coupons = $obj->itemsByIds($coupon_ids);
        foreach($items as $k=>$val){
            $val['coupon'] = $coupons[$val['coupon_id']];
            $items[$k] = $val;
        }
        return $items;
    }
}