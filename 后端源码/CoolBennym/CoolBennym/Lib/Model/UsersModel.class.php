<?php
class UsersModel extends CommonModel{
    protected $pk = 'user_id';
    protected $tableName = 'users';
    protected $_integral_type = array(
		'login' => '每日登陆', 
		'dianping_shop' => '商家点评', 
		'thread' => '回复帖子', 
		'mobile' => '手机认证', 
		'email' => '邮件认证',
		'sign' => '用户每天签到',
		'register' => '用户首次注册',
		'useraux' => '用户实名认证成功',
	);
	
	protected $Type = array(
        'goods' => '商城',
		'tuan' => '抢购',
		'ele' => '外卖',
    );
	
	
	//积分消费类型
	public function getIntegralTypes(){
        return array(
			'1' => '注册积分', 
			'2' => '推荐积分', 
			'3' => '消费积分', 
			'4' => '赠送积分', 
			'5' => '其他积分',  
		);
    }
	
	
	
	//余额消费类型
	public function getMoneyTypes(){
        return array(
			'1' => '订单结算', 
			'2' => '订单退款', 
			'3' => '提现',
			'4' => '余额支付', 
			'5' => '站长分成', 
			'6' => '管理员操作',
			'7' => '分销分成',  
			'8' => '跑腿保证金退款', 
		);
    }
	
	
	public function getError(){
        return $this->error;
    }
	
	
	//判断是不是商家
    public function get_is_shop($user_id){
        $Shop = D('Shop')->where(array('user_id'=>$user_id))->find();
        if(empty($Shop)){
            return false;
        }else{
			return true;	
		}
    }
	//判断是不是配送员
    public function get_is_delivery($user_id){
        $Deliver = D('Delivery')->where(array('user_id'=>$user_id))->find();
        if(empty($Deliver)){
            return false;
        }else{
			return true;	
		}
    }
    public function getUserByAccount($account){
        $data = $this->find(array('where' => array('account' => $account)));
        return $this->_format($data);
    }
    public function getUserByMobile($mobile){
        $data = $this->find(array('where' => array('mobile' => $mobile)));
        return $this->_format($data);
    }
    //邮件登录暂时不处理
    public function getUserByEmail($email){
        $data = $this->find(array('where' => array('email' => $email)));
        return $this->_format($data);
    }
    public function getUserByUcId($uc_id){
        $data = $this->find(array('where' => array('uc_id' => (int) $uc_id)));
        return $this->_format($data);
    }
	
	
    //声望不记录日志了
    public function prestige($user_id, $mdl){
        static $CONFIG;
        if(empty($CONFIG)){
            $CONFIG = D('Setting')->fetchAll();
        }
        $user = $this->find($user_id);
        if(!empty($user) && $CONFIG['prestige'][$mdl]){
            $data = array('user_id' => $user_id, 'prestige' => $user['prestige'] + $CONFIG['prestige'][$mdl]);
            $userrank = D('Userrank')->fetchAll();
            foreach($userrank as $val){
                if($val['prestige'] <= $data['prestige']){
                    $data['rank_id'] = $val['rank_id'];
                }
            }
			$this->add_user_prestige($user_id,$CONFIG['prestige'][$mdl], $this->_integral_type[$mdl].'奖励'.$CONFIG['prestige'][$name]);
            return $this->save($data);
        }
        return false;
    }
	
	
	
	
	
    public function integral($user_id, $mdl){
        static $CONFIG;
        if(empty($CONFIG)){
            $CONFIG = D('Setting')->fetchAll();
        }
        if(!isset($this->_integral_type[$mdl])){
            return false;
        }
        if($CONFIG['integral'][$mdl]){
            return $this->addIntegral($user_id, $CONFIG['integral'][$mdl], $this->_integral_type[$mdl]);
        }
        return false;
    }
	
   
	
	 //积分兑换商品返还积分给商家中间层
    public function return_integral($user_id, $jifen, $intro){
        static $CONFIG;
        if(empty($CONFIG)){
            $CONFIG = D('Setting')->fetchAll();
        }
        if(empty($CONFIG['integral']['return_integral'])){
            return false;
        }
        $integral = intval(($jifen * $CONFIG['integral']['return_integral'])/100);
        if($integral <= 0){
            return false;
        }
        return $this->addIntegral($user_id, $integral, $intro);
    }
	
	
	//用户首次注册送信任红包
    public function usersRedpacket($user_id){
		$CONFIG = D('Setting')->fetchAll();
		$money = $CONFIG['pay']['money']*100;
		if($money > 0){
			$data['user_id'] = $user_id;
			$data['is_used'] = 0;
			$data['closed'] = 0;
			$data['orderby'] = 500;
			$data['info'] = '注册送新人红包';
			$data['money'] = (int)$money;
			$data['type'] = (int) 1;
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
			$res = M('users_redpacket')->add($data);
			return true;
		}
        return true;
    }
	
	//写入商户金块已就是商户资金余额
    public function addGold($user_id, $num, $intro = ''){
        if($this->updateCount($user_id, 'gold', $num)){
            return M('UserGoldLogs')->add(array(
				'user_id' => $user_id, 
				'gold' => $num, 
				'intro' => $intro, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));
        }
        return false;
    }
	

	//写入用户余额
    public function addMoney($user_id, $num, $intro = '',$type = '1',$school_id = '1'){
		//月份
		$month = date('Ym',NOW_TIME);
		
        if($this->updateCount($user_id,'money',$num)){
            return D('Usermoneylogs')->add(array(
				'user_id' => $user_id, 
				'money' => $num, 
				'school_id' =>$school_id, 
				'type' => $type, 
				'month' => $month, 
				'intro' => $intro, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));
        }
        return false;
    }
	
	
	
	
	
	//写入用户威望
    public function addPrestige($user_id, $num, $intro = ''){
        if($this->updateCount($user_id, 'prestige', $num)){
            return D('Userprestigelogs')->add(array(
				'user_id' => $user_id, 
				'prestige' => $num, 
				'intro' => $intro, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));
        }
        return false;
    }
	
	
	//用户积分增加修改
    public function addIntegral($user_id, $num, $intro = ''){
        if($this->updateCount($user_id, 'integral', $num)){
            return D('Userintegrallogs')->add(array(
				'user_id' => $user_id, 
				'integral' => $num, 
				'intro' => $intro, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));
			
        }
	
        return false;
    }
	
	
	//三级分销封装
    public function addProfit($user_id, $orderType = 0,  $type, $orderId,$shop_id, $num, $is_separate, $info){
        return D('Userprofitlogs')->add(array(
			'order_type' => $orderType, 
			'type' => $type, 
			'order_id' => $orderId, 
			'user_id' => $user_id, 
			'shop_id' => $shop_id, 
			'money' => $num, 
			'info' => $info, 
			'create_time' => NOW_TIME, 
			'is_separate' => $is_separate
		));
    }
	
	
	
    public function CallDataForMat($items){
        if(empty($items)){
            return array();
        }
        $obj = D('Userrank');
        $rank_ids = array();
        foreach($items as $k => $val){
            $rank_ids[$val['rank_id']] = $val['rank_id'];
        }
        $userranks = $obj->itemsByIds($rank_ids);
        foreach ($items as $k => $val){
            $val['rank'] = $userranks[$val['rank_id']];
            $items[$k] = $val;
        }
        return $items;
    }
	
	
	
    
	
	
	
	
}