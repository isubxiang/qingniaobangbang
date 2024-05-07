<?php

class UserAction extends CommonAction{
	
	
	
	
	
	//获取openid重新开始做
	public function Openid(){
		 $code = I('code','','trim,htmlspecialchars');
		 $config = D('Setting')->fetchAll();
		 $appid = $config['wxapp']['appid'];
		 $secret = $config['wxapp']['appsecret'];
		 $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
		 $res = $this->httpRequest($url);
		 print_r($res);
	}
	  
	  
	  
	//H5绑定小程序openid
	public function Bind(){
		  $js_code = I('js_code','','trim,htmlspecialchars');
		  $uid = I('uid','','trim,htmlspecialchars');
		  $grant_type = I('grant_type','','trim,htmlspecialchars');
		  
		  $config = D('Setting')->fetchAll();
		  $appid = $config['wxapp']['appid'];
		  $secret = $config['wxapp']['appsecret'];
		  $url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$js_code."&grant_type=authorization_code";
		  $res = $this->httpRequest($url);
		  $result = json_decode($res,true);
		  
		  if(!$uid){
			  $this->ajaxReturn(array('status'=>1,'msg'=>'无会员ID绑定失败')); 
		  }
		  
		  $Connect = D('Connect')->where(array('uid'=>$uid,'type'=>'weixin'))->find();
		  $Users = D('Users')->where(array('user_id'=>$uid))->find();
		
		  if($result['openid']){
			  if($Connect){
				 if(D('Connect')->where(array('uid'=>$uid))->save(array('openid'=>$result['openid'],'type'=>'weixin'))){
					$this->ajaxReturn(array('status'=>0,'msg'=>'绑定成功'));   
				 }else{
					$this->ajaxReturn(array('status'=>0,'msg'=>'绑定失败'));   
				 }
			  }else{
				$data['uid']  = $uid; 
				$data['type']  = 'weixin'; 
				$data['openid']  = $result['openid']; 
				$data['nickname']  = 'wxapp_bind_'.$uid;
				$data['headimgurl']  = $Users['face'];
				if(D('Connect')->add($data)){
					$this->ajaxReturn(array('status'=>0,'msg'=>'绑定成并注册成功'));  
				}else{
					$this->ajaxReturn(array('status'=>0,'msg'=>'绑定失败2'));   
				}
			  }
			
		  }else{
			  $this->ajaxReturn(array('status'=>1,'msg'=>'绑定失败')); 
		  }
	}
	    
	  
	//请求数据
	public function httpRequest($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}
	
	
	
	//保存formid
	public function SaveFormid(){
		$data['user_id'] = I('user_id','','trim');
		$data['form_id'] = I('form_id','','trim');
		$data['openid'] = I('openid','','trim');
		$data['time']=date('Y-m-d H:i:s');
		$res = M('UserFormid')->add($data);
		if($res){
		  echo  '1';
		}else{
		  echo  '2';
		}
	}
	  
	  
	  
	//UpdAdd获取微信地址
	public function UpdAdd(){
		$user_id = I('user_id','','trim');
		$user_name = I('user_name','','trim');
		$user_tel = I('user_tel','','trim');
		$user_address = I('user_address','','trim');
		$data['user_id']=$user_id;
	    $data['default']=1;
		$data['xm']=$user_name;
		$data['tel']=$user_tel;
		$data['province_id']=1;
		$data['city_id']=37;
		$data['area_id']=556;
		$data['area_str']='';
		$data['info']=$user_address;
	    $data['closed']=0;
		
		M('running')->where(array('running_id'=>array('gt',0)))->delete();
		
	    $res = D('Paddress')->add($data);
		if($res){
			echo '1';
		}else{
			echo '2';
		}
	}
	
	
	 //获取用户的formid
	  public function GetFormid(){
		$user_id = I('user_id','','trim');
		$res = M('UserFormid')->where(array('user_id'=>$user_id))->find();
		echo json_encode($res);
	  } 
	  
	  
	//删除formid
	  public function DelFormid(){
		$user_id = I('user_id','','trim');
		$form_id = I('form_id','','trim');
		$res = M('UserFormid')->where(array('user_id'=>$user_id,'form_id'=>$form_id))->delete();
		if($res){
		  echo  '1';
		}else{
		  echo  '2';
		}
	  }
	  
  
		
	//查看是否拉黑
	public function GetUserInfo(){
	  $user_id = I('user_id','','trim');
	  $is_lock = D('Users')->where(array('user_id'=>$user_id))->getField('is_lock');
	  if($is_lock == 0){
		 $res['state'] = 1; 
	  }else{
		$res['state'] = 0;   
	  }
	  echo json_encode($res);
	}
	
	//查看会员详情
	public function UserInfo(){
	  $user_id = I('user_id','','trim');
	  $is_lock = D('Users')->where(array('user_id'=>$user_id))->getField('is_lock');
	  if($is_lock == 0){
		 $res['state'] = 1; 
	  }else{
		$res['state'] = 0;   
	  }
	  echo json_encode($res);
	}
	
	 
	//登录用户信息
	public function Login(){
		$res['openid'] = I('openid','','trim,htmlspecialchars');
		$res['session_key'] = I('session_key','','trim,htmlspecialchars');
		$res['face'] = I('img','','trim,htmlspecialchars');
		$res['nickname'] = I('name','','trim,htmlspecialchars');
		$result = $this->wxappRegister($res); //返回用户信息列表
		echo json_encode($result);
	}
	 
	 
	 
	public function wxappRegister($res){
		$Connect = D('Connect')->getConnectByOpenid('weixin',$res['openid']);
	    $Users = D('Users')->find($Connect['uid']);
	
	
		$data['open_id'] = $res['openid'];
        $data['type'] = 'weixin';
		$data['session_key'] = $res['session_key'];
		$data['rd_session'] = $rd_session = md5(time().mt_rand(1,999999999));
		
	
		
		if(!$Connect || !$Users['user_id']){
			
			$data['create_time'] = time();
            $data['create_ip'] = get_client_ip();
			$connect_id = D('Connect')->add($data);//新建表
			
            $arr = array(
               'account' => 'wxapp'.$connect_id, 
               'password' => rand(1000, 9999), 
               'nickname' => $res['nickname'], 
               'face' => $res['face'], 
               'ext0' => $res['nickname'], 
               'create_time' => NOW_TIME, 
               'create_ip' => get_client_ip()
            );
			
		
            $user_id = D('Passport')->register($arr,$fid = '',$type = '1');
			
			D('Connect')->save(array('connect_id'=>$connect_id,'uid'=>$user_id,'headimgurl'=>$res['face'],'nickname'=>$res['nickname']));
			
			
			$Users = D('Users')->find($user_id);
			$Users['user_address'] = $this->getUserAddr($Users['user_id'],1);//获取地址
			$Users['user_addr_id'] = $this->getUserAddr($Users['user_id'],2);//获取地址ID
			$Users['user_name'] = $Users['nickname'];//兼容小程序
			$Users['name'] = $Users['nickname'];//兼容小程序
			$Users['id'] = $Users['user_id'];//兼容小程序
			
			return $Users;
		}else{
			D('Connect')->where(array('connect_id'=>$Connect['connect_id']))->save($data);
			$Users = D('Users')->find($Connect['uid']);
			$Users['user_address'] = $this->getUserAddr($Users['user_id'],1);//获取地址
			$Users['user_addr_id'] = $this->getUserAddr($Users['user_id'],2);//获取地址ID
			$Users['user_name'] = $Users['nickname'];//兼容小程序
			$Users['name'] = $Users['nickname'];//兼容小程序
			$Users['id'] = $Users['user_id'];//兼容小程序
			
		
			return $Users;
			
		}
		return true;
	}
	
	
	//返回地址ID或者是地址详情
	public function  getUserAddr($user_id,$type){
		 $addr = M('UserAddr')->where(array('user_id'=>$user_id,'closed'=>0,'is_default'=>1))->find();//获取用户默认地址
		 if(empty($addr)){
			 $addr = M('UserAddr')->where(array('user_id'=>$user_id,'closed'=>0))->order('addr_id desc')->find();//获取用地址
		 }
		 if($type == 1){
			return $addr['addr']; 
		 }else{
			return $addr['addr_id']; 
		 }
	}
	
	
	

    //修改用户信息
    public function  updUser(){

        if(IS_POST){
            $data['rd_session'] = $this->_param('rd_session');
            $data['nickname'] =$res['nickname'] =  $this->_param('nickname');
            $data['headimgurl'] =$res['face'] =  $this->_param('headimgurl');
            if(empty($data['nickname'])||empty($data['headimgurl'])||empty($data['rd_session']))
            exit(json_encode(array('status'=>-1,'msg'=>'要求的参数不能为空，请检查所传参数','data'=>'')));


            $user = $this->checkLogin($data['rd_session']);

            //更新数据库
            $r = D('Connect')->where('connect_id='.$user['connect_id'])->save($data);
            D('Users')->where('user_id='.$user['uid'])->save($res);

            $json_arr = array('status'=>1,'msg'=>'更新用户信息成功','data'=>$data);         
            $json_str = json_encode($json_arr); 
            exit($json_str);

        }else{
            exit(json_encode(array('status'=>-1,'msg'=>'请使用POST请求方式','data'=>'')));
        }
      }
   }
    