<?php
class EduOrderModel extends CommonModel{  
    protected $pk   = 'order_id';
    protected $tableName =  'edu_order';
	
	protected $types = array(
		-1 => '已取消', 
		0 => '未付款', 
		1 => '已付款', 
		3 => '申请退款中', 
		4 => '客户已收货', 
		8 => '已完成配送'
	);
	//订单状态
    public function getType(){
        return $this->types;
    }
	//提示
	public function getError(){
        return $this->error;
    }
	
	
    //取消订单返还
    public function cancel($order_id){
        if(!$order_id = (int)$order_id){
            return false;
        }elseif(!$detail = $this->find($order_id)){
            return false;
        }else{
            if($detail['order_status'] == 1){
                $detail['is_fan'] = 1;
            }
            if(false !== $this->save(array('order_id'=>$order_id,'order_status'=>-1))){
                if($detail['is_fan'] == 1){
					
                    D('Users')->addMoney($detail['user_id'],(int)$detail['need_pay'],'教育订单ID:'.$order_id.'，取消返还余额');
                }
                return true;
            }else{
                return false;
            }
            
        }  
    }
    
     //教育频道资金结算2018/8/21
    public function complete($order_id){
        if(!$order_id = (int)$order_id){
            return false;
        }elseif(!$detail = $this->find($order_id)){
            return false;
        }elseif($detail['order_status'] != 1){
            return false;
        }else{
			$Educourse = D('Educourse')->find($detail['course_id']);
            $Edu = D('Edu')->where(array('edu_id'=>$Educourse['edu_id']))->find();
            if(false !== $this->save(array('order_id'=>$order_id,'order_status'=>8,'is_used_code'=>1,'is_used_time'=>NOW_TIME))){
					$rate = $Edu['rate'] ? $Edu['rate'] : '10';
					$yongjin = ($detail['need_pay'] * $rate)/100;
			
					$settlement_price = $detail['need_pay'] - $yongjin;
					
					if($settlement_price > 0) {
						$info = '教育订单号【'.$order_id.'】结算，结算佣金比例【'.$rate.'%】，订单总价【'.round($detail['need_pay']/100,2).'】元 - 佣金【'.round($yongjin/100,2).'】元';
						D('Shopmoney')->insertData($order_id,$detail['shop_id'],$settlement_price,$type ='edu',$info);//结算给商家 
                    }
                return true;
            }else{
                return false;
            }
            
        }  
    }
	
	
	
	
	//获取验证码
	public function getCode(){
        $i = 0;
        while(true){
            $i++;
            $code = rand_string(8, 1);
            $data = $this->find(array('where' => array('code' => $code)));
            if(empty($data)){
                return $code;
            }
            if($i > 20){
                return $code;
            }
        }
    }
     
	 //获取付款价格
	public function get_edu_need_pay($type,$course_id){
		$detail = D('Educourse')->where(array('course_id'=>$course_id))->find();
		if(!empty($detail)){
            if($type == 1){
				return $detail['test_price'];
			}else{
				return $detail['course_price'];
			}
        }
       return false;
    }
	
	
	
	//在线支付回调
	public function save_edu_logs_status($order_id){
        $detail = $this->where(array('order_id'=>$order_id))->find();
        if(!empty($detail)){
			if ($this->save(array('order_id' => $order_id, 'order_status' => '1'))){
				D('Edu')->where(array('edu_id'=>$detail['edu_id']))->setDec('sale',1);
				D('Sms')->sms_edu_notice_user($order_id);//通知用户
				D('Sms')->sms_edu_notice_shop($order_id);//通知商家
				return TRUE; 
			}
        }else{
			return TRUE; 
           //由于支付回调，直接忽略报错 return false;
        }
    }
}