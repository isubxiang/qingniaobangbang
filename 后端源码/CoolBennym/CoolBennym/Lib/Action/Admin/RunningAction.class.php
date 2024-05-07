<?php
class RunningAction extends CommonAction{
	
	//订单状态
	 private function getOrderStatus(){
        return array(
			'1' => '待付款', 
			'2' => '待处理', 
			'4' => '制作中', 
			'8' => '待配送', 
			'16' => '已接单', 
			'32' => '配送中',
			'64' => '待评价',
			'128' => '已完成',
			'256' => '付款超时', 
			'512' => '用户取消', 
			'1024' => '商家取消',
			'2048' => '过期取消',
			'4096' => '后台取消',
			'8192' => '退款失败',
		);
    }
	
	
	//订单状态
	private function getDeliveryAudits(){
        return array(
			'0' => '未认证', 
			'1' => '审核中', 
			'2' => '已认证', 
			'3' => '认证失败', 
		);
    }
	
	
	
	//构架函数
	public function _initialize(){
        parent::_initialize();
		$this->config  = D('Setting')->fetchAll();
        $this->assign('getOrderStatus', $this->getOrderStatus());
		$this->assign('getDeliveryAudits', $this->getDeliveryAudits());
		$this->assign('getDeliveryDeposit', D('Running')->getDeliveryDeposit());
    }
	
	
	
	//分类设置
	public function set(){
        $this->title = '系统设置';
        if(IS_POST){
            $channelname = I('channelname','','trim');
            if(empty($channelname)){
				$this->tuError('不能留空');
            }
            $file = CONF_PATH.'/config.site.php';
            $arr = array_keys($_POST);
            $siteConfig = array();
            for($i=0;$i<count($arr);$i++){
                $siteConfig['cfg_'.$arr[$i]] = htmlspecialchars($_POST[$arr[$i]]);
            }
            if(!writeArr($siteConfig,$file)){
				$this->tuError('保存失败');
            }
			$this->tuSuccess('保存成功', U('running/set'));
            exit;
        }
        $this->display();
    }
	
	
	//配送员所有的费用记录
	 public function finance(){

        import('ORG.Util.Page');
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		
		if($year = (int) $this->_param('year')){
			$map['year'] = $year;
			$this->assign('year',$year);
		}
		
		if($month = (int) $this->_param('month')){
			$map['month'] = $month;
			$this->assign('month',$month);
		}
		
		if($order_id = (int) $this->_param('order_id')){
			$map['order_id'] = $order_id;
			$this->assign('order_id', $order_id);
		}
		
		
		
		if($delivery_id = (int) $this->_param('delivery_id')){
			$map['delivery_id'] = $delivery_id;
			$this->assign('delivery_id', $delivery_id);
		}
		if($running_id = (int) $this->_param('running_id')){
			$map['running_id'] = $running_id;
			$this->assign('running_id', $running_id);
		}
		
        if($user_id = (int) $this->_param('user_id')){
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['intro'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
        $count = M('running_money')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('running_money')->where($map)->order(array('create_time' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$user_ids = array();
        foreach($list as $k => $val){
            $user_ids[$val['user_id']] = $val['user_id'];
			$list[$k]['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
			$list[$k]['delivery'] = M('running_delivery')->where(array('delivery_id'=>$val['delivery_id']))->find();
        }
		$this->assign('money',$money = M('running_money')->where($map)->sum('money'));
		$this->assign('commission',$commission = M('running_money')->where($map)->sum('commission'));
        $this->assign('users', D('Users')->itemsByIds($user_ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	//文件列表
	public function files($running_id = 0){
		
		if($running_id = (int) $this->_param('running_id')){
            $this->assign('running_id',$running_id);
        }
		if($p = (int) $this->_param('p')){
            $this->assign('p',$p);
        }
		
		
		$file = M('running_file')->where(array('running_id'=>$running_id))->select();
		foreach($file as $k2 => $v2){
			if($v2['types'] == 'pptx'){
				$srcImg = $this->_CONFIG['site']['host'].'/Public/images/ppt.png';
			}else if($v2['types'] == 'doc'){
				$srcImg = $this->_CONFIG['site']['host'].'/Public/images/word.png';
			}else if($v2['types'] == 'docx'){
				$srcImg = $this->_CONFIG['site']['host'].'/Public/images/word.png';
			}else if($v2['types'] == 'pdf'){
				$srcImg = $this->_CONFIG['site']['host'].'/Public/images/pdf.png';
			}else if($v2['types'] == 'ppt'){
				$srcImg = $this->_CONFIG['site']['host'].'/Public/images/ppt.png';
			}else{
				$srcImg = $v2['file'];
			}
			$file[$k2]['srcImg'] = $srcImg ;
		}
		$this->assign('list', $file);
		$this->display();
	}
	
	
	
	
    public function index(){
		
        import('ORG.Util.Page');
		
        $map = array('closed'=>0);  
        if($keyword = $this->_param( "keyword", "htmlspecialchars")){
            $map['title|addr|mobile'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		if($p = (int) $this->_param('p')){
            $this->assign('p', $p);
        }
		if($colour = (int) $this->_param('colour')){
            $this->assign('colour', $colour);
        }
		
		if($running_id = (int) $this->_param('running_id')){
            $this->assign('running_id', $running_id);
        }
		
		$getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
		
		
		if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
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
		
		if(isset($_GET['Type']) || isset($_POST['Type'])){
            $Type = (int) $this->_param('Type');
            if($Type != 999) {
                $map['Type'] = $Type;
            }
            $this->assign('Type', $Type);
        }else{
            $this->assign('Type',999);
        }
		
		if(isset($_GET['is_ele_pei']) || isset($_POST['is_ele_pei'])){
            $is_ele_pei = (int) $this->_param('is_ele_pei');
            if($is_ele_pei != 999){
                $map['is_ele_pei'] = $is_ele_pei;
            }
            $this->assign('is_ele_pei', $is_ele_pei);
        }else{
            $this->assign('is_ele_pei',999);
        }
		
		if(isset($_GET['OrderStatus']) || isset($_POST['OrderStatus'])){
            $OrderStatus = (int) $this->_param('OrderStatus');
            if($OrderStatus != 999) {
                $map['OrderStatus'] = $OrderStatus;
            }
            $this->assign('OrderStatus', $OrderStatus);
        }else{
            $this->assign('OrderStatus',999);
        }
		
		if(isset($_GET['orderType']) || isset($_POST['orderType'])){
            $orderType = (int) $this->_param('orderType');
            if($orderType != 999) {
                $map['orderType'] = $orderType;
            }
            $this->assign('orderType', $orderType);
        }else{
            $this->assign('orderType',999);
        }
		
		
		
        $count = M('Running')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('Running')->where($map)->order('running_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val) {
			$list[$k]['d'] = M('Delivery')->where(array('user_id'=>$val['cid']))->find();
			$list[$k]['city'] = M('City')->where(array('city_id'=>$val['city_id']))->find();
			$list[$k]['user'] = M('Users')->where(array('user_id'=>$val['user_id']))->find();
			$list[$k]['money'] = M('RunningMoney')->where(array('type'=>'running','order_id'=>$val['running_id']))->find();
			
			$list[$k]['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
			
			$file = M('running_file')->where(array('running_id'=>$val['running_id']))->select();
			foreach($file as $k2 => $v2){
				$file[$k2]['srcImg'] = $srcImg ;
			}
			
			if($file){
				$list[$k]['file'] = $file;
				$list[$k]['files'] = 1;
			}
			
			
			
			
			
			if($list[$k]['d'] && $val['cid']){
				$deliveryInfo = $list[$k]['d']['name'].''.$list[$k]['d']['mobile'];
			}elseif($val['delivery_id']){
				$RunningDelivery = M('RunningDelivery')->where(array('delivery_id'=>$val['delivery_id']))->find();
				$deliveryInfo = $RunningDelivery['RealName'].''.$RunningDelivery['phoneNumber'];
			}else{
				$deliveryInfo = '暂无信息';
			}
			
			$list[$k]['deliveryInfo'] = $deliveryInfo;
			$list[$k]['thumbs'] = unserialize($val['thumb']);
        }
		
        $this->assign('list', $list);
        $this->assign('page', $show);
		$this->assign('types', D('Running')->getType());
		session('running_index_list',$map);//保存session
        $this->display();
    }

	
	
	//会员订单列表导出
    public function export($admin_id = 0,$value = 0){
		$admin_id = (int) $admin_id;
		
        $list = M('running')->where($_SESSION['running_index_list'])->order(array('running_id' =>'desc'))->select();
        $date = date("Y_m_d", time());
        $filetitle = "订单列表";
        $fileName = $filetitle . "_" . $date;
        $html = "﻿";
        $filter = array(
			'aa' => '订单ID', 
			'bb' => '学校名称', 
			'cc' => '订单类型', 
			'dd' => '配送方式', 
			'ee' => '订单状态', 
			'ff' => '限制时间', 
			'gg' => '订单重量', 
			'hh' => '跑腿费用', 
			'ii' => '实际支付', 
			'jj' => '垫付金额', 
			'kk' => '需求标题', 
			'll' => '配送地址', 
			'mm' => '下单会员信息', 
			'nn' => '配送员信息', 
			'oo' => '评价内容', 
			'pp' => '评价标签',  
			'ss' => '退款理由', 
			'tt' => '创建订单时间', 
			'uu' => '订单付款时间', 
			'vv' => '配送接单时间', 
			'ww' => '订单完成时间', 
			'xx' => '备注' 
		);
        foreach ($filter as $key => $title) {
            $html .= $title . "\t,";
        }
        $html .= "\n";
        foreach ($list as $k => $v) {
			
			$school = M('running_school')->where(array('school_id'=>$v['school_id']))->find();
			
			$Users = D('Users')->find($v['user_id']);
			$RunningDelivery = M('RunningDelivery')->where(array('delivery_id'=>$v['delivery_id']))->find();
			$deliveryInfo = $RunningDelivery['RealName'].''.$RunningDelivery['phoneNumber'];
			
		
            if($v['Type'] == 2) {
                $Type = '跑腿订单';
            }else {
                $Type = '外卖订单';
            }
			if($v['is_ele_pei'] == 1) {
                $is_ele_pei = '商家配送';
            }else {
                $is_ele_pei = '平台配送';
            }
			//订单状态
			$getOrderStatus = $this->getOrderStatus();
			
			if($v['is_backers'] == 1) {
                $backers = '申请中';
            }elseif($v['is_backers'] == 2) {
                $backers = '已审核';
            }else {
                $backers = '已拒绝';
            }
            
            $filter = array(
				'aa' => '订单ID', 
				'bb' => '学校名称', 
				'cc' => '订单类型', 
				'dd' => '配送方式', 
				'ee' => '订单状态', 
				'ff' => '限制时间', 
				'gg' => '订单重量', 
				'hh' => '跑腿费用', 
				'ii' => '实际支付', 
				'jj' => '垫付金额', 
				'kk' => '需求标题', 
				'll' => '配送地址', 
				'mm' => '下单会员信息', 
				'nn' => '配送员信息', 
				'oo' => '评价内容', 
				'pp' => '评价标签',  
				'ss' => '退款理由', 
				'tt' => '创建订单时间', 
				'uu' => '订单付款时间', 
				'vv' => '配送接单时间', 
				'ww' => '订单完成时间', 
				'xx' => '备注' 
			);
            $list[$k]['aa'] = $v['running_id'];
            $list[$k]['bb'] = $school['Name'];
            $list[$k]['cc'] = $Type;
            $list[$k]['dd'] = $is_ele_pei;
            $list[$k]['ee'] = $getOrderStatus[$v['OrderStatus']];//订单状态
            $list[$k]['ff'] = $v['ExpiredMinutes'];
            $list[$k]['gg'] = $v['Weight'];
            $list[$k]['hh'] = round($v['freight']/100,2);
            $list[$k]['ii'] = round($v['need_pay']/100,2);
            $list[$k]['jj'] = round($v['Money']/100,2);
            $list[$k]['kk'] = $v['Remark'];
            $list[$k]['ll'] = '姓名：'.$v['name'].'手机：'.$v['mobile'].'地址：'.$v['addr'];
            $list[$k]['mm'] = $Users['nickanme'];
            $list[$k]['nn'] = $deliveryInfo;
            $list[$k]['oo'] = $v['content'];
            $list[$k]['pp'] = $v['labels'];
            $list[$k]['ss'] = $v['OrderRefundInfo'];
            $list[$k]['tt'] = date('H:i:s', $v['create_time']);
            $list[$k]['uu'] = date('H:i:s', $v['pay_time']);
            $list[$k]['vv'] = date('H:i:s', $v['update_time']);
            $list[$k]['ww'] = date('H:i:s', $v['end_time']);
            $list[$k]['xx'] = $v['Remark'];
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
        echo $html;
        exit;
    }
	
	
	
    public function detail($running_id = 0){
        
        $map = array('running_id'=>$running_id);  
        if($keyword = $this->_param( "keyword", "htmlspecialchars")){
            $map['title|addr|mobile'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		if($p = (int) $this->_param('p')){
            $this->assign('p', $p);
        }
		
        $var = M('running')->where($map)->find();
		
		$var['d'] = M('Delivery')->where(array('user_id'=>$var['cid']))->find();
		$var['city'] = M('City')->where(array('city_id'=>$var['city_id']))->find();
		$var['user'] = M('Users')->where(array('user_id'=>$var['user_id']))->find();
		$var['money'] = M('RunningMoney')->where(array('type'=>'running','order_id'=>$var['running_id']))->find();
		
		$var['school'] = M('running_school')->where(array('school_id'=>$var['school_id']))->find();
		
		
		$file = M('running_file')->where(array('running_id'=>$var['running_id']))->select();
		foreach($file as $k2 => $v2){
			$var['srcImg'] = $srcImg ;
		}
		
		if($file){
			$var['file'] = $file;
			$var['files'] = 1;
		}
		if($var['d'] && $var['cid']){
			$deliveryInfo = $var['d']['name'].''.$var['d']['mobile'];
		}elseif($val['delivery_id']){
			$RunningDelivery = M('RunningDelivery')->where(array('delivery_id'=>$var['delivery_id']))->find();
			$deliveryInfo = $RunningDelivery['RealName'].''.$RunningDelivery['phoneNumber'];
		}else{
			$deliveryInfo = '暂无信息';
		}
		
		$var['deliveryInfo'] = $deliveryInfo;
		$var['thumbs'] = unserialize($var['thumb']);
		
		$var['startAddress'] = unserialize($var['startAddress']);
		$var['endAddress'] = unserialize($var['endAddress']);

		
        $this->assign('var', $var);
        $this->assign('page', $show);
		$this->assign('types', D('Running')->getType());
        $this->display();
    }

   
    
	
	 //配送员列表
	 public function delivery(){
		 
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['RealName|Major'] = array('LIKE', '%'.$keyword.'%');
			$this->assign('keyword',$keyword);
        } 
		 
		if($SchoolId= (int) $this->_param('SchoolId')){
            $map['SchoolId'] = $SchoolId;
            $this->assign('SchoolId', $SchoolId);
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
        if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $user = D('Users')->find($user_id);
            $this->assign('nickname', $user['nickname']);
            $this->assign('user_id', $user_id);
        }
		
		$getSearchDate = $this->getSearchDate();
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		
		if(isset($_GET['audit']) || isset($_POST['audit'])){
            $audit =(int) $this->_param('audit');
            if($audit != 999){
                $map['audit'] = $audit;
            }
            $this->assign('audit', $audit);
        }else{
            $this->assign('audit', 999);
        }
		
        $count = M('RunningDelivery')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('RunningDelivery')->where($map)->order(array('create_time'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val) {
			$user = M('Users')->where(array('user_id'=>$val['user_id']))->find();
			$list[$k]['notifyFlag'] = $user['notifyFlag'];
			$list[$k]['bindFlag'] = $user['bindFlag'];
			$list[$k]['notifyFrom'] = $user['notifyFrom'];
			$list[$k]['notifyEnd'] = $user['notifyEnd'];
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }
	
	
	
	 public function deliveryPublish(){
       	$delivery_id= (int) $this->_param('delivery_id');
		if($this->isPost()){
			
			$data = $this->checkFields($this->_post('data',false),array(
				'delivery_id','user_id','SchoolId','school_id','StudentCode','RealName','Major','IdCode','Gender','EnrollmentDate','Department','PicUrl0','PicUrl1','phoneNumber','audit','deposit','is_deposit'
			));
			
			$data['delivery_id'] = (int) $data['delivery_id'];
			
			
			$data['user_id'] = (int) $data['user_id'];
			if(empty($data['user_id'])){
				$this->tuError('会员不能为空');
			}
			$data['school_id'] = (int) $data['school_id'];
			$data['SchoolId'] = (int) $data['school_id'];
			
			
			$data['deposit'] = (int)($data['deposit']*100);
			
			
			$data['RealName'] = htmlspecialchars($data['RealName']);
			
			if($data['delivery_id']){
				$data['update_time'] = NOW_TIME;
				$data['update_ip'] = get_client_ip();
				$res = M('RunningDelivery')->save($data);
				$intro = '修改成功';
			}else{
				$data['delivery_id'] = (int) $data['user_id'];
				$data['create_time'] = NOW_TIME;
				$data['create_ip'] = get_client_ip();
				$res = M('RunningDelivery')->add($data);
				$intro = '添加成功';
			}
			if($res){
				$this->tuSuccess($intro, U('running/delivery'));
			}else{
				$this->tuError('操作失败');
			}
		}else{
			
			$this->assign('detail',$detail = M('RunningDelivery')->where(array('delivery_id'=>$delivery_id))->find());
			$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
			$this->assign('user', M('Users')->where(array('user_id'=>$detail['user_id']))->find());
			$this->display();
		}
        
    }
	
	
	 public function deliveryDelete($delivery_id = 0){
        if($delivery_id = (int) $delivery_id){
			if(M('RunningDelivery')->where(array('delivery_id'=>$delivery_id))->delete()){
				$this->tuSuccess('删除成功', U('running/delivery'));
			}else{
				$this->tuError('删除失败');
			}
        }else{
            $this->tuError('非法操作');
        }
    }
	
	
	//解冻会员的配送保证金
	public function userDepositThaw($delivery_id = 0,$is_deposit= 0){
		$delivery_id = (int) $delivery_id;
		
		$v = M('RunningDelivery')->where(array('delivery_id'=>$delivery_id))->find();
		if($v['is_deposit'] != 2){
			$this->tuError('解冻状态不正确');
		}
		if($v['deposit'] <= 0){
			$this->tuError('配送员冻结金不正确');
		}
		$u = M('users')->where(array('user_id'=>$v['user_id']))->find();
		if(!$u){
			$this->tuError('配送员绑定的会员不存在');
		}
		
		$res = D('Users')->addMoney($v['user_id'],$v['deposit'],'配送员冻结金解冻',8,$v['school_id']);//退款给会员账户余额
		
		if($res){
			
			$rest = M('RunningDelivery')->where(array('delivery_id'=>$v['delivery_id']))->save(array('is_deposit'=>3,'deposit'=>0));
			
			$this->tuSuccess('操作成功【冻结金退回会员账户】', U('running/delivery'));
		}else{
			$this->tuError('解冻失败');
		}
    }
	
	
	
	
	 //学校
	 public function school(){
		 
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['Name|Region'] = array('LIKE', '%'.$keyword.'%');
			$this->assign('keyword',$keyword);
        } 
		 
        if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $user = D('Users')->find($user_id);
            $this->assign('nickname', $user['nickname']);
            $this->assign('user_id', $user_id);
        }
		
		$getSearchDate = $this->getSearchDate();
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
	
		$getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
        if($area_id = (int) $this->_param('area_id')){
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
		
        $count = M('RunningSchool')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('RunningSchool')->where($map)->order(array('school_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['city'] = D('City')->where(array('city_id'=>$val['city_id']))->find();
			$list[$k]['area'] = D('Area')->where(array('area_id'=>$val['area_id']))->find();
			$list[$k]['business'] = D('Business')->where(array('business_id'=>$val['business_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }
	
	 //筛选学校
	 public function schoolselect(){
        import('ORG.Util.Page'); 
        $map = array('closed'=>0);
        if($keyword = $this->_param('keyword','htmlspecialchars')){
            $map['Name|Region'] = array('LIKE','%'.$keyword.'%');
            $this->assign('keyword',$keyword);
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        $count = M('RunningSchool')->where($map)->count(); 
        $Page = new Page($count, 10); 
        $pager = $Page->show(); 
        $list = M('RunningSchool')->where($map)->order(array('school_id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['city'] = D('City')->where(array('city_id'=>$val['city_id']))->find();
			$list[$k]['area'] = D('Area')->where(array('area_id'=>$val['area_id']))->find();
			$list[$k]['business'] = D('Business')->where(array('business_id'=>$val['business_id']))->find();
        }
        $this->assign('list', $list); 
        $this->assign('page', $pager); 
        $this->display(); 
    }
	
	//操作学校
	 public function schoolPublish($school_id = 0){
		 
		$school_id = (int)$school_id;
		 
		if($this->isPost()){
			
			$data = $this->checkFields($this->_post('data',false),array('user_id','city_id','school_id','area_id','business_id','deposit','is_deposit_qiang','Id','Name','Region','FreightMoneyCaption','MinFreightMoney','is_cash','user','user_big','user_cash_commission','shop','shop_big','shop_cash_commission','user_cash_second','shop_cash_second','admin_yongjin_rate','city_yongjin_rate','lat','lng','orderby'));
			
			$data['school_id'] = (int) $data['school_id'];
			$data['city_id'] = (int) $data['city_id'];
			$data['area_id'] = (int) $data['area_id'];
			$data['business_id'] = (int) $data['business_id'];
			$data['user_id'] = (int) $data['user_id'];
			
			$data['deposit'] = (int)($data['deposit']*100);
			$data['is_deposit_qiang'] = (int) $data['is_deposit_qiang'];
			
			$data['Name'] = htmlspecialchars($data['Name']);
			if(!$data['Name']){
				$this->tuError('名称不能为空');
			}
			
			if($data['school_id']){
				$data['update_time'] = NOW_TIME;
				$data['update_ip'] = get_client_ip();
				$res = M('RunningSchool')->save($data);
				$intro = '修改成功';
			}else{
				$data['create_time'] = NOW_TIME;
				$data['create_ip'] = get_client_ip();
				
				$res = M('RunningSchool')->add($data);
				$res = M('RunningSchool')->where(array('school_id'=>$res))->save(array('Id'=>$res));//备用字段方便赋值
				
				$intro = '添加成功';
			}
			
			if($res){
				$this->tuSuccess($intro, U('running/school'));
			}else{
				$this->tuError('操作失败');
			}
		}else{
			$this->assign('detail',$detail = M('RunningSchool')->where(array('school_id'=>$school_id))->find());
			$this->assign('user', D('Users')->find($detail['user_id']));
			$this->display();
		}
    }
	
	
	 public function schoolDelete($school_id = 0){
		$this->tuError('为了数据安全暂时不提供删除功能谢谢合作');
		
        if($school_id = (int) $school_id){
			if(M('RunningSchool')->where(array('school_id'=>$school_id))->delete()){
				$this->tuSuccess('删除成功', U('running/school'));
			}else{
				$this->tuError('删除失败');
			}
        }else{
            $this->tuError('非法操作');
        }
    }
	
	
	
	 //学校
	 public function product(){
		 
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['product_name'] = array('LIKE', '%'.$keyword.'%');
			$this->assign('keyword',$keyword);
        } 
		 
        if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $user = D('Users')->find($user_id);
            $this->assign('nickname', $user['nickname']);
            $this->assign('user_id', $user_id);
        }
	
		
		if($shop_id = (int) $this->_param('shop_id')){
			$map['shop_id'] = $shop_id;
			$shop = D('Shop')->find($map['shop_id']);
            $this->assign('shop_name', $shop['shop_name']);
            $this->assign('shop_id',$map['shop_id']);
		}
		
		
		if($running_id = (int) $this->_param('running_id')){
            $map['running_id'] = $running_id;
            $this->assign('running_id', $running_id);
        }
		
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		if($shop_id = (int) $this->_param('shop_id')){
            $map['shop_id'] = $shop_id;
            $this->assign('shop_id', $shop_id);
        }
		
        $count = M('RunningProduct')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('RunningProduct')->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['user'] = D('Users')->where(array('user_id'=>$val['user_id']))->find();
			$list[$k]['product'] = M('EleProduct')->where(array('product_id'=>$val['product_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }
	
	
	
	 //学校内的的外送商家列表
	 public function shop($school_id){
		if(!$detail = M('RunningSchool')->find($school_id)){
            $this->tuError('错误');
        }
		$this->assign('school_id',$school_id);
		$this->assign('detail', $detail);
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['shop_id'] = array('LIKE', '%'.$keyword.'%');
        }  
		if($shop_id = (int) $this->_param('shop_id')){
			$map['shop_id'] = $shop_id;
			$shop = D('Shop')->find($map['shop_id']);
            $this->assign('shop_name', $shop['shop_name']);
            $this->assign('shop_id',$map['shop_id']);
		}
        $this->assign('keyword',$keyword);
        $count = M('RunningSchoolShop')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('RunningSchoolShop')->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['ele'] = M('Ele')->where(array('shop_id'=>$val['shop_id']))->find();
			$list[$k]['shop'] = M('Shop')->where(array('shop_id'=>$val['shop_id']))->find();
			$list[$k]['school'] = M('RunningSchool')->where(array('school_id'=>$val['school_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }
	
	
	//学校内的的外送商家添加编辑
	 public function schoolShopPublish($id = 0,$school_id = 0){
        if($school_id = (int) $school_id){
            if(!$detail = M('RunningSchool')->find($school_id)){
                $this->tuError('学校不存在');
            }
			
		    $list = M('RunningSchoolShop')->find($id);
            $this->assign('list',$list);
			
			
            if($this->isPost()){
				
                $data = $this->checkFields($this->_post('data', false),array('id','shop_id','school_id','orderby'));
				
				$data['id'] = $id;
				$data['school_id'] = (int) $data['school_id'];
				$data['shop_id'] = (int) $data['shop_id'];
                
				if($data['id']){
					$res = M('RunningSchoolShop')->save($data);
					$intro = '修改成功';
				}else{
					$data['create_time'] = NOW_TIME;
					$data['create_ip'] = get_client_ip();
					$res = M('RunningSchoolShop')->add($data);
					$intro = '添加成功';
				}
				
                if($res){
					$res2 = D('Ele')->where(array('shop_id'=>$data['shop_id']))->save(array('school_id'=>$data['school_id']));
                    $this->tuSuccess($intro, U('running/shop',array('school_id'=>$school_id)));
                }else{
					$this->tuError('操作失败');
				}
            }else{
                $this->assign('detail', $detail);
				$this->assign('shop', D('Ele')->where(array('shop_id'=>$list['shop_id']))->find());
				$this->assign('school_id',$school_id);
                $this->display();
            }
        }else{
            $this->tuError('非法操作');
        }
    }
	
	//学校内的的外送商家删除
	 public function schoolShopDelete($id = 0){
        if($id = (int) $id){
			if(!$detail = M('RunningSchoolShop')->find($id)){
                $this->tuError('内容不存在');
            }
			if(M('RunningSchoolShop')->where(array('id'=>$id))->delete()){
				$this->tuSuccess('删除成功', U('running/shop',array('school_id'=>$detail['school_id'])));
			}else{
				$this->tuError('删除失败');
			}
        }else{
            $this->tuError('非法操作');
        }
    }
	
    
	public function addr(){
        import('ORG.Util.Page');
        $map = array('closed'=>0,'city_id'=>array('eq',''),'SchoolId'=>array('neq',''));
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['name|mobile|addr'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if($user_id = (int) $this->_param('user_id')){
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
        $count = M('UserAddr')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('UserAddr')->where($map)->order(array('addr_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['user'] = M('Users')->where(array('user_id'=>$val['user_id']))->find();
            $list[$k]['school'] = M('RunningSchool')->where(array('school_id'=>$val['SchoolId']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	
	//删除地址
	public function addrDelete($addr_id = 0){
        if(is_numeric($addr_id) && ($addr_id = (int) $addr_id)){
			M('UserAddr')->save(array('addr_id' => $addr_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('running/addr'));
        }else{
            $addr_id = $this->_post('addr_id', false);
            if(is_array($addr_id)){
                foreach($addr_id as $id){
                    M('UserAddr')->save(array('addr_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('删除成功', U('running/addr'));
            }
            $this->tuError('请选择要删除的收货地址');
        }
    }
	
	
	

	
	//统计
	public function tongji(){
		$bg_time = time() - 86400 * 30;
		$bg_date = date('Y-m-d',$bg_time);
        $end_date = date('Y-m-d',time());
		$this->assign('bg_date', $bg_date);
        $this->assign('end_date', $end_date);
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		if(isset($_GET['OrderStatus']) || isset($_POST['OrderStatus'])){
            $OrderStatus = (int) $this->_param('OrderStatus');
            if($OrderStatus != 999) {
                $map['OrderStatus'] = $OrderStatus;
            }else{
				$OrderStatus = '';
			}
            $this->assign('OrderStatus', $OrderStatus);
        }else{
            $this->assign('OrderStatus',999);
        }
		
		$data = D('Running')->getDbHighcharts($bg_time,time(),$school_id,$OrderStatus);
        $this->assign('data',$data);
		$this->display();
    }
	
	
	//后台关闭订单超时退款
	public function closedOrder($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		
		$v = M('Running')->find($running_id);
		
		if($v['OrderStatus'] != 2){
			$this->tuError('订单状态不正确');
		}
		$cha = time() - $v['pay_time'];
		if($cha > ($v['ExpiredMinutes']*60)){
			
			$res = M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>'2048'));//修改订单状态
			if($v['Type'] == 1){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>2048));//修改菜单状态
			}
			
			
			//原路退回资金
			$info = '【管理员关闭订单】批量取消订单跑腿订单ID【'.$v['running_id'].'】超时无人接单退款余额';
			$mix = $this->config['running']['running_weixin_original_refund_mix'] ? $this->config['running']['running_weixin_original_refund_mix'] : 10;
			$mix2 = $mix*100;
			if($this->config['running']['running_weixin_original_refund'] == 1 && $v['MoneyPayment'] < $mix2  && $v['MoneyTip'] == 0){
				$runningOrderRefundUser = D('Running')->runningOrderRefundUser($v['running_id'],$v['user_id'],$v['MoneyPayment'],'running',$info);
				//退款出问题
				if($runningOrderRefundUser == false){
					M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>8192,'OrderRefundInfo'=>D('Running')->getError()));
					$this->tuError('退款有一点问题'.D('Running')->getError());
				}else{
					$this->tuSuccess('操作成功【资金原路返回】', U('running/index',array('p'=>$p)));
				}					
			}else{
				D('Users')->addMoney($v['user_id'],$v['MoneyPayment'],$info,2,$v['school_id']);//退款给会员账户余额
				$this->tuSuccess('操作成功【资金退回会员账户】', U('running/index',array('p'=>$p)));
			}
			
		}else{
			$this->tuError('当前接单时间未超过超时时间暂时无法改变订单状态');
		}
    }
	
	
	
	//后台强制退款
	public function refund($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		
		$v = M('Running')->find($running_id);
		
		//强制改变订单状态
		$res= M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>'4096'));
		$RunningProduct = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>4096));
		
		if($v['OrderStatus'] == 0){
			$this->tuError('当前订单未付款');
		}
		if($v['OrderStatus'] >= 16){
			$this->tuError('当前状态系统不同意强制退款');
		}
		//新增退款安全
		$logs = M('PaymentLogs')->where(array('type'=>'running','order_id'=>$running_id,'is_paid'=>1))->find();
		if(!$logs){
			$this->tuError('支付订单不存在');
		}
		if(!empty($logs) && !empty($logs['refund_id'])){
			$this->tuError('请不要重复申请退款');
		}
		
		
		$commonRefundUser = D('Running')->commonRefundUser($v['running_id'],$saveOrderStatus = '8192',$refundInfo = '管理员强制退款',2,$type = 1);//超时无人接单退款功能封装
		
		if($commonRefundUser){
			$this->tuSuccess('操作成功【资金退回会员账户】', U('running/index',array('p'=>$p)));
		}else{
			$this->tuError('操作失败');
		}
		
		
		
    }
	
	
	
	
	//后台关闭订单
	public function deleteOrder($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		
		$v = M('Running')->find($running_id);
		
		if($v['OrderStatus'] != 1){
			$this->tuError('订单状态不正确');
		}else{
			$Running = M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>256));
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>256));
			$this->tuSuccess('操作成功', U('running/index',array('p'=>$p)));
		}
    }
	
	//强制完成订单
	public function complete($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		$v = M('Running')->find($running_id);
		$runingSettlement = D('Running')->runingSettlement($v['running_id'],$v['delivery_id'],$labels = '',$content = '');//结算封装函数
		if($runingSettlement == false){
			$this->tuError(D('Running')->getError());
		}
		$this->tuSuccess('强制完成成功', U('running/index',array('p'=>$p)));
    }
	
	
	//完成订单
	public function completeOrder($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		
		$v = M('Running')->find($running_id);
		
		if($v['OrderStatus'] != 32){
			$this->tuError('订单状态不正确');
		}else{
			$runingSettlement = D('Running')->runingSettlement($v['running_id'],$v['delivery_id'],$labels = '',$content = '');//结算封装函数
			if($runingSettlement == false){
				$this->tuError(D('Running')->getError());
			}
			$this->tuSuccess('操作成功', U('running/index',array('p'=>$p)));
		}
    }
	
	
	//删除
	public function delete($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		$detail = M('Running')->where(array('running_id'=>$running_id))->find();
        if($detail['status'] !=0){
            $this->tuError('当前跑腿状态不能删除订单');
        }
		if(M('Running')->where(array('running_id'=>$running_id))->delete()){
			$this->tuSuccess('删除成功', U('running/index',array('p'=>$p)));
		}else{
            $this->tuError('删除失败');            
        }
    }
	
	//强制删除
	public function delete2($running_id = 0,$p= 0){
		$running_id = (int) $running_id;
		$detail = M('Running')->where(array('running_id'=>$running_id))->find();
		if(!$detail){
            $this->tuError('状态错误');
        }
		if(M('Running')->where(array('running_id'=>$running_id))->delete()){
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->delete();
			$this->tuSuccess('强制删除成功', U('running/index',array('p'=>$p)));
		}else{
            $this->tuError('强制删除失败');            
        }
    }
	
	//常用地址
	public function address($school_id = 0){
        import('ORG.Util.Page');
        $map = array('closed'=>0,'school_id'=>$school_id,'type'=>2);
		
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['name|mobile|addr'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
        $count = M('UserAddr')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('UserAddr')->where($map)->order(array('addr_id' =>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
            $list[$k]['school'] = M('RunningSchool')->where(array('school_id'=>$val['SchoolId']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
		$this->assign('school_id',$school_id);
        $this->display();
    }
	
	
	
	 //常用地址添加编辑
	 public function addressPublish($addr_id = 0,$school_id = 0){
        if($school_id = (int) $school_id){
            if(!$school = M('RunningSchool')->find($school_id)){
                $this->tuError('学校不存在');
            }

			
            if($this->isPost()){
				
                $data = $this->checkFields($this->_post('data', false),array('addr_id','name','mobile','addr','lat','lng'));
				
				$data['addr_id'] = $addr_id;
				$data['school_id'] = (int) $school_id;
				$data['SchoolId'] = (int) $school_id;
                $data['type'] = 2;
				
				if($data['addr_id']){
					$res = M('user_addr')->save($data);
					$intro = '修改成功';
				}else{
					$res = M('user_addr')->add($data);
					$intro = '添加成功';
				}
				if($res){
				  $this->tuSuccess($intro,U('running/address',array('school_id'=>$school_id)));
				}
				$this->tuError('操作失败');
            }else{
				$this->assign('school',$school);
                $this->assign('detail', $detail = M('user_addr')->find($addr_id));
				$this->assign('school_id',$school_id);
				$this->assign('addr_id',$addr_id);
                $this->display();
            }
        }else{
            $this->tuError('非法操作');
        }
    }
	
	
	//删除常用地址
	public function addressDelete($addr_id = 0,$school_id = 0){
        if(is_numeric($addr_id) && ($addr_id = (int) $addr_id)){
			M('UserAddr')->save(array('addr_id'=>$addr_id,'closed' => 1));
            $this->tuSuccess('删除常用地址成功', U('running/address',array('school_id'=>$school_id)));
        }else{
            $addr_id = $this->_post('addr_id', false);
            if(is_array($addr_id)){
                foreach($addr_id as $id){
                    M('UserAddr')->save(array('addr_id'=>$id,'closed' => 1));
                }
                $this->tuSuccess('删除常用地址成功', U('running/address',array('school_id'=>$school_id)));
            }
            $this->tuError('请选择要删除的常用地址');
        }
    }
	
	
	//打印详情
	public function Printing($running_id = 0){
        
        $map = array('running_id'=>$running_id);  
        if($keyword = $this->_param( "keyword", "htmlspecialchars")){
            $map['title|addr|mobile'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		if($p = (int) $this->_param('p')){
            $this->assign('p', $p);
        }
		
        $var = M('Running')->where($map)->find();
		$var['d'] = M('Delivery')->where(array('user_id'=>$val['cid']))->find();
		$var['city'] = M('City')->where(array('city_id'=>$val['city_id']))->find();
		$var['user'] = M('Users')->where(array('user_id'=>$val['user_id']))->find();
		$var['money'] = M('RunningMoney')->where(array('type'=>'running','order_id'=>$val['running_id']))->find();
		$var['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
		
		
		
		$file = M('running_file')->where(array('running_id'=>$val['running_id']))->select();
		foreach($file as $k2 => $v2){
			$var['srcImg'] = $srcImg ;
		}
		
		if($file){
			$var['file'] = $file;
			$var['files'] = 1;
		}
		if($var['d'] && $val['cid']){
			$deliveryInfo = $var['d']['name'].''.$var['d']['mobile'];
		}elseif($val['delivery_id']){
			$RunningDelivery = M('RunningDelivery')->where(array('delivery_id'=>$val['delivery_id']))->find();
			$deliveryInfo = $RunningDelivery['RealName'].''.$RunningDelivery['phoneNumber'];
		}else{
			$deliveryInfo = '暂无信息';
		}
		
		$var['deliveryInfo'] = $deliveryInfo;
		$var['thumbs'] = unserialize($val['thumb']);
		
		$var['startAddress'] = unserialize($val['startAddress']);
		$var['endAddress'] = unserialize($val['endAddress']);

		
        $this->assign('var', $var);
        $this->assign('page', $show);
		$this->assign('types', D('Running')->getType());
        $this->display();
    }
	
	
	//打印电子面单
	public function PrintOrder($running_id = 0){
		$data = $_POST;
        $running_id = $data['running_id'];
        
        if(!($detail = M('running')->find($running_id))){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '没有该订单'.$running_id));
        }
        if($detail['Type'] != 2){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '当前订单不支持打印'));
        }
		if($detail['status'] == 1) {
            $this->ajaxReturn(array('status' => 'error', 'msg' => '当前订单未付款不支持打印'));
        }
		
		$ShipperCode = $data['ShipperCode'];
		if(empty($ShipperCode)){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '快递公司必须选择'));
        }
		$OrderCode = $data['OrderCode'];
		if(empty($OrderCode)){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '快递单号必须选择'));
        }
		$p = $data['p'];
		
		import('ORG.Util.Expressprint');
		
		$getConfigKey = getConfigKey('config');
		
		if($getConfigKey['ReqURLType'] == 1){
			$ReqURL = 'http://testapi.kdniao.com:8081/api/Eorderservice';
		}else{
			$ReqURL = 'http://api.kdniao.com/api/Eorderser';
		}
		
		//执行打印
		$Expressprint = new Expressprint();//实例化
		$Expressprint->EBusinessID = $getConfigKey['EBusinessID'];//EBusinessID
		$Expressprint->AppKey = $getConfigKey['EBusinessID'];//传入EBusinessID
		$Expressprint->ReqURL = $ReqURL;//传入ReqURL
		
        $res = $Expressprint->printOrder($getConfigKey,$ShipperCode,$OrderCode,$running_id);
		
		if($res['code'] == 1){
			$array['running_id'] = $running_id;
			$array['ShipperCode'] = $ShipperCode;
			$array['OrderCode'] = $OrderCode;
			$array['PrintingInfo'] = $res['msg'];
			$array['PrintingTime'] = NOW_TIME;
			$array['IsPrinting'] = 1;
			$rest = M('running')->save($array);
			//更新打印逻辑
			$this->ajaxReturn(array('status' => 'success', 'msg' => '操作成功', 'url' => U('running/Printing',array('running_id'=>$running_id,'p'=>$p))));
		}else{
			$this->ajaxReturn(array('status' => 'error', 'msg' =>$res['msg']));	
		}
	}
	
	
	
}


