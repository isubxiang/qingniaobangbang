
<?php
class UserprofitlogsModel extends CommonModel{
    protected $pk = 'log_id';
    protected $tableName = 'user_profit_logs';
	
	protected function _initialize(){
        parent::_initialize();
		$this->config = D('Setting')->fetchAll();
    }
	
	
	protected $Type = array(
        'goods' => '商城',
		'appoint' => '家政',
		'tuan' => '抢购',
		'ele' => '外卖',
		'booking'  => '订座',
		'breaks'=>'优惠买单',
		'hotel' =>'酒店',
		'farm'=>'农家乐', 
		'rank'=>'会员购买等级', 
		'grade'=>'商家购买等级', 
    );
	
	protected $separate = array(
        1 => '已分成',
        2 => '已取消',
    );

    public function getType(){
        return $this->Type;
    }

    public function getSeparate(){
        return $this->separate;
    }
	
	//反转数组
	public function get_money_type($type){
		$types = $this->getType();
		$result = array_flip($types);//反转数组
		$types = array_search($type, $result);
		if(!empty($types)){
			return $types;
		}else{
			return false;
		}
        return false;
	}
	
	
	protected $_type = array(
		'tuan' => '抢购', 
		'ele' => '外卖', 
		'farm' => '农家乐', 
		'goods' => '商城', 
		'booking' => '订座', 
		'hotel' => '酒店',
		'appoint' => '家政',
		'breaks' => '优惠买单',
	);
	

	
	//分销判断权限
	public function determinePower($uid){
	
		$Users = D('Users')->find($uid);
		
		if($this->config['profit']['profit_min_rank_id'] == 0){
			return true;
		}
		
		$rank = D('Userrank')->find($this->config['profit']['profit_min_rank_id']);//后台分销配置
		$userRank = D('Userrank')->find($Users['rank_id']);//会员的分销配置
 		
        if($rank){
            if($userRank && $userRank['integral'] >= $rank['integral']){
                return true;
            }else{
               return false;
            }
			return false;
        }
		return true;
	}
	
	
	
	
	//给推荐人升级
	public function profitUserRankUpdate($user_id,$fuid){
		if(!$user_id){
			return false;
		}
		if(!$fuid){
			return false;
		}
		$f = M('users')->where(array('user_id'=>$fuid))->find();
		if(!$f){
			return false;
		}
		
		$a1 = $this->updateRank($fuid);//给推荐人升级
		$a2 = $this->updateRank($f['fuid1']);//给推荐人的推荐人升级
		$a3 = $this->updateRank($f['fuid2']);//给推荐人的推荐人的推荐人升级
		//$a4 = $this->updateRank($f['fuid3']);//给推荐人的推荐人的推荐人的推荐人升级
        return true;
	}
	
	
	//正式升级
	public function updateRank($user_id){
		if(!$user_id){
			return false;
		}
		
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		if(!$users){
			return false;
		}
		
		//自己的等级
		$rank_id = $users['rank_id']+1;
		//等级详情
		$rank = M('user_rank')->where(array('rank_id'=>$rank_id))->find();
		//如果等级不存在
		if(!$rank){
			return false;
		}
		//寻找自己的下级总人数非直推
		$getUserFuidCount = $this->getUserFuidCount($user_id);
		
		//升级成功
		if($rank['number'] > 0 && $getUserFuidCount >= $rank['number']){
			$res = M('users')->where(array('user_id'=>$user_id))->save(array('rank_id'=>$rank_id));
			//升级日志暂时不写了
			return true;
		}
        return true;
	}
	
	//查看自己消费多少给自己升级
	public function profitUserTotalRankUpdate($user_id,$log_id = 0){
		if(!$user_id){
			return true;
		}
		
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		if(!$users){
			return true;
		}
		
		//自己的等级
		$rank_id = $users['rank_id']+1;
		//自己的等级详情
		$rank = M('user_rank')->where(array('rank_id'=>$rank_id))->find();
		//如果等级不存在
		if(!$rank){
			return true;
		}
		
		//统计当前会员总消费支付数据太大就完蛋了
		$need_pay = (int)M('payment_logs')->where(array('user_id'=>$user_id,'is_paid'=>1))->sum('need_pay');

		//升级成功
		if($rank['total'] > 0 && $need_pay >= $rank['total']){
			$res = M('users')->where(array('user_id'=>$user_id))->save(array('rank_id'=>$rank_id));
			//升级日志暂时不写了
			return true;
		}
        return true;
	}
	
	
	//获取寻找自己的下级总人数非直推
	public function getUserFuidCount($fuid){
		$c1 = (int)M('users')->where(array('fuid1'=>$fuid))->count();
		$c2 = (int)M('users')->where(array('fuid2'=>$fuid))->count();
		$c3 = (int)M('users')->where(array('fuid3'=>$fuid))->count();
		$c4 = (int)M('users')->where(array('fuid4'=>$fuid))->count();
		$c5 = (int)M('users')->where(array('fuid5'=>$fuid))->count();
		$c6 = (int)M('users')->where(array('fuid6'=>$fuid))->count();
		$c7 = (int)M('users')->where(array('fuid7'=>$fuid))->count();
		$c8 = (int)M('users')->where(array('fuid8'=>$fuid))->count();
		$c9 = (int)M('users')->where(array('fuid9'=>$fuid))->count();
		$c10 = (int)M('users')->where(array('fuid10'=>$fuid))->count();
		$c11 = (int)M('users')->where(array('fuid11'=>$fuid))->count();
		$c12 = (int)M('users')->where(array('fuid12'=>$fuid))->count();
		$c13 = (int)M('users')->where(array('fuid13'=>$fuid))->count();
		$c14 = (int)M('users')->where(array('fuid14'=>$fuid))->count();
		$c15 = (int)M('users')->where(array('fuid15'=>$fuid))->count();
		$c16 = (int)M('users')->where(array('fuid16'=>$fuid))->count();
		$c17 = (int)M('users')->where(array('fuid17'=>$fuid))->count();
		$c18 = (int)M('users')->where(array('fuid18'=>$fuid))->count();
		$c19 = (int)M('users')->where(array('fuid19'=>$fuid))->count();
		$c20 = (int)M('users')->where(array('fuid20'=>$fuid))->count();
		return $c1 + $c2 + $c3 + $c4 + $c6 + $c6 + $c7 + $c8 + $c9 + $c0 + $c11 + $c12 + $c3 + $c14 + $c15 + $c16 + $c17 + $c18 + $c19 + $c20;
	}
	
	
	
	//新版N级分销
	public function profitUsers($order_id,$id,$shop_id,$jiesuan_price, $type){
		
		//p($order_id.'----'.$id.'----'.$shop_id.'----'.$jiesuan_price.'----'. $type);die;
		
		
		$shop = M('shop')->where(array('shop_id'=>$shop_id))->find();
		if($shop['is_profit'] == 0){
			return false;
		}
		
		//回去会员ID，分销总价
		list($user_id,$money)= $this->getModelMoneyUser($order_id,$id,$jiesuan_price,$type);
		if($money <= 0){
			return false;
		}
		if(!$user_id){
			return false;
		}
		$Users = M('users')->find($user_id);
		if(!$Users){
			return false;
		}	
			
			
			
		//如果有上级或者上级具备分销权限
		$fuser1 = M('users')->find($Users['fuid1']);
		if($Users['fuid1'] && $fuser1 && (true == $this->determinePower($Users['fuid1']))){
			
			$order = M('order')->find($order_id);
			$goods_profit = M('goods_profit')->find($order['goods_id']);
			
			if($type == 'goods' && $goods_profit['profit_enable'] == 1 && ($goods_profit['profit_rate1']*100) > 1){
				$money1 = round($goods_profit['profit_rate1']*$money/100);
				$rate1 = $goods_profit['profit_rate1'];
				$info1 = $this->_type[$type]. '订单ID:'.$order_id.',一级分成:'.round($money1/100,2).'元，商品独立分成比例【'.$rate1.'】%';
			}else{
				//开启等级分成
				if($this->config['profit']['profit_user_type'] == 1){
					$rank1 = M('user_rank')->where(array('rank_id'=>$fuser1['rank_id']))->find();
					$rate1 = round($rank1['rate1']/100,2);
				}else{
					$rate1 = $this->config['profit']['currency_profit_rate1'];
				}
				$money1 = round($rate1*$money/100);
				$info1 = $this->_type[$type].'订单ID:'.$order_id.'1级分成:'.round($money1/100,2).'元，分成比例【'.$rate1.'】%';
			}
			
			if($money1 > 0){
				D('Users')->addMoney($Users['fuid1'],$money1,$info1);
				D('Users')->addProfit($Users['fuid1'],$order_type = 0,$type,$order_id,$shop_id,$money1,$is_separate = '1',$info1);
			}
		}
		
		$fuser2 = M('users')->find($Users['fuid2']);
		if($Users['fuid2'] && $fuser2 && (true == $this->determinePower($Users['fuid2']))){
			
			$order = M('order')->find($order_id);
			$goods_profit = M('goods_profit')->find($order['goods_id']);
			
			if($type == 'goods' && $goods_profit['profit_enable'] == 1 && ($goods_profit['profit_rate2']*100) > 1){
				$money2 = round($goods_profit['profit_rate2']*$money/100);
				$rate2 = $goods_profit['profit_rate2'];
				$info2 = $this->_type[$type]. '订单ID:'.$order_id.',2级分成:'.round($money2/100,2).'元，商品独立分成比例【'.$rate1.'】%';
			}else{
				//开启等级分成
				if($this->config['profit']['profit_user_type'] == 1){
					$rank2 = M('user_rank')->where(array('rank_id'=>$fuser2['rank_id']))->find();
					$rate2 = round($rank2['rate2']/100,2);
				}else{
					$rate2 = $this->config['profit']['currency_profit_rate2'];
				}
				$money2 = round($rate2*$money/100);
				$info2 = $this->_type[$type].'订单ID:'.$order_id.'1级分成:'.round($money2/100,2).'元，分成比例【'.$rate2.'】%';
			}
			
			if($money2 > 0){
				D('Users')->addMoney($Users['fuid2'],$money2,$info2);
				D('Users')->addProfit($Users['fuid2'],$order_type = 0,$type,$order_id,$shop_id,$money2,$is_separate = '1',$info2);
			}
		}
				
				
		$fuser3 = M('users')->find($Users['fuid3']);
		if($Users['fuid3'] && $fuser3 && (true == $this->determinePower($Users['fuid3']))){
			
			$order = M('order')->find($order_id);
			$goods_profit = M('goods_profit')->find($order['goods_id']);
			
			if($type == 'goods' && $goods_profit['profit_enable'] == 1 && ($goods_profit['profit_rate3']*100) > 1){
				$money3 = round($goods_profit['profit_rate3']*$money/100);
				$rate3 = $goods_profit['profit_rate3'];
				$info3 = $this->_type[$type]. '订单ID:'.$order_id.',3级分成:'.round($money1/100,2).'元，商品独立分成比例【'.$rate3.'】%';
			}else{
				//开启等级分成
				if($this->config['profit']['profit_user_type'] == 1){
					$rank3 = M('user_rank')->where(array('rank_id'=>$fuser3['rank_id']))->find();
					$rate3 = round($rank3['rate3']/100,2);
				}else{
					$rate3 = $this->config['profit']['currency_profit_rate3'];
				}
				$money3 = round($rate3*$money/100);
				$info3 = $this->_type[$type].'订单ID:'.$order_id.'3级分成:'.round($money3/100,2).'元，分成比例【'.$rate3.'】%';
			}
			
			if($money3 > 0){
				D('Users')->addMoney($Users['fuid3'],$money3,$info3);
				D('Users')->addProfit($Users['fuid3'],$order_type = 0,$type,$order_id,$shop_id,$money3,$is_separate = '1',$info3);
			}
		}
		return $money1 + $money2 + $money3;//返回分成金额
	}
	
	
	//获取上级跟自己等级一致的
	public function getUserRank($user_id,$rank_id){
		$Users = M('Users')->find($user_id);
		
		if($Users['fuid1']){
			$fuid1 = M('Users')->find($Users['fuid1']);
			if($fuid1['rank_id'] == $rank_id){
				$uid = $Users['fuid1'];
			}
		}
		
		if($Users['fuid2']){
			$fuid2 = M('Users')->find($Users['fuid2']);
			if($fuid2['rank_id'] == $rank_id){
				$uid = $Users['fuid2'];
			}
		}
		if($Users['fuid3']){
			$fuid3 = M('Users')->find($Users['fuid3']);
			if($fuid3['rank_id'] == $rank_id){
				$uid = $Users['fuid3'];
			}
		}
		if($Users['fuid4']){
			$fuid4 = M('Users')->find($Users['fuid4']);
			if($fuid4['rank_id'] == $rank_id){
				$uid = $Users['fuid4'];
			}
		}
		if($Users['fuid5']){
			$fuid5 = M('Users')->find($Users['fuid5']);
			if($fuid5['rank_id'] == $rank_id){
				$uid = $Users['fuid5'];
			}
		}
		if($Users['fuid6']){
			$fuid6 = M('Users')->find($Users['fuid6']);
			if($fuid6['rank_id'] == $rank_id){
				$uid = $Users['fuid6'];
			}
		}
		if($Users['fuid7']){
			$fuid7 = M('Users')->find($Users['fuid7']);
			if($fuid7['rank_id'] == $rank_id){
				$uid = $Users['fuid7'];
			}
		}
		if($Users['fuid8']){
			$fuid8 = M('Users')->find($Users['fuid8']);
			if($fuid8['rank_id'] == $rank_id){
				$uid = $Users['fuid8'];
			}
		}
		if($Users['fuid9']){
			$fuid9 = M('Users')->find($Users['fuid9']);
			if($fuid9['rank_id'] == $rank_id){
				$uid = $Users['fuid9'];
			}
		}
		if($Users['fuid10']){
			$fuid10 = M('Users')->find($Users['fuid10']);
			if($fuid10['rank_id'] == $rank_id){
				$uid = $Users['fuid10'];
			}
		}
		if($Users['fuid11']){
			$fuid11 = M('Users')->find($Users['fuid11']);
			if($fuid11['rank_id'] == $rank_id){
				$uid = $Users['fuid11'];
			}
		}
		if($Users['fuid12']){
			$fuid6 = M('Users')->find($Users['fuid12']);
			if($fuid12['rank_id'] == $rank_id){
				$uid = $Users['fuid12'];
			}
		}
		if($Users['fuid13']){
			$fuid6 = M('Users')->find($Users['fuid13']);
			if($fuid13['rank_id'] == $rank_id){
				$uid = $Users['fuid13'];
			}
		}
		if($Users['fuid14']){
			$fuid14 = M('Users')->find($Users['fuid14']);
			if($fuid14['rank_id'] == $rank_id){
				$uid = $Users['fuid14'];
			}
		}
		if($Users['fuid15']){
			$fuid15 = M('Users')->find($Users['fuid15']);
			if($fuid15['rank_id'] == $rank_id){
				$uid = $Users['fuid15'];
			}
		}
		if($Users['fuid16']){
			$fuid16 = M('Users')->find($Users['fuid16']);
			if($fuid16['rank_id'] == $rank_id){
				$uid = $Users['fuid16'];
			}
		}
		if($Users['fuid17']){
			$fuid17 = M('Users')->find($Users['fuid17']);
			if($fuid17['rank_id'] == $rank_id){
				$uid = $Users['fuid17'];
			}
		}
		if($Users['fuid18']){
			$fuid18 = M('Users')->find($Users['fuid18']);
			if($fuid18['rank_id'] == $rank_id){
				$uid = $Users['fuid18'];
			}
		}
		if($Users['fuid19']){
			$fuid19 = M('Users')->find($Users['fuid19']);
			if($fuid19['rank_id'] == $rank_id){
				$uid = $Users['fuid19'];
			}
		}
		if($Users['fuid20']){
			$fuid20 = M('Users')->find($Users['fuid20']);
			if($fuid20['rank_id'] == $rank_id){
				$uid = $Users['fuid20'];
			}
		}
		return $uid;
	}
	
	
	
	
   //获取会员ID，金额，模型
   public function getModelMoneyUser($order_id,$id,$jiesuan_price, $type){
		if($type == 'ele'){
			if($this->config['profit']['profit_is_ele']){
				$order = M('ele_order')->find($order_id);
				$money = $order['need_pay'];
				M('ele_order')->save(array('order_id'=>$order_id,'is_profit'=>1));	
				return array($order['user_id'],$money);
			}
		}elseif($type == 'farm'){
			if($this->config['profit']['profit_is_farm']){
				$order = M('farm_order')->find($order_id);
				$money = $order['amount']*100;
				M('farm_order')->save(array('order_id'=>$order_id,'is_profit'=>1));	
				return array($order['user_id'],$money);
			 }
		}elseif($type == 'goods'){
			if($this->config['profit']['profit_is_goods']){
				$order =M('order')->find($order_id);
				$money = $order['need_pay'];
				return array($order['user_id'],$money);
			}
		}elseif($type == 'tuan'){
			if($this->config['profit']['profit_is_tuan']){
				$code = M('tuan_code')->find($id);
				$money = $Tuancode['real_money'];
				M('tuan_code')->save(array('code_id'=>$id,'is_profit' =>1));	
				return array($code['user_id'],$money);
			}
		}elseif($type == 'booking'){
			if($config['profit']['profit_is_booking']){
				$order = M('booking_order')->find($order_id);
				$money = $order['amount'];
				M('booking_order')->save(array('order_id'=>$order_id,'is_profit' => 1));	
				return array($order['user_id'],$money);
			}
		}elseif($type == 'hotel'){
			if($this->config['profit']['profit_is_hotel']){
				$order = M('hotel_order')->find($order_id);
				$money = $order['amount']*100;
				M('hotel_order')->save(array('order_id'=>$order_id,'is_profit'=>1));	
				return array($order['user_id'],$money);
			 }
		}elseif($type == 'breaks'){
			if($this->config['profit']['profit_is_breaks']){
				$order = M('breaks_order')->find($order_id);
				return array($order['user_id'],$order['need_pay']*100);
			}
		}
	
   }
   
   
	//会员等级分成
	public function pay_rank_profit_user($user_id, $price,$rank_name){
	
		$Users = M('users')->find($user_id);
		
		if($Users['fuid1']){
			$money1 = round($price*$this->config['profit']['rank_profit_rate1'] / 100);
			if($money1 > 0){
				$info1 = '会员昵称:' . $Users['nickanme'] . '购买会员等级【'.$rank_name.'】一级分成:'. round($money1/100,2);
				$fuser1 = M('users')->find($Users['fuid1']);
				if($fuser1){
					D('Users')->addMoney($Users['fuid1'], $money1, $info1);
					D('Users')->addProfit($Users['fuid1'], $order_type = 0, $type = 'rank', $order_id = '0', $shop_id = '0',$money1, $is_separate = '1', $info1);
				}
			}
		}
		
		if($Users['fuid2']) {
			$money2 = round($price *$this->config['profit']['rank_profit_rate2'] / 100);
			if($money2 > 0) {
				$info2 = '会员昵称:' . $Users['nickanme'] . '购买会员等级【'.$rank_name.'】二级分成: '.round($money2/100, 2);
				$fuser2 = M('users')->find($Users['fuid2']);
				if($fuser2){
					D('Users')->addMoney($Users['fuid2'], $money2, $info2);
					D('Users')->addProfit($Users['fuid2'], $order_type = 0, $type = 'rank', $order_id = '0', $shop_id = '0',$money2, $is_separate = '1', $info2);
				}
			}
		}
		
		if($Users['fuid3']){
			$money3 = round($price * $this->config['profit']['rank_profit_rate3'] / 100);
			if($money3 > 0){
				$info3 = '会员昵称:'.$Users['nickanme'].'购买会员等级【'.$rank_name.'】一级分成:'. round($money3/100, 2);
				$fuser3 = M('users')->find($Users['fuid3']);
				if($fuser3){
					D('Users')->addMoney($Users['fuid3'], $money3, $info3);
					D('Users')->addProfit($Users['fuid3'], $order_type = 0, $type = 'rank', $order_id = '0', $shop_id = '0',$money3, $is_separate = '1', $info3);
				}
			}
		}
   }
   
   
   
    //五折卡分成
	public function pay_zhe_profit_user($detail){
		$Users = M('users')->find($detail['user_id']);
		$price = $detail['need_pay'];
		
		if($Users['user_id']){
			$money1 = round($price*$this->config['profit']['zhe_profit_rate1']/100);
			if($money1 > 0){
				$info1 = '会员昵称:'.$Users['nickanme'] . '购买无折卡订单ID【'.$detail['order_id'].'】1级分成:'.round($money1/100,2);
				$fuser1 = M('users')->find($Users['fuid1']);
				if($fuser1){
					D('Users')->addMoney($Users['user_id'],$money1,$info1);
					D('Users')->addProfit($Users['user_id'],$order_type = 0,$type='zhe',$order_id= '0',$shop_id = '0',$money1,$is_separate = '1',$info1);
				}
			}
		}
		
		if($Users['fuid2']){
			$money2 = round($price*$this->config['profit']['zhe_profit_rate2']/100);
			if($money2 > 0){
				$info2 = '会员昵称:'.$Users['nickanme'] . '购买无折卡订单ID【'.$detail['order_id'].'】2级分成:'.round($money2/100,2);
				$fuser2 = M('users')->find($Users['fuid2']);
				if($fuser2){
					D('Users')->addMoney($Users['fuid2'],$money2,$info2);
					D('Users')->addProfit($Users['fuid2'],$order_type = 0,$type='zhe',$order_id= '0',$shop_id = '0',$money2,$is_separate = '1',$info2);
				}
			}
		}
		
		if($Users['fuid3']){
			$money3 = round($price*$this->config['profit']['zhe_profit_rate3']/100);
			if($money3 > 0){
				$info3 = '会员昵称:'.$Users['nickanme'] . '购买无折卡订单ID【'.$detail['order_id'].'】3级分成:'.round($money3/100,2);
				$fuser3 = M('users')->find($Users['fuid3']);
				if($fuser3){
					D('Users')->addMoney($Users['fuid3'],$money3,$info3);
					D('Users')->addProfit($Users['fuid3'],$order_type = 0,$type='zhe',$order_id= '0',$shop_id = '0',$money3,$is_separate = '1',$info3);
				}
			}
		}
		
	
   }
   
   
    //商家等级购买分成
	public function buy_shop_grade_profit_user($shop_id,$price,$grade_name){
		
		$shop = D('Shop')->find($shop_id);
		$Users = M('users')->find($Shop['user_id']);
		
		if($Users['fuid1']){
			$money1 = round($price*$this->config['profit']['grade_profit_rate1']/100);
			if($money1 > 0){
				$info1 = '商家【:'.$shop['shop_name'].'】购买商家等级【'.$grade_name.'】1级分成:'. round($money1/100,2);
				$fuser1 = M('users')->find($Users['fuid1']);
				if($fuser1){
					D('Users')->addMoney($Users['fuid1'],$money1,$info1);
					D('Users')->addProfit($Users['fuid1'],$order_type = 0,$type = 'grade',$order_id = '0',$shop['shop_id'],$money1,$is_separate = '1',$info1);
				}
			}
		}
		
		if($Users['fuid2']){
			$money2 = round($price*$this->config['profit']['grade_profit_rate2']/100);
			if($money2 > 0){
				$info2 = '商家【:'.$shop['shop_name'].'】购买商家等级【'.$grade_name.'】2级分成:'. round($money2/100,2);
				$fuser2 = M('users')->find($Users['fuid2']);
				if($fuser2){
					D('Users')->addMoney($Users['fuid2'],$money2,$info2);
					D('Users')->addProfit($Users['fuid2'],$order_type = 0,$type = 'grade',$order_id = '0',$shop['shop_id'],$money2,$is_separate = '1',$info2);
				}
			}
		}
		
		if($Users['fuid3']){
			$money3 = round($price*$this->config['profit']['grade_profit_rate3']/100);
			if($money3 > 0){
				$info3 = '商家【:'.$shop['shop_name'].'】购买商家等级【'.$grade_name.'】3级分成:'. round($money3/100,2);
				$fuser3 = M('users')->find($Users['fuid3']);
				if($fuser3){
					D('Users')->addMoney($Users['fuid3'],$money3,$info3);
					D('Users')->addProfit($Users['fuid3'],$order_type = 0,$type = 'grade',$order_id = '0',$shop['shop_id'],$money3,$is_separate = '1',$info3);
				}
			}
		}
   }
   

}
