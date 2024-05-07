<?php

class FlowModel extends CommonModel{
    protected $pk   = 'flow_id';
    protected $tableName =  'flow';
	
    public function getStatus(){
        return array(
            0  => '未付款',
            1  => '已付款',
            2  => '已派单',
			3  => '已接单',
			8  => '已完成',
        );
    }
	
	public function getTypes(){
        return array(
			0  => '抢单模式',
            1  => '报价模式',
            2  => '未知模式',
        );
    }
	
    public function getLengths(){
        return array(
            1  => '4.2米',
            2  => '4.5米',
			3  => '5米',
			4  => '5.2米',
			5  => '6.2米',
            6  => '6.8米',
			7  => '7.2米',
			8  => '7.6米',
			9  => '7.8米',
            10  => '8.2米',
			11  => '8.6米',
			12  => '9.2米',
			13  => '11.7米',
            14  => '12.5米',
			15  => '13米',
			16  => '13.5米',
			17  => '14米',
            18  => '16米',
			19  => '17米',
			20  => '18米',
        );
    }
	
	
	//在线支付后订单回调
	public function updateFlow($flow_id,$pay_log_id){
		$config = D('Setting')->fetchAll();
		
		$status = $config['flow']['is_robbing'] ?  1 : '2';//如果管理员派单状态为1，否则物流车抢单状态为2
		
        D('Flow')->where(array('flow_id'=>$flow_id))->save(array('status'=>$status,'pay_log_id'=>$pay_log_id,'pay_time'=>NOW_TIME,'pay_ip'=>get_client_ip()));//更新状态
		//通知管理员
		
		if($config['flow']['is_robbing'] == 0 && $config['flow']['type'] == 0){
			
			D('Sms')->sms_flow_send_worker($flow_id);//批量通知物流车配送员
			D('Weixintmpl')->weixin_tpl_flow_send_worker($flow_id);//批量微信物流车配送员
		}
		return true;	
    }
	
	//在线支付后订单回调
	public function updateFlows($flow_id,$pay_log_id){
		
		M('flow_buy')->where(array('buy_id'=>$flow_id))->save(array('status'=>1));//更新状态
	
		return true;	
    }
	
	
	
	//自动收货
	public function flowComplete($shop_id,$user_id){
		$config = D('Setting')->fetchAll();
		$flow_complete_time = (($config['flow']['flow_complete_time'] > 1) ? $config['flow']['flow_complete_time'] :'7')*24*3600;
		$flow_time = $time - $flow_complete_time; 
		$arr = M('Flow')->where(array('status'=>'8','create_time'=>array(array('ELT',$flow_time))))->select();
		foreach($arr as $key => $val){
			$settlement = M('FlowMoneyLogs')->where(array('flow_id'=>$val['flow_id']))->find();
			if(!$settlement){
				D('Flow')->confirm($flow_id);//执行自动收货操作
			}
        }
	}
	
	
	//自动确认收货
	public function confirm($flow_id){
		$config = D('Setting')->fetchAll();
        $flow = D('Flow')->where(array('flow_id'=>$flow_id))->find();
		if(!$flow){
			$this->error = '订单不存在';
			return false;
		}
		if($flow['status'] != 8){
			$this->error = '订单状态不正确';
			return false;
		}
		//去结算
		if(false != D('Flow')->okFlow($flow['worker_id'],$flow_id)){
			return true;
		}else{
			$this->error = D('Flow')->getError();
			return false;
		}
		return true;	
    }
	
	
	
	
	 //抢单数据库操作
	 public function uploadFlow($worker_id,$flow_id){
		$config = D('Setting')->fetchAll();
		
		$interval_time = (int)$config['flow']['interval_time'] ? (int)$config['flow']['interval_time'] :'300';
		$num = (int)$config['flow']['num'] ? (int)$config['flow']['num'] :'5';
		
		
		$res = M('Flow')->where(array('worker_id' =>$worker_id,'status'=>'3','closed'=>'0'))->order('update_time desc')->find();
		
		$cha = time() - $res['update_time'];
		if($cha < $interval_time){
			$second = $interval_time  -	$cha;
		}
		if($res && $cha < $interval_time){
			$this->error = '操作频繁请【'.$second .'】秒后再试';
			return false;
		}
		
		$count = M('Flow')->where(array('worker_id' =>$worker_id,'status'=>'2','closed'=>'0'))->count();
		
		if($count && $count >= $num){
			$this->error = '已配置中订单的数量已经超过限制请先完成配送后再抢单';
			return false;
		}
			
		$Worker = M('FlowWorker')->where(array('worker_id'=>$worker_id))->find();
		$Flow = D('Flow')->where(array('flow_id'=>$flow_id))->find();//详情
			
	
			
		if($Worker['closed'] == 1){
			$this->error = '您当前状态不能抢单';
			return false;
		}
			
		if(empty($Flow)){
			$this->error = '配送订单不存在';
			return false;
		}elseif($Flow['closed'] ==1){
			$this->error = '当前订单已经关闭';
			return false;
		}else{
			D('Sms')->sms_flow_send($flow_id);
			D('Weixintmpl')->weixin_tmpl_flow_send($flow_id);
			return true;//暂时不写逻辑
		}
		return true;
	  } 
	  
	  
	  
	  
	 //确认完成数据库操作
	 public function okFlow($worker_id,$flow_id){
		 
		 	$config = D('Setting')->fetchAll();//调用全局设置
			$Flow = D('Flow')->where(array('flow_id'=>$flow_id))->find();
			if(empty($Flow) || $Flow['closed'] ==1 ){
				$this->error = '订单不存在或者订单已经关闭';
				return false;	
			}else{
				
		        $ratio = (int)$config['flow']['ratio'] ? (int)$config['flow']['ratio'] :'60';
				$commission = (int)(($Flow['need_pay'] * $ratio)/100);//佣金
				
				if(!$commission){
					$this->error = '佣金设置不正确';
					return false;
				}
				$money = $Flow['need_pay'] - $commission;
				$intro = '物流车订单号【'.$flow_id.'】结算，当前结算佣金比例【'.$ratio.'%】，佣金【'.round($commission/100,2).'】元，订单总价【'.round($Flow['need_pay']/100,2).'】元';
			
			
			    $settlement = M('FlowMoneyLogs')->where(array('flow_id'=>$Flow['flow_id'],'worker_id'=>$Flow['worker_id'],'user_id' => $Flow['user_id']))->find();
				if($settlement){
					$this->error = '当前订单已经结算请不要重复操作';
					return false;
				}
				
			
				$arr['city_id'] = $Flow['city_id'];
				$arr['flow_id'] = $Flow['flow_id'];
				$arr['worker_id'] = $Flow['worker_id'];
				$arr['user_id'] = $Flow['user_id'];
				$arr['money'] = $money;
				$arr['intro'] = $intro;
				$arr['create_time'] = NOW_TIME;
				$arr['create_ip'] = get_client_ip();
			
				if(M('FlowMoneyLogs')->add($arr)){
					D('Sms')->sms_flow_send($flow_id);
					D('Weixintmpl')->weixin_tmpl_flow_send($flow_id);
					D('Users')->addMoneys($Flow['worker_id'],$money,$intro);  //写入物流车余额
				}else{
					$this->error = '写入数据库结算失败';
					return false;	
				}
				return true;//暂时不写逻辑
			}
			return true;
	  }  
	  
	  
	
}