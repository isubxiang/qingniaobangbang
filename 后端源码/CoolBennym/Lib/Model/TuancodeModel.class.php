<?php
class TuancodeModel extends CommonModel{
    protected $pk = 'code_id';
    protected $tableName = 'tuan_code';
	
	
	
	//抢购劵订单状态
    public function getTuanCodeStatus(){
        return array(
			0 => '未付款', 
			1 => '已付款', 
			2 => '已核销', 
			3 => '退款中', 
			4 => '已退款', 
			8 => '已完成', 
		);
    }
	
	//抢购劵订单核销状态
    public function getTuanCodeUsed(){
        return array(
			-1 => '未核销', 
			1 => '已核销', 
		);
    }
	
    public function getCode(){
        $i = 0;
        while(true){
            $i++;
            $code = rand_string(8, 1);
            $data = $this->find(array('where' => array('code' => $code)));
            if(empty($data)){
                return $code;
            }
            if($i > 20){
                return $code;
            }
        }
    }
	
	
	public function refund($code_id){
		
	 	$detail = M('TuanCode')->find($code_id);
        $order = M('TuanOrder')->find($detail['order_id']);
		
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
		
		if($TuanCode = M('TuanCode')->save(array('code_id' =>$code_id,'status' =>4))){
            if($detail['real_money'] > 0){
				
				$info = '抢购券订单ID【'.$code_id.'】【'.$detail['code'].'】退款余额';
				
				$logs = M('PaymentLogs')->where(array('type'=>'hotel',$detail['order_id'],'is_paid'=>1))->find();
				if($logs['return_trade_no']){
					$res = D('Paymentlogs')->weixinRefund($order_id,$detail['real_money'],'tuan',$info);//微信退款订单ID，金额，类型,说明
				}else{
					D('Users')->addMoney($detail['user_id'],$detail['real_money'],$info);
				}
               
            }
			
			
			
            if($detail['real_integral'] > 0){
               D('Users')->addIntegral($detail['user_id'], $detail['real_integral'], '抢购券订单ID【'.$code_id.'】【'.$detail['code'].'】退款积分');
            }
			$num = M('TuanOrder')->where(array('tuan_id'=>$detail['tuan_id']))->getField('num');
			D('Tuan')->where(array('tuan_id'=>$detail['tuan_id']))->setInc('num',$num);//修复退款后增加库存
			D('Sms')->tuancode_refund_user($code_id);
			D('Weixinmsg')->weixinTmplOrderMessage($detail['order_id'],$cate = 1,$type = 4,$status = 4);
			D('Weixinmsg')->weixinTmplOrderMessage($detail['order_id'],$cate = 2,$type = 4,$status = 4);
			return true;
        }else{
			$this->error = '抢购劵退款处理数据库失败';
			return false;
        }
	}
	
	
	
	
	//抢购劵验证封装函数
	public function saveShopMoney($data,$shop){
		 	$count = M('TuanCode')->where(array('order_id'=>$data['order_id'],'is_used'=>0,'status'=>8))->count();
			
            if(!$count || $count <= 1){
                 M('TuanOrder')->save(array('order_id' => $data['order_id'],'status' =>8));
            }
		
			
			$Shop = M('Shop')->find($data['shop_id']);
			$yong = $Shop['commission'] > 0 ? $Shop['commission'] : '5';
			
			$yong_price = ($data['price'] * $yong)/10000;//佣金
		
			
			$money = $data['price'] - (($data['price'] * $yong)/10000);//实际结算金额
			
					
			$intro = '抢购订单号【'.$data['order_id'].'】cId='.$data['code_id'].'结算价'.round($money/100,2).'元 = 实付【'.round($data['price']/100,2).'元】-佣金【'.round($yong_price/100,2).'】元，佣金比例【'.round($yong/100,2).'%】';
			
			
			
			if($money){
				D('Shopmoney')->insertData($data['order_id'],$data['code_id'],$data['shop_id'],$money,$type ='tuan',$intro);//结算给商家
                
				
				
				
				$TuanOrder = M('TuanOrder')->where(array('order_id'=>$data['order_id']))->find();
				$integral = $TuanOrder['fanhuan'] * $TuanOrder['num'];   
				if($integral > 0){
					D('Users')->integralRestoreUser($data['user_id'],$data['order_id'],$data['code_id'],$integral,$type ='tuan');//抢购购物返利积分
				}
				
				D('Users')->rewardPrestige($data['user_id'],(int)($money/100),$data['order_id'],'tuan');//返威望	
				
				
				D('Sms')->tuan_TZ_user($data['code_id']);//短信通知
				D('Weixinmsg')->weixinTmplOrderMessage($data['order_id'],$cate = 1,$type = 4,$status = 8);//微信通知
				D('Weixinmsg')->weixinTmplOrderMessage($data['order_id'],$cate = 2,$type = 4,$status = 8);
				return true;	
		  }
		
	}
	
	
}