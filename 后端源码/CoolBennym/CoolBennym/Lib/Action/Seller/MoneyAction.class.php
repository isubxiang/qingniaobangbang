<?php
class MoneyAction extends CommonAction{
    public function _initialize(){
        parent::_initialize();
		$this->assign('types', $types = D('Shopmoney')->getType());
    }
	

	
    public function index(){
        $bg_time = strtotime(TODAY);
        $counts = array();
        //财务管理
        $counts['money'] = (int) D('Shopmoney')->where(array('shop_id' => $this->shop_id))->sum('money');
        //这个统计今日，要求统计昨日数据，+最近7天总收入。
        $str = '-1 day';
        $bg_time_yesterday = strtotime(date('Y-m-d', strtotime($str)));
        $counts['money_day'] = (int) D('Shopmoney')->where(array('create_time' => array(array('ELT', NOW_TIME), array('EGT', $bg_time)), 'shop_id' => $this->shop_id))->sum('money');
   
        $counts['money_day_yesterday'] = (int) D('Shopmoney')->where(array('create_time' => array(array('ELT', $bg_time), array('EGT', $bg_time_yesterday)), 'shop_id' => $this->shop_id))->sum('money');
        
        $this->assign('counts', $counts);
        $this->display();
    }
	
	
	public function detail(){
        $this->display();
    }
	


    public function load(){
        $map = array('shop_id' => $this->shop_id);
        $money = D('Shopmoney');
        import('ORG.Util.Page');
        $count = $money->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
		$var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if($Page->totalPages < $p){
            die('0');
        }		
        $list = $money->where($map)->order(array('money_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$type = D('Shopmoney')->get_money_type($val['type']);
            $list[$k]['type'] = $type;
			
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	
    public function cashlogs(){
        $map = array('shop_id' => $this->shop_id,'type' => shop);
        import('ORG.Util.Page');
        $count = M('UsersCash')->where($map)->count();
        $Page = new Page($count, 16);
        $show = $Page->show();
        $list = M('UsersCash')->where($map)->order(array('cash_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	
	
    public function cash(){
		
		$Shop = M('Shop')->where(array('user_id'=>$this->uid))->find();
		if(!$Shop){
			$this->error('您是店员不是商家无法操作');
		}
		
		
		if(false == D('Userscash')->check_cash_addtime($this->uid,2)){
			$this->error('您提现太频繁了，明天再来试试吧');
		}	
		
			
        $Shop = M('Shop')->where(array('shop_id'=>$this->shop_id))->find();
        $UsersCash = M('UsersCash')->where(array('user_id' =>$Shop['user_id']))->find();
        $Shop2 = M('Shop')->where(array('user_id'=>$user_id))->find();
		
        if($Shop2 == ''){
            $cash_money = $this->_CONFIG['cash']['user'];
            $cash_money_big = $this->_CONFIG['cash']['user_big'];
        }elseif($Shop['is_renzheng'] == 0){
            $cash_money = $this->_CONFIG['cash']['shop'];
            $cash_money_big = $this->_CONFIG['cash']['shop_big'];
        }elseif($Shop['is_renzheng'] == 1){
            $cash_money = $this->_CONFIG['cash']['renzheng_shop'];
            $cash_money_big = $this->_CONFIG['cash']['renzheng_shop_big'];
        }else{
            $cash_money = $this->_CONFIG['cash']['user'];
            $cash_money_big = $this->_CONFIG['cash']['user_big'];
        }
		
		
		$Connect = M('Connect')->where(array('uid' => $this->uid,'type'=>weixin))->find();
		
		
        //对比手机号码，验证码
        $shop = M('Shop')->where(array('shop_id' => $this->shop_id))->find();
        $users = M('Users')->where(array('user_id' => $shop['user_id']))->find();
        $s_mobile = session('mobile');
        $cash_code = session('cash_code');
        //获取life_code
        if(IS_POST){
            $gold = (int) ($_POST['gold'] * 100);
            if($gold <= 0) {
                $this->ajaxReturn(array('code'=>'0','msg'=>'提现金额不能小于0'));
            }
            if($gold < $cash_money * 100){
                $this->ajaxReturn(array('code'=>'0','msg'=>'提现金额小于最低提现额度'));
            }
            if($gold > $cash_money_big * 100) {
                $this->ajaxReturn(array('code'=>'0','msg'=>'您单笔最多能提现' . $cash_money_big . '元'));
            }
            if($gold > $this->member['gold'] || $this->member['gold'] == 0){
				$this->ajaxReturn(array('code'=>'0','msg'=>'商户资金不足，无法提现'));
            }
			
			if(!($code = $this->_post('code'))){
				$this->ajaxReturn(array('code'=>'0','msg'=>'请选择提现方式'));
			}
			
			if($code == 'bank'){
				if(!($data['bank_name'] = htmlspecialchars($_POST['bank_name']))){
					$this->ajaxReturn(array('code'=>'0','msg'=>'开户行不能为空'));
				}
				if(!($data['bank_num'] = htmlspecialchars($_POST['bank_num']))){
					$this->ajaxReturn(array('code'=>'0','msg'=>'银行账号不能为空'));
				}
				if(!is_numeric($data['bank_num'])){
					$this->ajaxReturn(array('code'=>'0','msg'=>'银行账号只能为数字'));
				}
				if(strlen($data['bank_num']) < 15){
					$this->ajaxReturn(array('code'=>'0','msg'=>'银行账号格式不正确'));
				}
				if(!($data['bank_realname'] = htmlspecialchars($_POST['bank_realname']))){
					$this->ajaxReturn(array('code'=>'0','msg'=>'开户姓名不能为空'));
				}
				$data['bank_branch'] = htmlspecialchars($_POST['bank_branch']);
				
			}
	
            $data['user_id'] = $this->uid;
			
			
			if(empty($Connect['open_id'])){
				if($code == weixin){
					$this->ajaxReturn(array('code'=>'0','msg'=>'您非微信登录，暂时不能选择微信提现方式'));
				}
			}
			
			if($code == weixin){
				if (!($data['re_user_name'] = htmlspecialchars($_POST['re_user_name']))) {
					$this->ajaxReturn(array('code'=>'0','msg'=>'请填写真实姓名'));
				}
				if ($data['re_user_name'] == '输入真实姓名') {
					$this->ajaxReturn(array('code'=>'0','msg'=>'请填写真实姓名'));
				}
			}
			
			
			if($code == 'alipay'){
				if(!($data['alipay_account'] = htmlspecialchars($_POST['alipay_account']))){
					$this->ajaxReturn(array('code'=>'0','msg'=>'支付宝账户不能为空'));
				}
				if(!($data['alipay_real_name'] = htmlspecialchars($_POST['alipay_real_name']))){
					$this->ajaxReturn(array('code'=>'0','msg'=>'支付宝真实姓名不能为空'));
				}
			}
			
			
			if(!empty($this->_CONFIG['cash']['shop_cash_commission'])){
				$commission = intval(($gold * $this->_CONFIG['cash']['shop_cash_commission'])/100);
			}
			
            $arr = array();
            $arr['user_id'] = $this->uid;
            $arr['shop_id'] = $this->shop_id;
			$arr['school_id'] = $shop['school_id'];
			
            //提现商家
            $arr['city_id'] = $shop['city_id'];
            $arr['area_id'] = $shop['area_id'];
            $arr['gold'] = $gold - $commission;
			$arr['commission'] = $commission;
            $arr['type'] = shop;
			$arr['code'] = $code;;
            $arr['addtime'] = NOW_TIME;
            $arr['account'] = $this->member['account'];
            $arr['bank_name'] = $data['bank_name'];
            $arr['bank_num'] = $data['bank_num'];
            $arr['bank_realname'] = $data['bank_realname'];
            $arr['bank_branch'] = $data['bank_branch'];
			$arr['re_user_name'] = $data['re_user_name'];
			$arr['alipay_account'] = $data['alipay_account'];
			$arr['alipay_real_name'] = $data['alipay_real_name'];
			
			if(!empty($commission)){
				$intro = $shop['shop_name'].'申请提现，扣款'.round($gold/100,2).'元，其中手续费：'.round($commission/100,2).'元';
			}else{
				$intro = $shop['shop_name'].'申请提现，扣款'.round($gold/100,2).'元';
			}
			
			
			D('Users')->addGold($this->uid, -$gold, $intro);
			
			
			if($cash_id = M('UsersCash')->add($arr)){
				
				//保存提现日志
				$getUserex = D('Usersex')->getUserex($this->uid);
			
				if($getUserex){
					//p(2);
					M('users_ex')->save($data);
				}else{
					M('users_ex')->add($data);
				}
				
				
				//p(1);die;
				
				$this->ajaxReturn(array('code'=>'1','msg'=>'恭喜，申请提现成功，请等待管理员审核','url'=>U('money/cashlogs')));
			}else{
				$this->ajaxReturn(array('code'=>'0','msg'=>'抱歉，提现操作失败'));
			}	
			
        }else{
            $this->assign('info', D('Usersex')->getUserex($this->uid));
            $this->assign('gold', $this->member['gold']);
            $this->assign('cash_money', $cash_money);
			$this->assign('connect', $Connect);
            $this->assign('cash_money_big', $cash_money_big);
            $this->assign('userscash', $userscash);
            $this->display();
        }
    }
}