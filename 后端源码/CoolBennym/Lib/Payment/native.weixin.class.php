<?php
    //微信NATIVE--原生扫码支付，www.hatudou.com二次开发
    require_once "weixin/WxPay.Api.php";
    require_once "weixin/WxPay.NativePay.php";
    require_once 'weixin/WxPay.Notify.php';
    require_once 'weixin/notify.php';
	require_once APP_PATH . 'Lib/phpqrcode/phpqrcode.php';
	
	
    class native{

		public function init($payment){
        define('WEIXIN_APPID', $payment['appid']);
        define('WEIXIN_MCHID', $payment['mchid']);
        define('WEIXIN_APPSECRET', $payment['appsecret']);
        define('WEIXIN_KEY',$payment['appkey']);
        define('WEIXIN_SSLCERT_PATH', '../cert/apiclient_cert.pem');
        define('WEIXIN_SSLKEY_PATH', '../cert/apiclient_key.pem');
        define('WEIXIN_CURL_PROXY_HOST', "0.0.0.0"); 
        define('WEIXIN_CURL_PROXY_PORT', 0); 
        define('WEIXIN_REPORT_LEVENL', 1);
        require_once "weixin/WxPay.Api.php";
        require_once "weixin/WxPay.JsApiPay.php";

    }
	
	
        public function getCode($logs ,$payment ){
			$config = D('Setting')->fetchAll();
			$this->init($payment);
            $notify = new NativePay();
            $url1 = $notify->GetPrePayUrl($logs['logs_id']);
            $url1 = urlencode( $url1 );
            $input = new WxPayUnifiedOrder();
            $input->SetBody( $logs['subject'] );//是 商品或支付单简要描述
            $input->SetAttach( $logs['subject'] );//否 附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
            $input->SetDetail( $logs['subject'] );//否 商品名称明细列表
            $input->SetOut_trade_no( $logs['logs_id'].'-'.time());//商户系统内部的订单号,32个字符内、可包含字母,
            $logs['logs_amount'] = $logs['logs_amount'] * 100;
            $input->SetTotal_fee($logs['logs_amount'] );//订单总金额，单位为分
            $input->SetTime_start(date("YmdHis"));//否 订单生成时间
            $input->SetTime_expire(date("YmdHis" , time() + 600));// 否 注意：最短失效时间间隔必须大于5分钟
            $input->SetGoods_tag($logs['subject']); // 否 商品标记，代金券或立减优惠功能的参数
            $input->SetNotify_url( $config['site']['host'] . U( 'Home/payment/respond' , array('code' => 'native')));
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id($logs['logs_id']);//此参数必传此id为二维码中包含的商品ID，商户自行定义
            $result = $notify->GetPayUrl($input);
			$url2 = $result["code_url"];
			$token = 'logs_id_' . time();
			$img = $this->buildCode($token,$url2);	
			$img = '<img src=' . '\'' . $img . '\'' . 'class="tu-native-pay"/>';
            return $img;

        }
		
		
		//生成支付二维码
	public function buildCode($token,$url2){
		$config = D('Setting')->fetchAll();
		$name = date('Y/m/d/',time());
		$md5 = md5($token);
		$patch =BASE_PATH.'/attachs/'.'weixin/'.$name;
		if(!file_exists($patch)){
			mkdir($patch,0755,true);
		}
		$file = '/attachs/weixin/'.$name.$md5.'.png';
		$fileName  =BASE_PATH.''.$file;
		if(!file_exists($fileName)){
			$level = 'L';
			QRcode::png($url2,$fileName,$level,$size = 8,2,true);
		}
		return $file; 
	}
	
	
	
	//扫码支付回调   
    public function respond(){
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        if(!empty($xml)){
			$res = new WxPayResults();
			$data = $res->FromXml($xml);
		}
		if($data['return_code'] == 'SUCCESS' && $data['result_code'] == 'SUCCESS'){
			ksort($data);
        	reset($data);
			$trade = @explode('-',$data['out_trade_no']);
            $logs = D('Paymentlogs')->where(array('log_id'=>$trade[0]))->find();
			if(!$logs){
				//file_put_contents(BASE_PATH.'/extend/Payment/trade.txt', var_export($trade[0], true));
				D('Payment')->logsPaid($trade[0],$data['out_trade_no'],$data['transaction_id']);
				return true;
			}else{
                if(!$logs['is_paid']){
                   $result = D('Payment')->checkMoney($trade[0],$data['total_fee']);
                   if($result){
                      D('Payment')->logsPaid($trade[0],$data['out_trade_no'],$data['transaction_id']);
                    return true;
                 }
             }
         }
     }
      return false;
    }
	

 }

