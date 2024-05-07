<?php

class PaymentModel extends CommonModel{
   protected $pk = 'payment_id';
    protected $tableName = 'payment';
    protected $token = 'payment';
    protected $types = array(
        'thread'=>'贴吧购买',
        'money' => '余额充值',
		'running'=>'跑腿',
		'group'=>'拼团支付',
		'pinche'=>'拼车支付',
		'coupon'=>'优惠券支付',
		'deposit'=>'保证金',
    );

    protected $type = null;
    protected $log_id = null;
    public function getType(){
        return $this->type;
    }

    public function getLogId(){
        return $this->log_id;
    }

    public function getTypes(){
        return $this->types;
    }

    public function getPayments($mobile = false){
        $datas = $this->fetchAll();
        $return = array();
        foreach($datas as $val){
            if($val['is_open']){
                if ($mobile == false){
                    if (!$val['is_mobile_only'])
                        $return[$val['code']] = $val;
                }else{
                   if($val['code'] != 'tenpay' && $val['code'] != 'native' && $val['code'] != 'micro'){
                      $return[$val['code']] = $val;
                   }
                }
            }
        }
		
		//手机浏览器
        if(!is_weixin()){
            unset($return['weixin'],$return['wxapp']);
        }
		
		//微信
        if(is_weixin()){
            unset($return['alipay']);
        }
		
		//小程序
		
		
		if(is_miniprogram()){
            unset($return['alipay'],$return['weixin'],$return['opay'],$return['native'],$return['integral'],$return['chinapay'],$return['weixinh5'],$return['paypal']);
        }
		
        return $return;
    }
	
	
	//智能支付,是否手机版，支付金额，会员ID
	public function getNoopPayments($mobile = false,$money,$user_id){
        $datas = $this->fetchAll();
        $return = array();
        foreach($datas as $val){
            if($val['is_open']){
                if($mobile == false){
                    if(!$val['is_mobile_only'])
                        $return[$val['code']] = $val;
                }else{
                   if($val['code'] != 'tenpay' && $val['code'] != 'native' && $val['code'] != 'micro'){
                      $return[$val['code']] = $val;
                   }
                }
            }
        }
        if(!is_weixin()){
            unset($return['weixin']);
        }
        if(is_weixin()){
            unset($return['alipay']);
        }
		
		$Users = M('Users')->find($user_id);
		if($Users['money'] < $money){
			unset($return['money']);
		}
        return $return;
    }


	//外卖关闭在线支付
	 public function getPayments_delivery($mobile = false){
        $datas = $this->fetchAll();
        $return = array();
        foreach($datas as $val){
            if($val['is_open']){
                if($mobile == false){
                    if(!$val['is_mobile_only'])
                        $return[$val['code']] = $val;
                }else{
                    if($val['code'] != 'tenpay'){
                        $return[$val['code']] = $val;
                    }
                }
            }
        }
        unset($return['money']);
		unset($return['tenpay']);
		unset($return['native']);
        unset($return['weixin']);
        unset($return['alipay']);
        return $return;
    }

	

	//订座关闭WAP扫码支付

	 public function getPayments_booking($mobile = false){
        $datas = $this->fetchAll();
        $return = array();
        foreach($datas as $val){
            if($val['is_open']){
                if($mobile == false){
                    if(!$val['is_mobile_only'])
                        $return[$val['code']] = $val;
                }else{
                    if($val['code'] != 'tenpay'){
                        $return[$val['code']] = $val;
                    }
                }
            }
        }
        if(!is_weixin()){
            unset($return['weixin']);
			unset($return['native']);
        }
        if(is_weixin()){
            unset($return['alipay']);
			unset($return['native']);
        }
        return $return;
    }

	//跑腿直接只能在线支付

	 public function getPayments_running($mobile = false){
        $datas = $this->fetchAll();
        $return = array();
        foreach($datas as $val){
            if($val['is_open']){
                if($mobile == false){
                    if(!$val['is_mobile_only'])
                        $return[$val['code']] = $val;
                }else{
                    if($val['code'] != 'tenpay'){
                        $return[$val['code']] = $val;
                    }
                }
            }
        }
        if(!is_weixin()){
            unset($return['weixin']);
			unset($return['native']);
        }
        if(is_weixin()){
            unset($return['alipay']);
			unset($return['native']);
        }
        return $return;
    }
	
    public function _format($data){
        $data['setting'] = unserialize($data['setting']);
        return $data;
    }	
	
	//全站回调	
	public function respond($code,$id = 0){
        $payment = $this->checkPayment($code);
        if(empty($payment))
            return false;
		if($code == 'native' || $code == 'micro' ){
			  require_cache( APP_PATH . 'Lib/Payment/' . $code . '.weixin' . '.class.php');//扫码支付
		}elseif(defined('IN_MOBILE')) {
            require_cache(APP_PATH . 'Lib/Payment/' . $code . '.mobile.class.php');
        }else{
            require_cache(APP_PATH . 'Lib/Payment/' . $code . '.class.php');
        }
        $obj = new $code();
        return $obj->respond($id);//传一个参数2018年4月新增这里的ID是日志ID
    }

	public function getCode($logs){
        $CONFIG = D('Setting')->fetchAll();
        $datas = array(
            'subject' => $CONFIG['site']['sitename'] . $this->types[$logs['type']],
            'logs_id' => $logs['log_id'],
            'logs_amount' => $logs['need_pay'] / 100,
        );
        $payment = $this->getPayment($logs['code']);
		if($logs['code'] == 'native' || $logs['code'] == 'micro' ){
			 require_cache( APP_PATH . 'Lib/Payment/' . $logs['code'] . '.weixin' . '.class.php' );//扫码支付
		}elseif (defined('IN_MOBILE')) {
            require_cache(APP_PATH . 'Lib/Payment/' . $logs['code'] . '.mobile.class.php');
        }else{
            require_cache(APP_PATH . 'Lib/Payment/' . $logs['code'] . '.class.php');
        }
        $obj = new $logs['code']();
        return $obj->getCode($datas, $payment);
    }	



    public function checkMoney($logs_id,$money){
        $money = (int) ($money );
        $logs = D('Paymentlogs')->find($logs_id);
        if($logs['need_pay'] == $money)
            return true;
        return false;
    }

	public function checkPayment($code){
        $datas = $this->fetchAll();
        foreach($datas as $val){
            if($val['code'] == $code)
                return $val;
        }
        return array();
    }

    public function getPayment($code){
        $datas = $this->fetchAll();
        foreach($datas as $val){
            if($val['code'] == $code)
                return $val['setting'];
        }
        return array();

    }



	//拼团回调
	public function updateGroupOrder($logs_id,$order_id){
		
		$order=M('group_order')->find($order_id);
		
		//file_put_contents(BASE_PATH.'/Tudou/Lib/Model/$order.txt', var_export($order, true));
		
		if($order['state']==1){
			
			
			//2是拼团成功
			//$res = M('group_order')->where(array('id'=>$order['id']))->save(array('state'=>2,'pay_time'=>time()));
			$res = M('group_order')->where(array('id'=>$order['id']))->save(array('pay_time'=>time()));
			
			$goods = M('group_goods')->where(array('id'=>$order['goods_id']))->find();
			$res2 = M('group_goods')->where(array('id'=>$order['goods_id']))->save(array('ysc_num'=>$goods['ysc_num']+$order['goods_num'],'inventory'=>$goods['inventory'] - $order['goods_num']));
			
			if($order['group_id']>0){
				
				
				//计算数量
				$count = (int)M('group_order')->where(array('group_id'=>$order['group_id'],'state'=>1))->sum('goods_mum');
				file_put_contents(BASE_PATH.'/Tudou/Lib/Model/$count-'.$count.'.txt', var_export($count,true));
				
				$group = M('group')->where(array('id'=>$order['group_id']))->find();
				file_put_contents(BASE_PATH.'/Tudou/Lib/Model/$group.txt', var_export($group, true));
				
				
				if($group['kt_num'] >= $count && $group['kt_num'] > 0){
					$state=2;
				}else{
					$state=1;
				}
				
				file_put_contents(BASE_PATH.'/Tudou/Lib/Model/$state-'.$state.'.txt', var_export($state, true));
				
				//新增已购数量这里是拼团表
				$res3 = M('group')->where(array('id'=>$order['group_id']))->save(array('state'=>$state,'yg_num'=>$group['yg_num'] + $order['goods_num']));
				
				//分成
				if($state==2){
					$res4 = M('group_order')->where(array('id'=>$order['id']))->save(array('state'=>$state));
					//$res4 = M('group_order')->where(array('id'=>$order['id']))->save(array('state'=>$state,'pay_time'=>time()));
				}
				
				
				//改变团状态
				if($state==2 or $order['group_id']==0){
					file_get_contents("".$this->CONFIG['site']['host']."/app/Wxapp/PtMessage/group_id/".$grouporder['group_id']);//模板消息
				}
			}			
		}
		return true;
	}
	
	
	
    public function logsPaid($logs_id,$return_order_id,$return_trade_no) {
		
		
        $this->log_id = $logs_id; //用于外层回调
        $logs = D('Paymentlogs')->find($logs_id);
		
		
        if(!empty($logs) && !$logs['is_paid']){
			
			
            $data = array('log_id' => $logs_id,'is_paid' => 1);
			
			
            if(D('Paymentlogs')->save($data)){
				
                $ip = get_client_ip();
				
                D('Paymentlogs')->save(array(
					'log_id' => $logs_id,
					'pay_time' => NOW_TIME,
					'pay_ip' => $ip,
					'return_order_id' =>$return_order_id,//返回订单号
					'return_trade_no' =>$return_trade_no,//返回交易号
				));
				
				
				//更新付款时间
                $this->type = $logs['type'];
				
				
                if($logs['type'] == 'thread'){
					//暂时不考虑置顶
					$post = M('thread_post')->where(array('log_id'=>$logs['log_id']))->find();
					if($post){
						M('thread_post')->save(array('post_id'=>$post['post_id'],'status'=>1));
						M('PaymentLogs')->where(array('type'=>'thread','log_id'=>$logs['log_id']))->save(array('order_id'=>$post['post_id']));
					}
					return true;
				}elseif($logs['type'] == 'pinche'){ 
					//拼车详情
					$pinche = M('pinche')->where(array('log_id'=>$logs['log_id']))->find();
					if($pinche){
						M('pinche')->save(array('pinche_id'=>$pinche['pinche_id'],'status'=>1,'money'=>$logs['need_pay']));
						M('PaymentLogs')->where(array('type'=>'pinche','log_id'=>$logs['log_id']))->save(array('order_id'=>$pinche['pinche_id']));
					}	
					return true;
				}elseif($logs['type'] == 'deposit'){ 
					//更新表配送员保证金表
					 M('running_delivery')->where(array('user_id'=>$logs['user_id']))->save(array('is_deposit'=>1,'deposit'=>$logs['need_pay']));
					return true;
				}elseif($logs['type'] == 'shop'){   
					D('Shop')->save(array('shop_id'=>$logs['order_id'],'shop_apply_prrice'=>$logs['need_pay']));//商家入驻
					return true;
				}elseif($logs['type'] == 'group'){
					$this->updateGroupOrder($logs['log_id'],$logs['order_id']);//拼团回调
					return true;
				}elseif($logs['type'] == 'coupon'){
					D('Coupondownload')->updateCouponOrder($logs['log_id'],$logs['order_id']);//优惠券回调
					return true;
				}elseif($logs['type'] == 'money'){
					
					D('Users')->updateCount($logs['user_id'],'money',$logs['need_pay']);
					
					$payment = D('Payment')->where(array('code'=>$logs['code']))->find();//获取支付
					
					D('Usermoneylogs')->add(array(
						'user_id' => $logs['user_id'], 
						'money' => $logs['need_pay'], 
						'create_time' => NOW_TIME, 
						'create_ip' => $ip, 
						'intro' => '余额充值【'.round($logs['need_pay']/100,2).'】支付ID'.$logs['log_id'].'支付方式【'.$payment['name'].'】', 
					));
					return true;
                }elseif($logs['type'] == 'running'){
					D('Running')->updateOrder($logs['log_id'],$logs['order_id'],$logs['order_ids'],$logs['code'],$logs['need_pay']);//在线支付
					return true;
				}else{
					return true;
                }
				
            }
        return true;
      }

   }

   

}



