<?php


class Expressprint{
	
	public $EBusinessID = '715454784';//请到快递鸟官网申请http://kdniao.com/reg
	public $AppKey = 'AppKey';//请到快递鸟官网申请http://kdniao.com/reg
	
	//请求url，正式环境地址：http://api.kdniao.com/api/Eorderservice    测试环境地址：http://testapi.kdniao.com:8081/api/EOrderServic
	public $ReqURL = 'http://testapi.kdniao.com:8081/api/Eorderservice';//


	//传数组
	public function printOrder($config,$ShipperCode,$OrderCode,$running_id = 0){
		
		//构造电子面单提交信息
		$eorder = array();
		$eorder["ShipperCode"] = $ShipperCode;//顺风编码
		$eorder["OrderCode"] = $OrderCode;//订单号
		$eorder["PayType"] = 1;
		$eorder["ExpType"] = 1;
		
		$running = M('running')->find($running_id);
		$school = M('running_school')->find($running['school_id']);
		
		//到达地址
		$sender = array();
		$sender["Name"] = $running['name'];
		$sender["Mobile"] = $running['mobile'];
		$sender["ProvinceName"] = $running['name'];
		$sender["CityName"] = $school['Region'];//城市
		$sender["ExpAreaName"] = $school['Region'];//地区
		$sender["Address"] = $running['addr'];
		
		$endAddress = unserialize($running['endAddress']);
		$addr = M('user_addr')->find($endAddress['AddressId']);
		
		//取件地址
		$receiver = array();
		$receiver["Name"] = $addr['name'];//到达地区
		$receiver["Mobile"] = $addr['mobile'];
		$receiver["ProvinceName"] = $addr['name'];
		$receiver["CityName"] = $school['Region'];
		$receiver["ExpAreaName"] = $school['Region'];
		$receiver["Address"] = $addr['addr'];
		
		$commodityOne = array();
		$commodityOne["GoodsName"] = "其他";
		$commodity = array();
		$commodity[] = $commodityOne;
		
		$eorder["Sender"] = $sender;
		$eorder["Receiver"] = $receiver;
		$eorder["Commodity"] = $commodity;
		
		//p($eorder);die;
		
		//调用电子面单
		$jsonParam = json_encode($eorder, JSON_UNESCAPED_UNICODE);
	
		$jsonResult = $this->submitEOrder($jsonParam);

	
		//解析电子面单返回结果
		$result = json_decode($jsonResult, true);
	
		if($result["Success"] == true){
			return array('code'=>1,'msg'=>'下单失败【'.$result["Reason"].'】');
		}else {
			return array('code'=>0,'msg'=>'电子面单下单失败【'.$result["Reason"].'】');
		}
	}
	
	
	
	
	/**
	 * Json方式 调用电子面单接口
	 */
	public function submitEOrder($requestData){
		$datas = array(
			'EBusinessID' => $this->EBusinessID,
			'RequestType' => '1007',
			'RequestData' => urlencode($requestData) ,
			'DataType' => '2',
		);
		
		//p($datas);die;
		
		$datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
		$result=$this->sendPost($this->ReqURL, $datas);	
		
		//根据公司业务处理返回的信息......
		
		return $result;
	}
	
	 
	/**
	 *  post提交数据 
	 * @param  string $url 请求Url
	 * @param  array $datas 提交的数据 
	 * @return url响应返回的html
	 */
	public function sendPost($url, $datas){
		$temps = array();	
		foreach ($datas as $key => $value){
			$temps[] = sprintf('%s=%s', $key, $value);		
		}	
		$post_data = implode('&', $temps);
		$url_info = parse_url($url);
		if(empty($url_info['port'])){
			$url_info['port']=80;	
		}
		$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
		$httpheader.= "Host:" . $url_info['host'] . "\r\n";
		$httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
		$httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
		$httpheader.= "Connection:close\r\n\r\n";
		$httpheader.= $post_data;
		$fd = fsockopen($url_info['host'], $url_info['port']);
		fwrite($fd, $httpheader);
		$gets = "";
		$headerFlag = true;
		while(!feof($fd)){
			if(($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")){
				break;
			}
		}
		while(!feof($fd)){
			$gets.= fread($fd, 128);
		}
		fclose($fd);  
		
		return $gets;
	}
	
	/**
	 * 电商Sign签名生成
	 * @param data 内容   
	 * @param appkey Appkey
	 * @return DataSign签名
	 */
	public function encrypt($data,$appkey) {
		return urlencode(base64_encode(md5($data.$appkey)));
	}
	/************************************************************** 
	 * 
	 *  使用特定function对数组中所有元素做处理 
	 *  @param  string  &$array     要处理的字符串 
	 *  @param  string  $function   要执行的函数 
	 *  @return boolean $apply_to_keys_also     是否也应用到key上 
	 *  @access public 
	 * 
	 *************************************************************/  
	public function arrayRecursive(&$array, $function, $apply_to_keys_also = false){  
		static $recursive_counter = 0;  
		if(++$recursive_counter > 1000){  
			die('possible deep recursion attack');  
		}  
		foreach($array as $key => $value){  
			if(is_array($value)){  
				$this->arrayRecursive($array[$key], $function, $apply_to_keys_also);  
			}else{  
				$array[$key] = $function($value);  
			}  
	   
			if($apply_to_keys_also && is_string($key)){  
				$new_key = $function($key);  
				if($new_key != $key){  
					$array[$new_key] = $array[$key];  
					unset($array[$key]);  
				}  
			}  
		}  
		$recursive_counter--;  
	}  
	
	
	/************************************************************** 
	 * 
	 *  将数组转换为JSON字符串（兼容中文） 
	 *  @param  array   $array      要转换的数组 
	 *  @return string      转换得到的json字符串 
	 *  @access public 
	 * 
	 *************************************************************/  
	public function JSON($array){  
		$this->arrayRecursive($array, 'urlencode', true);  
		$json = json_encode($array);  
		return urldecode($json);  
	}  

}