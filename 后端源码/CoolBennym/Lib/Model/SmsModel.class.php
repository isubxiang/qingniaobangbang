<?php
class SmsModel extends CommonModel{
    protected $pk = 'sms_id';
    protected $tableName = 'sms';
    protected $token = 'tu_sms';
	
	protected function _initialize(){
		$this->config  = D('Setting')->fetchAll();
    }
	
	public function __construct(){
        import("@/Net.Curl");
        $this->curl = new Curl();
    }
	
	//数据缓存
	public function fetchAll(){
        $cache = cache(array('type' => 'File', 'expire' => $this->cacheTime));
        if(!($data = $cache->get($this->token))){
            $result = $this->order($this->orderby)->select();
            $data = array();
            foreach($result as $row){
                $data[$row['sms_key']] = $row;
            }
            $cache->set($this->token, $data);
        }
        return $data;
    }
	
	
	//发送短信
	public function send($code,$shop_id,$mobile, $data){
		$config = D('Setting')->fetchAll();
		if($config['sms']['dxapi'] == 'dy') {
           $this->DySms($code,$shop_id, $mobile, $data);
        }elseif($config['sms']['dxapi'] == 'bo'){
           $this->smsBaoSend($code,$shop_id, $mobile, $data);
        }elseif($config['sms']['dxapi'] == 'yunpian'){
           $this->yunPianSmsSend($code,$shop_id, $mobile, $data);
        }else{
			return false;	
		}
		return true;
	}
	
	
	//获取发送详情
	public function getSmsContent($code,$shop_id,$mobile,$data){
		$config = D('Setting')->fetchAll();
		
		$tmpl = M('Sms')->where(array('sms_key'=>$code))->find();
		//p($tmpl);
		//p($code,$shop_id, $mobile, $data);die;
		
        if(!empty($tmpl['is_open'])){
            $content = $tmpl['sms_tmpl'];
            $data['sitename'] = $config['site']['sitename'];
            $data['tel'] = $config['site']['tel'];
            foreach ($data as $k => $val) {
                $val = str_replace('【', '', $val);
                $val = str_replace('】', '', $val);
                $content = str_replace('{' . $k . '}', $val, $content);
            }
            if(is_array($mobile)) {
                $mobile = join(',', $mobile);
            }
            if($config['sms']['charset']){
                $content = auto_charset($content, 'UTF8', 'gbk');
            }
			$sms_id = $this->sms_bao_add($mobile,$shop_id, $content);//添加数据
            return array($sms_id,$shop_id,$content);
        }
	}
	
	//短信宝发接口
    public function smsBaoSend($code, $shop_id,$mobile, $data){
			$config = D('Setting')->fetchAll();
		    list($sms_id,$shop_id,$content) = $this->getSmsContent($code, $shop_id,$mobile, $data);
            $local = array('mobile' => $mobile, 'content' => $content);
			if($shop_id){
				$Smsshop = D('Smsshop')->where(array('type'=>'shop','status'=>'0','shop_id'=>$shop_id))->find();
				if($Smsshop['num'] <= 0){
					
					D('Smsbao')->ToUpdate($sms_id,$shop_id,$res = '-1');//更新状态未-1
					return true;
				}
			}
			
			
            $http = tmplToStr($config['sms']['url'], $local);
			
			//如果是选择get模式
			if($config['sms']['curl'] == 'get'){
				$res = $this->curl->get($http);
				$res = json_decode($res, true);
			}else{
				$res = file_get_contents($http);
			}
			//p($res);die;
			D('Smsbao')->ToUpdate($sms_id,$shop_id,$res);//更新短信宝状态
            return true;
       
    }
	
	
	public function ChinaMobile($mobile){
		if(isMobile($mobile)){
           return true;
        }else{
		   return false;
		}
	}
	

	
	
	
	
	
	//大鱼发送接口
    public function DySms($code,$shop_id, $mobile,$data){
        $config = D('Setting')->fetchAll();
        $dycode = D('Dayu')->where(array("dayu_local='{$code}'"))->find();
        if (!empty($dycode['is_open'])) {
            $sms_id = $this->sms_dayu_add($config['sms']['sign'], $code,$shop_id, $mobile, $data, $dycode['dayu_note']);
			if($config['sms']['dayu_version'] ==1){
				import('ORG.Util.Dayu');
				$obj = new AliSms($config['sms']['dykey'], $config['sms']['dysecret']);
				if($obj->sign($config['sms']['sign'])->data($data)->sms_id($sms_id)->code($dycode['dayu_tag'])->send($mobile)) {
					return true;
				}
			}elseif($config['sms']['dayu_version'] ==2){
				import('ORG.Util.DayuSend');
				$obj = new SmsDemo($config['sms']['dykey'], $config['sms']['dysecret']);
				if($obj->send($config['sms']['sign'],$dycode['dayu_tag'],$mobile,$data,$sms_id)){
					return true;
				}
			}else{
				return false;
			}
			return false;
        }
        return false;
    }
	
	
	//大于添加
    public function sms_dayu_add($sign, $code, $shop_id,$mobile, $data, $dayu_note){
        foreach ($data as $k => $val) {
            $content = str_replace('${' . $k . '}', $val, $dayu_note);
            $dayu_note = $content;
        }
        $sms_data = array();
        $sms_data['sign'] = $sign . '-' . time();
        $sms_data['code'] = $code;
		$sms_data['shop_id'] = $shop_id;
        $sms_data['mobile'] = $mobile;
        $sms_data['content'] = $content;
        $sms_data['create_time'] = time();
        $sms_data['create_ip'] = get_client_ip();
        if ($sms_id = D('Dayusms')->add($sms_data)) {
            return $sms_id;
        }
        return true;
    }
	
	
	//短信宝添加
    public function sms_bao_add($mobile,$shop_id, $content){
        $sms_data = array();
        $sms_data['mobile'] = $mobile;
		$sms_data['shop_id'] = $shop_id;
        $sms_data['content'] = $content;
        $sms_data['create_time'] = time();
        $sms_data['create_ip'] = get_client_ip();
        if ($sms_id = D('Smsbao')->add($sms_data)) {
            return $sms_id;
        }
        return true;
    }
	
	
    //验证码
    public function sms_yzm($mobile, $randstring){
		$this->send('sms_yzm',$shop_id = '0', $mobile,array('code' => $randstring));
        return true;
    }
	
			
	 //用户重置新密码
    public function sms_user_newpwd($mobile, $password){
		$config = D('Setting')->fetchAll();
		$this->send('sms_user_newpwd',$shop_id = '0', $mobile, array(
			'sitename' => $config['site']['sitename'],
			'newpwd' => $password,
		));
	    return true;
    }



 
 
	//配送员接单通知用户sms_Running_Delivery_User
    public function sms_Running_Delivery_User($running_id,$status){
        $running = D('Running')->find($running_id);
        $users = D('Users')->find($running['user_id']);
        $delivery = D('Delivery')->find($running['cid']);
		
		$mobile = $running['mobile'] ? $running['mobile'] : $users['mobile'];
		$statusName =  $status== 2 ? '执行中' : '已完成';
        if($mobile){
			$this->send('sms_running_delivery_user',$shop_id = 0,$mobile, array(
				'userName' =>$users['nickname'],
				'statusName' =>$statusName,
				'deliveryName' =>$delivery['name'],
				'deliveryMobile' =>$delivery['mobile']
			));
		}
        return true;
    }
	

	
	
	//后台账户异地登录通知管理员
    public function sms_admin_login_admin($mobile,$user_name,$time){ 
		$this->send('sms_admin_login_admin',$shop_id = '0', $mobile, array(
			'userName' => niuMsubstr($user_name, 0, 8, false),  
			'time' => $time  
		));
        return true;
    }
	
	
	//新用户注册短信通知接口，支持扣除商家短信
    public function register($user_id,$mobile,$account,$password,$shop_id){
		$Shop = D('Shop')->find($shop_id);
		$this->send('register',$shop_id, $mobile, array(
			'userId' => $user_id, 
			'userAccount' => niuMsubstr($account, 0, 8, false), 
			'userPassword' => $password,
			'shopName' =>niuMsubstr($Shop['shop_name'],0, 8, false),
		));
        return true;
    }
	
	
	
	
	
	//runningPayTzUser跑腿发布通知用户
    public function runningPayUser($running_id){
        $Running = M('Running')->find($running_id);
		
        $Users = M('Users')->find($Running['user_id']);

		//file_put_contents(BASE_PATH.'/Tudou/Lib/Model/$Users.txt', var_export($Users,true));
		
		$mobile = $Users['mobile']? $Users['mobile'] : $Running['mobile'] ;
		//通知买家
		$this->send('runningPayUser',$shop_id = 0, $mobile, array(
			'sitename' => $this->config['site']['sitename'], 
			'userName' => niuMsubstr($Users['nickname'],0,8,false),
			'needPay' => round($Running['need_pay']/100,2), 
			'runningId' => $running_id, 
			'time' => date('Y-m-d H:i:s ',time())
		));
		
		if($Running['Type'] == 1){
			D('Sms')->eleTZshop($running_id);
		}
		
		
        return true;
    }
	
	
	//新订单外卖通知商家
    public function eleTZshop($running_id){
		$Running = D('Running')->find($running_id);
		$Shop = D('Shop')->where(array('shop_id'=>$Running['ShopId']))->find();
		$Users = D('Users')->find($Shop['user_id']);//新用户账户
		
		$mobile = $Shop['mobile'] ? $Shop['mobile'] : $Users['mobile'];
		
        if($mobile){
			$this->send('sms_ele_tz_shop',$shop_id = 0,$mobile,array(
				'runningId' => $running_id,
				'sitename' => $this->config['site']['sitename'],
				'shopName' => niuMsubstr($Shop['shop_name'],0,8,false),
			));
        }
        return true;
    }
	
	
	
	//runningAcceptUser配送员接单通知用户
    public function runningAcceptUser($running_id){
        $Running = M('Running')->find($running_id);
		$rd = M('RunningDelivery')->find($Running['delivery']);
		
        $Users = M('Users')->find($Running['user_id']);
		$rdUsers = M('Users')->find($rd['user_id']);
		
		$mobile = $Users['mobile'] ? $Users['mobile'] : $Running['mobile'];
		$tel = $rd['phoneNumber'] ? $rd['phoneNumber'] : $rdUsers['mobile'];
		
		//通知买家
		$this->send('runningAcceptUser',$shop_id = 0, $mobile, array(
			'sitename' => $this->config['site']['sitename'], 
			'userName' => niuMsubstr($Users['nickname'],0,8,false),//会员名称
			'DeliveryName' => niuMsubstr($rd['RealName'],0,8,false),//配送员名称
			'DeliveryMobile' => $mobile,//配送员电话
			'needPay' => round($Running['need_pay']/100,2), //付金额
			'runningId' => $running_id, 
			'time' => date('Y-m-d H:i:s ',time())
		));
        return true;
    }
	
	
		
	//批量推送给配送员
    public function sms_delivery_user($running_id){
		
		$detail = M('running')->where(array('running_id'=>$running_id))->find();
	
		//如果是到店自提就不管了
		if($detail['orderType'] == 2){
			return true;
		}
		//如果商家配送不能抢单
		if($detail['is_ele_pei'] == 1){
			return true;
		}
		
		//限制男女
		$map = array('audit'=>2,'closed'=>0,'school_id'=>$detail['school_id']);
		if($detail['LimitDelivererGender'] == 2){
			$map['Gender'] == 2;
		}elseif($detail['LimitDelivererGender'] == 1){
			$map['Gender'] == 1;
		}
		

		$list = M('running_delivery')->where($map)->select();
		$i = 0;
		
		foreach($list as $k=>$v){
			//会员开启
			$users = M('users')->where(array('user_id'=>$v['user_id']))->find();
			$notifyFlag = $users['notifyFlag'];
			//类目开启
			$notify = M('running_cate_notify')->where(array('cate_id'=>$detail['cate_id'],'user_id'=>$v['user_id']))->find();
			//时间开启对比
			$notifyFrom = (int)rtrim($users['notifyFrom'],":00");
			$notifyEnd = (int)rtrim($users['notifyEnd'],":00");
			$time = date('H',time()); //当前时间
			if($time >= $notifyFrom && $time <= $notifyEnd){
				$times = 1;
			}elseif($notifyEnd == 0){
				$times = 1;
			}else{
				$times = 0;
			}
			if($notifyFlag && $notify && $times){
				$form = $this->getUserFormid($v['user_id']);//获取会员的form
				$i++;
				file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$i.'_sms_delivery_user.txt', var_export($v,true));
				if($v['phoneNumber']){
					$this->send('sms_delivery_user',$shop_id = '0',$v['phoneNumber'],array(
						'userName' => niuMsubstr($v['RealName'],0,8,false),
						'runningName' => niuMsubstr($detail['title'] ? $detail['title'] : $detail['Remark'],0,12,false),
						'date'=> date('Y-m-d H:i:s ',time())
					));
				}
			}
		}	
        return true;
    }
	
	
	
	
			
   
}