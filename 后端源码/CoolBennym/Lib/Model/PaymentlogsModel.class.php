<?php
    class PaymentlogsModel extends CommonModel{
        protected $pk   = 'log_id';
        protected $tableName =  'payment_logs';
        protected $type = array(
            money => '充值',
			running => '跑腿',
			thread=>'贴吧购买',
			group=>'拼团支付',
			pinche=>'拼车支付',
			coupon=>'优惠券支付',
			deposit=>'保证金',
        );
        protected $is_paid = array(
            0 => '未支付',
            1 => '已支付',
        );

        protected $code = array(
			wxapp => '小程序支付',

        );

        public function getType(){
            return $this->type;
        }

        public function getis_paid(){
            return $this->is_paid;
        }

        public function getcode(){
            return $this->code;
        }
		
   //返回商户订单表的支付类型
	public function get_payment_logs_type($type){
		$types = D('Payment')->getTypes();
		$result = array_flip($types);//反转数组
		$types = array_search($type, $result);
		if(!empty($types)){
			return $types;
		}else{
			return false;
		}
        return false;
	}

	//返回支付日志数据
    public function getLogsByOrderId($type,$order_id){
         $order_id = (int)$order_id;
         $type = addslashes($type);
         return M('PaymentLogs')->where(array('type'=>$type,'order_id'=>$order_id))->find();
     }
	
	
	 
	//退款原路返回weixinRefund
    public function weixinRefund($order_id,$user_id,$need_pay,$type = 'running',$info){
		if(empty($order_id)){
			$this->error = '退款单号不能为空';
			return false;
		}
		
		if(empty($need_pay)){
			$this->error = '退款金额错误';
			return false;
		}
		
		
		$logs = M('PaymentLogs')->where(array('type'=>$type,'order_id'=>$order_id,'is_paid'=>1))->find();
		if(empty($logs['return_trade_no'])){
			
			if($logs['code'] == 'money'){
				D('Users')->addMoney($logs['user_id'],$need_pay,$info,2,$logs['school_id']);//退款给余额
				return true;
			}else{
				$this->error = '商户订单号错误';
				return false;
			}
		}
		
		
		
		
		
		if(empty($logs['return_order_id'])){
			$this->error = '微信交易单号错误';
			return false;
		}

		$payment = D('Payment')->getPayment('wxapp');
		if(empty($payment['appid'])){
			$this->error = 'appid不能为空';
			return false;
		}
		if(empty($payment['mchid'])){
			$this->error = 'mchid不能为空';
			return false;
		}
	
		$dir = file_exists(BASE_PATH.'/cret/apiclient_key.pem');

		if($dir == false){
			$this->error = '证书必须配置';
			return false;
		}
		
		
		$connect = M('connect')->where(array('type'=>'weixin','uid'=>$logs['user_id']))->find();
		if(empty($connect['openid'])){
			$this->error = '当前会员的openid不存在';
			return false;
		}
		
	
        include (APP_PATH . 'Lib/Payment/WxPayPubHelper/WxPayPubHelper.php');
		//调用请求接口基类
        $Redpack = new Refund_pub();
		
		$Redpack->setParameter('transaction_id',$logs['return_trade_no']);//商户订单号
		$Redpack->setParameter('out_trade_no',$logs['return_order_id']);//商户订单号
		$Redpack->setParameter('out_refund_no',$order_id);//商户退款单号
		$Redpack->setParameter('total_fee',$need_pay);//订单金额
		$Redpack->setParameter('refund_fee',$need_pay);//退款金额
		$Redpack->setParameter('op_user_id',$connect['openid']);//操作员，会员的openid
		$Redpack->setParameter('appid', $payment['appid']);
		$Redpack->setParameter('mch_id', $payment['mchid']);
	
        $result = $Redpack->getResult();
		$result = (array)$result;
		//p($result);die;
		
		if(is_array($result) && $result['result_code'] == 'SUCCESS'){
			$data['out_refund_no'] = $result['out_refund_no'];//退款单号
			$data['refund_id'] = $result['refund_id'];//微信退款单号
			$data['refund_fee'] = $result['refund_fee'];//退款金额
			$data['settlement_refund_fee'] = $result['settlement_refund_fee'];//应结退款金额
			$data['refund_time'] = time();
			$data['refund_info'] = $info;
			$data['is_paid'] = 4;
			M('PaymentLogs')->where(array('type'=>$type,'order_id'=>$order_id))->save($data);
			return true;
		}else{
			$this->error = '操作失败:原因【'.$result['return_msg'] .''.$result['err_code_des'].'】【'.$logs['log_id'].'】';
			return false;
		}	
        return false;
     }
	 
     
    }