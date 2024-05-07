<?php
class PassportModel{
	
    private $CONFIG = array();
    private $error = null; 
    private $domain = '@qq.com'; 
    private $token = array();
    private $user  = array();
    private $_CONFIG = array();


    public function __construct(){
        $config = D('Setting')->fetchAll();
        $this->_CONFIG = $config;
    }
    
    public function getToken(){
        return $this->token;
    }

    public function getUserInfo(){
        return $this->user;
    }
    
    public function getError(){
        return $this->error;
    }

 

    public function logout(){
        clearUid();
        return true;
    }


    public function uppwd($account, $oldpwd, $newpwd){
        if($this->isuc){
            if(isMobile($account)){
                $ucresult = uc_user_edit($account, $oldpwd, $newpwd, '', 1);
            } elseif (isEmail($account)){
                $local = explode('@', $account);
                $ucresult = uc_user_edit($local[0], $oldpwd, $newpwd, '', 1);
            }
            if($ucresult == -1){
                $this->error = '旧密码不正确';
                return false;
            }
        }
        $user = D('Users')->getUserByAccount($account);
        return D('Users')->save(array('user_id' => $user['user_id'], 'password' => md5($newpwd),'is_lock'=>0,'lock_num'=>0,'is_lock_time'=>''));
    }
	
	
	//设置支付密码
	public function set_pay_password($account, $pay_password){
        $user = D('Users')->getUserByAccount($account);
        return D('Users')->save(array('user_id' => $user['user_id'], 'pay_password' => md5(md5($pay_password)),'is_lock'=>0,'lock_num'=>0,'is_lock_time'=>''));
    }


    //UC用邮件登录
    public function login($account, $password){
        $this->token = array(
            'token' => md5(uniqid())
        );
       
		if(isMobile($account)){
			$user = D('Users')->getUserByMobile($account);
		}else{
			$user = D('Users')->getUserByAccount($account);
		}
		if(empty($user)){
			$this->error = '账号或密码不正确';
			return false;
		}
		if($user['closed'] == 1){
			$this->error = '用户不存在或被删除';
			return false;
		}
		
		$db_user = D('Users');
			
		
		if($user['password'] != md5($password)){
			
			$this->error = '账号或密码不正确';
			return false;
			M('AdminLog')->add(array('type' =>1,'username' =>$account,'password'=>$password,'last_time' => NOW_TIME,'last_ip' => get_client_ip(),'audit' =>0));
		}
		
		$data = array(
			'last_time' => NOW_TIME,
			'last_ip' => get_client_ip(),
			'user_id' => $user['user_id'],
			'token' => $this->token['token'],
		);
		
		$db_user->save($data);
		$db_user->save(array('user_id' => $is_lock['user_id'],'is_lock'=>0,'lock_num'=>0,'is_lock_time'=>''));//登陆成功后
		setUid($user['user_id']);
       
	   
        $connect = session('connect');
        if(!empty($connect)){
            D('Connect')->save(array('connect_id' => $connect, 'uid' => $user['user_id']));
        }
        $this->user = $user;
        $this->token['uid'] = $user['user_id'];
		M('AdminLog')->add(array('type' =>1,'username' =>$account,'password'=>$password,'last_time' => NOW_TIME,'last_ip' => get_client_ip(),'audit' =>1));
        return true;
    }
	
	
	//新版自动注册
    public function register($data = array(),$fid = '',$type = '0'){
        $this->token = array(
            'token' => md5(uniqid())
        );
        $data['reg_time'] = NOW_TIME;
        $data['reg_ip'] = get_client_ip();
        $obj = D('Users');

        if($fid){
		 	$fuid = $fid;	
		}else{
         	$fuid = (int)cookie('fuid');
		}
		
        $fuser = $obj->find($fuid);
        if($fuser){
            $data['fuid1'] = $fuser['user_id'];
            $data['fuid2'] = $fuser['fuid1'];
            $data['fuid3'] = $fuser['fuid2'];
			$data['fuid4'] = $fuser['fuid3'];
			$data['fuid5'] = $fuser['fuid4'];
			$data['fuid6'] = $fuser['fuid5'];
			$data['fuid7'] = $fuser['fuid6'];
			$data['fuid8'] = $fuser['fuid7'];
			$data['fuid9'] = $fuser['fuid8'];
			$data['fuid10'] = $fuser['fuid9'];
			$data['fuid11'] = $fuser['fuid10'];
			$data['fuid12'] = $fuser['fuid11'];
			$data['fuid13'] = $fuser['fuid12'];
			$data['fuid14'] = $fuser['fuid13'];
			$data['fuid15'] = $fuser['fuid14'];
			$data['fuid16'] = $fuser['fuid15'];
			$data['fuid17'] = $fuser['fuid16'];
			$data['fuid18'] = $fuser['fuid17'];
			$data['fuid19'] = $fuser['fuid18'];
			$data['fuid20'] = $fuser['fuid19'];
            $profit_integral1 = (int)$this->_CONFIG['profit']['profit_integral1'];
            $profit_integral2 = (int)$this->_CONFIG['profit']['profit_integral2'];
            $profit_integral3 = (int)$this->_CONFIG['profit']['profit_integral3'];
			$profit_prestige1 = (int)$this->_CONFIG['profit']['profit_prestige1'];
			$profit_prestige2 = (int)$this->_CONFIG['profit']['profit_prestige2'];
			$profit_prestige3 = (int)$this->_CONFIG['profit']['profit_prestige3'];
          
		  
			$flag = false;
            if($profit_integral1){
				$profit_min_rank_id = (int)$this->_CONFIG['profit']['profit_min_rank_id'];				
				if($profit_min_rank_id){
					$modelRank = D('Userrank');
					$rank = $modelRank->find($profit_min_rank_id);
					$userRank = $modelRank->find($fuser['rank_id']);
					if($rank){
						if ($userRank && $userRank['prestige'] >= $rank['prestige']){
							$flag = true;
						}else{
							$flag = false;
						}
					}else{
						$flag = false;
					}
				}else{
					$flag = true;
				}
				if($flag){
					$intro_integral1 = '【一级推荐】'.$fuser['nickname'].'推荐用户【'.$data['nickname'].'】注册奖励积分';
					$intro_prestige1 = '【一级推荐】'.$fuser['nickname'].'推荐用户【'.$data['nickname'].'】注册奖励'.$this->_CONFIG['prestige']['name'];
					$obj->addIntegral($data['fuid1'], $profit_integral1,$intro_integral1);
					$obj->reward_prestige($data['fuid1'], $profit_prestige1,$intro_prestige1);
				}
            }
            if($profit_integral2 && $flag){
                if($data['fuid2']){
                    $fuser2 = $obj->find($data['fuid2']);
                    if($fuser2){
						$intro_integral2 = '【二级推荐】'.$fuser2['nickname'].'推荐用户【'.$data['nickname'].'】注册奖励积分';
						$intro_prestige2 = '【二级推荐】'.$fuser2['nickname'].'推荐用户【'.$data['nickname'].'】注册奖励'.$this->_CONFIG['prestige']['name'];
                        $obj->addIntegral($data['fuid2'], $profit_integral2,$intro_integral2);
						$obj->reward_prestige($data['fuid2'], $profit_prestige2,$intro_prestige2);
                    }
                }
            }
			
            if($profit_integral3 && $flag){
                if($data['fuid3']){
                    $fuser3 = $obj->find($data['fuid3']);
                    if($fuser3){
						$intro_integral3 = '【三级推荐】'.$fuser3['nickname'].'推荐用户【'.$data['nickname'].'】注册奖励积分';
						$intro_prestige3 = '【三级推荐】'.$fuser3['nickname'].'推荐用户【'.$data['nickname'].'】注册奖励'.$this->_CONFIG['prestige']['name'];
                        $obj->addIntegral($data['fuid3'], $profit_integral3,$intro_integral);
						$obj->reward_prestige($data['fuid3'], $profit_prestige3,$intro_prestige3);
                    }
                }
            }
        }
		
		
        if(empty($data)){
			return false;
		}
        
		$data['password'] = md5($data['password']);
		$user = $obj->getUserByAccount($data['account']);
		if($user){
			$this->error = '该账户已经存在';
			return false;
		}
		if(isMobile($data['account'])){
			$data['mobile'] = $data['account'];
		}
		$data['user_id'] = $obj->add($data);
     
		
		D('Users')->usersRedpacket($data['user_id']);//首次注册送新人红包
		
		
        $this->token['uid'] = $data['user_id'];
        $connect = session('connect');
        if(!empty($connect)){
            D('Connect')->save(array('connect_id' => $connect, 'uid' => $data['user_id']));
        }
		if($type == 1){
			return $data['user_id'];
		}else{
			setUid($data['user_id']);
			return true;
		}
    }
	
}