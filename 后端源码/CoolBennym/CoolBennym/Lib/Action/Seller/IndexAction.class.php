<?php
class IndexAction extends CommonAction{
	
	
    public function index(){
		
        $counts = array();
        $bg_time = strtotime(TODAY);
		$str = '-1 day';
        $bg_time_yesterday = strtotime(date('Y-m-d', strtotime($str)));
        $counts['money_day'] = (int) M('ShopMoney')->where(array('create_time' => array(array('ELT', NOW_TIME), array('EGT', $bg_time)), 'shop_id' => $this->shop_id))->sum('money');
        $counts['money_day_yesterday'] = (int) M('ShopMoney')->where(array('create_time' => array(array('ELT', $bg_time), array('EGT', $bg_time_yesterday)), 'shop_id' => $this->shop_id))->sum('money');
        $this->assign('counts', $counts);
		
		
		$count['ele_order'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'closed'=>0))->count();
		$count['ele_order_2'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>2,'closed'=>0))->count();
		$count['ele_order_8'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>8,'closed'=>0))->count();
		$count['ele_order_16'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>16,'closed'=>0))->count();
		$count['ele_order_32'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>32,'closed'=>0))->count();
		$count['ele_order_64'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>64,'closed'=>0))->count();
		$count['ele_order_128'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>128,'closed'=>0))->count();
		$this->assign('count', $count);
		
		
        $this->display();
    }
	
	
    public function dingwei(){
        $lat = $this->_get('lat', 'htmlspecialchars');
        $lng = $this->_get('lng', 'htmlspecialchars');
        cookie('lat', $lat);
        cookie('lng', $lng);
        die(NOW_TIME);
    }
	
	
	
}