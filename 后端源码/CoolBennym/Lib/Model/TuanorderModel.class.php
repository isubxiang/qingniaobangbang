<?php
class TuanorderModel extends CommonModel{
    protected $pk = 'order_id';
    protected $tableName = 'tuan_order';
	
	
	//订单状态
    public function getTuanOrderStatus(){
        return array(
			0 => '未付款', 
			1 => '已付款', 
			2 => '已核销', 
			3 => '退款中', 
			4 => '已退款', 
			8 => '已完成', 
		);
    }
	
	
	
	public function updateOrder($order_id,$user_id){
		$order = M('tuan_order')->find($order_id);
		$tuan = M('Tuan')->find($order['tuan_id']);
		$add = array(
			'user_id' => $user_id, 
			'shop_id' => $tuan['shop_id'], 
			'order_id' => $order['order_id'], 
			'tuan_id' => $order['tuan_id'], 
			'code' => D('Tuancode')->getCode(), 
			'num' => $order['num'], 
			'price' => $order['need_pay'], //总价
			'real_money' => $order['need_pay'], //退款的时候用
			'real_integral' => (int)$order['use_integral'], //退款的时候用
			'fail_date' => $tuan['fail_date'], 
			'settlement_price' => 0, //舍弃
			'create_time' => NOW_TIME, 
			'create_ip' => get_client_ip(), 
		);
		M('TuanCode')->add($add);
		
		
		M('tuan_order')->save(array('order_id' =>$order['order_id'], 'status' => 1));//设置已付款
		D('Sms')->sms_tuan_user($order['user_id'],$order['order_id']);//团购商品通知用户
		D('Tuan')->updateCount($order['tuan_id'],'sold_num');//更新卖出产品
		
		
		D('Tuanorder')->updateTuanNum($order['order_id']);//新版更新库存
		
		D('Sms')->tuanTZshop($order['shop_id']);//发送短信通知商家
		D('Tuanorder')->combinationTuanPrint($order_id);//抢购打印万能接口

		D('Weixinmsg')->weixinTmplOrderMessage($logs['order_id'],$cate = 1,$type = 4,$status = 1);
		D('Weixinmsg')->weixinTmplOrderMessage($logs['order_id'],$cate = 2,$type = 4,$status = 1);
		return true;
	}
	
	//抢购打印
	public function combinationTuanPrint($order_id){	
		$order_id = (int) $order_id;
		$order = M('tuan_order')->find($order_id);
		$TuanCode = M('TuanCode')->where(array('order_id'=>$order_id))->find();
		$Tuan = M('Tuan')->find($order['tuan_id']);
		$Users = M('Users')->find($order['user_id']);//会员信息
		$Shop = M('Shop')->where(array('shop_id'=>$order['shop_id']))->find();//商家信息
		
		$msg .= '@@2抢购菜单__________NO:' . $order['order_id'] . '\r';
		$msg .= '客户姓名：' . $Users['nickname'] . '\r';
		$msg .= '客户电话：' . $Users['mobile'] . '\r';
		$msg .= '下单时间：' . date('Y-m-d H:i:s', $order['create_time']) . '左右\r';
		$msg .= '订单金额：' . round($order['need_pay']/100,2) . '\r';
		
		$msg .= '----------------------\r';
		
		$msg .= '@@2订单明细\r';
		$msg .= $order['orderName'] ? $order['orderName'] : $Tuan['title'].'—'.($order['tuan_price']/100).'元'.'*'.$order['num'].'份\r';
		
		$msg .= '----------------------\r';
		$msg .= '@@2核销码 ：' .$TuanCode['code'] . '\r';
		$msg .= '留言：'.$order['note'].'\r';
		
		$result = D('Print')->printOrder($msg,$order['shop_id']);
		$result = json_decode($result);
		$backstate = $result->state;
		
	}
	
	
	
	//检测抢购订单过期时间
	public function chenk_guoqi_time(){
		$CONFIG = D('Setting')->fetchAll();
		$guoqi_time = $CONFIG['tuan']['tuan_time']*60;
		$time = time();
		$jiancha_time = $CONFIG['tuan']['tuan_time']/10*60;
		if(file_exists(BASE_PATH.'/tuantime.txt')){
			$up_time = filemtime(BASE_PATH.'/tuantime.txt');
			if($time-$up_time>$jiancha_time){
				 $a =  fopen(BASE_PATH.'/tuantime.txt', 'w');
				 $this->update_guoqi_time($guoqi_time);
			}
		}else{
			$a =  fopen(BASE_PATH.'/tuantime.txt', 'w');
		}
	}	
		
	//更新过期时间
	public function update_guoqi_time($guoqi_time){
		$time = time();
		$max_time = $time - $guoqi_time;
		$itmes = M('tuan_order')->where(array('create_time'=>array('lt',$max_time),'status'=>'0'))->select();
		$array = $orders = array();
		foreach($itmes as $k => $v){
			$array[$v['tuan_id']] += $v['num'];
			$orders[] = $v['order_id'];
		}
		$order_list = implode(',',$orders);
		
		if($res = M('tuan_order')->where(array('order_id'=>array('in',$order_list)))->save(array('status'=>'-1','update_time'=>$time))){
			foreach($array as $k => $v){
				M('tuan')->where(array('tuan_id'=>$k))->setInc('num',$v);
				M('tuan')->where(array('tuan_id'=>$k))->setDec('sold_num',$v);
			}
		}
	}
	
	//抢购库存更新
	public function updateTuanNum($order_id){	
       $list = M('tuan_order')->where(array('order_id'=>$order_id))->select();
       foreach($list as $k =>$v){
		   if($v['option_id']){
			    M('TuanOptions')->where(array('id'=>$v['option_id']))->setDec('total',$v['num']);//减去库存
		   }else{
			    M('tuan')->where(array('tuan_id'=>$v['tuan_id']))->setDec('num',$v['num']);//减去库存
		   }
       }
      return true;
	}
	
	
	
	
	//获取抢购实际价格
	public function get_tuan_need_pay($order_id,$user_id,$type){
        $order_id = (int)$order_id;
        $order = M('tuan_order')->find($order_id);
		$users = M('users')->find($user_id);
        if(empty($order) || $order['status'] != 0 || $order['user_id'] != $user_id){
            return false;
        }else{
			$tuan = M('tuan')->find($order['tuan_id']);
			if(empty($tuan) || $tuan['closed'] == 1 || $tuan['end_date'] < TODAY){
               return false;
            }
			
			$canuse = $tuan['use_integral'] * $order['num'];//实际扣分数量
            $used = 0;
			
            if($users['integral'] < $canuse){
                $used = $users['integral'];
                $users['integral'] = 0;
            }else{
                $used = $canuse;
                $users['integral'] -= $canuse;
            }
			
            //D('Users')->save(array('user_id' => $user_id, 'integral' => $users['integral']));

			//如果后台没有开启积分比例按照原来的积分设置，如果以开启乘以比例数
			$config = D('Setting')->fetchAll();
			
			if($config['integral']['buy'] ==0 ){
				$use_integral = $used;
			}elseif($config['integral']['buy'] == 10){
				$use_integral = $used * $config['integral']['buy'];
			}elseif($config['integral']['buy'] == 100){
				$use_integral = $used * $config['integral']['buy'];
			}else{
				$use_integral = 0;
			}
			
		
			//这里加上判断，就是不管你怎么样，积分兑换的金额大于抢购结算价就返回失败
			if($use_integral == 0 || $use_integral > ($order['total_price'] - $order['mobile_fan'])  || $users['integral'] < $use_integral){
				$order['use_integral'] = 0;
			}else{
				$order['use_integral'] = $use_integral;
			}
			
				
			if($type ==1){
				$order['need_pay'] = $order['total_price']  - $order['use_integral']; //PC不减去手机下单立减
			}else{
				$order['need_pay'] = $order['total_price'] - $order['mobile_fan'] - $order['use_integral'];
			}
			
			
			$intro = '购买抢购商品【'.$tuan['title'].'】订单ID【' . $order_id . '】积分抵用';
			D('Users')->addIntegral($user_id,-$order['use_integral'],$intro);
		
		
			
			M('tuan_order')->save(array('order_id' => $order_id, 'use_integral'=>$order['use_integral'],'need_pay' => $order['need_pay']));
			
			return $order['need_pay'];
		}
        return false;
    }
	
	
	
	//退款封装
	public function refund($order_id){
		
        $detail = M('tuan_order')->find($order_id);
		
		if(!$detail){
			$this->error = '抢购劵订单不存在';
			return false;
		}
		if($detail['status'] != 3){
			$this->error = '抢购劵状态不正确';
			return false;
		}
		if($detail['is_used'] != 0){
			$this->error = '抢购劵已核销';
			return false;
		}
		
		$code = M('tuan_code')->where(array('order_id'=>$order_id))->find();
		if($code['status'] == 4){
			$this->error = '抢购劵订单状态不正确';
			return false;
		}
		
		
		if($codes = M('tuan_code')->where(array('order_id'=>$order_id))->save(array('status' =>4))){
		
            if($detail['need_pay'] > 0){
				
				$info = '抢购订单ID【'.$order_id.'】退款余额';
				
				
				$rest = false;
				
				$getConfigKey = getConfigKey('pay');
				if($getConfigKey['tuan_weixin_original_refund'] != 1){
					$rest == false;
				}
				
				$logs = M('PaymentLogs')->where(array('type'=>'tuan',$detail['order_id'],'is_paid'=>1))->find();
				if($logs['return_trade_no'] && $getConfigKey['tuan_weixin_original_refund'] == 1){
					$rest = D('Paymentlogs')->weixinRefund($order_id,$detail['need_pay'],'tuan',$info);//微信退款订单ID，金额，类型,说明
				}
				
				if($rest == false){
					D('Users')->addMoney($detail['user_id'],$detail['need_pay'],$info);
				}
				
            }
			
			
			
            if($detail['use_integral'] > 0){
               D('Users')->addIntegral($detail['user_id'], $detail['use_integral'], '抢购订单ID【'.$order_id.'】退款积分');
            }
			
			
			$num = M('tuan_order')->where(array('tuan_id'=>$detail['tuan_id']))->getField('num');
			M('tuan')->where(array('tuan_id'=>$detail['tuan_id']))->setInc('num',$num);//修复退款后增加库存
			
			
			$res = M('tuan_order')->where(array('order_id'=>$order_id))->save(array('status' =>4));
			
			D('Weixinmsg')->weixinTmplOrderMessage($detail['order_id'],$cate = 1,$type = 4,$status = 4);
			D('Weixinmsg')->weixinTmplOrderMessage($detail['order_id'],$cate = 2,$type = 4,$status = 4);
			return true;
        }else{
			$this->error = '更新抢购卷状态失败';
			return false;
        }
	}
	
	
	
	
	
    public function source(){
        $y = date('Y', NOW_TIME);
        $data = $this->query(" SELECT count(1) as num,is_mobile,FROM_UNIXTIME(create_time,'%c') as m from".$this->getTableName()." where status=1 AND FROM_UNIXTIME(create_time,'%Y') ='{$y}'  group by  is_mobile,FROM_UNIXTIME(create_time,'%c')");
        $showdata = array();
        $mobile = array();
        $pc = array();
        for($i = 1; $i <= 12; $i++){
            $mobile[$i] = 0;
            $pc[$i] = 0;
            foreach($data as $val){
                if($val['m'] == $i){
                    if($val['is_mobile']){
                        $mobile[$i] = $val['num'];
                    }else{
                        $pc[$i] = $val['num'];
                    }
                }
            }
        }
        ksort($mobile);
        ksort($pc);
        $showdata['mobile'] = join(',', $mobile);
        $showdata['pc'] = join(',', $pc);
        return $showdata;
    }
	
	
    public function money_yue(){
        $y = date('Y', NOW_TIME);
        $data = $this->query(" SELECT sum(total_price)/100 as price,FROM_UNIXTIME(create_time,'%c') as m from ".$this->getTableName()." where status=1 AND FROM_UNIXTIME(create_time,'%Y') ='{$y}' group by  FROM_UNIXTIME(create_time,'%c')");
        $showdata = array();
        for($i = 1; $i <= 12; $i++){
            $showdata[$i] = 0;
            foreach($data as $val){
                if($val['m'] == $i){
                    $showdata[$i] = $val['price'];
                }
            }
        }
        ksort($showdata);
        return join(',', $showdata);
    }
	
	
    public function money($bg_time, $end_time, $shop_id){
        $bg_time = (int) $bg_time;
        $end_time = (int) $end_time;
        $shop_id = (int) $shop_id;
        if(!empty($shop_id)){
            $data = $this->query(" SELECT sum(total_price)/100 as price,FROM_UNIXTIME(create_time,'%m%d') as d from  " . $this->getTableName() . "   where status=1 AND create_time >= '{$bg_time}' AND create_time <= '{$end_time}' AND shop_id = '{$shop_id}'  group by  FROM_UNIXTIME(create_time,'%m%d')");
        }else{
            $data = $this->query(" SELECT sum(total_price)/100 as price,FROM_UNIXTIME(create_time,'%m%d') as d from  " . $this->getTableName() . "   where status=1 AND create_time >= '{$bg_time}' AND create_time <= '{$end_time}'  group by  FROM_UNIXTIME(create_time,'%m%d')");
        }
        $showdata = array();
        $days = array();
        for($i = $bg_time; $i <= $end_time; $i += 86400){
            $days[date('md', $i)] = '\'' . date('m月d日', $i) . '\'';
        }
        $price = array();
        foreach($days as $k => $v){
            $price[$k] = 0;
            foreach($data as $val){
                if($val['d'] == $k){
                    $price[$k] = $val['price'];
                }
            }
        }
        $showdata['d'] = join(',', $days);
        $showdata['price'] = join(',', $price);
        return $showdata;
    }
	
	
    public function weeks(){
        $y = NOW_TIME - 86400 * 6;
        $data = $this->query(" SELECT count(1) as num,is_mobile,FROM_UNIXTIME(create_time,'%d') as d from  __TABLE__ where status=1 AND create_time >= '{$y}' group by is_mobile,FROM_UNIXTIME(create_time,'%d')");
        $showdata = array();
        $mobile = array();
        $pc = array();
        $days = array();
        for($i = 0; $i <= 6; $i++){
            $d = date('d', $y + $i * 86400);
            $mobile[$i] = 0;
            $pc[$i] = 0;
            $days[] = '\'' . $d . '号\'';
            foreach($data as $val){
                if($val['d'] == $d){
                    if ($val['is_mobile']) {
                        $mobile[$i] = $val['num'];
                    }else{
                        $pc[$i] = $val['num'];
                    }
                }
            }
        }
        ksort($mobile);
        ksort($pc);
        $showdata['mobile'] = join(',', $mobile);
        $showdata['pc'] = join(',', $pc);
        $showdata['days'] = join(',', $days);
        return $showdata;
    }
}