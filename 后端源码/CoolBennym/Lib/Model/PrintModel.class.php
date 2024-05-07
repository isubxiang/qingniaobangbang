<?php


//以下参数不需要修改
define('IP','api.feieyun.cn');//接口IP或域名
define('PORT',80);//接口IP端口
define('PATH','/Api/Open/');//接口路径



class PrintModel{

	public function getError(){
        return $this->error;
    }

    public function printSend($val,$msg){
		header("Content-type: text/html; charset=utf-8");
		$params = array(
			'partner'=>$val['partner'],
			'machine_code'=>$val['machine_code'],
			'time'=>time()	
		);
		
        $sign = $this->generateSign($params,$val['apiKey'],$val['mKey']);
		$params['sign'] = $sign;
		$params['content'] = $msg;
		
		$url = 'open.10ss.net:8888';
		$p = '';
		foreach($params as $k => $v){
			$p .= $k.'='.$v.'&';
		}
		$data = rtrim($p, '&');
	
		$res = $this->liansuo_post($url,$data);
		
		
		$result = json_decode($result);
		$backstate = $result->state;
		$id = $result->id;
			
		if($backstate == 1){
			$backstateName == '数据提交成功';
		}elseif($backstate == 2){
			$backstateName == '提交时间超时。验证你所提交的时间戳超过3分钟后拒绝接受';
		}elseif($backstate == 3){
			$backstateName == '参数有误';
		}elseif($backstate == 4){
			$backstateName == 'sign加密验证失败';
		}
			


		//易连云打印回调
		if($backstate == 1){
			$taking = D('Running')->taking($running_id,$isPrint = 1,$isPrintInfo = '易连云打印打印单ID：【'.$id.'】状态码【'.$backstate.'】【'.$backstateName.'】');
			$printing = D('Running')->where(array('running_id'=>$running_id))->save(array('is_ticket_printing'=>1,'is_ticket_printing_time'=>$time));
			return true;
		}else{
			$this->error = $backstateName;
			return false;
		}
	}
	
	
	
	//生成签名
	public function signature($time,$partner,$mKey){
		return sha1($partner.$mKey.$time);//公共参数，请求公钥
	}


	//飞蛾打印机打印
	public function wpPrint($printer_sn,$orderInfo,$times,$shop_id,$running_id){
		
		$shop = M('Shop')->find($shop_id);
		
		$printer_sn = $shop['machine_code'];
		$time = time();	//请求时间

		$queryPrinterStatus = array(			
			'user'=>$shop['partner'],//飞鹅云后台注册账号飞鹅云注册账号后生成的UKEY
			'stime'=>$time,
			'sig'=>$this->signature($time,$shop['partner'],$shop['mKey']),
			'apiname'=>'Open_queryPrinterStatus',
			'sn'=>$printer_sn,
		);
		
		
		//引入文件
		import('ORG.Util.HttpClient');	
		
		$client2 = new HttpClient(IP,PORT);
		if(!$client2->post(PATH,$queryPrinterStatus)){
			return false;
		}else{
			//服务器返回的JSON字符串，建议要当做日志记录起来
			$getContent2 =  $client2->getContent();
			$getContent3 = json_decode($getContent2,true);
			if($getContent3['ret'] !=2){
				$this->error = '打印机离线或者坏了请排查【'.$getContent3['ret'].'】【'.$getContent3['data'].'】';
				return false;
			}
		}
		
		
		
		$content = array(			
			'user'=>$shop['partner'],//飞鹅云后台注册账号飞鹅云注册账号后生成的UKEY
			'stime'=>$time,
			'sig'=>$this->signature($time,$shop['partner'],$shop['mKey']),
			'apiname'=>'Open_printMsg',
			'sn'=>$printer_sn,
			'content'=>$orderInfo,
			'times'=>1//打印次数
		);
		
		
		$client = new HttpClient(IP,PORT);
		if(!$client->post(PATH,$content)){
			return false;
		}else{
			//服务器返回的JSON字符串，建议要当做日志记录起来
			$getContent =  $client->getContent();
			$getContent = json_decode($getContent,true);
			//更新订单打印状态
			//p($getContent);die;
			if($getContent['msg'] == 'ok'){
				$taking = D('Running')->taking($running_id,$isPrint = 1,$isPrintInfo = '打印单号：【'.$getContent['data'].'】【'.$getContent['msg'].'】');
				
				$printing = D('Running')->where(array('running_id'=>$running_id))->save(array('is_ticket_printing'=>1,'is_ticket_printing_time'=>$time));
				return true;
			}
			
			$this->error = $getContent['msg'];
			return false;
			//打印结果返回
		}
		
	}
	
	
	
	public function printOrder($msg,$shop_id,$running_id){
		
		header("Content-type: text/html; charset=utf-8");
		$shop = M('Shop')->find($shop_id);
		
		//飞蛾打印机
		if($shop['is_ele_print_type'] == 1){
			
			//打印机编号，文档，类型
			$wpPrint = $this->wpPrint($shop['machine_code'],$msg,1,$shop_id,$running_id);
			return $wpPrint;


			
		}else{
			//易连云打印机
			$partner = explode(',',$shop['partner']);
			$list[0]['partner'] = $partner[0];
			$list[1]['partner'] = $partner[1];
			$list[2]['partner'] = $partner[2];
			$list[3]['partner'] = $partner[3];
			
			$machine_code = explode(',',$shop['machine_code']);
			$list[0]['machine_code'] = $machine_code[0];
			$list[1]['machine_code'] = $machine_code[1];
			$list[2]['machine_code'] = $machine_code[2];
			$list[3]['machine_code'] = $machine_code[3];
			
			$apiKey = explode(',',$shop['apiKey']);
			$list[0]['apiKey'] = $apiKey[0];
			$list[1]['apiKey'] = $apiKey[1];
			$list[2]['apiKey'] = $apiKey[2];
			$list[3]['apiKey'] = $apiKey[3];
			
			$mKey = explode(',',$shop['mKey']);
			$list[0]['mKey'] = $mKey[0];
			$list[1]['mKey'] = $mKey[1];
			$list[2]['mKey'] = $mKey[2];
			$list[3]['mKey'] = $mKey[3];
			
			$i = 0;
			foreach($list as $key=>$val){
				if($val['partner'] && $val['machine_code']){
					$i++;
					$printSend = $this->printSend($val,$msg);
				}
			}
			//更新打印状态
			return $i;
		}
    }
	
	
	


	public function liansuo_post($url,$data){ 
		$curl = curl_init();  
		curl_setopt($curl, CURLOPT_URL, $url);              
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); 
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); 
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);    
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);    
		curl_setopt($curl, CURLOPT_POST, 1);  
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
		curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);  
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$tmpInfo = curl_exec($curl);
		if(curl_errno($curl)){      
		   echo 'Errno'.curl_error($curl);      
		}      
		curl_close($curl);  
		return $tmpInfo; 
	}  



	public function generateSign($params, $apiKey, $msign){
		ksort($params);
		$stringToBeSigned = $apiKey;

		foreach ($params as $k => $v)
		{
			$stringToBeSigned .= urldecode($k.$v);
		}
		unset($k, $v);
		$stringToBeSigned .= $msign;
		return strtoupper(md5($stringToBeSigned));

	}



}

