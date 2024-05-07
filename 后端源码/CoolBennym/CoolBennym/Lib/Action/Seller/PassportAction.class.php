<?php
class PassportAction extends CommonAction{
    private $create_fields = array('account', 'password', 'nickname');
    public function bind(){
        $this->display();
    }
	
	 public function login(){
		 clearUid();
        if($this->isPost()){
            $account = $this->_post('account');
			$password = $this->_post('password');
			
            if (true == D('Passport')->login($account, $password)){
                $backurl = U('index/index');
                $this->ajaxReturn(array('status' => 'success', 'message' => '恭喜您登录成功', 'backurl' => $backurl));
            }else{
				$this->ajaxReturn(array('status' => 'error', 'message' =>D('Passport')->getError()));
			}
			
        }else{
			
			$this->assign('backurl', $backurl);
            $this->display();
            
        }
    }
	
	 public function shopcate($parent_id = 0) {
        $datas = D('Shopcate')->fetchAll();
        $str = '';

        foreach ($datas as $var) {
            if ($var['parent_id'] == 0 && $var['cate_id'] == $parent_id) {

                foreach ($datas as $var2) {

                    if ($var2['parent_id'] == $var['cate_id']) {
                        $str.='<option value="' . $var2['cate_id'] . '">' . $var2['cate_name'] . '</option>' . "\n\r";
                    }
                }
            }
        }
        echo $str;
        die;
    }

    public function child($parent_id = 0) {
        $datas = D('Activitytype')->fetchAll();
        $str = '';

        foreach ($datas as $var) {
            if ($var['parent_id'] == 0 && $var['type_id'] == $parent_id) {

                foreach ($datas as $var2) {

                    if ($var2['parent_id'] == $var['type_id']) {
                        $str.='<option value="' . $var2['type_id'] . '">' . $var2['type_name'] . '</option>' . "\n\r";
                    }
                }
            }
        }
        echo $str;
        die;
    }

	
    public function apply(){
        if(empty($this->uid)){
			$this->error('请先登录后再来操作', U('passport/login'));
            die;
        }
		
        $shop = M('shop')->where(array('user_id'=>$this->uid))->find();
		
		
		
		
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false),array(
				'user_id','school_id','city_id','area_id','business_id','logo','cate_id','tel','logo','photo','shop_name','contact','details','business_time','area_id','addr','lng','lat'
			));
			$detail = M('shop')->where(array('user_id' =>$this->uid))->find();
			if(!empty($detail)){
				$this->ajaxReturn(array('code'=>'0','msg'=>'您已经是商家了'));
			}
			$data['user_id'] = $this->uid;
			$data['photo'] = htmlspecialchars($data['photo']);
			if(empty($data['photo'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'请上传商家形象图'));
			}
			if(!isImage($data['photo'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'商家形象图格式不正确'));
			}
			$data['logo'] = htmlspecialchars($data['logo']);
			$data['shop_name'] = htmlspecialchars($data['shop_name']);
			if(empty($data['shop_name'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'店铺名称不能为空'));
			}
			$data['cate_id'] = (int) $data['cate_id'];
			if(empty($data['cate_id'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'分类不能为空'));
			}
			
			$data['contact'] = htmlspecialchars($data['contact']);
			$data['business_time'] = htmlspecialchars($data['business_time']);
			$data['addr'] = htmlspecialchars($data['addr']);
			$data['tel'] = htmlspecialchars($data['tel']);
			if(empty($data['tel'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'手机号不能为空'));
			}
			if(!isPhone($data['tel']) && !isMobile($data['tel'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'手机号格式不正确'));
			}
			if(isMobile($data['tel'])){
				$data['mobile'] = $data['tel'];
			}
			$data['school_id'] = $data['school_id'];
			$data['recognition'] = 1;
			$data['audit'] = 0;
			$data['user_id'] = $this->uid;
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
		
            $details = $this->_post('details', 'htmlspecialchars');
            $ex = array('details' => $details, 'near' => $data['near'], 'price' => $data['price'], 'business_time' => $data['business_time']);
            unset($data['near'], $data['price'], $data['business_time']);
			
			
			
            if($shop_id = M('shop')->add($data)){
                D('Shopdetails')->upDetails($shop_id,$ex);
				$this->ajaxReturn(array('code'=>'1','msg'=>'恭喜您申请成功','url'=>U('passport/apply')));
            }
			$this->ajaxReturn(array('code'=>'0','msg'=>'申请失败'));
        }else{
            $lat = addslashes(cookie('lat'));
            $lng = addslashes(cookie('lng'));
            if(empty($lat) || empty($lng)) {
                $lat = $this->_CONFIG['site']['lat'];
                $lng = $this->_CONFIG['site']['lng'];
            }
            $this->assign('lat', $lat);
            $this->assign('lng', $lng);
			$this->assign('shop', $shop);
			$this->assign('detail', $shop);
            $this->assign('cates', D('Shopcate')->fetchAll());
			
			$this->assign('schools',$schools = M('RunningSchool')->where(array('closed'=>0))->order(array('school_id'=>'desc'))->select());
			
            $this->display();
        }
    }
	
	
    public function forget(){
        $way = (int) $this->_param('way');
        $this->assign('way', $way);
        $this->display();
    }
	
	

    public function newpwd(){
        $mobile = htmlspecialchars($_POST['mobile']);
		$count = D('Users')->where(array('mobile'=>$mobile,'closed'=>0))->count();
		if($count > 1){
			session('scode', null);
			$this->ajaxReturn(array('code'=>'0','msg'=>'参数错误，请联系管理员解决'));
		}
		if(!$Users = D('Users')->where(array('mobile'=>$mobile))->find()){
			session('scode', null);
			$this->ajaxReturn(array('code'=>'0','msg'=>'手机号码输入错误'));
        }
        if(!($scode = trim($_POST['scode']))){
			$this->ajaxReturn(array('code'=>'0','msg'=>'请输入短信验证码'));
        }
        $scode2 = session('scode');
        if(empty($scode2)){
			$this->ajaxReturn(array('code'=>'0','msg'=>'请获取短信验证码'));
        }
        if($scode != $scode2){
			$this->ajaxReturn(array('code'=>'0','msg'=>'请输入正确的短信验证码'));
        }
		
		$password = htmlspecialchars($_POST['password']);
		if(empty($password)){
			$this->ajaxReturn(array('code'=>'0','msg'=>'密码不能为空'));
        }
		$password2 = htmlspecialchars($_POST['password2']);
		if($password != $password2){
			$this->ajaxReturn(array('code'=>'0','msg'=>'二次密码输入不一致'));
        }
		if(D('Passport')->uppwd($Users['account'], '',$password)){
			session('scode', null);
			$this->ajaxReturn(array('code'=>'1','msg'=>'恭喜您更新密码成功','url'=>U('passport/login')));
		}else{
			session('scode', null);
			$this->ajaxReturn(array('code'=>'0','msg'=>'更新密码失败'));
		}
    }
	
	
    public function findsms(){
        if(!($mobile = htmlspecialchars($_POST['mobile']))){
            die('请输入正确的手机号码');
        }
        if(!isMobile($mobile)){
            die('请输入正确的手机号码');
        }
		
        if($user = D('Users')->getUserByAccount($account)){
            if(empty($user['mobile'])){
                die('你还未绑定手机号，请选择其他方式');
            }else{
                if($user['mobile'] != $mobile){
                    die('请填写您的绑定手机号');
                }
            }
        }
        $randstring = session('scode');
        if(empty($randstring)){
            $randstring = rand_string(6,1);
            session('scode', $randstring);
        }
		D('Sms')->sms_yzm($mobile,$randstring);
        die('1');
    }
	
   
   
    public function register(){
        if($this->isPost()){
			
            if(isMobile(htmlspecialchars($_POST['account']))){
                if(!($scode = trim($_POST['scode']))){
					$this->ajaxReturn(array('code'=>0,'msg'=>'请输入短信验证码'));
                }
                $scode2 = session('scode');
                if(empty($scode2)){
					$this->ajaxReturn(array('code'=>0,'msg'=>'请获取短信验证码'));
                }
                if($scode != $scode2){
					$this->ajaxReturn(array('code'=>0,'msg'=>'请输入正确的短信验证码'));
                }
            }
			
            $data = $this->checkFields($this->_post('data', false),array('account','password','mobile_','requestCode','nickname'));
			$data['fuid1'] = htmlspecialchars($_POST['fuid1']);
			$data['account'] = htmlspecialchars($_POST['account']);
			
			
		
			if(!isMobile($data['account'])){
				session('verify', null);
				$this->ajaxReturn(array('code'=>0,'msg'=>'只允许手机号注册，请检查手机号是否正确'));
			}
			$data['password'] = htmlspecialchars($data['password']);
			
			if(empty($data['password'])){
				$this->ajaxReturn(array('code'=>0,'msg'=>'请输入密码'));
            }
            
			
			$data['nickname'] = $data['account'];
			$data['token'] = $data['token'];
			$data['ext0'] = $data['account'];
			$data['mobile'] = $data['account'];
			
			$backurl = $this->_post('backurl', 'htmlspecialchars');
            if(empty($backurl)){
                $backurl = U('index/index');
            }
			
            if(true == D('Passport')->register($data,$users['user_id'],0)){
				$this->ajaxReturn(array('code'=>1,'msg'=>'恭喜您注册成功','url'=>$backurl));
            }else{
				$this->ajaxReturn(array('code'=>0,'msg'=>D('Passport')->getError()));
			}
			
			
        }else{
            
			$backurl =  cookie('backurl');
			if(!$backurl){
				if(!empty($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) && !strstr($_SERVER['HTTP_REFERER'], 'passport')){
					$backurl = $_SERVER['HTTP_REFERER'];
				}else{
					$backurl = U('index/index');
				}
			}
            $this->assign('backurl', $backurl);
			
            $this->display();
        }
    }
	
	
	
	
	
    public function sendsms(){
        if(!($mobile = htmlspecialchars($_POST['mobile']))){
            die('请输入正确的手机号码');
        }
        if(!isMobile($mobile)){
            die('请输入正确的手机号码');
        }
		
        if($user = D('Users')->getUserByAccount($mobile)){
            die('手机号码已经存在');
        }
		
        $randstring = session('scode');
		if(!empty($randstring)){
			session('scode',null);
		}
        $randstring = rand_string(4,1);
        session('scode', $randstring);
		D('Sms')->sms_yzm($mobile,$randstring);//发送短信
        die('1');
    }
	

    public function logout(){
        D('Passport')->logout();
        $this->success('退出登录成功', U('passport/login'));
    }
	
	
	
}