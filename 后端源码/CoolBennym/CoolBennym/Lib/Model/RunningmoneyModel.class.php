<?php
class RunningmoneyModel extends CommonModel{
    protected $pk   = 'money_id';
    protected $tableName =  'running_money';
	
	//写入外卖配送费
	public function add_delivery_logistics($order_id,$logistics,$type){
		
		
		if($type ==1){
			$map = (array('type_order_id'=>$order_id,'type'=>1,'closed'=>0));
			$typeName = '外卖';
			$typeNames = 'ele';
		}elseif($type ==0){
			$map = (array('type_order_id'=>$order_id,'type'=>0,'closed'=>0));
			$typeName = '商城';
			$typeNames = 'goods';
		}elseif($type ==3){
			$map = (array('type_order_id'=>$order_id,'type'=>3,'closed'=>0));
			$typeName = '菜市场';
			$typeNames = 'market';
		}elseif($type ==4){
			$map = (array('type_order_id'=>$order_id,'type'=>4,'closed'=>0));
			$typeName = '便利店';
			$typeNames = 'store';
		}
		
		
		$do = D('DeliveryOrder')->where($map)->find();	
		
		$delivery = D('Delivery')->where(array('id'=>$do['delivery_id']))->find();
			
		$info = '配送ID【'.$do['order_id'].'】类型：'.$typeName.'原始订单ID【'.$order_id.'】配送费结算【'.round($logistics/100,2).'】元';
		
		//已经结算不再结算
		if($RunningMoney = M('RunningMoney')->where((array('order_id'=>$order_id,'type'=>$typeNames,'user_id'=>$delivery['user_id'])))->find()){
			return true;
		}
		
		
		if(!empty($do) && $logistics > 0){
            $this->add(array(
				'city_id' => $do['city_id'], 
				'area_id' => $do['area_id'], 
				'business_id' => $do['business_id'],
				'shop_id' => $do['shop_id'],  
				'running_id' => $order_id, 
				'order_id' => $order_id, 
				'delivery_id' => $delivery['id'], 
				'user_id' => $delivery['user_id'], 
				'money' => $logistics, 
				'type' => $typeNames, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(), 
				'intro' => $info
			));
            D('Users')->addMoney($delivery['user_id'],$logistics,$info);  //写入配送员余额
       }
        return true;
    }
	
	
	//写入商城配送费
	public function add_express_price($order_id,$express_price,$type){
		$order = D('Order')->find($order_id);	
		
		$do = D('DeliveryOrder')->where(array('type_order_id'=>$order_id,'type'=>0))->find();	
		$delivery = D('Delivery')->where(array('id'=>$do['delivery_id']))->find();	
		$info = '商城订单ID【'.$order_id.'】【结算给配送员运费'.round($do['logistics_price']/100,2).'】元';
		
		//已经结算不再结算
		if($RunningMoney = M('RunningMoney')->where((array('order_id'=>$order_id,'type'=>'goods','user_id'=>$delivery['user_id'])))->find()){
			return true;
		}
		
		
		
		if($delivery && $do['logistics_price'] > 0){
             $this->add(array(
			 	'city_id' => $do['city_id'], 
				'area_id' => $do['area_id'], 
				'business_id' => $do['business_id'],
				'shop_id' => $do['shop_id'],  
				'running_id' => $order_id, 
				'order_id' => $order_id, 
				'delivery_id' => $delivery['id'], 
				'user_id' => $delivery['user_id'], 
				'money' => $do['logistics_price'], 
				'type' => 'goods', 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(), 
				'intro' => $info
			));
            D('Users')->addMoney($delivery['user_id'], $do['logistics_price'],$info);  //写入配送员余额
       }
      return true;
    }

}