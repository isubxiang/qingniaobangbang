<?php
class ShopModel extends CommonModel{
    protected $pk = 'shop_id';
    protected $tableName = 'shop';
	
	//商家有效期
	public function getEndDate(){
        return array(
			1 => '1-30天', 
			2 => '31-60天', 
			3 => '61-90天', 
			4 => '90天以上', 
		);
    }
	
	//商家客服
	public function getTypes(){
        return array(
			1 => 'POP800', 
		);
    }
	
	
	public function buildShopQrcode($shop_id,$size = '0'){
		$config = D('Setting')->fetchAll();
		$size = $size ? $size : '15';
        $url = U('wap/shop/detail', array('shop_id'=>$shop_id,'t' => NOW_TIME,'sign' =>md5($shop_id.C('AUTH_KEY').NOW_TIME)));
        $token = 'shop_id_' . $shop_id;
        $file = ToQrCode($token, $url,$size,'shop');
		M('Shop')->save(array('shop_id'=>$shop_id,'qrcode'=>$file));
        return true;
    }
	
		//获取列表图片开始
	public function getShopListPics($shop_id){
		$list = M('ShopPic')->where(array('shop_id'=>$shop_id,'audit'=>1))->limit(0,30)->select();
		foreach($list as $k => $val){
			$photos[$k] = $val['photo'];
		}
		$Shop = D('Shop')->find($shop_id);
		$arr = explode(",",$Shop['photo'].','.$Shop['logo']);
		if($photos){
			$array = array_merge($arr,$photos);
		}else{
			$array = $arr;
		}
		return $array;
	}
	//审核
	public function shop_audit($shop_id){
		$config = D('Setting')->fetchAll();
		if($detail = $this->find($shop_id)){
			
				if($config['sms_shop']['shop_audit_sms']){
					if(!D('Smsshop')->where(array('type'=>'shop','shop_id'=>$shop_id))->find()){
						$data = array();
						$data['user_id'] = $detail['user_id'];
						$data['shop_id'] = $shop_id;
						$data['type'] = shop;
						$data['num'] = $config['sms_shop']['shop_audit_sms'];
						$data['status'] = 0;
						$data['create_time'] = NOW_TIME;
						$data['create_ip'] = get_client_ip();
						if(D('Smsshop')->add($data)){
							return true;
						}else{
							$this->error = '给商户添加短信数据库更新失败';
							return false;
						}
					}else{
						return true;
					}
				
			}
		}else{
			$this->error = '商家不存在';
			return false;
		}
    }
	
	
	
    public function get_tj($city_id, $keyword){
        $map = array('is_ding' => 1, 'audit' => 1, 'closed' => 0, 'city_id' => $city_id, 'shop_name' => array('LIKE', '%' . $keyword . '%'));
        $shop = $this->where($map)->order(array('orderby' => 'asc', 'score' => 'desc', 'view' => 'desc'))->limit(0, 8)->select();
        $shop_ids = $cate_ids = $arr = array();
        foreach ($shop as $val) {
            $shop_ids[] = $val['shop_id'];
            $cate_ids[] = $val['cate_id'];
        }
        if ($shop_ids) {
            $obj = D('Shopdetails');
            $setting = D('Shopdingsetting');
            $arr['details'] = $obj->itemsByIds($shop_ids);
            $arr['set'] = $setting->itemsByIds($shop_ids);
        }
        if ($cate_ids) {
            $cate = D('Shopcate');
            $arr['cat'] = $cate->itemsByIds($cate_ids);
        }
        $arr['shop'] = $shop;
        return $arr;
    }
	
    public function countDingShop($where){
        $sql = "select  s.*,d.price from  " . $this->tablePrefix . $this->tableName . " s join " . $this->tablePrefix . 'shop_details' . " d  ON (s.shop_id = d.shop_id)" . " where " . $where;
        $count = count($this->query($sql));
        return $count;
    }
    public function get_ding_shop($where, $order, $start, $limit){
        $arr = $shop_ids = $cate_ids = $tem = array();
        $sql = "select  s.*,d.price from  " . $this->tablePrefix . $this->tableName . " s join " . $this->tablePrefix . 'shop_details' . " d  ON (s.shop_id = d.shop_id)" . " where " . $where . ' ORDER BY ' . $order . ' limit ' . $start . ',' . $limit;
        $shop = $this->query($sql);
        foreach ($shop as $val) {
            $shop_ids[] = $val['shop_id'];
            $cate_ids[] = $val['cate_id'];
        }
        if ($shop_ids) {
            $setting = D('Shopdingsetting');
            $tem['set'] = $setting->itemsByIds($shop_ids);
        }
        if ($cate_ids) {
            $cate = D('Shopcate');
            $tem['cat'] = $cate->itemsByIds($cate_ids);
        }
        $tem['shop'] = $shop;
        return $tem;
    }
	//检测是否自己购买
	public function check_shop_user_id($shop_id, $user_id){
        $check_shop_user = $this->where(array('shop_id'=>$shop_id,'user_id'=>$user_id))->find();
        if(!empty($check_shop_user)){
		   return false;
		}else{
		   return true;   
	    }
    }
	
	//获取推荐人昵称
	public function get_guide_name($user_id){
        $Users = D('Users')->find($user_id);
        if(!empty($Users['nickname'])){
		   return $Users['nickname'];
		}else{
		   return $Users['account'];   
	    }
    }
	//获取商家中结算金额，后期优化到月，年get_shop_sales
	public function get_shop_sales($shop_id){
        $sales = D('Shopmoney')->where(array('shop_id'=>$shop_id))->sum('money');
		return $sales;
    }
	
    public function getshop($order, $city_id){
        $shop = $this->where('is_ding = 1 and city_id=  ' . $city_id)->order(array($order => 'desc'))->limit(0, 6)->select();
        $shop_ids = $cate_ids = $get_shop = array();
        foreach ($shop as $val) {
            $shop_ids[] = $val['shop_id'];
            $cate_ids[] = $val['cate_id'];
        }
        if ($shop_ids) {
            $obj = D('Shopdetails');
            $setting = D('Shopdingsetting');
            $get_shop['details'] = $obj->itemsByIds($shop_ids);
            $get_shop['set'] = $setting->itemsByIds($shop_ids);
        }
        if ($cate_ids) {
            $cate = D('Shopcate');
            $get_shop['cat'] = $cate->itemsByIds($cate_ids);
        }
        $get_shop['shop'] = $shop;
        return $get_shop;
    }
    public function getbuyshopID($shop_id){
        $shop = $this->where('is_ding = 1 and shop_id=' . $shop_id)->find();
        $obj = D('Shopdetails');
        $setting = D('Shopdingsetting');
        $get_shop['details'] = $obj->where('shop_id=' . $shop_id)->find();
        $get_shop['set'] = $setting->where('shop_id=' . $shop_id)->find();
        $cate = D('Shopcate');
        $get_shop['cat'] = $cate->where('cate_id=' . $shop['cate_id'])->find();
        $get_shop['shop'] = $shop;
        return $get_shop;
    }
    public function getphoto($shop_id, $photo){
        $obj = D('Shoppic');
        $pic = $obj->field('photo')->where('shop_id=' . $shop_id)->limit(0, 2)->select();
        $photos = array();
        $photos[] = $photo;
        foreach ($pic as $k => $v) {
            $photos[] = $v["photo"];
        }
        return $photos;
    }
    public function gettuan($shop_id){
        $obj = D('Tuan');
        $tuan = $obj->where('audit=1 and closed=0 and shop_id=' . $shop_id)->order(array('create_time' => 'desc'))->find();
        return $tuan;
    }
	public function getcoupon($shop_id){
        $obj = D('Coupon');
        $coupon = $obj->where('audit=1 and closed=0 and shop_id=' . $shop_id)->order(array('create_time' => 'desc'))->find();
        return $coupon;
    }
    public function getShopIdsByTuiId($tui_uid){
        $tui_uid = (int) $tui_uid;
        $datas = $this->where(array('tui_uid' => $tui_uid))->select();
        $return = array();
        foreach ($datas as $v) {
            $return[$v['shop_id']] = $v['shop_id'];
        }
        return $return;
    }
	//检测分站的城市ID是不是对的
	public function fenzhan_check_city_id($shop_id,$city_id){
		if(!empty($shop_id) && !empty($city_id)){
            $detail = $this->find($shop_id);
            if ($detail['city_id'] != $city_id) {
                return false;
            }else{
				return true; 
			}
		}else{
			return true; 
		}
    }
	
			
           
			
			
			
			
    public function CallDataForMat($items) {
        if (empty($items)) {
            return array();
        }
        $obj = D('Shopdetails');
        $sd_ids = array();
        foreach ($items as $k => $val) {
            $sd_ids[$val['shop_id']] = $val['shop_id'];
        }
        $shopdetail = $obj->itemsByIds($sd_ids);
        foreach ($items as $k => $val) {
            $val['shopdetail'] = $shopdetail[$val['shop_id']];
            $items[$k] = $val;
        }
        return $items;
    }
}