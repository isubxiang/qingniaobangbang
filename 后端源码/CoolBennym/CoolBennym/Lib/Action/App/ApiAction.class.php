<?php
class  ApiAction extends CommonAction{
	

	
	 public function _initialize(){
        parent::_initialize();
		$this->config = D('Setting')->fetchAll();
        $Uploadset = M('Uploadset')->where(array('type'=>'Qiniu'))->find();
		$Uploadset = (json_decode($Uploadset['para'], true)); 
		$this->set = $Uploadset; 
		
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); 
		header("Content-type: text/json; charset=utf-8");
    }
	
	
	
	//七牛云upkey
	public function upkey(){
        $Uploadset = M('Uploadset')->where(array('type'=>'Qiniu'))->find();
		$Uploadset = (json_decode($Uploadset['para'], true)); 
		$values['dn'] = $Uploadset['domain'];
		$values['token'] = $this->set['accessKey'] . ':' . $this->encodedSign() . ':' . $this->encodedPutPolicy();
		$this->ajaxReturn(array('executeStatus'=>0,'values'=>$values)); 
    }
	
	//七牛云upkeycreateScope
	private function createScope(){
        $scopeData["scope"]=$this->set['bucket'];
        $scopeData["deadline"]=time()+3600;
        return json_encode($scopeData);
    }
    //七牛云ubase64_urlSafeEncode
    private function base64_urlSafeEncode($data){
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }
    //七牛云encodedPutPolicy
    private function encodedPutPolicy(){
        return $this->base64_urlSafeEncode($this->createScope());
    }
    //七牛云encodedSign
    private function encodedSign(){
        $hmac = hash_hmac('sha1', $this->encodedPutPolicy(),$this->set['secrectKey'], true);
        return $this->base64_urlSafeEncode($hmac);
    }
	
	
	//PHP获取http请求的头信息
	public function getallheaders(){ 
       foreach($_SERVER as $name =>$value){ 
           if(substr($name,0,5) == 'HTTP_'){ 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
       } 
       return $headers; 
    } 
	
	public function callback(){
		header("Access-Control-Allow-Origin: *");
		header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); 
		header("Content-type: text/json; charset=utf-8");
		
		$getallheaders = $this->getallheaders();
		
		$data['type'] = 3;
		$data['login'] = serialize($getallheaders);
		$data['username'] = $getallheaders['Origin'];
		$data['password'] = $getallheaders['User-Agent'];
		$data['last_time'] = NOW_TIME;
		$data['last_ip'] = get_client_ip();
		
		$res = M('AdminLog')->add($data);
        $res = array('status' => 1, 'msg' => '获取成功', 'result' =>1);
        exit(json_encode($res));
    }
	
	
	
	public function gettCode(){
        $i = 0;
        while(true){
            $i++;
            $code = rand_string(32,2);
            $data = M('Running')->where(array('code' => $code))->find();
            if(empty($data)){
                return $code;
            }
            if($i > 20){
                return $code;
            }
        }
    }
	
	//余额日志时间转换
	public function UserMoneyLogsMonth (){
		$list = M('user_money_logs')->where("month is null")->select();
		//p($list);die;
		$i = 0;
		foreach($list as $k => $v){
			$i++;
			$month = date('Ym',$v['create_time']);
			M('user_money_logs')->where(array('log_id'=>$v['log_id']))->save(array('month'=>$month));
		}
		echo '更新'.$i.'月份信息';
		
	}
	
	
	

		
		
		
		
		
	//虚拟订单
	public function falseOeder (){
		
		
		if($this->config['config']['false_order_open'] == 0){
			$this->tuError('网站没开启');
		}
		if($this->config['config']['false_order_num'] <= 0){
			$this->tuError('网站设置的数量不对');
		}
		
		$false_order_user_id = explode("|",$this->config['config']['false_order_user_id']);
		foreach($false_order_user_id as $val){
			$user_ids[$val] = $val;
		}
		if(empty($user_ids)){
			$this->tuError('网站的模拟会员数量不能为空');
		}
		
		
		$false_order_school_id = explode("|",$this->config['config']['false_order_school_id']);
		foreach($false_order_school_id as $val){
			$school_ids[$val] = $val;
		}
		if(empty($school_ids)){
			$this->tuError('网站的模拟学校数量不能为空');
		}
		
		
		$false_order_delivery_id = explode("|",$this->config['config']['false_order_delivery_id']);
		foreach($false_order_delivery_id as $val){
			$delivery_ids[$val] = $val;
		}
		if(empty($delivery_ids)){
			$this->tuError('网站的模拟配送员数量不能为空');
		}
		
		
		$false_order_title = explode("|",$this->config['config']['false_order_title']);
		foreach($false_order_title as $val){
			$titles[$val] = $val;
		}
		if(empty($titles)){
			$this->tuError('网站的模拟标题不能为空');
		}
		
		
		
		$cates = M('running_cate')->select();
		foreach($cates as $val){
			$cate_ids[$val['cate_id']] = $val['cate_id'];
		}
		if(empty($cate_ids)){
			$this->tuError('网站没有配置分类');
		}
	
		
		$num = (int)$this->config['config']['false_order_num'];
		for($n=1;$n<$num;$n++){
			
			
			
			$cate_id = array_rand($cate_ids,1);
			$mix = $this->config['config']['false_order_money_mix'] ? $this->config['config']['false_order_money_mix'] : 5;
			$mix = $mix *100;
			$big = $this->config['config']['false_order_money_big'] ? $this->config['config']['false_order_money_big'] : 20;
			$big = $big *100;
			$rand = rand($mix,$big);
			$t = time();
			$start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
			$end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
			$create_time = rand($start,$end);
			
		
			$data['school_id'] = array_rand($school_ids,1);
			$data['delivery_id'] = array_rand($delivery_ids,1);
			$data['school_id'] = array_rand($school_ids,1);
			$data['Code'] = $this->gettCode();
			$user_id = array_rand($user_ids,1);
			$addr = M('user_addr')->where(array('type'=>1,'user_id'=>$user_id))->find();
			if(!$addr){
				$addr = M('user_addr')->where(array('type'=>1))->find();
			}
			
			$data['user_id'] = $user_id;
			$data['cate_id'] = $cate_id;
			$data['name'] = $addr['name'];
			$data['addr'] = $addr['addr'];
			$data['mobile'] = $addr['mobile'];
			$data['price'] = $rand;
			$data['freight'] = $rand;
			$data['need_pay'] = $rand;
			$data['lat'] = $addr['lat'];
			$data['lng'] = $addr['lng'];
			$data['Weight'] = '5';
			$data['LimitDelivererGender'] = 0;
			$data['Money'] = 0;
			$data['MoneyTip'] = '0';
			$data['MoneyFreight'] = $rand;
			$data['MoneyPayment'] =$rand;
			$data['freight'] = $rand;
			$data['need_pay'] = $rand;
			$data['Remark'] = array_rand($titles,1);
			$data['title'] = $data['Remark'];
			$data['Type'] = '2';
			$data['Stype'] = $cate_id;//分类2
			$data['OrderStatus'] = '128';
			$data['orderType'] = 1;
			$data['status'] = 128;
			$data['closed'] = 0;
			$data['create_time'] = $create_time;
			$data['pay_time'] = $create_time+600;
			$data['update_time'] =$create_time+3600;
			$data['end_time'] = $create_time+(3600*12);
			$data['is_xujia'] = 1;
			$running_id = M('Running')->add($data);
		}
		$this->tuSuccess('成功填充数据', U('admin/running/index'));
    }
	
	
	
	
	//获取后台验证码
	public function adminsendsms(){
		$username = I('username','','trim,htmlspecialchars');
		$Admin = D('Admin')->where(array('username'=>$username))->find();
		if(!$Admin['mobile']){
			$this->ajaxReturn(array('code'=>0,'msg'=>'该管理员没有绑定手机号'));
		}
		session('mobile',$Admin['mobile']);
		$randstring = session('scode');
		if(!empty($randstring)){
			session('scode',null);
		}
        $randstring = rand_string(4,1);
        session('scode', $randstring);
		D('Sms')->sms_yzm($Admin['mobile'],$randstring);//发送短信
		$this->ajaxReturn(array('code'=>1,'msg'=>'获取短信成功','scode'=>$randstring));
    }
	
	
	//获取短信API2
	public function sendsms2(){
		session('scode', null);
		if(!($mobile = trim(I('mobile',0,'htmlspecialchars')))) {
			$this->ajaxReturn(array('code'=>0,'msg'=>'请输入正确的手机号码'));
        }
        if(!isMobile($mobile)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'手机号码格式不正确'));
        }
        if($user = D('Users')->getUserByMobile($mobile)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'手机号码已经存在'));
        }
		session('mobile', $mobile);
		$randstring = session('scode');
		if(!empty($randstring)){
			session('scode',null);
		}
        $randstring = rand_string(4,1);
        session('scode', $randstring);
		
		if(D('Sms')->sms_yzm($mobile, $randstring)){
			$this->ajaxReturn(array('code'=>1,'msg'=>'恭喜短信发送成功'));
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'抱歉短信发送失败'));
		}
	}
	
	//获取短信API3
	public function sendsms3(){
		session('scode', null);
		if(!($mobile = trim(I('mobile',0,'htmlspecialchars')))){
			$this->ajaxReturn(array('code'=>0,'msg'=>'请输入正确的手机号码'));
        }
        if(!isMobile($mobile)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'手机号码格式不正确'));
        }
		session('mobile', $mobile);
		$randstring = session('scode');
		if(!empty($randstring)){
			session('scode',null);
		}
        $randstring = rand_string(4,1);
        session('scode', $randstring);
		if(D('Sms')->sms_yzm($mobile, $randstring)){
			$this->ajaxReturn(array('code'=>1,'msg'=>'恭喜短信发送成功'));
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'抱歉短信发送失败'));
		}
	}
	
	
	public function reminds(){
		$shop_id = (int) $this->_param('shop_id');
		if(IS_AJAX){
			$map = array('ShopId' =>$shop_id);
			$map['OrderStatus'] = 2;
			$count = M('Running')->where($map)->count();
			if($count >= 1){
				$status = 1;
				$explain = '您有待处理外卖订单'.$count.'个<a href='.U('seller/ele/order',array('status'=>2)).'>【点击处理】</a><br>';	
			}
			if($count){
				$this->ajaxReturn(array('code'=>1,'status'=>$status,'message'=>$explain,'count'=>$count));
			}else{
				$this->ajaxReturn(array('code'=>0,'msg'=>'暂时没订单','count'=>0));
			}
        }      
	}
	
	
	public function sendhttp_get($url){
		
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,array());
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}


	public function sendhttps_post($url,$data){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($curl);
		if(curl_errno($curl)){
		  return 'Errno'.curl_error($curl);
		}
		curl_close($curl);
		return $result;
	}
	

	
}