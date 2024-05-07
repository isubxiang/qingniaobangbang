<?php

class BookingorderModel extends CommonModel{
    protected $pk   = 'order_id';
    protected $tableName =  'booking_order';
	
    public function getStatus(){
        return array(
            -1 => '已取消',
            0  => '未付款',
            1  => '已付款',
            2  => '已完成',
			8  => '店内支付',
        );
    }
	
	
	//在线订座支付回调
	public function updateBookingOrder($order_id,$code){
		$order_status = ($code == 'wait') ? '8' : '1';
        D('Bookingorder')->save(array('order_id'=>$order_id,'order_status' =>$order_status,'code' =>$code,'consume_code' =>$this->getCode()));
		D('Sms')->sms_booking_user($order_id);//短信通知会员
		D('Sms')->sms_booking_shop($order_id);//短信通知商家
		
		
		$order = D('Bookingorder')->find($order_id);
		$Shop = D('Shop')->find($order['shop_id']);
		if($Shop['is_booking_print'] == 1){
			D('Bookingorder')->combinationBookingPrint($order_id);//打印
		}
		
		if($order_status == 1){
			D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 7,$status = 1);
			D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 7,$status = 1);
		}
		
		return true;
	}
    
	
	//订单核销码
	public function getCode(){
        $i = 0;
        while(true){
            $i++;
            $code = rand_string(8,1);
            $data = M('BookingOrder')->find(array('where'=>array('code' => $code)));
            if(empty($data)){
                return $code;
            }
            if($i > 20){
                return $code;
            }
        }
    }
	
	
	//获取订座当前订单满减2组
	public function getFullReducePrice($total_money,$shop_id){	
	   $setting = M('bookingSetting')->find($shop_id);
	   //p($setting);die;
	   
	   if($setting['is_full'] == 1){
		   //第一种可能
		   if(!empty($setting['order_price_full_1']) && !empty($setting['order_price_full_2'])){
			   //中间
			   if($total_money >= $setting['order_price_full_1'] && $total_money <= $$setting['order_price_full_2']){
				   if($setting['order_price_reduce_1'] > 0){
					  return $setting['order_price_reduce_1'];   
				   }
				}
				//大于第二个满减
				if($total_money >= $setting['order_price_full_2']){
				   if($setting['order_price_reduce_2'] > 0){
					  return $setting['order_price_reduce_2'];   
				   }
				}
				if($total_money <= $setting['order_price_full_1']){
				   return 0;
				}
			}
			//第二种可能
			if(!empty($setting['order_price_full_1'])){
			   if($total_money >= $setting['order_price_full_1']){
				   if($Ele['order_price_reduce_1'] > 0){
					  return $setting['order_price_reduce_1'];   
				   }
				}
			   if($total_money <= $setting['order_price_full_1']){
				   return 0; //不返回
				}
			}
			return 0; 
	   }else{
		  return 0; 
	   }
	}
	
	
	
	
	//取消订单
    public function cancel($order_id){
        if(!$order_id = (int)$order_id){
            return false;
        }elseif(!$detail = $this->find($order_id)){
            return false;
        }else{
			
            if($detail['order_status'] ==1 || $detail['order_status'] == 0){
                if(false !== $this->save(array('order_id'=>$order_id,'order_status'=>-1,'update_time'=>NOW_TIME))){
                    if($detail['order_status'] == 1){
						D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 7,$status = 11);
						D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 7,$status = 11);
                        D('Users')->addMoney($detail['user_id'],(int)$detail['amount'],'订座订单取消,ID:'.$order_id.'，返还支付金额');
                    }
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
            
        }                                 
    }
     
	 
   
    //新版订座结算
    public function complete($order_id){
        if(!$order_id = (int)$order_id){
            return false;
        }elseif(!$detail = $this->find($order_id)){
            return false;
        }else{
			
			$status = $detail['order_status'];
			
            if($detail['order_status'] == 1 || $detail['order_status'] == 8){
                if(false !== $this->save(array('order_id'=>$order_id,'order_status'=>2,'update_time'=>NOW_TIME))){
					
					
					$shop = D('Shop')->find($detail['shop_id']);
					$commission = $shop['commission'] > 0 ?  $shop['commission'] : '5';
					
					$commission_price = ($detail['amount'] * $commission)/10000;//佣金
					$commission_price = round($commission_price/1,2);
			
			
					$money = $detail['amount'] - (($detail['amount'] * $commission)/10000);//实际结算金额
					$money = round($money/1,2);
					
					//店内支付不结算
					if($status != 8 && $money > 0){
						$info = '订单号：【'.$order_id.'】，实付【'.round($detail['amount']/100,2).'】元，结算价格【'.round($money/100,2).'】元，佣金【'.round($commission_price/100,2).'元】，佣金费率【'.round($commission/100,2).'%】';
						
						D('Shopmoney')->insertData($order_id,$id ='0',$detail['shop_id'],$money,$type ='booking',$info);//结算给商家
					}
					
					D('Users')->rewardPrestige($detail['user_id'],(int)($money/100),$detail['order_id'],'booking');//返威望	
				
					D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 1,$type = 7,$status = 8);
					D('Weixinmsg')->weixinTmplOrderMessage($order_id,$cate = 2,$type = 7,$status = 8);
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }  
        }  
    }



	//订座万能打印接口
    public function combinationBookingPrint($order_id){
        $order = D('Bookingorder')->find($order_id);
		$Book = D('Booking')->find($order['shop_id']);
	    $msg = $this->bookingPrint($order['order_id']);//获取打印信息
		$result = D('Print')->printOrder($msg,$Book['shop_id']);
		$result = json_decode($result);
		$backstate = $result->state;
		if($backstate == 1){
			return true;
		}	
		return true;	
			
    }
	//生成房间二维码
	public function getBookingPrintCode($order_id){
		$order = D('Bookingorder')->find($order_id);
		$url = U('wap/booking/pay', array('orderType'=>$order['orderType'],'order_id'=>$order_id,'t' => NOW_TIME, 'sign' => md5($order_id . C('AUTH_KEY') . NOW_TIME)));
        $token = 'booking_order_id_' . $order_id;
        $file = ToQrCode($token,$url,8,'booking');
        return $file;
    }

	//获取订座打印信息
	public function bookingPrint($order_id){	
		$order = M('BookingOrder')->find($order_id);
        $member = M('Users')->find($order['user_id']);//会员信息
		$Booking = M('Booking')->find($order['shop_id']);//订座商家信息
		$room = M('BookingRoom')->where(array('room_id'=>$order['room_id']))->find();
	
			
			$msg .= '<MN>2</MN>\r';
			$msg .= '********************************\r';
			$msg .= '@@2订单编号:' . $order['order_id'] . '\r';
			
			if($order['room_id']){
				$msg .= '@@2桌台号:' . $order['room_id'] . '\r';
				$msg .= '@@2桌台名称:' . $room['name'] . '\r';
			}
			
			$msg .= '用户昵称:' . $member['nickname'] . '\r';
			$msg .= '用户手机:' . $member['name'] . '\r';
			$msg .= '预定时间：' . $order['ding_time'] . '\r';
			$msg .= '预定人数：' . $order['ding_num'] . '\r';
			$msg .= '下单时间：' . date('Y-m-d H:i:s', $order['create_time']) . '\r';
			$msg .= '********************************\r';
			
            $msg .= '----------------------\r';
            $msg .= '@@2菜品明细\r';
            $menus = D('Bookingordermenu')->where(array('order_id'=>$order['order_id']))->select();
            foreach ($menus as $key => $value) {
                $msg .= ($key+1).'.'.$value['menu_name'].'—'.($value['price']/100).'元'.'*'.$value['num'].'份\r';
            }
            $msg .= '----------------------\r';
			
			$msg .= '@@2点菜付款：' . round($order['menu_amount']/100,2) .'元\r';
			$msg .= '@@2定金付款：' . round($order['room_amount']/100,2) .'元\r';
			$msg .= '@@2用户实际付款：' . round($order['amount']/100,2) .'元\r';
			
			$msg .= '----------------------\r';
			$msg .= '商家名称：' . $Booking['shop_name'] .'\r';
            $msg .= '配货电话：' . $Booking['tel'] ? $Booking['tel'] : $Booking['mobile'] . '\r';
		
			$msg .= '@@2备注：'. $order['note'] .'\r';
			$msg .= '\r';
			return $msg;//返回数组
   }
   
    public function plqx($shop_id){
        if($shop_id = (int)$shop_id){
            $ntime = date('H:i',NOW_TIME);
            $order = $this->where("`shop_id` = ".$shop_id." AND `ding_date` <".TODAY." OR (`ding_date` =".TODAY." AND `ding_time` <".$ntime.") ")->select();
            foreach ($order as $k=>$val){
                $this->cancel($val['order_id']);
            }
            return true;
        }else{
            return false;
        }
    }
	
	
	public function get_ding($shop_id,$list){
		$dings = $arr = $rooms = $tmp =  array();
		if($list){
			foreach($list as $k => $v){
				$dings[] = $v['ding_id'];
			}
		}
		$Cfg = D('Bookingsetting')->getCfg();
		$type = D('Bookingroom')->getType();
		$arr = D('Bookingyuyue')->itemsByIds($dings);
		$room = D('Bookingroom')->where('shop_id = '.$shop_id)->select();
		foreach($room as $k => $v){
			$rooms[$v['room_id']] = $v;
		}
		foreach($arr as $k => $v){
			if($v['room_id'] == 0){
				$arr[$k]['room_id'] = '大厅';
			}else{
				$arr[$k]['room_id'] = $rooms[$v['room_id']]['name'];
			}
			$arr[$k]['last_t'] = $Cfg[$v['last_t']];
			$arr[$k]['number'] = $type[$v['number']];
		}
		return $arr;
	}

	public function get_detail($shop_id,$order,$yuyue){
		$Cfg = D('Bookingsetting')->getCfg();
		$type = D('Bookingroom')->getType();
		$room = D('Bookingroom')->where('shop_id = '.$shop_id)->select();
		foreach($room as $k => $v){
			$rooms[$v['room_id']] = $v;
		}
		if($yuyue['room_id'] == 0){
			$yuyue['room_id'] = '大厅';
		}else{
			$yuyue['room_id'] = $rooms[$yuyue['room_id']]['name'];
		}
		$yuyue['last_t'] = $Cfg[$yuyue['last_t']];
		$yuyue['number'] = $type[$yuyue['number']];
		$arr = array_merge($yuyue,$order);
		
		$a = substr($arr['menu'],0,-1);
		$arr1 = explode('|',$a);
		foreach($arr1 as $k => $v){
			$arr2[] = explode(':',$v);
		}
		$arr['menu'] = $arr2;
		return $arr;
	}

	public function get_d($yuyue){
		$Cfg = D('Bookingsetting')->getCfg();
		$type = D('Bookingroom')->getType();
		$tem =array();
		foreach($yuyue as $k => $v){
			$yuyue[$k]['last_t'] = $Cfg[$v['last_t']];
			$yuyue[$k]['number'] = $type[$v['number']];
		}
		
		return $yuyue;
	}	
    
}