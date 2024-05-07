<?php
class UserAction extends CommonAction{
    private $create_fields = array('account', 'password', 'pay_password','rank_id','city_id','is_backers','is_user_earnest', 'face','mobile', 'email', 'nickname', 'face', 'ext0');
    private $edit_fields = array('account', 'password','pay_password', 'rank_id','city_id','is_backers','is_user_earnest', 'face', 'mobile', 'email', 'nickname', 'face', 'ext0');
	
	private $binding_edit_fields = array('user_id','uid','open_id','openid','nickname');
   
   	public function _initialize(){
        parent::_initialize();
		$this->assign('citys', D('City')->fetchAll());
    }
   
   
   
    public function index(){
        $User = D('Users');
        import('ORG.Util.Page');
        $map = array('closed'=>0);
        if($keyword = $this->_param('keyword','htmlspecialchars')){
            $map['user_id|account|nickname|mobile|email|ext0'] = array('LIKE','%'.$keyword.'%');
            $this->assign('keyword',$keyword);
        }
        if($rank_id = (int) $this->_param('rank_id')){
            $map['rank_id'] = $rank_id;
            $this->assign('rank_id', $rank_id);
        }
        $getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
	
		if(isset($_GET['is_prestige_frozen']) || isset($_POST['is_prestige_frozen'])){
            $is_prestige_frozen = (int) $this->_param('is_prestige_frozen');
            if($is_prestige_frozen != 999) {
                $map['is_prestige_frozen'] = $is_prestige_frozen;
            }
            $this->assign('is_prestige_frozen', $is_prestige_frozen);
        }else{
            $this->assign('is_prestige_frozen', 999);
        }
		
		if(isset($_GET['is_aux']) || isset($_POST['is_aux'])){
            $is_aux = (int) $this->_param('is_aux');
            if($is_aux != 999) {
                $map['is_aux'] = $is_aux;
            }
            $this->assign('is_aux', $is_aux);
        }else{
            $this->assign('is_aux', 999);
        }
		
		if(isset($_GET['is_lock']) || isset($_POST['is_lock'])){
            $is_lock = (int) $this->_param('is_lock');
            if($is_lock != 999) {
                $map['is_lock'] = $is_lock;
            }
            $this->assign('is_lock', $is_lock);
        }else{
            $this->assign('is_lock', 999);
        }
	
		
        $count = $User->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $User->where($map)->order(array('user_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$rank_ids = array();
        foreach ($list as $k => $val) {
			$rank_ids[$val['rank_id']] = $val['rank_id'];
            $val['reg_ip_area'] = $this->ipToArea($val['reg_ip']);
            $val['last_ip_area'] = $this->ipToArea($val['last_ip']);
            $val['is_shop'] = $User->get_is_shop($val['user_id']);
			$val['is_weixin'] = D('Connect')->check_connect_bing($val['user_id'],1);
			$list[$k] = $val;
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('ranks', D('Userrank')->fetchAll());
		$this->assign('rank', D('Userrank')->itemsByIds($rank_ids));
		session('user_index_list', $map);//保存session
        $this->display();
    }
	
	
	
	//会员绑定列表首页
	public function binding(){
        $Connect = D('Connect');
        import('ORG.Util.Page');
        $map = array();
       	if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['nickname|open_id|openid|unionid|uid'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if($uid = (int) $this->_param('uid')){
            $map['uid'] = $uid;
            $this->assign('uid', $uid);
        }
		if(isset($_GET['type']) || isset($_POST['type'])){
            $type = (int) $this->_param('type');
            if ($type == 1) {
                $map['type'] = 'weixin';
            }elseif($type == 2){
				$map['type'] = 'qq';
			}elseif($type == 3){
				$map['type'] = 'weibo';
			}
            $this->assign('type', $type);
        }else{
            $this->assign('type', 999);
        }
		
		
        $count = $Connect->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Connect->where($map)->order(array('connect_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$uids = array();
        foreach($list as $k => $val){
            if($val['uid']){
                $uids[$val['uid']] = $val['uid'];
            }
        }
        $this->assign('users', D('Users')->itemsByIds($uids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	//回收站的会员彻底删除
    public function binding_delete($connect_id = 0){
        $connect_id = (int) $connect_id;
		if(false !== D('Connect')->delete($connect_id)){
			$this->tuSuccess('删除会员绑定成功', U('user/binding'));
		}else{
			$this->tuError('操作失败');
		}
    }
	
	
	
	
	//会员回收站
	public function recycle(){
        $User = D('Users');
        import('ORG.Util.Page');
        $map = array('closed' =>1);
        if($account = $this->_param('account', 'htmlspecialchars')){
            $map['account'] = array('LIKE', '%' . $account . '%');
            $this->assign('account', $account);
        }
        if($nickname = $this->_param('nickname', 'htmlspecialchars')){
            $map['nickname'] = array('LIKE', '%' . $nickname . '%');
            $this->assign('nickname', $nickname);
        }
        if($mobile = $this->_param('mobile', 'htmlspecialchars')){
            $map['mobile'] = array('LIKE', '%' . $mobile . '%');
            $this->assign('mobile', $mobile);
        }
        $count = $User->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $User->where($map)->order(array('user_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$rank_ids = array();
        foreach($list as $k => $val){
			$rank_ids[$val['rank_id']] = $val['rank_id'];
            $val['reg_ip_area'] = $this->ipToArea($val['reg_ip']);
            $val['last_ip_area'] = $this->ipToArea($val['last_ip']);
			$list[$k] = $val;
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
		$this->assign('rank', D('Userrank')->itemsByIds($rank_ids));
        $this->display();
    }
	
	
	
	
	//删除会员重写
    public function delete($user_id = 0){
		
		if($this->_admin['admin_id'] != 1){
			$this->tuError('分站管理员不能执行此操作');
		}
		
        if(is_numeric($user_id) && ($user_id = (int) $user_id)){
            $obj = D('Users');
            $obj->save(array('user_id' => $user_id, 'closed' => 1));
            $this->tuSuccess('已把会员移动到回收站', U('user/index'));
        }else{
            $user_id = $this->_post('user_id', false);
            if(is_array($user_id)){
                $obj = D('Users');
                foreach ($user_id as $id){
				$obj->save(array('user_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('已把会员移动到回收站', U('user/index'));
            }
            $this->tuError('请选择要删除的会员');
        }
    }
	
	
	
	
    public function audit($user_id = 0){
        if(is_numeric($user_id) && ($user_id = (int) $user_id)){
            $obj = D('Users');
            $obj->save(array('user_id' => $user_id, 'closed' => 0));
            $this->tuSuccess('审核成功', U('user/index'));
        }else{
            $user_id = $this->_post('user_id', false);
            if(is_array($user_id)){
                $obj = D('Users');
                foreach($user_id as $id){
                    $obj->save(array('user_id' => $id, 'closed' => 0));
                }
                $this->tuSuccess('审核成功', U('user/index'));
            }
            $this->tuError('请选择要审核的会员');
        }
    }
	
	
	
	//删除会员重写
    public function renew($user_id = 0){
        if(is_numeric($user_id) && ($user_id = (int) $user_id)){
            $obj = D('Users');
            $obj->save(array('user_id' => $user_id, 'closed' => 0));
            $this->tuSuccess('恢复成功', U('user/recycle'));
        }else{
            $user_id = $this->_post('user_id', false);
            if(is_array($user_id)){
                $obj = D('Users');
                foreach($user_id as $id){
				$obj->save(array('user_id' => $id, 'closed' => 0));
                }
                $this->tuSuccess('批量恢复成功', U('user/recycle'));
            }
            $this->tuError('请选择要删除的会员');
        }
    }
	
	
	
	
	//回收站的会员彻底删除，为了数据安全暂时不开放
    public function recycle_delete($user_id = 0){
        $user_id = (int) $user_id;
		
		if(!($detail = D('Users')->find($user_id))){
            $this->tuError('删除的会员不存在');
        }
		
	
		$connect = D('Connect')->where(array('uid'=>$user_id))->select();
		foreach ($connect as $k => $v){
			D('Connect')->delete($v['connect_id']);
        }
		if(false !== D('Users')->delete($user_id)){
			$this->tuSuccess('彻底删除成功', U('user/recycle'));
		}else{
			$this->tuError('操作失败');
		}
	
    }
	
    public function select(){
        $User = D('Users');
        import('ORG.Util.Page');
        $map = array('closed' => array('IN', '0,-1'));
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['account|nickname|mobile|user_id|email|ext0'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        $count = $User->where($map)->count();
        $Page = new Page($count, 8);
        $pager = $Page->show();
        $list = $User->where($map)->order(array('user_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $pager);
        $this->display();
    }
	
	
	
	
	
    public function create(){
		if($this->_admin['admin_id'] != 1){
			$this->tuError('分站管理员不能执行此操作');
		}
        if($this->isPost()){
            $data = $this->createCheck();
            $obj = D('Users');
            if($obj->add($data)){
                $this->tuSuccess('添加成功', U('user/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->assign('ranks', D('Userrank')->fetchAll());
            $this->display();
        }
    }
	
	
    private function createCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['account'] = htmlspecialchars($data['account']);
        if(empty($data['account'])){
            $this->tuError('账户不能为空');
        }
        if(D('Users')->getUserByAccount($data['account'])){
            $this->tuError('该账户已经存在');
        }
        $data['password'] = htmlspecialchars($data['password']);
        if (empty($data['password'])){
            $this->tuError('密码不能为空');
        }
     
		$data['mobile'] = htmlspecialchars($data['mobile']);
		
		if($data['mobile']){
			if(!isMobile($data['mobile'])){
				$this->tuError('手机号格式不正确');
			}
			if($user = D('Users')->getUserByAccount($data['mobile'])){
				$this->tuError('当前手机号已有会员ID【'.$user['user_id'].'】使用请更换手机号');
			}
		}
		
        $data['nickname'] = htmlspecialchars($data['nickname']);
        if(empty($data['nickname'])){
            $this->tuError('昵称不能为空');
        }
        $data['rank_id'] = (int) $data['rank_id'];
        $data['email'] = htmlspecialchars($data['email']);
        $data['face'] = htmlspecialchars($data['face']);
        $data['reg_ip'] = get_client_ip();
        $data['reg_time'] = NOW_TIME;
        return $data;
    }
	
	
	
    public function edit($user_id = 0){
        if($user_id = (int) $user_id) {
            $obj = D('Users');
            if(!($detail = $obj->find($user_id))){
                $this->tuError('请选择要编辑的会员');
            }
            if($this->isPost()){
                $data = $this->editCheck();
                $data['user_id'] = $user_id;
				
				if($data['mobile']){
					$Users = D('Users')->where(array('user_id'=>array('neq',$data['user_id']),'mobile'=>$data['mobile']))->find();
					if($Users){
						$this->tuError('会员ID【'.$Users['user_id'].'】在使用手机号导致重复请更换手机号后提交');
					}
				}
				
                if(false !== $obj->save($data)){
                    $this->tuSuccess('操作成功', U('user/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->assign('ranks', D('Userrank')->fetchAll());
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的会员');
        }
    }
	
	
	
    private function editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['account'] = htmlspecialchars($data['account']);
        if(empty($data['account'])){
            $this->tuError('账户不能为空');
        }
        if($data['password'] == '******'){
            unset($data['password']);
        }else{
            $data['password'] = htmlspecialchars($data['password']);
            if(empty($data['password'])){
                $this->tuError('密码不能为空');
            }
            $data['password'] = md5($data['password']);
        }
		
		
		if($data['mobile']){
			$data['mobile'] = htmlspecialchars($data['mobile']);
			if(!isMobile($data['mobile'])){
				$this->tuError('手机号格式不正确');
			}
		}
		
        $data['nickname'] = htmlspecialchars($data['nickname']);
		if(empty($data['nickname'])){
            $this->tuError('昵称不能为空');
        }
        $data['face'] = htmlspecialchars($data['face']);
        $data['email'] = htmlspecialchars($data['email']);
        $data['rank_id'] = (int) $data['rank_id'];
        return $data;
    }
   
	
    public function integral(){
        $user_id = (int) $this->_get('user_id');
        if(empty($user_id)){
            $this->tuError('请选择用户');
        }
        if(!($detail = D('Users')->find($user_id))){
            $this->tuError('没有该用户');
        }
        if($this->isPost()){
            $integral = (int) $this->_post('integral');
            if($integral == 0){
                $this->tuError('请输入正确的积分数');
            }
            $intro = $this->_post('intro', 'htmlspecialchars');
			if(empty($intro)){
                $this->tuError('积分说明不能为空');
            }
            if($detail['integral'] + $integral < 0){
                $this->tuError('积分余额不足');
            }
            D('Users')->save(array('user_id' => $user_id, 'integral' => $detail['integral'] + $integral));
            D('Userintegrallogs')->add(array(
				'user_id' => $user_id, 
				'integral' => $integral, 
				'intro' => $intro, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));
            $this->tuSuccess('操作成功', U('userintegrallogs/index'));
        }else{
            $this->assign('user_id', $user_id);
            $this->display();
        }
    }
	
	public function gold(){
        $user_id = (int) $this->_get('user_id');
        if(empty($user_id)){
            $this->tuError('请选择用户');
        }
        if(!($detail = D('Users')->find($user_id))){
            $this->tuError('没有该用户');
        }
        if($this->isPost()){
            $gold = (int) ($this->_post('gold') * 100);
            if($gold == 0){
                $this->tuError('请输入正确的商户资金数');
            }
            $intro = $this->_post('intro', 'htmlspecialchars');
			if(empty($intro)){
                $this->tuError('变动商户资金说明不能为空');
            }
            if($detail['gold'] + $gold < 0){
                $this->tuError('商户资金余额不足');
            }
			D('Users')->save(array('user_id' => $user_id, 'gold' => $detail['gold'] + $gold));
            M('UserGoldLogs')->add(array(
				'user_id' => $user_id, 
				'gold' => $gold, 
				'intro' => '管理员后台操作说明：'.$intro, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));

            $this->tuSuccess('操作成功', U('user/index'));
        }else{
            $this->assign('user_id', $user_id);
            $this->display();
        }
    }
	
	
	
    public function money(){
        $user_id = (int) $this->_get('user_id');
        if(empty($user_id)){
            $this->tuError('请选择用户');
        }
        if(!($detail = D('Users')->find($user_id))){
            $this->tuError('没有该用户');
        }
        if($this->isPost()){
            $money = (int) ($this->_post('money') * 100);
            if($money == 0){
                $this->tuError('请输入正确的余额数');
            }
            $intro = $this->_post('intro', 'htmlspecialchars');
			if(empty($intro)){
                $this->tuError('添加余额必须输入说明');
            }
            if($detail['money'] + $money < 0){
                $this->tuError('余额不足');
            }
			
            D('Users')->save(array('user_id'=>$user_id,'money'=>$detail['money']+$money));
			
            D('Usermoneylogs')->add(array(
				'user_id' => $user_id, 
				'money' => $money, 
				'intro' => $intro,
				'type' => 6,
				'school_id' => $detail['school_id'],    
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			));
            $this->tuSuccess('操作成功', U('usermoneylogs/index'));
        }else{
            $this->assign('user_id', $user_id);
            $this->display();
        }
    }
	
	
	
	//会员绑定编辑
	public function binding_edit($connect_id = 0){
        if($connect_id = (int) $connect_id){
            $obj = D('Connect');
            if(!($detail = $obj->find($connect_id))){
                $this->tuError('请选择要编辑的绑定会员');
            }
            if($this->isPost()){
                $data = $this->binding_editCheck();
                $data['connect_id'] = $connect_id;
                if(false !== $obj->save($data)) {
                    $this->tuSuccess('操作成功', U('user/binding'));
                }
                $this->tuError('操作失败');
            }else{
				$this->assign('user', D('Users')->find($detail['uid']));
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的会员');
        }
    }
	
	
	
    private function binding_editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->binding_edit_fields);
        $data['uid'] = (int) $data['user_id'];
        if(empty($data['uid'])){
            $this->tuError('请选择会员');
        }
		$data['open_id'] = htmlspecialchars($data['open_id']);
		if(empty($data['open_id'])){
            $this->tuError('open_id不能为空');
        }
        $data['nickname'] = htmlspecialchars($data['nickname']);
		if(empty($data['nickname'])){
            $this->tuError('昵称不能为空');
        }
        return $data;
    }
	
	
	
	
	
	//会员订单列表导出
    public function export_code(){
		$admin_id = (int) $_POST['admin_id'];
        if(empty($admin_id)) {
            $this->ajaxReturn(array('status' => 'error', 'msg' => '非法错误'));
        }
		$value = $this->_param('value', 'htmlspecialchars');
        if(empty($value)) {
            $this->ajaxReturn(array('status' => 'error', 'msg' => '请填写导出密码'));
        }
		if($value != 123456) {
            $this->ajaxReturn(array('status' => 'error', 'msg' => '导出密码错误'));
        }else{
			session('export_code', md5($admin_id.'--'.$value));
			$this->ajaxReturn(array('status' => 'success', 'msg' => '输入密码成功，正在为你跳转', 'url' => U('user/export',array('admin_id'=>$admin_id,'value'=>$value))));
		}
		
    }
	
	
	
	
	//会员订单列表导出
    public function export($admin_id = 0,$value = 0){
		$admin_id = (int) $admin_id;
		$value = $this->_param('value', 'htmlspecialchars');
		$export_code = session('export_code');
		if(!$export_code || $export_code != md5($admin_id.'--'.$value)){
			exit;
		}
        $list = D('Users')->where($_SESSION['user_index_list'])->order(array('user_id' => 'asc'))->select();
        $date = date("Y_m_d", time());
        $filetitle = "会员列表";
        $fileName = $filetitle . "_" . $date;
        $html = "﻿";
        $filter = array(
			'aa' => '会员ID', 
			'bb' => '账户', 
			'cc' => '昵称', 
			'dd' => '积分', 
			'ee' => '声望', 
			'ff' => '余额', 
			'gg' => '商户资金', 
			'hh' => '会员昵称', 
			'ii' => '会员等级', 
			'jj' => '会员等级', 
			'kk' => '冻结金-商户资金', 
			'll' => '邮箱', 
			'mm' => '手机号', 
			'nn' => '一级推荐人', 
			'oo' => '二级推荐人', 
			'pp' => '三级推荐人',  
			'ss' => '实名状态', 
			'tt' => '推手状态', 
			'uu' => '锁定状态', 
			'vv' => '注册时间', 
			'ww' => '注册IP', 
			'xx' => '最后登录时间' 
		);
        foreach ($filter as $key => $title) {
            $html .= $title . "\t,";
        }
        $html .= "\n";
        foreach ($list as $k => $v) {
			$fuid1 = D('Users')->find($v['fuid1']);
			$fuid2 = D('Users')->find($v['fuid2']);
			$fuid3 = D('Users')->find($v['fuid3']);
            if($v['is_aux'] == 1) {
                $aux = '已实名';
            }else {
                $aux = '未实名';
            }
			if($v['is_lock'] == 1) {
                $lock = '已锁定';
            }else {
                $lock = '未锁定';
            }
			if($v['is_backers'] == 1) {
                $backers = '申请中';
            }elseif($v['is_backers'] == 2) {
                $backers = '已审核';
            }else {
                $backers = '已拒绝';
            }
            
            $filter = array(
				'aa' => '会员ID', 
				'bb' => '账户', 
				'cc' => '昵称', 
				'dd' => '积分', 
				'ee' => '声望', 
				'ff' => '余额', 
				'gg' => '商户资金', 
				'hh' => '会员昵称', 
				'ii' => '会员等级', 
				'jj' => '冻结金-会员余额', 
				'kk' => '冻结金-商户资金', 
				'll' => '邮箱', 
				'mm' => '手机号', 
				'nn' => '一级推荐人', 
				'oo' => '二级推荐人', 
				'pp' => '三级推荐人', 
				'ss' => '实名状态', 
				'tt' => '推手状态', 
				'uu' => '锁定状态', 
				'vv' => '注册时间', 
				'ww' => '注册IP', 
				'xx' => '最后登录时间' 
			);
            $list[$k]['aa'] = $v['user_id'];
            $list[$k]['bb'] = $v['account'];
            $list[$k]['cc'] = $v['nickname'];
            $list[$k]['dd'] = $v['integral'];
            $list[$k]['ee'] = $v['prestige'];
            $list[$k]['ff'] = $v['money']/ 100;
            $list[$k]['gg'] = $v['gold']/ 100;
            $list[$k]['hh'] = $v['nickname'];
            $list[$k]['ii'] = $this->ranks[$v['rank_id']]['rank_name'];
            $list[$k]['jj'] = $v['frozen_money']/ 100;
            $list[$k]['kk'] = $v['frozen_gold']/ 100;
            $list[$k]['ll'] = $v['email'];
            $list[$k]['mm'] = $v['mobile'];
            $list[$k]['nn'] = $fuid1['nickname'].'【'.$v['fuid1'].'】';
            $list[$k]['oo'] = $fuid2['nickname'].'【'.$v['fuid2'].'】';
            $list[$k]['pp'] = $fuid3['nickname'].'【'.$v['fuid3'].'】';
            $list[$k]['ss'] = $aux;
            $list[$k]['tt'] = $backers;
            $list[$k]['uu'] = $lock;
            $list[$k]['vv'] = date('H:i:s', $v['reg_time']);
            $list[$k]['ww'] = $v['reg_ip'];
            $list[$k]['xx'] = date('H:i:s', $v['last_time']);
            foreach ($filter as $key => $title) {
                $html .= $list[$k][$key] . "\t,";
            }
            $html .= "\n";
        }
        ob_end_clean();
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename={$fileName}.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
		session('export_code', null);
        echo $html;
        exit;
    }
	
	
	//新人红包列表
	public function redpacket(){
        
        import('ORG.Util.Page');
        $map = array();
		
		if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $user = D('Users')->find($user_id);
            $this->assign('nickname', $user['nickname']);
            $this->assign('user_id', $user_id);
        }
		
        $count = M('users_redpacket')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('users_redpacket')->where($map)->order(array('redpacket_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
             $list[$k]['user'] = M('users')->where(array('user_id'=>$val['user_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	
	//操作学校
	 public function redpacketPublish($redpacket_id = 0){
		 
		$redpacket_id = (int)$redpacket_id;
		 
		if($this->isPost()){
			
			$data = $this->checkFields($this->_post('data',false),array('user_id','redpacket_id','money','info','type','is_used','orderby'));
			
			$data['redpacket_id'] = (int) $data['redpacket_id'];
			$data['user_id'] = (int) $data['user_id'];
			if(!$data['user_id']){
				$this->tuError('会员不能为空');
			}
			
			
			$data['money'] = (int)($data['money']*100);
			if($data['money'] <= 0){
				$this->tuError('金额不能为空');
			}
			
			$data['type'] = (int) 1;
			
			if($data['redpacket_id']){
				$res =  M('users_redpacket')->save($data);
				$intro = '修改成功';
			}else{
				unset($data['redpacket_id']);
				$data['create_time'] = NOW_TIME;
				$data['create_ip'] = get_client_ip();
				$res = M('users_redpacket')->add($data);
				$intro = '添加成功';
			}
			
			if($res){
				$this->tuSuccess($intro, U('user/redpacket'));
			}else{
				$this->tuError('操作失败');
			}
		}else{
			$this->assign('detail',$detail = M('users_redpacket')->where(array('redpacket_id'=>$redpacket_id))->find());
			$this->assign('user', D('Users')->where(array('user_id'=>$detail['user_id']))->find());
			$this->display();
		}
    }
	
	public function redpacketDelete($redpacket_id = 0){
		
        if($redpacket_id = (int) $redpacket_id){
			if($res = M('users_redpacket')->where(array('redpacket_id'=>$redpacket_id))->delete()){
				$this->tuSuccess('删除成功', U('user/redpacket'));
			}else{
				$this->tuError('删除失败');
			}
        }else{
            $this->tuError('非法操作');
        }
    }
    
}