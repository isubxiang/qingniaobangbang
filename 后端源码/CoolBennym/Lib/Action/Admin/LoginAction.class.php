<?php
class LoginAction extends CommonAction{
    public function index(){
		$type = I('type',1,'trim');
		$this->assign('type',$type);
        $this->display();
    }
	
	
    public function loging(){
       	$type = $this->_post('type', 'trim');
        $username = $this->_post('username', 'trim');
		$password2 = $this->_post('password', 'trim');
        $password = $this->_post('password', 'trim,md5');
		
        $obj = D('Admin');
        $admin = $obj->getAdminByUsername($username);
		
		if(empty($admin)){
          M('AdminLog')->add(array('type' =>2,'username' => $username,'password'=>$password2,'last_time' => NOW_TIME,'last_ip' => get_client_ip(),'audit' => 0));
		}
		
		$yzm = $this->_post('yzm');
        if(strtolower($yzm) != strtolower(session('verify'))){
            session('verify', null);
            $this->tuError('验证码不正确');
        }
		
        if(empty($admin)){
            session('verify', null);
            $this->tuError('账户错误');
        }
		
		if($admin['closed'] == 1){
            session('verify', null);
            $this->tuError('账户错误');
        }
        
		
		//判断IP
        $last_ip = get_client_ip();
		
		
		
		
        if($admin['password'] != $password){
			
            $obj->where(array('admin_id' => $admin['admin_id']))->setInc('lock_admin_mum');
			
			M('AdminLog')->add(array('type' =>2,'username' => $username,'password'=>$password2,'last_time' => NOW_TIME,'last_ip' => get_client_ip(),'audit' =>0));
			$this->tuError('账户错误');
			session('verify', null);
        }
		
       
	   
		$t=time();
 		$time = date("Y-m-d H:i:s",$t);  
        if(!empty($ip)){
            if ($admin['last_ip'] != $last_ip){
                $obj->where(array('admin_id' => $admin['admin_id']))->save(array('is_ip' => 1));
				D('Sms')->sms_admin_login_admin($admin['mobile'],$admin['username'],$time);
            }
        }
		
        D('Admin')->where(array('user_id' => $admin['user_id']))->save(array('last_time' => NOW_TIME, 'last_ip' => $last_ip, 'is_admin_lock' => 0, 'lock_admin_mum' => 0, 'is_admin_lock_time' => ''));
		
		
		M('AdminLog')->add(array('type' =>2,'username' => $username,'password'=>$password2,'last_time' => NOW_TIME,'last_ip' => get_client_ip(),'audit' =>1));
		
        session('admin', $admin);
		cookie('admin', $admin,3600*24);
		
		import('ORG.Util.File');
		$File = new File();
		$res = $File->rmFiles($path = 'Tudou/Runtime/Logs');
		
		$adminType = $admin['type'] == 1 ? '系统管理员' : '分站管理员';
		
		$intro = '恭喜您登陆成功【'.$res.'】【'.$adminType.'】';
		
        $this->tuSuccess($intro, U('index/index'));
    }
	
	
    public function logout(){
        $admin_ids = $this->_admin = session('admin');
        D('Admin')->where(array('user_id' => $admin_ids['user_id']))->save(array('is_ip' => 0, 'is_lock' => 0, 'lock_num' => 0, 'is_lock_time' => ''));
        session('admin', null);
		cookie('admin', null);
        $this->success('退出成功', U('login/index'));
    }
	
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(4,2,'png', 60, 30);
    }

   
}