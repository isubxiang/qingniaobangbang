<?php

class PayAction extends CommonAction{
  	
	
	//订单回调
	public function SavePayLogs(){
		$testxml = file_get_contents("php://input");
		$jsonxml = json_encode(simplexml_load_string($testxml,'SimpleXMLElement',LIBXML_NOCDATA));
		$result = json_decode($jsonxml, true);
		
		
		if($result){
			 //如果成功返回了
			 if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
				 
				 $trade = explode('-',$result['out_trade_no']);
				 $log_id = $trade[0];//支付日志ID
				 //支付表
				 $logs = M('payment_logs')->where(array('log_id'=>$log_id))->find();//支付日志记录
				
				 //D('Running')->updateOrder($logs['log_id'],$logs['order_id'],$logs['order_ids'],$logs['code'],$logs['need_pay']);//在线支付跑腿订单回调
				
				 $order_id = $logs['order_id'];//订单ID
				 $id = $logs['order_id'];//订单ID
				 $log_id = $logs['log_id'];//支付ID
				 
				 
				//小费付款订单回调
				if($logs['types'] == 2 && $logs['is_paid'] == 0){
					
					$old_order = M('running')->where(array('running_id'=>$logs['order_id']))->find();//正常付费的原始订单
					$logs2 = M('payment_logs')->where(array('type'=>'running','log_id'=>$log_id,'types'=>2))->find();//当前订单回调

					$arr['MoneyTip'] =$logs2['need_pay'];//小费
					$arr['MoneyPayment'] = $old_order['MoneyFreight'] + $logs2['need_pay'];//实际支付
					$res2 = M('running')->where(array('running_id'=>$id))->save($arr);//更新跑腿订单
					
					file_put_contents(BASE_PATH.'/Tudou/Lib/Action/App/'.$log_id.'_logs.txt', var_export($logs,true));
				 
				}elseif($logs['types'] == 1 && $logs['is_paid'] == 0){
					
					 //正常支付订单回调
					$running = M('running')->where(array('running_id'=>$id))->find();//判断状态
					if($running['OrderStatus'] == 1){
						$res2= M('running')->where(array('running_id'=>$id))->save(array('status'=>1,'OrderStatus'=>2,'pay_time'=>time()));//跑腿订单支付回调
						
						//如果是外卖订单
						if($running['Type'] == 1){
							$res3 = M('RunningProduct')->where(array('running_id'=>$id))->save(array('OrderStatus'=>2,'pay_time'=>time()));//商家外卖订单已付款支付回调
							//更新外卖销量
							$list = M('running_product')->where(array('running_id'=>$id))->select();
							foreach($list as $k =>$v){
							   M('EleProduct')->where(array('product_id'=>$v['product_id']))->setInc('sold_num',$v['Quantity']);//新增已售
							   M('Ele')->where(array('shop_id'=>$v['shop_id']))->setInc('sold_num',$v['Quantity']);//新增外卖已售
							}
							
							$shop = M('shop')->where(array('shop_id'=>$v['ShopId']))->find();//查商家
							if($shop['is_taking']){
								//确认发货逻辑封装自动接单
								$taking = D('Running')->taking($id,$isPrint = 0,$isPrintInfo = '');
							}
							//外卖订单回调结束
						}
				    }
					
					//核销优惠券
					if($running['download_coupon_id']){
						M('coupon_download')->where(array('download_id'=>$running['download_coupon_id']))->save(array('is_used'=>1,'used_time'=>time()));
					}
				}
				
				//微信通知短信通知
				if($res2){
					//更新支付日志表状态跟返回的订单号
					$res = M('PaymentLogs')->where(array('type'=>'running','log_id'=>$log_id))->save(array(
						'is_paid'=>1,
						'pay_time'=>time(),
						'return_order_id'=>$result['out_trade_no'],
						'return_trade_no'=>$result['transaction_id'],
						'pay_ip'=>get_client_ip()
					));
					//微信模板消息批量给配送员发送订单
					D('Weixintmpl')->runningWxappNotice($id,$OrderStatus = 2,$user_id= '',$type = 1,$openid='',$form_id='');//通知买家，订单ID，订单状态，下单人，类型
					D('Weixintmpl')->runningNoticeDelivery($id);//微信模板消息批量给配送员发送订单
					D('Sms')->runningPayUser($id);//订单付款短信通知买家
					D('Sms')->sms_delivery_user($id);//配送员短信循环通知
					D('Running')->combinationElePrint($id);//打印跑腿订单
				}
				//给上级分钱
		  	}
			
	        //如果不是跑腿订单就回调，是就不用二次回调了
			if($logs['type'] != 'running'){
				//订单回调
				$logsPaid = D('Payment')->logsPaid($logs['log_id'],$result['out_trade_no'],$result['transaction_id']);//通过订单ID回调，其他错误后期在写
				return true;
			}
			
			
		}
		return true;
	}
	

   
  
	
	
}
