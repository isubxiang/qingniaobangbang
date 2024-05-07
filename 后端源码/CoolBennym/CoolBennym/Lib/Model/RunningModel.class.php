<?php
class RunningModel extends CommonModel{
    protected $pk   = 'running_id';
    protected $tableName =  'running';
	
	protected $types = array(
		0 => '未付款', 
		1 => '已付款', 
		2 => '跑腿中', 
		3 => '跑完腿', 
		4 => '退款中', 
		5 => '已退款', 
		8 => '已完成'
	);
	
	
								
	//保证金状态
	public function getDeliveryDeposit(){
        return array(
			'0' => '未缴纳', 
			'1' => '已缴纳', 
			'2' => '解冻中', 
			'3' => '已解冻', 
			'4' => '解冻失败', 
		);
    }
	
	//跑腿认证状态
	public function getDeliveryAudits(){
        return array(
			'0' => '未认证', 
			'1' => '审核中', 
			'2' => '已认证', 
			'3' => '认证失败', 
		);
    }

    public function getType(){
        return $this->types;
    }
	public function getError(){
        return $this->error;
    }
	
	//返回平均评分
	public function getEleScore($shop_id){
		if(!$shop_id){
			return 5;
		}
		$count = M('running')->where(array('ShopId' =>$shop_id,'score'=>array('gt',0)))->count();
		$sum = M('running')->where(array('ShopId' =>$shop_id,'score'=>array('gt',0)))->sum('score');
		$sum = ($sum/$count)*20;
		$sum = round($sum/1,0);
        return $sum;
	}
	
	
	//确认接单
	public function taking($running_id,$isPrint = 0,$isPrintInfo = ''){	
		$detail = M('Running')->find($running_id);
		//商家配送
		if($detail['orderType'] == 2 || $detail['is_ele_pei'] == 1){
			M('Running')->where(array('running_id'=>$detail['running_id']))->save(array('OrderStatus'=>32,'status'=>32,'update_time'=>time(),'isPrint'=>$isPrint,'isPrintInfo'=>$isPrintInfo));
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>32,'update_time'=>time()));
		}else{
			//平台配送员抢单
			M('Running')->where(array('running_id'=>$detail['running_id']))->save(array('OrderStatus'=>8,'status'=>8,'update_time'=>time(),'isPrint'=>$isPrint,'isPrintInfo'=>$isPrintInfo));
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>8,'update_time'=>time()));
		}
		return true;
	}
	
	
	
	
	//飞蛾打印代码2
	public function runningPrint2($running_id){	
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		$member = M('users')->find($running['user_id']);//会员信息
		$shop = M('shop')->where(array('shop_id'=>$running['ShopId']))->find();//商家信息
		
		
	
		$msg .= '<CB>'.'点菜清单__________NO:' .$running_id.'</CB>';
		$msg .= $shop['shop_name'].'<BR>';
		$msg .= '联系人：' .$running['name'].'<BR>';
		$msg .= '电话：' . $running['mobile'] .'<BR>';
		$msg .= '客户地址：' .$running['addr'] .'<BR>';
		$msg .= '下单时间：' .date('Y-m-d H:i:s',$running['create_time']).'<BR>';

		$msg .= '用餐地址：' .$shop['addr'] .'<BR>';
		$msg .= '商家电话：' .$shop['tel'].'<BR>';
		
		$msg .= '<CB>'.菜品明细.'</CB>';
		
		$products = M('running_product')->where(array('running_id'=>$running['running_id']))->select();
		foreach($products as $key => $value){
			$product = M('ele_product')->where(array('product_id' =>$value['product_id']))->find();
			$msg.= ''.($key+1).'.'.$product['product_name'].'—'.($product['price']/100).'元'.'*'.$value['num'].'份'.'<BR>';
		}
	
	
		$msg .= '外送费用：' . $running['MoneyFreight']/100 .'<BR>';
		$msg .= '<CB>付款总额：' . $running['need_pay']/100 .'</CB>';
		return $msg;//返回数组
   }
   
   
   	//易连云打印代码1
	public function runningPrint1($running_id){	
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		$member = M('users')->find($running['user_id']);//会员信息
		$shop = M('shop')->where(array('shop_id'=>$running['ShopId']))->find();//商家信息
		
		
	
		$msg .= '@@2'.'点菜清单__________NO:' .$running_id.'\r';
		$msg .= $shop['shop_name'].'\r';
		$msg .= '联系人：' .$running['name'].'\r';
		$msg .= '电话：' . $running['mobile'] .'\r';
		$msg .= '客户地址：' .$running['addr'] .'\r';
		$msg .= '下单时间：' .date('Y-m-d H:i:s',$running['create_time']).'\r';

		$msg .= '用餐地址：' .$shop['addr'] .'\r';
		$msg .= '商家电话：' .$shop['tel'].'\r';
		
		$msg .= '@@2'.菜品明细.'\r';
		
		$products = M('running_product')->where(array('running_id'=>$running['running_id']))->select();
		foreach($products as $key => $value){
			$product = M('ele_product')->where(array('product_id' =>$value['product_id']))->find();
			$msg.= ''.($key+1).'.'.$product['product_name'].'—'.($product['price']/100).'元'.'*'.$value['num'].'份'.'\r';
		}
	
	
		$msg .= '外送费用：' . $running['MoneyFreight']/100 .'\r';
		$msg .= '付款总额：' . $running['need_pay']/100 .'\r';
		return $msg;//返回数组
   }
   
   
   
   //打印接口中间件
   public function combinationElePrint($running_id){	
   
  		$running = M('running')->where(array('running_id'=>$running_id))->find();
		$shop = M('shop')->find($running['ShopId']);
		
			
		if($shop['is_ele_print_type'] == 1){
			//飞蛾
			$msg = $this->runningPrint2($running_id);
		}else{
			$msg = $this->runningPrint1($running_id);
		}
		
		//打印结果
		$result = D('Print')->printOrder($msg,$shop['shop_id'],$running_id);
		if($result == true){
			
			$taking = D('Running')->taking($running_id,$isPrint = 0,$isPrintInfo = '');
			return true;
		}else{
			$this->error = '打印状态【'.D('Print')->getError().'】';
			return true;
		}
		
	 }
	
	
	public function commonRefundUser($running_id,$saveOrderStatus,$refundInfo,$type){
		
		$v = M('Running')->where(array('running_id'=>$running_id))->find();
		if(!$v){
			$this->error = '当前订单【'.$running_id.'】订单不存在';
			return false;
		}
		
		$rest = M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>$saveOrderStatus));
		$rest2 = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>$saveOrderStatus));
		
		
		//退款说明
		$info = $refundInfo.'，当前订单状态【'.$v['OrderStatus'].'】';
		
		
		$mix = $this->config['running']['running_weixin_original_refund_mix'] ? $this->config['running']['running_weixin_original_refund_mix'] : 10;
		$mix2 = $mix*100;
		
		$flag = $flag2 = 0;
		if($this->config['running']['running_weixin_original_refund'] == 1){
			$flag = 1;//应该用余额
		}
		if($v['MoneyPayment'] < $mix2){
			$flag = 1;//应该用余额
		}
		if($v['MoneyTip'] == 0){
			$flag = 1;//应该用余额
		}
		
		//微信退款
		if($flag == 0){
			
			$result = D('Running')->runningOrderRefundUser($v['running_id'],$v['user_id'],$v['MoneyPayment'],'running',$info);
			if($result == false){
				D('Users')->addMoney($v['user_id'],$v['MoneyPayment'],$info,2,$v['school_id']);//【微信退款失败】退款给余额
			}	
		}else{
			//余额退款
			D('Users')->addMoney($v['user_id'],$v['MoneyPayment'],$info,2,$v['school_id']);//【未使用微信退款】退款给余额
		}
		
		D('Weixintmpl')->runningWxappNotice($v['running_id'],$OrderStatus = $saveOrderStatus,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
		return true;
	}
	
	
	
	
	//退款逻辑封装
	public function runningOrderRefundUser($running_id,$user_id,$money,$type = 'running',$info){
		
		$count = M('PaymentLogs')->where(array('type'=>$type,'user_id'=>$user_id,'order_id'=>$running_id,'is_paid'=>1))->count();
		if($count > 1){
			$this->error = '当前订单【'.$running_id.'】存在多笔支付记录请联系网站管理员处理';
			return false;
		}
		
		
		$logs = M('PaymentLogs')->where(array('type'=>$type,'user_id'=>$user_id,'order_id'=>$running_id,'is_paid'=>1))->find();
		if(!$logs){
			$this->error = '当前订单【'.$running_id.'】没找到支付日志或者该订单存在多次支付';
			return false;
		}
		if(!$logs['return_trade_no']){
			$this->error = '当前支付订单【'.$logs['log_id'].'】退款商户号不存在';
			return false;
		}
		if($logs['refund_id']){
			$this->error = '当前支付订单【'.$logs['log_id'].'】订单已经退款过了';
			return false;
		}
		
		$config = getConfigKey('running');
		$mix = $config['running_weixin_original_refund_mix'] ? $config['running_weixin_original_refund_mix'] : 10;
		$mix2 = $mix*100;
		if($money > $mix2){
			$this->error = '退款金额超过【'.$mix.'】元不支持原路退款操作';
			return false;
		}
		//退款操作
		if($logs['return_trade_no'] && $config['running_weixin_original_refund'] == 1){
			$res = D('Paymentlogs')->weixinRefund($running_id,$user_id,$money,'running',$info);//微信退款订单ID，金额，类型,说明
			if($res == false){
				$this->error = D('Paymentlogs')->getError();
				return false;
			}
			return true;
		}
		return true;
	 }  
	  
	  
	   
	//订单结算函数结算
	public function runingSettlement($running_id,$delivery_id,$labels,$content,$score = 5){
		//获取全局配置信息
		$config = D('Setting')->fetchAll();
		
		if(!$running_id){
			$this->error = 'RUNNING_ID不存在';
			return false;
		}
		
		
		$v = M('Running')->where(array('running_id'=>$running_id))->find();//订单详情
		
		$delivery_id = $delivery_id ? $delivery_id : $v['delivery_id'];
		
	
		if($v['is_ele_pei'] == 0){
			if($v['orderType'] == 1){
				$RunningDelivery = M('RunningDelivery')->where(array('delivery_id'=>$delivery_id))->find();//配送员
				if(!$RunningDelivery){
					$this->error = '配送员不存在无法继续您的操作';
					return false;
				}
			}
		}
	
		
		
		
		$RunningSchool = M('RunningSchool')->where(array('school_id'=>$v['school_id']))->find();//校园
		if(empty($RunningSchool)){
			$this->error = '学校不存在';
			return false;
		}
		
		if($v['Type'] == 0){
			$this->error = '订单类型Type = 0错误暂时无法结算';
			return false;
		}
		
		
		$paotuiMoney = $v['MoneyFreight']+$v['MoneyTip'];//跑腿应付金额
		
		
		$runningCate = M('running_cate')->where(array('cate_id'=>$v['cate_id']))->find();//分类
		//佣金规则
		if($runningCate['rate']){
			$paotuiRate = $runningCate['rate'];
			$paotuiRateName = '分类设置佣金';
		}elseif($RunningSchool['admin_yongjin_rate']){
			$paotuiRate = $RunningSchool['admin_yongjin_rate'];
			$paotuiRateName = '学校设置佣金';
		}else{
			$paotuiRate = $config['running']['rate'];
			$paotuiRateName = '全局设置佣金';
		}
		
		
		//$paotuiRate = $RunningSchool['admin_yongjin_rate'] ? $RunningSchool['admin_yongjin_rate'] : $config['running']['rate'];//跑腿佣金比例
		
		
		
		
		$paotuiRate = (int)$paotuiRate;//平台跑腿佣金比例	
		$cityYongjinRate = (int)$RunningSchool['city_yongjin_rate'];//站长佣金比例
		
			
		//如果是跑腿订单逻辑
		if($v['Type'] == 2){
			if($paotuiRate <= 0){
				$jiesuan = $paotuiMoney;//结算价格跑腿费用
				$commission = 0;//跑腿佣金等于0
				$info = '跑腿订单号【'.$running_id.'】配送员结算配送费【'.round($paotuiMoney/100,2).'】元';
			}else{
					
				//平台分成佣金
				$yongjin = ($paotuiMoney*$paotuiRate)/100;
				$yongjin = (int)$yongjin;
				
				
				$commission = $yongjin;//跑腿佣金
				$jiesuan = $paotuiMoney - $yongjin;//扣除佣金的结算价
				$info = '跑腿订单号【'.$running_id.'】结算，当前结算佣金类型【'.$paotuiRateName.'】比例【'.$paotuiRate.'%】配送费总价【'.round($paotuiMoney/100,2).'】元，结算【'.round($jiesuan/100,2).'】元，佣金【'.round($yongjin/100,2).'】元';
			}
			
			//跑腿订单站长分成佣金
			$cityYongjinRateMoney = ($paotuiMoney*$cityYongjinRate)/100;
			$cityYongjinRateMoney = (int)$cityYongjinRateMoney;//站长分成佣金
			$info3 = '跑腿订单站长分成佣金【'.round($cityYongjinRateMoney/100,2).'】元';
		}
		
		
		
		
	
		//如果是外卖结算给商家			
		if($v['Type'] == 1){
			$ele = M('ele')->find($v['ShopId']);
			if($ele['rate'] > 0){
				$rate = $ele['rate'];
			}else{
				$rate = 100;//默认10%比例
			}
			
		
			//外卖订单给站长分成佣金
			$cityYongjinRateMoney = ($paotuiMoney*$cityYongjinRate)/100;
			$cityYongjinRateMoney = (int)$cityYongjinRateMoney;//站长分成佣金
			$info3 = '外卖订单站长分成佣金【'.round($cityYongjinRateMoney/100,2).'】元';
			
			
			
			$settlementType = (int)$config['ele']['settlementType'];//结算模式
			
			$settlement_price = $Price = 0;
			$products = M('running_product')->where(array('running_id'=>$running_id))->select();
			foreach($products as $k8 =>$v8){
				$settlement_price += $v8['settlement_price'];
				$Price += $v8['Price'];
			}
			
		
				
			//菜品价格结算模式并餐具费大于或等于结算费用
			if($settlementType == 1 && $Price >= $settlement_price){
				
				if($v['orderType'] == 2){
					//如果是到店自提
					$money = $settlement_price;//结算金额
					$commission2 = $v['Money']-$settlement_price;//佣金
					$money = $money + $v['MoneyFreight']+ $v['MoneyTip'];
					$info2 .= '【1】【到店自提】订单id【'.$v['running_id'].'】结算，餐费【'.round($v['Money']/100,2).'元】，配送费【'.round($v['MoneyFreight']/100,2).'元】，小费【'.round($v['MoneyTip']/100,2).'元】';
				}elseif($v['is_ele_pei'] == 1){
					
					//如果是商家自主配送
					$money = $settlement_price;//结算金额
					$commission2 = $v['Money']-$settlement_price;//计算商家佣金
					$money = $money + $v['MoneyFreight']+ $v['MoneyTip'];
					$info2 .= '【1】【商家自主配送】订单id【'.$v['running_id'].'】结算，餐费【'.round($v['Money']/100,2).'元】，配送费【'.round($v['MoneyFreight']/100,2).'元】，小费【'.round($v['MoneyTip']/100,2).'元】';
				}else{
					
					//如果是平台配送
					$money = $settlement_price;
					$commission2 = $v['Money'] - $settlement_price;//计算商家佣金
					
					$money = $money;//只结算菜品总费用不结算跑腿费小费
					$info2 .= '【1】【平台配送】订单id【'.$v['running_id'].'】结算，餐费【'.round($v['Money']/100,2).'元】';
					
					//if($paotuiMoney)
					
					//给配送员算钱
					$yongjin = ($paotuiMoney * $paotuiRate)/100;
					$yongjin = (int)$yongjin;
					$jiesuan = $paotuiMoney - $yongjin;//结算价格有问题
					$info = '【平台配送】外卖订单号【'.$running_id.'】结算，当前结算佣金比例【'.$paotuiRate.'%】配送费总价【'.round($paotuiMoney/100,2).'】元，结算【'.round($jiesuan/100,2).'】元';
				}
			
			
			}else{
				if($v['orderType'] == 2){
					
					//如果是到店自提
					$money = $v['Money'] - (($v['Money']*$rate)/1000);//结算金额
					$commission2 = ($v['Money']*$rate)/1000;//计算商家佣金
					$money = $money + $v['MoneyFreight']+ $v['MoneyTip'];
					
					$info2 .= '【到店自提】订单id【'.$v['running_id'].'】结算，餐费【'.round($v['Money']/100,2).'元】，配送费【'.round($v['MoneyFreight']/100,2).'元】，小费【'.round($v['MoneyTip']/100,2).'元】，结算费率【'.$rate.'】‰';
					
					
				}elseif($v['is_ele_pei'] == 1){
					
					//如果是商家自主配送
					$money = $v['Money'] - (($v['Money']*$rate)/1000);//结算金额
					$commission2 = ($v['Money']*$rate)/1000;//计算商家佣金
					$money = $money + $v['MoneyFreight']+ $v['MoneyTip'];
					
					$info2 .= '【商家自主配送】订单id【'.$v['running_id'].'】结算，餐费【'.round($v['Money']/100,2).'元】，配送费【'.round($v['MoneyFreight']/100,2).'元】，小费【'.round($v['MoneyTip']/100,2).'元】，结算费率【'.$rate.'】‰';
				}else{
					
					//如果是平台配送
					$money = $v['Money'] - (($v['Money']*$rate)/1000);//结算金额
					$commission2 = ($v['Money']*$rate)/1000;//计算商家佣金
					$money = $money;
					$info2 .= '【平台配送】订单id【'.$v['running_id'].'】结算，餐费【'.round($v['Money']/100,2).'元】，结算费率【'.$rate.'】‰';
					
					//给配送员算钱
					$yongjin = ($paotuiMoney * $paotuiRate)/100;
					$yongjin = (int)$yongjin;
					$jiesuan = $paotuiMoney - $yongjin;//结算价格有问题
					$info = '【平台配送】外卖订单号【'.$running_id.'】结算，当前结算佣金比例【'.$paotuiRate.'%】配送费总价【'.round($paotuiMoney/100,2).'】元，结算【'.round($jiesuan/100,2).'】元';
				}
			}
		}
		
		
			
				
		
		//外卖满减配送费用
		if($v['MoneyFreightFullMoney']){
			$money2 = $money - $v['MoneyFreightFullMoney'];
			if($money2 <= 0){
				$money = $money;
				$info2 .= '【未减去满减配送费】';
			}else{
				$money = $money2;
				$info2 .= '【减去满减配送费【'.round($v['MoneyFreightFullMoney']/100,2).'元】';
			}
			
			
		}

		//给商家结算商户资金
		if($money > 0){
			D('Shopmoney')->insertData($v['running_id'],$v['school_id'],$v['ShopId'],$money,$commission2,$type ='ele',$info2);//结算给商家
		}
		
		//给站长分成佣金
		if($cityYongjinRateMoney > 0 && $RunningSchool['user_id']){
			D('Users')->addMoney($RunningSchool['user_id'],$cityYongjinRateMoney,$info3,5,$v['school_id']); //写入学校站长绑定的会员余额
		}
		
		
		//查询是否已经结算
		//$rest = M('RunningMoney')->where(array('intro'=>$info,'running_id'=>$v['running_id'],'user_id'=>$RunningDelivery['user_id']))->find();
		
		//查看结算日志
		$rest = M('RunningMoney')->where(array('money'=>$jiesuan,'order_id'=>$v['running_id'],'user_id'=>$RunningDelivery['user_id']))->find();
		
		//给配送员结算
		//这里是结算为空加上结算价格 == 0
		if(empty($rest) && $jiesuan > 0){
		
			
			//查询最后一次配送日志结算时间秒判断间隔时间
			$rest2 = M('RunningMoney')->where(array('user_id'=>$RunningDelivery['user_id'],'school_id'=>$v['school_id'],'type'=>'running'))->order('create_time desc')->find();
			if($rest2['create_time']){
				if(NOW_TIME-$rest2['create_time'] <=3){
					$this->error = '操作结算太频繁，请稍后再试试';
					return false;
				}
			}
			
			
			$data['city_id'] = $RunningSchool['city_id']; 
			$data['area_id'] = $RunningSchool['area_id']; 
			$data['business_id'] = $RunningSchool['business_id']; 
			$data['shop_id'] = $v['ShopId']; 
			$data['school_id'] = $v['school_id'];   
			$data['running_id'] = $v['running_id']; 
			$data['order_id'] = $v['running_id']; 
			$data['delivery_id'] = $v['delivery_id'];  
			$data['user_id'] = $RunningDelivery['user_id'];  
			$data['money'] = $jiesuan;  
			$data['commission'] = $commission;  
			$data['type'] = running;  
			$data['year'] = date('Y',NOW_TIME);
			$data['month'] = date('Ym',NOW_TIME);
			$data['create_time'] = NOW_TIME;  
			$data['create_ip'] = get_client_ip();  
			$data['intro'] = $info; 
			
				
			//写入配送员资金记录
			if(M('RunningMoney')->add($data)){
				
			
				D('Users')->addMoney($RunningDelivery['user_id'],$jiesuan,$info,1,$v['school_id']); //写入配送员余额订单结算
				D('Running')->payRunningProfitUser($running_id,$jiesuan,$info); //给上级分成
			}
		}
		
			
			
		//写入订单表更新订单状态	
		$res['running_id']= $v['running_id'];
		
		if($labels=='' || $content =''){
			
			$res['OrderStatus']= '128';
			$res['labels']= '自动完成订单';
			$res['content']= '自动完成订单';
			$res['score']= $score ? $score : '5';
			
		}else{
			$res['OrderStatus']= '64';
		}
		$res['end_time']= time();
	
		
		
		$res2= M('Running')->save($res);//配送订单完成
		
		//改变订单完成状态	
		if($v['Type'] == 1 && $res2){
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>128,'end_time'=>time()));
		}
		return true;
	}
	
	
	
	
	//分销结算这个金额是结算给配送员的金额
	public function payRunningProfitUser($running_id,$price=0,$info=''){
		
		//获取全局配置信息
		$Setting = D('Setting')->fetchAll();
		$config = $Setting['config'];
		
		if(!$running_id){
			return false;
		}
		if(!$price){
			return false;
		}
		//是否开启分销
		if(!$config['is_profit']){
			return false;
		}
		//订单详情
		$v = M('running')->where(array('running_id'=>$running_id))->find();
		if(!$v){
			return false;
		}
		
		
		$Users = M('users')->where(array('user_id'=>$v['user_id']))->find();//会员详情
		
		$money1 = $money2 = $money3 = 0;
		
		if($Users['fuid1']){
			$money1 = round($price * $config['running_profit_rate1']/100);
			if($money1 > 0){
				$info1 = '跑腿订单ID【'.$running_id.'】，1级分成:'.round($money1/100,2).'元';
				$fuser1 = M('users')->find($Users['fuid1']);
				if($fuser1){
					D('Users')->addMoney($Users['fuid1'],$money1,$info1,7);
				}
			}
		}
		
		if($Users['fuid2']){
			$money2 = round($price * $config['running_profit_rate2']/100);
			if($money2 > 0){
				$info2 = '跑腿订单ID【'.$running_id.'】，2级分成:'.round($money2/100,2).'元';
				$fuser2 = M('users')->find($Users['fuid2']);
				if($fuser2){
					D('Users')->addMoney($Users['fuid2'],$money2,$info2,7);
				}
			}
		}
		
		if($Users['fuid3']){
			$money3 = round($price * $config['running_profit_rate3']/100);
			if($money3 > 0){
				$info3 = '跑腿订单ID【'.$running_id.'】，3级分成:'.round($money3/100,2).'元';
				$fuser3 = M('users')->find($Users['fuid3']);
				if($fuser3){
					D('Users')->addMoney($Users['fuid3'],$money3,$info3,7);
				}
			}
		}
		
		return $money3 + $money2 + $money1;
   }
   
   
	
	//更新订单
    public function updateOrder($log_id,$order_id,$order_ids,$code,$need_pay){
		 $log_id = $log_id;
		 //支付表
		 $logs = M('payment_logs')->where(array('log_id'=>$log_id))->find();//支付日志记录
		
		 file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$log_id.'_modelLogs.txt', var_export($logs,true));
		
		
		 $order_id = $logs['order_id'];//订单ID
		 $id = $logs['order_id'];//订单ID
		 $log_id = $logs['log_id'];//支付ID
		 
		//小费付款订单回调
		if($logs['types'] == 2){
			
			$old_order = M('running')->where(array('running_id'=>$logs['order_id']))->find();//正常付费的原始订单
			$logs2 = M('payment_logs')->where(array('type'=>'running','log_id'=>$log_id,'types'=>2))->find();//当前订单回调
		
		
			$arr['MoneyTip'] =$logs2['need_pay'];//小费
			$arr['MoneyPayment'] = $old_order['MoneyFreight'] + $logs2['need_pay'];//实际支付
			
			$res2 = M('running')->where(array('running_id'=>$id))->save($arr);//更新跑腿订单
			
		 
		}elseif($logs['types'] == 1){
			
			
			 //正常支付订单回调
			$running = M('running')->where(array('running_id'=>$id))->find();//判断状态
			if($running['OrderStatus'] == 1){
				$res2= M('running')->where(array('running_id'=>$id))->save(array('status'=>1,'OrderStatus'=>2,'pay_time'=>time()));//跑腿订单支付回调
				//如果是外卖订单
				if($running['Type'] == 1){
					$res3 = M('RunningProduct')->where(array('running_id'=>$id))->save(array('OrderStatus'=>2,'pay_time'=>time()));//商家外卖订单已付款支付回调
					//更新外卖销量
					$list = M('running_product')->where(array('running_id'=>$id))->select();
					
					foreach($list as $k =>$v){
					   M('EleProduct')->where(array('product_id'=>$v['product_id']))->setDec('num',$v['Quantity']);//减去库存
					   M('EleProduct')->where(array('product_id'=>$v['product_id']))->setInc('sold_num',$v['Quantity']);//新增已售
					   M('Ele')->where(array('shop_id'=>$v['shop_id']))->setInc('sold_num',$v['Quantity']);//新增外卖已售
					}
					
					$shop = M('shop')->where(array('shop_id'=>$v['ShopId']))->find();//查商家
					if($shop['is_taking']){
						//确认发货逻辑封装自动接单
						$taking = D('Running')->taking($id,$isPrint = 0,$isPrintInfo = '');
					}
					//外卖订单回调结束
				}
			}
			
			//核销优惠券
			if($running['download_coupon_id'] && $running['coupon_price']){
				M('coupon_download')->where(array('download_id'=>$running['download_coupon_id']))->save(array('is_used'=>1,'status'=>2,'used_time'=>time()));
			}
			
			
			//核销新人红包
			if($running['redpacket_id'] && $running['redpacket_money']){
				M('users_redpacket')->where(array('redpacket_id'=>$running['redpacket_id']))->save(array('is_used'=>1,'running_id'=>$id,'used_time'=>time()));
			}
			
			
			
		}
		
		//微信通知短信通知
		if($res2){
			//更新支付日志表状态跟返回的订单号
			$res = M('PaymentLogs')->where(array('type'=>'running','log_id'=>$log_id))->save(array(
				'is_paid'=>1,
				'pay_time'=>time(),
				'return_order_id'=>$result['out_trade_no'],
				'return_trade_no'=>$result['transaction_id'],
				'pay_ip'=>get_client_ip()
			));
			
			
			
			
			D('Weixintmpl')->runningNoticeDelivery($id);//微信模板消息批量给配送员发送订单
			
			//微信模板消息批量给配送员发送订单
			D('Weixintmpl')->runningWxappNotice($id,$OrderStatus = 2,$user_id= '',$type = 1,$openid='',$form_id='');//通知买家，订单ID，订单状态，下单人，类型
			
			D('Running')->combinationElePrint($id);//打印跑腿订单
			
			D('Sms')->runningPayUser($id);//订单付款短信通知买家
			D('Sms')->sms_delivery_user($id);//配送员短信循环通知
			
		}
		//给上级分钱
	}			
		
		
	//计算外卖订单预计送达时间
    public function sendTime($running_id){
		
		$running = M('running')->where(array('running_id'=>$running_id))->find();
        $delivery = M('running_delivery')->where(array('delivery_id'=>$running['delivery_id']))->field('lng,lat')->find();
		
		$endAddress = unserialize($running['endAddress']);
		$AddressId = $endAddress['AddressId'];
        $UserAddr = M('UserAddr')->where(array('addr_id'=>$AddressId))->field('lat,lng')->find();
		
		$shop = M('shop')->where(array('shop_id'=>$running['ShopId']))->find();
		
		
        $dist1 = get_dist_info($delivery['lat'],$delivery['lng'],$shop['lat'],$shop['lng']);
        $dist2 = get_dist_info($delivery['lat'],$delivery['lng'],$UserAddr['lat'],$UserAddr['lng']);
		
		
        $total_time = $dist1['time_value']+$dist2['time_value'];
        $ok_date = date('H:i',time()+$total_time);
        return $ok_date;
    }
			
	
	//统计获取统计
    public function getDbHighcharts($bg_time,$end_time,$school_id,$OrderStatus){
		 
      	 $TR = 'tu_running';   
		 $CT = 'create_time';
	  
        if($school_id && $OrderStatus){
            $data = $this->query(" SELECT count(Money) as num,FROM_UNIXTIME(".$CT.",'%m%d') as day from  ".$$TR." where  ".$CT." >= '{$bg_time}' AND ".$CT." <= '{$end_time}' and school_id='{$school_id}' and OrderStatus='{$OrderStatus}'  group by  FROM_UNIXTIME(".$CT.",'%m%d')");
        }elseif(!$school_id && $OrderStatus){
            $data = $this->query(" SELECT count(Money) as num,FROM_UNIXTIME(".$CT.",'%m%d') as day from  ".$TR." where  ".$CT." >= '{$bg_time}' AND ".$CT." <= '{$end_time}' and OrderStatus='{$OrderStatus}'  group by  FROM_UNIXTIME(".$CT.",'%m%d')");
		}elseif($school_id && !$OrderStatus){
            $data = $this->query(" SELECT count(Money) as num,FROM_UNIXTIME(".$CT.",'%m%d') as day from  ".$TR." where  ".$CT." >= '{$bg_time}' AND ".$CT." <= '{$end_time}' and school_id='{$school_id}'  group by  FROM_UNIXTIME(".$CT.",'%m%d')");
		}else{
            $data = $this->query(" SELECT count(Money) as num,FROM_UNIXTIME(".$CT.",'%m%d') as day from  ".$TR." where  ".$CT." >= '{$bg_time}' AND ".$CT." <= '{$end_time}'  group by  FROM_UNIXTIME(".$CT.",'%m%d')");
        }
		//p($data);die;
		
        $showdata = array();
        $days = array();
        for($i = $bg_time; $i<=$end_time; $i += 86400){
            $days[date('md',$i)] = '\''.date('m月d日',$i).'\''; 
        }
		
        $num = array();
        foreach($days  as $k=>$v){
            $num[$k] = 0;
            foreach($data as $val){
                if($val['day'] == $k){
                    $num[$k] = round($val['num']/100,2);
                }
            }
        }
		
       $showdata['day'] = join(',',$days);
       $showdata['num'] = join(',',$num);
	   
	  //p($showdata);die;

       return $showdata;
    }      
	
	
	
	
	
}