<?php
class EledianpingModel extends CommonModel{

    protected $pk = 'order_id';
    protected $tableName = 'ele_dianping';

    public function check($order_id, $user_id){
        $data = $this->find(array('where' => array('order_id' => (int) $order_id, 'user_id' => (int) $user_id)));
        return $this->_format($data);
    }
	
	public function getShopScore($shop_id){
        $count = $this->where(array('shop_id' =>$shop_id,'closed'=>0))->count();
		$sum = $this->where(array('shop_id' =>$shop_id,'closed'=>0))->sum('score');
		$sum = ($sum/$count)*20;
		$sum = round($sum/1,0);
        return $sum;
    }
	
	public function getWxappShopScore($shop_id){
        $count = $this->where(array('shop_id' =>$shop_id,'closed'=>0))->count();
		$sum = $this->where(array('shop_id' =>$shop_id,'closed'=>0))->sum('score');
		$sum = round(($sum/$count)/1,0);
        return $sum;
    }

}