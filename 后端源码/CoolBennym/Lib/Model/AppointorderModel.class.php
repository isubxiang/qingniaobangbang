<?php
class AppointorderModel  extends  CommonModel{
    protected $pk   = 'order_id';
    protected $tableName =  'appoint_order';
    
	protected $types = array(
		0 => '等待付款', 
		1 => '已付款', 
		2 => '已接单', 
		3 => '退款中', 
		4 => '已退款', 
		8 => '已完成'
	);
	
    public function getType(){
        return $this->types;
    }
	
	//家政订单回调
	public function updateOrder($log_id,$order_id,$order_ids,$types,$code = ''){	
	
		
		//订单详情
		$order = M('appoint_order')->find($order_id);
		$logs = M('payment_logs')->find($log_id);
		$shop = M('shop')->find($order['shop_id']);
		
		
		//file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$order_id.'_order.txt',var_export($order,true));
		//file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$order_id.'_types.txt',var_export($types,true));
		//p($log_id.'----'.$order_id.'----'.$order_ids.'----'.$types.'----'.$code = '');die;
		
		
		file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$types.'_'.$order_id.'_logs.txt',var_export($logs,true));
		
		if($types == 2){
			
			//尾款订单支付
			
			M('appoint_order_append')->save(array('id'=>$order_ids,'status'=>1,'pay_time' => time()));//补差价家政改变订单状态
			
			//二次验证商家的存在
			
			$append = M('appoint_order_append')->find($order_ids);//差价订单
			$order = M('appoint_order')->find($append['order_id']);
			$shop = M('shop')->find($order['shop_id']);
			
			
			//商家佣金比例
			$shop['commission'] = $shop['commission'] ? $shop['commission'] : 100;
			//佣金
			$commission = (int)(($logs['need_pay'] * $shop['commission'])/10000);
			
			//结算金额
			$jiesuan_price = $logs['need_pay'] - $commission;
		
			$info = '家政尾款订单ID【'.$order_ids.'】，原始订单ID【'.$append['order_id'].'】结算，佣金比例【'.round($shop['commission']/100,2).'%】，尾款总价【'.round($logs['need_pay']/100,2).'】元-佣金【'.round($commission/100,2).'】元';
			
			//尾款订单结算给家政这里可能有问题
			$data['shop_id'] = $order['shop_id'];
			$data['city_id'] = $shop['city_id'];
			$data['area_id'] = $shop['area_id'];
			$data['money'] = $jiesuan_price;
			$data['type'] = 'appoint';
			$data['order_id'] = $append['order_id'];
			$data['intro'] = $info;
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
			D('Shopmoney')->add($data);//写入数据库
			D('Users')->addGold($shop['user_id'],$jiesuan_price,$info);//写入金币，商户资金
			
			
		}else{
			M('appoint_order')->where(array('order_id'=>$order_id))->save(array('status'=>1,'code'=>$code,'pay_time' => time(),'buy_num'=>$order['buy_num']+1));//家政改变订单状态
			M('appoint')->where(array('appoint_id'=>$order['appoint']))->setInc('yuyue_num',1);//新增已售
		}
		
		
		
		//让优惠卡失效
	
		$CouponCard = D('AppointCard')->find($order['card_id']);
		if($CouponCard){
			$arr = array();
			$arr['order_id'] = $order_id;
			$arr['appoint_id'] = $order['appoint_id'];
			$arr['user_id'] = $order['user_id'];
			$arr['shop_id'] = $order['shop_id'];
			$arr['card_id'] = $order['card_id'];
			$arr['money'] = $CouponCard['cardMoney'];
			$arr['status'] = 1;
			$arr['create_time'] = NOW_TIME;
			$arr['create_ip'] = get_client_ip();
			D('AppointCardLogs')->add($arr);
			D('AppointCard')->where(array('card_id'=>$order['card_id']))->save(array('state'=>'1','used_time'=>NOW_TIME));
		}
		
		D('Appointorder')->appoint_order_print($order_id);//家政打印万能接口
		D('Sms')->sms_appoint_TZ_user($order_id);//家政短信通知用户
		D('Sms')->sms_appoint_TZ_shop($order_id);//家政短信通知商家
		D('Weixinmsg')->weixinTmplOrderMessage($$order_id,$cate = 1,$type = 3,$status = 1);
		D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 3,$status = 1);
        return true;
    }
	
	
	
		
	//家政配送接口
    public function appointPeisong($order_id){
	    $order = M('appoint_order')->where(array('order_id'=>$order_id))->find();
	    //已经有该订单返回假，避免重复提
	    $res = M('delivery_order')->where(array('type_order_id'=>$order_id,'type'=>'3'))->find();
		
		$appoint = M('appoint')->find($order['appoint_id']);
		$shop = M('shop')->find($order['shop_id']);
		
		//商家开通配送并以前没订单
        if($shop['is_appoint_pei'] == 1 && !$res){
           $data = array(
                'type' => 3,
                'type_order_id' => $order['order_id'],
                'delivery_id' => 0,
                'shop_id' => $order['shop_id'],
                'city_id' => $shop['city_id'],
			    'area_id' => $shop['area_id'],
			    'business_id' => $shop['business_id'],
                'lat' => $shop['lat'],
                'lng' => $shop['lng'],
				'user_id' => $order['user_id'],
				'shop_name' => $shop['shop_name'],
				'name' => $order['name'],
				'mobile' => $order['tel'],
				'addr' => $order['addr'],
                'address_id' => 0,
				'need_pay' => $order['need_pay'], //订单总价
                'logistics_price' =>$shop['express_price'] ? $shop['express_price'] : '300',//运费
                'create_time' => time(),
                'update_time' => 0,
                'status' => 1,//如果是到付可就是0，不是到付就是1
				'closed'=> 0
             );
             M('delivery_order')->add($data);
        }
        return true;
    }
	
	
	
	
	//检查配送状态
	public function confirm($order_id){
		$detail = M('appoint_order')->find($order_id);
		if(empty($detail)){
			$this->error = '订单不存在';
			return false;
		}
		$do = M('delivery_order')->where(array('type'=>3,'type_order_id' =>$detail['order_id'],'closed'=>0))->find();
		if($do['status'] == 2){
			$this->error = '配送员已抢单暂时无法操作';
			return false;
		}
	    return true;
	}
	
	
	//检测配送状态2
	public function Appoint_order_Distribution($order_id, $type=''){
		$order = M('appoint_order')->where(array('order_id'=>$order_id))->find();
		$appoint = M('appoint')->find($order['appoin_id']);
		if($appoint['is_orders'] == 1){
			$orders = M('appoint_orders')->where(array('appoint_order_id' => $order_id, 'type' => $type))->find();
			if(!empty($orders)){
				if($orders['closed'] ==0){
					M('appoint_orders')->where(array('appoint_order_id' => $order_id, 'type' => $type))->setField('closed',0); //重新开启订单
				}else{
					if($order['status'] == 2 || $order['status'] == 8){
						return false;
					}else{
						M('appoint_orders')->where(array('appoint_order_id' => $order_id, 'type' => 0))->setField('closed',1);//更改阿姨抢单状态
					}	
			   }
			}else{
				return false;
			}
		  return true;
		}	
	   return true;
		
	}
	
	
	
	
	//返回详情
	public function detail($id){
        $id = (int)$id;
        $data = M('appoint_order')->find($id);
        if(empty($data)){
            $data = array('id'=>$id);
            $this->add($data);
        }
        return $data;
    }
	
	
   //更新预约数量
    public function updateCount_yuyue_num($order_id){
        $order_id = (int)$order_id;
		$Appoint = M('appoint_order')->where(array('order_id'=>$order_id))->find();;//查找日志
        D('Appoint')->updateCount($Appoint['appoint_id'], 'yuyue_num');	
        return true;
    }
	
	
	//家政退款给用户封装
    public function refund_user($order_id){
		$order_id = (int)$order_id;
		if(empty($order_id)){
			 return false;
		}
		$detail = M('appoint_order')->find($order_id);
		if(!empty($detail)){
			$Appoint = M('appoint')->find($detail['appoint_id']);
            if(false !== M('appoint_order')->save(array('order_id'=>$order_id,'status'=>4))){
				D('Sms')->sms_appoint_refund_user($order_id);
				//家政退款通知用户手机
				D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 3,$status = 4);
				D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 3,$status = 4);
				$info = '家政申请退款，订单号：'.$order_id;
                D('Users')->addMoney($detail['user_id'], $detail['need_pay'],$info);//给用户增加金额
                return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
     }  
	 
	 
	 
	//家政结算封装
    public function appoint_settlement($order_id){
		$order_id = (int)$order_id;
		if(empty($order_id)){
			$this->error = 'ID不正确';
			return false;
		}
		$detail = M('appoint_order')->find($order_id);
		if(empty($detail)){
			$this->error = '订单不存在';
			return false;
		}
		$shop = D('Shop')->find($detail['shop_id']);
		if(empty($shop)){
			$this->error = '上架不存在';
			return false;
		}
		$appoint = D('Appoint')->find($detail['appoint_id']);
		if(empty($appoint)){
			$this->error = '家政不存在';
			return false;
		}
		
		$shop['commission'] = $shop['commission'] ? $shop['commission'] : 100;
		$commission = (int)(($detail['need_pay'] * $shop['commission'])/10000);
		
	
		if(!$commission){
			$this->error = '商户佣金设置不正确';
			return false;
		}
		$jiesuan_amount = ($detail['need_pay']) - $commission;
			
		$info = '家政订单号【'.$order_id.'】结算，名称【'.$appoint['title'].'】佣金比例【'.round($shop['commission']/100,2).'%】，订单总价【'.round($detail['need_pay']/100,2).'】元-佣金【'.round($commission/100,2).'】元';
		
		
		
		if(false !== M('appoint_order')->save(array('order_id'=>$order_id,'status'=>8))){
			  D('Shopmoney')->insertData($order_id,$id ='0',$detail['shop_id'],$jiesuan_amount,$type ='appoint',$info);//结算给家政商家
			  D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 3,$status = 8);
			  D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 3,$status = 8);
			  return true;
       }else{
		   	 $this->error = '更新数据库失败';
			 return false;  
		}
    }
	
	//家政打印
	public function appoint_order_print($order_id){	
		$Appointorder = M('appoint_order')->where('order_id='.$order_id)->find();
		$Shop = M('shop')->find($Appointorder['shop_id']);
		if($Shop['is_appoint_print'] ==1){
		  $msg = $this->appoint_print($Appointorder['order_id']);
		  $result = D('Print')->printOrder($msg, $Shop['shop_id']);
		}
	  return true;
    }
	
	//家政订单打印
	public function appoint_print($order_id){	
			$Appointorder = M('appoint_order')->find($order_id);
			$Shop = M('shop')->where(array('shop_id'=>$Appointorder['shop_id']))->find();//商家信息
			
            $msg .= '@@家政订单__________NO:' . $Appointorder['order_id'] . '\r';
            $msg .= '预约姓名：' . $Appointorder['name'] . '\r';
            $msg .= '预约电话：' . $Appointorder['tel'] . '\r';
            $msg .= '预约地址：' . $Appointorder['addr'] . '\r';
            $msg .= '预约时间：' . $$Appointorder['svctime'] . '\r';
			
			if(!empty($Appointorder['worker_id'])){
				$msg .= '----------------------\r';
				$msg .= '@@预约技师信息\r';
				$appointworker = D('Appointworker')->where(array('worker_id' => $Appointorder['worker_id']))->find();
				$msg .= '技师姓名：'.$appointworker['name'].'技师职务：'.$appointworker['office'].'技师手机：'.$appointworker['mobile'].'\r';
			}
			
            $msg .= '----------------------\r';
			$msg .= '商家名称：' . $Shop['shop_name'] . '\r';
            $msg .= '已付定金：' . $Appointorder['need_pay'] / 100 . '元\r';
			return $msg;//返回数组
   }
}