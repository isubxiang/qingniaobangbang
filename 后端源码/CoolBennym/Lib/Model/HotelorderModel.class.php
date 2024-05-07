<?php
class HotelorderModel extends CommonModel{
    protected $pk   = 'order_id';
    protected $tableName =  'hotel_order';
    
	
	public function getError(){
        return $this->error;
    }
	
    public function cancel($order_id){
        if(!$order_id = (int)$order_id){
			$this->error = 'ID不存在';
            return false;
        }elseif(!$detail = M('hotel_order')->find($order_id)){
			$this->error = '订单不存在';
            return false;
        }else{
            
            $room = D('Hotelroom')->find($detail['room_id']);
            if(!$room['is_cancel']){
				$this->error = '未知错误';
                return false;
            }
			
			if($detail['order_status'] == 2){
				$this->error = '当前状态已入住';
			 	return false;
			}
			if($detail['order_status'] ==3){
				$this->error = '当前状态退款中';
			 	return false;
			}
			if($detail['order_status'] ==4){
				$this->error = '当前状态退款完成';
			 	return false;
			}
			if($detail['order_status'] == -1){
				$this->error = '当前状态已取消订单';
			 	return false;
			}
			
			
			
			
            if(false !== M('hotel_order')->save(array('order_id'=>$order_id,'order_status'=>-1))){
				
				if($detail['order_status'] == 1 && $detail['online_pay'] == 1){
					
					D('Users')->addMoney($detail['user_id'],(int)$detail['amount']*100,'酒店订单ID【'.$order_id.'】，取消操作返还余额');
				}
				
				D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 6,$status = 11);
				D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 6,$status = 11);
                D('Hotelroom')->updateCount($detail['room_id'],'sku',$detail['num']);//增加库存
                return true;
            }else{
				$this->error = '更新数据库失败';
                return false;
            }
            
        }  
    }
    
	//取消过期时间
    public function plqx($hotel_id){
        if($hotel_id = (int)$hotel_id){
            $ntime = date('Y-m-d',NOW_TIME);
            $map['stime'] = array('LT',$ntime);
            $map['hotel_id'] = $hotel_id;
			$map['order_status'] = 1;
            $order = M('hotel_order')->where($map)->select();
            foreach ($order as $k=>$val){
                $this->cancel($val['order_id']);
            }
            return true;
        }else{
            return false;
        }
    }
	
	
    //酒店结算
    public function complete($order_id){
		$order_id = (int)$order_id;
		if(empty($order_id)){
			 $this->error = '必要的参数order_id没有传入';
			 return false;
		}
		$detail = M('hotel_order')->find($order_id);
		if(!empty($detail)){
			$Hotel = D('Hotel')->find($detail['hotel_id']);
            if($detail['order_status'] == 1){
                $detail['is_fan'] = 1;
            }
			
            $room = D('Hotelroom')->find($detail['room_id']);
			$Hotel = D('Hotel')->find($detail['hotel_id']);
			$shop = D('Shop')->find($Hotel['shop_id']);
			
			
			
			
			
			$commission = (int)(($detail['amount'] * $shop['commission'])/100);
			
			
			if(!$commission){
				$this->error = '商户佣金设置不正确';
			   	return false;
			}
			$jiesuan_amount = ($detail['amount']*100) - $commission;
			
		
		
            if(false !== M('hotel_order')->save(array('order_id'=>$order_id,'order_status'=>2))){
				
                if($detail['is_fan'] == 1 && $jiesuan_amount > 1){
					
					$info = '酒店订单号【'.$order_id.'】结算，房间名称【'.$room['title'].'】当前结算佣金比例【'.round($shop['commission']/100,2).'%】订单总价【'.$detail['amount'].'】元';
					D('Shopmoney')->insertData($order_id,$id ='0',$Hotel['shop_id'],$jiesuan_amount,$type ='hotel',$info);//结算给商家
					D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 6,$status = 8);
					D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 6,$status = 8);
                }else{
					 $this->error = '订单状态不正确';
			   		 return false;
				}
            }else{
                $this->error = '更新酒店订单已完成数据库操作失败';
			   	return false;
            }
		}else{
			$this->error = '没有找到该订单详情';
			return false;
		}
    } 
	
	 
	//酒店退款给用户封装
    public function hotel_refund_user($order_id){
		$order_id = (int)$order_id;
		if(empty($order_id)){
			 return false;
		}
		$detail = M('hotel_order')->find($order_id);
		if(!empty($detail)){
            if(false !== M('hotel_order')->save(array('order_id'=>$order_id,'order_status'=>4))){
				
				$info = '酒店订单号：【'.$order_id.'】申请退款退资金'.$detail['amount'].'元';
                
				
				
				$rest = false;
				
				$getConfigKey = getConfigKey('pay');
				if($getConfigKey['hotel_weixin_original_refund'] != 1){
					$rest == false;
				}
				
				$logs = M('PaymentLogs')->where(array('type'=>'hotel','order_id'=>$order_id,'is_paid'=>1))->find();
				
				if($logs['return_trade_no'] && $getConfigKey['hotel_weixin_original_refund'] == 1){
					$rest = D('Paymentlogs')->weixinRefund($order_id,$detail['amount']*100,'hotel',$info);//微信退款订单ID，金额，类型,说明
				}
				
				if($rest == false){
					D('Users')->addMoney($detail['user_id'],$detail['amount']*100, $info);//给用户增加金额
				}
				
				
				
				
				
				D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 6,$status = 4);
			    D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 6,$status = 4);
                return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
     }  
}