<?php

class EleorderModel extends CommonModel{
    protected $pk = 'order_id';
    protected $tableName = 'ele_order';
	
    protected $cfg = array(
        0 => '待付款',
        1 => '待接单',
        2 => '配送中',
		3 => '退款中',
		4 => '已退款',		
        8 => '已完成',
    );
	
	
	public function getEleOrderType(){
        return array(
            '1' => '在线下单',
            '2' => '用户自提',
        );
    }
	
	
	public function getError(){
        return $this->error;
    }
	
	

	
	//更新库存+多规格的
	public function updateProductNum($order_id){	
       $list = M('EleOrderProduct')->where(array('order_id'=>$order_id))->select();
       foreach($list as $k =>$v){
		   if($v['option_id']){
			    M('EleProductOptions')->where(array('id'=>$v['option_id']))->setDec('total',$v['num']);//减去库存
		   }else{
			   M('EleProduct')->where(array('product_id'=>$v['product_id']))->setDec('num',$v['num']);//减去库存
		   }
       }
      return true;
	}


    public function checkIsNew($uid, $shop_id){
        $uid = (int) $uid;
        $shop_id = (int) $shop_id;
        return $this->where(array('user_id' => $uid, 'shop_id' => $shop_id,'status' =>array('gt',0),  'closed' => 0))->count();
    }

    public function getCfg(){
        return $this->cfg;
    }
	
	
	//检测用户收获地址是否超区
	public function getAddrDistance($addr_id,$shop_id,$order_id){
		
		$EleOrder = M('EleOrder')->where(array('order_id'=>$order_id))->find();
		if($EleOrder['orderType'] == 1){
			$Useraddr = D('Useraddr')->where(array('addr_id'=>$addr_id))->find();
			$Shop = D('Shop')->find($shop_id);
			$Ele = D('Ele')->where(array('shop_id'=>$shop_id))->find();
			if(empty($Useraddr['lat'])){
				$this->error = '当前收货地址配置有误请点击地址修改位置坐标信息';
				return false;
			}
			$getAddrDistance = getAddrDistance($Useraddr['lat'], $Useraddr['lng'], $Shop['lat'], $Shop['lng']);
			if(empty($Ele['is_radius'])){
				$radius = 5000;
			}else{
				$radius = $Ele['is_radius']*1000;
			}
			if($getAddrDistance >= $radius  &&  !empty($Ele['is_radius'])){
				$this->error = '您的收货地址未在配送范围之内，该商家配送范围【'.round($radius/1000,2).'】KM';
				return false;
			}
		}else{
			return true;
		}
		return true;
	}

		

	//根据订单ID获取外卖订单名称
	public function get_ele_order_product_name($order_id){
		 $order = D('Eleorder')->find($order_id);
         $product_ids = D('Eleorderproduct')->where('order_id=' . $order_id)->getField('product_id', true);
         $product_ids = implode(',', $product_ids);
         $map = array('product_id' => array('in', $product_ids));
         $product_name = D('Eleproduct')->where($map)->getField('product_name', true);
         $product_name = implode(',', $product_name);
		return $product_name;
    }
		

	
	
	
			
			
		
	
	 //计算预计送达时间
    public function sendTime($order_id){
        $order_info = M('DeliveryOrder')->where(array('type_order_id'=>$order_id,'type'=>1))->find();
        $default_info = M('Delivery')->where(array('user_id'=>$order_info['delivery_id']))->field('lng,lat')->find();
        $addr_info = M('UserAddr')->where(array('addr_id'=>$order_info['addr_id']))->field('lat,lng')->find();
        $shop_coor = array('lng'=>$order_info['lng'], 'lat'=>$order_info['lat']);
        $user_coor = array('lng'=>$addr_info['lng'], 'lat'=>$addr_info['lat']);
        $delivery_coor = array('lng'=>$default_info['lng'], 'lat'=>$default_info['lat']);
        $dist1 = get_dist_info($delivery_coor['lat'], $delivery_coor['lng'], $shop_coor['lat'],$shop_coor['lng']);
        $dist2 = get_dist_info($shop_coor['lat'], $shop_coor['lng'], $user_coor['lat'],$user_coor['lng']);
        $total_time = $dist1['time_value']+$dist2['time_value'];
        $ok_date = date('H:i',time()+$total_time);
        return $ok_date;
    }
	
	
	
					
	
	
	public function ele_month_num($order_id){	
   	   $order_id = (int) $order_id;
       $Eleorderproduct = D('Eleorderproduct')->where('order_id =' . $order_id)->select();
       foreach ($Eleorderproduct as $k => $v){
       	 D('Eleproduct')->updateCount($v['product_id'], 'sold_num', $v['num']);
		 D('Ele')->updateCount($v['shop_id'], 'sold_num', $v['num']);
       }
      return TRUE;
	}
	
	
	
	//获取当前订单是否达到免邮条件
	public function get_logistics($total_money,$shop_id){	
	   $Ele = D('Ele')->find($shop_id);
	   if($Ele['logistics_full'] > 10){
		   if($total_money >= $Ele['logistics_full']){
			   return  $Ele['logistics'];
			}else{
				return false; 
		    }
	   }else{
		  return false; 
	   }
	}
	
	//获取当前订单满减
	public function get_full_reduce_price($total_money,$shop_id){	
	   $Ele = D('Ele')->find($shop_id);
	   if($Ele['is_full'] == 1){
		   //第一种可能
		   if(!empty($Ele['order_price_full_1']) && !empty($Ele['order_price_full_2'])){
			   //中间
			   if($total_money >= $Ele['order_price_full_1'] && $total_money <= $Ele['order_price_full_2']){
				   if($Ele['order_price_reduce_1'] > 0){
					  return $Ele['order_price_reduce_1'];   
				   }
				}
				//大于第二个满减
				if($total_money >= $Ele['order_price_full_2']){
				   if($Ele['order_price_reduce_2'] > 0){
					  return $Ele['order_price_reduce_2'];   
				   }
				}
				if($total_money <= $Ele['order_price_full_1']){
				   return 0; //不返回
				}
			}
			//第二种可能
			if(!empty($Ele['order_price_full_1'])){
			   if($total_money >= $Ele['order_price_full_1']){
				   if($Ele['order_price_reduce_1'] > 0){
					  return $Ele['order_price_reduce_1'];   
				   }
				}
			   if($total_money <= $Ele['order_price_full_1']){
				   return 0; //不返回
				}
			}
			return 0; 
	   }else{
		  return 0; 
	   }
	}

	
	
}

