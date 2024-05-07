<?php

class RunningAction extends CommonAction{
	
	//全局
	
	protected $session_key = null;
	
	//构架函数
	protected function _initialize(){
		
		$this->getPincheCate = D('Pinche')->getPincheCate();
		$this->assign('getPincheCate',$this->getPincheCate);
			
		$this->config = $config = D('Setting')->fetchAll();
		
		//获取默认学校
		$school = M('running_school')->find($this->config['site']['school_id']);
		if(!$school){
			$school = M('running_school')->where(array('closed'=>0))->find();
		}
		if($school){
			$this->schoolDefaultId  = $school['school_id'];	
		}else{
			$this->schoolDefaultId  = false;
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'系统没有学校请管理员配置','IsSuccess'=>false));
		}
		
		
		
		$this->postRunningClosed($user_id = 0);//批量关闭订单超时关闭
		$this->postRunningLimitSeconds($user_id = 0);//批量未付款订单关闭
		
		$this->postRunningComplete($user_id = 0);//批量完成订单
		
		D('Thread')->updateTopDate($life_id = 0);//删除过期置顶信息
		
		
    }
	
	public function getSessionKey(){
        return $this->session_key;
    }
	
	//Utc时间转换为时间戳
	public function utcUpdateDate($time){
		if($time){
			date_default_timezone_set('PRC');
			$strtotime = strtotime($time);
			$date = date('Y-m-d H:i ',$strtotime).'送达';
			return $date;	
		}else{
			return '即刻出发';
		}
    }
	
	
	
	//批量关闭订单
	public function postRunningClosed($user_id = 0){
		
		$map = array('closed'=>0,'OrderStatus'=>2,'orderType'=>1);
		if($user_id){
			$map['user_id']	 = $user_id;
		}
		
		$list = M('Running')->order('running_id desc')->where($map)->limit(0,100)->select();
		
		foreach($list as $k => $v){
			
			$cha = time() - $v['pay_time'];
			$ExpiredMinutes = $v['ExpiredMinutes'] ? $v['ExpiredMinutes'] : 240;
			
			//p($v);die;
			
			//超时退款封装
			if($v['OrderStatus'] == 2 && $cha > ($ExpiredMinutes*60) && $v['Type'] == 2 && empty($v['delivery_id'])){
				D('Running')->commonRefundUser($v['running_id'],$saveOrderStatus = '2048',$refundInfo = '【批量取消】超时无人接单',2,$type = 1);//退款功能封装
			}
		}
		return true;
    }
	

	
	
	
	//批量完成订单
	public function postRunningComplete($user_id = 0){
		$map = array('closed'=>0,'orderType'=>1,'OrderStatus'=>32);
		if($user_id){
			$map['user_id']	 = $user_id;
		}
		$list = M('Running')->order('running_id desc')->where($map)->limit(0,30)->select();
		foreach($list as $k => $v){
			
			$delivery_end_time = $v['delivery_end_time'] ? $v['delivery_end_time'] : $v['create_time'];
			$times = time() -($delivery_end_time+86400);//剩余时间
			//p($times);die;
			
			if($times >= 60){
				D('Running')->runingSettlement($v['running_id'],$v['delivery_id'],$labels = '',$content = '',$score = 5);//结算封装函数
			}
		}
		return true;
	}
	
	
	//批量未付款订单关闭
	public function postRunningLimitSeconds($user_id = 0){
		$map = array('closed'=>0,'OrderStatus'=>1);
		if($user_id){
			$map['user_id']	 = $user_id;
		}
		$list = M('Running')->order('running_id desc')->where($map)->limit(0,30)->select();
		foreach($list as $k => $v){
			$PaymentLimitSeconds = ($v['create_time']+900)-time();//剩余时间
			if($v['OrderStatus'] == 1){
				if($PaymentLimitSeconds <= 5){
					$Running= M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>256));
					
					D('Weixintmpl')->runningWxappNotice($v['running_id'],$OrderStatus = 256,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
					$RunningProduct = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>256));
				}
			}
		}
		return true;
	}
	
	
	
	//订单状态
	public function getOrderStatus(){
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
		);
    }



	//判断订单是否私密
	public function getORderIsSecret($title){
		if(strpos($title,"私密订单") !== false){ 
			return true;
		}else{
			return false;
		}
	}
	
	
	//基础配置
	public function getSetting(){
		
		$Data['color'] = $this->config['wxapp']['color'] ? $this->config['wxapp']['color'] : '#06c1ae';//全局配色
		
		$Data['is_group_open'] = (int)$this->config['wxapp']['is_group_open'];//开启拼团菜单
		$Data['is_pinche_open'] = (int)$this->config['wxapp']['is_pinche_open'];//开启拼车菜单
		$Data['is_coupon_open'] = (int)$this->config['wxapp']['is_coupon_open'];//开启优惠券菜单
		$Data['is_thread_open'] = (int)$this->config['wxapp']['is_thread_open'];//开启贴吧菜单
		$Data['is_canvas_open'] = (int)$this->config['wxapp']['is_canvas_open'];//分享海报开启菜单
		
		$Data['is_money_open'] = (int)$this->config['wxapp']['is_money_open'];//开启充值菜单
		$Data['is_deposit_open'] = (int)$this->config['wxapp']['is_deposit_open'];//开启保证金菜单
		$Data['is_redpacket_open'] = (int)$this->config['wxapp']['is_redpacket_open'];//开启红包菜单
		
		
		$Data['index_nav_name'] = $this->config['wxapp']['index_nav_name'] ? $this->config['wxapp']['index_nav_name'] :'想让同学帮你干点什么？';//微信配置
		$Data['is_index_category_type'] = $this->config['wxapp']['is_index_category_type'] ? $this->config['wxapp']['is_index_category_type'] :0;//首页图片设置
		
		$Data['idCard_code_name'] = $this->config['wxapp']['idCard_code_name'] ? $this->config['wxapp']['idCard_code_name'] :'身份证号';//身份证号
		$Data['idCard_code_placeholder'] = $this->config['wxapp']['idCard_code_placeholder'] ? $this->config['wxapp']['idCard_code_placeholder'] :'你的身份证号码18位';//你的身份证号码18位
		$Data['idCard_code_num'] = (int)$this->config['wxapp']['idCard_code_num'] ? (int)$this->config['wxapp']['idCard_code_num'] :18;//跑腿认证界面身份证号位数，【默认限制数量18位】
		$Data['is_auth_pay_code'] = $this->config['wxapp']['is_auth_pay_code'] ? $this->config['wxapp']['is_auth_pay_code'] :0;//开启后小程序跑腿认证上传收款二维码，【默】认关闭
		
		
		$Data['is_studentCard_code'] = (int)$this->config['wxapp']['is_studentCard_code'] ? (int)$this->config['wxapp']['is_studentCard_code'] :0;//学号
		$Data['is_studentCard_faculty'] = $this->config['wxapp']['is_studentCard_faculty'] ? (int)$this->config['wxapp']['is_studentCard_faculty'] :0;//院系
		$Data['is_studentCard_major'] = (int)$this->config['wxapp']['is_studentCard_major'] ? (int)$this->config['wxapp']['is_studentCard_major'] :0;//专业
		$Data['is_studentCard_enrollmentDate'] = (int)$this->config['wxapp']['is_studentCard_enrollmentDate'] ? (int)$this->config['wxapp']['is_studentCard_enrollmentDate'] :0;//入校年月
		$Data['is_logo'] = (int)$this->config['wxapp']['is_logo'] ? (int)$this->config['wxapp']['is_logo'] :1;//上传证件
		$Data['is_logo_name'] = $this->config['wxapp']['is_logo_name'] ? $this->config['wxapp']['is_logo_name'] :'上传学生证';//你的身份证号码18位
		
		$Data['titleName'] = $this->config['wxapp']['titleName'] ? $this->config['wxapp']['titleName'] :'学校';//学校
		$Data['tongxueName'] = $this->config['wxapp']['tongxueName'] ? $this->config['wxapp']['tongxueName'] :'同学';//同学
		$Data['peisongName'] = $this->config['wxapp']['peisongName'] ? $this->config['wxapp']['peisongName'] :'跑腿同学';//跑腿同学
		
		
		$Data['is_cash'] = (int)$this->config['cash']['is_cash'];
		$Data['is_alipay_cash'] = (int)$this->config['cash']['is_alipay_cash'];
		$Data['wxapp'] = $this->config['wxapp'];
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>$Data));
	}
	
	
	
	//HomeIndex
	public function HomeIndex(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		
		
		//默认学校
		$school_id = $getallheaders['Sp-School-Id'] ? $getallheaders['Sp-School-Id'] : $this->schoolDefaultId;
		
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'学校定位失败请重新开启定位','IsSuccess'=>false));
		}
		
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		$scene = I('scene ','','trim,htmlspecialchars');
		
		import('ORG.Util.Page2');
		
		
		$Data['IsLogin'] = $user_id > 0 ? true : false;
		$Data['PopupAd'] = false;//弹出广告
		$Data['siteName'] = $this->config['site']['sitename'] ? $this->config['site']['sitename'] : '金桃cms跑腿';
		$Data['RUNNING'] = $this->config['running'];//跑腿配置
		$Data['WXAPP'] = $this->config['wxapp'];//微信配置
		
		$Keywords = M('RunningCate')->order('orderby asc')->where(array('school_id'=>$school_id,'is_show'=>0))->limit(0,8)->select();
		foreach($Keywords as $k3 => $v3){
			$Keywords[$k3]['id'] = $v3['cate_id'];
			$Keywords[$k3]['Keyword'] = tu_msubstr($v3['cate_name'],0,3,false);
			$Keywords[$k3]['Detail'] = tu_msubstr($v3['Detail'],0,4,false);//简短介绍
			$Keywords[$k3]['ErrandType'] = 0;
			$Keywords[$k3]['DefaultRemark'] = '';
			$Keywords[$k3]['IsHot'] = $v3['is_hot'] == 1 ? 1 : '';
			$Keywords[$k3]['Tag'] = $v3['Tag'];//标签
			$Keywords[$k3]['Url'] = $v3['Url'];
			$Keywords[$k3]['is_index_category_type'] = $this->config['wxapp']['is_index_category_type'] ? $this->config['wxapp']['is_index_category_type']:0;
			$Keywords[$k3]['Src'] = config_weixin_img($v3['photo']);
			
		}
		
		$Data['Keywords'] = $Keywords ? $Keywords :array();//导航关键字
		
		$RunningSchool = M('RunningSchool')->find($school_id);//查询单个城市的配送费
		
		$SettingInfo['RunningSchoolId'] =$RunningSchool['school_id'];//学校
		$SettingInfo['RunningSchoolName'] =$RunningSchool['Name'];//学校
		$SettingInfo['FreightMoneyCaption'] =$RunningSchool['FreightMoneyCaption']?$RunningSchool['FreightMoneyCaption']:$this->config['running']['FreightMoneyCaption'];//运费说明
		$SettingInfo['MinFreightMoney'] =$RunningSchool['MinFreightMoney']?$RunningSchool['MinFreightMoney']:$this->config['running']['MinFreightMoney'];//最小运费
		$SettingInfo['NormalDeliveryAllowOrderTypes'] =$this->config['running']['NormalDeliveryAllowOrderTypes'];//正常递送允许订单类型
		$SettingInfo['ExpressCostArticleId'] =$this->config['running']['ExpressCostArticleId'] ? $this->config['running']['ExpressCostArticleId'] : 1;//运费文章ID
		$SettingInfo['ErrandServiceArticleId'] =$this->config['running']['ErrandServiceArticleId'] ? $this->config['running']['ErrandServiceArticleId'] : 1;//跑腿文章ID
		
		$Data['SettingInfo'] = $SettingInfo;//设置
		
		
		
		
		$Banners = M('ad')->where(array('site_id'=>'85','closed'=>'0','school_id'=>$school_id))->limit(0,4)->select();
		if(!$Banners){
			$Banners = M('ad')->where(array('site_id'=>'85','closed'=>'0'))->limit(0,4)->select();
		}
		
		foreach($Banners as $k => $v){
			//p(config_weixin_img($v['photo']));die;
			$Banners[$k]['id'] = $v['ad_id'];
			$Banners[$k]['Title'] = $v['name'];
			$Banners[$k]['Width'] = 0;
			$Banners[$k]['Height'] = 0;
			$Banners[$k]['Url'] = $v['src'];
			$Banners[$k]['Src'] = config_weixin_img($v['photo']);
		}
		$Data['Banners'] = $Banners ? $Banners :array();//广告
		
		//p($Data['Banners']);die;
	
	    $Data['Modules'] = array();


		$Delivery = M('RunningDelivery')->where(array('audit'=>2,'closed'=>0,'school_id'=>$school_id))->limit(0,30)->order('num desc')->select();
		foreach($Delivery as $k4 => $v4){
			$Users = M('users')->find($v4['user_id']);
			$Delivery[$k4]['NickName'] = $Users['nickname'];
			$RunningSchool = M('RunningSchool')->find($v4['school_id']);
			$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$v4['user_id']))->find();
			
			$Delivery[$k4]['SchoolName'] = $RunningDelivery['Department'];
			$Delivery[$k4]['AvatarUrl'] = config_weixin_img($Users['face']);
			$money = (int)M('RunningMoney')->where(array('user_id'=>$v4['user_id']))->sum('money');
			$Delivery[$k4]['MoneyIncome'] = round($money/100,2);
			$Delivery[$k4]['CreditScore'] = 0;
			$Delivery[$k4]['ErrandType'] = 0;
		}
		$Delivery = list_sort_by($Delivery,'MoneyIncome','desc');
		$Delivery = array_slice($Delivery,0,2);
		$Data['BestDeliverers'] = $Delivery;//最佳投递人
		
		//p($Data['BestDeliverers']);die;
		
		//强制抢单
		$map = array('closed'=>0,'school_id'=>$school_id,'orderType'=>1,'is_ele_pei'=>0,'OrderStatus'=>array('in',array(2,4,8,16,32,64,128)));
		$count = M('Running')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();
		//p($Page->totalPages);
		//p($pageIndex);
       	if($Page->totalPages < $pageIndex){
            die(0);
        }
		
		$ErrandOrders = M('Running')->order(array('OrderStatus'=>'asc','running_id'=>'desc'))->where($map)->limit($Page->firstRow .','.$Page->listRows)->select();
		foreach($ErrandOrders as $k2 => $v2){
			$ErrandOrders[$k2]['Id'] = $v2['running_id'];
			$ErrandOrders[$k2]['Code'] = $v2['running_id'];
			$ErrandOrders[$k2]['Status'] = $v2['OrderStatus'];
			$ErrandOrders[$k2]['Type'] = $v2['Type'];
			$cate = M('running_cate')->where(array('cate_id'=>$v2['cate_id']))->find();
			$ErrandOrders[$k2]['cateName'] = $cate['cate_name'] ? $cate['cate_name'] : '暂无分类';
			
			$Stype= M('RunningCate')->find($v2['cate_id']);
			$ErrandOrders[$k2]['Stype'] = $v2['Stype'];
			$ErrandOrders[$k2]['IsSecret'] = $this->getORderIsSecret($v2['title']);
			
		
  
			
			$ErrandOrders[$k2]['title'] = $this->findNumTHuan($v2['title'],$v2['running_id'],$user_id,$type=1);
			
			$ErrandOrders[$k2]['Money'] = round($v2['price']/100,2);
			$ErrandOrders[$k2]['MoneyFreight'] = round($v2['MoneyFreight']/100,2);
			$ErrandOrders[$k2]['MoneyTip'] = round($v2['MoneyTip']/100,2);
			$ErrandOrders[$k2]['MoneyFee'] = round($v2['price']/100,2);
			$ErrandOrders[$k2]['Serial'] = 0;//连续
			$ErrandOrders[$k2]['Remark'] = $this->findNumTHuan($v2['title'],$v2['running_id'],$user_id,$type=1);
			$ErrandOrders[$k2]['ShopName'] = '';
			$ErrandOrders[$k2]['ShopLogoSrc'] = '';
			$ErrandOrders[$k2]['IsAdvanceMoney'] = '';
			$ErrandOrders[$k2]['CommodityNames'] = '';
			
			
			$ErrandOrders[$k2]['IsForbiddenAccept'] = $this->IsForbiddenAccept($v2['running_id'],$user_id);
			
			$ErrandOrders[$k2]['NickName'] = $v2['name'];
			$ErrandOrders[$k2]['UserId'] = $v2['user_id'];
			$Users = M('users')->find($v2['user_id']);
			$ErrandOrders[$k2]['AvatarUrl'] = config_img($Users['face']);
			$ErrandOrders[$k2]['DelivererUserId'] = $v2['cid'];
			$ErrandOrders[$k2]['CreatedTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$ErrandOrders[$k2]['AcceptedTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$ErrandOrders[$k2]['ExpectTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$ErrandOrders[$k2]['DestinationAddress'] = $v2['addr'];
			$ErrandOrders[$k2]['CreatedTimeString'] = formatTime($v2['create_time']);
			$ErrandOrders[$k2]['Description'] = $v2['addr'].'-'.$this->utcUpdateDate($v2['ExpectTime']);
		}
		
		
		$Data['ErrandOrders'] = $ErrandOrders ? $ErrandOrders : array();//内容
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	//判断当前会员是否有抢单资格
	public function IsForbiddenAccept($running_id,$user_id){
		
		$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$user_id))->find();//配送员
		$Running = M('Running')->find($running_id);
		if(empty($RunningDelivery)){
			return false;
		}
	
		if($RunningDelivery['audit'] == 2){
			if($Running['LimitDelivererGender'] == 0 || $Running['LimitDelivererGender'] == $RunningDelivery['Gender']){
				return false;
			}
			return true;
		}
		return true;
	}
	
	
	//批量替换标题中的数字为****$type1是全能2是外卖
	public function findNumTHuan($title,$running_id,$user_id,$type){
		
		$Running = M('Running')->find($running_id);
		
		
		
		//p($Running);die;
		//p($type);
		if($type == 1){
			if($Running['delivery_id'] == $user_id || $Running['user_id'] == $user_id ){
				return $title;
			}
			if(strpos($title,"私密订单") !== false){ 
				$newstr = '********私密订单********';
			}else{
				$reg = '|[0-9a-zA-Z/]+|';
				$after = '****';
				$newstr = preg_replace($reg,$after,$title);
			}
		}else{
			if($Running['delivery_id'] == $user_id || $Running['user_id'] == $user_id){
				return $title;
			}
			$reg = '/((0|[1-9]\d*)(\.\d+)?)|(零|一|二|三|四|五|六|七|八|九|十)(百|十|零)?(一|二|三|四|五|六|七|八|九)?(百|十|零)?(一|二|三|四|五|六|七|八|九)?/';
			$after = '****';
			$newstr = preg_replace($reg,$after,$title);
		}
		return $newstr;
	}



	
	//经纬度搜索学校
	public function HomeNearestSchool (){
		$longitude = I('longitude','','trim,htmlspecialchars');
		$latitude = I('latitude','','trim,htmlspecialchars');
		$school = I('school','','trim,htmlspecialchars');
		
		
		
		
		$getMapChangeBaidu2 = getMapChangeBaidu2($latitude,$longitude);
		$lat = substr($getMapChangeBaidu2['lat'],0,strlen($getMapChangeBaidu2['lat'])-6);
		$lng = substr($getMapChangeBaidu2['lng'],0,strlen($getMapChangeBaidu2['lng'])-6);
		

		$orderby = " (ABS(lng - '{$lng}') +  ABS(lat - '{$lat}') ) asc ";
		
	
		$list = M('RunningSchool')->order($orderby)->limit(0,50)->select();
		
		
		$Data['Id'] = $list[0]['school_id'];
		$Data['Name'] = $list[0]['Name'];
		$Data['Region'] = $list[0]['Region'];
		
		if($Data){
			$this->ajaxReturn(array('ErrorCode'=>null,'ErrorMessage'=>null,'IsSuccess'=>true,'Data'=>json_encode($Data,JSON_UNESCAPED_SLASHES)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>0,'ErrorMessage'=>'未找到学校','IsSuccess'=>false));
		}
		
	}
	
	
	//经纬度学校列表
	public function SchoolGetSchoolList (){
		$longitude = I('longitude','','trim,htmlspecialchars');
		$latitude = I('latitude','','trim,htmlspecialchars');
		
		
		$school = I('school','','trim,htmlspecialchars');
		
		$Data = M('RunningSchool')->where(array('school'=>$school))->limit(0,50)->select();
		foreach($Data  as $k=>$v){
			$Data[$k]['Id'] = $v['school_id'];
		}
		if($Data){
			$this->ajaxReturn(array('ErrorCode'=>null,'ErrorMessage'=>null,'IsSuccess'=>true,'Data'=>json_encode($Data,JSON_UNESCAPED_SLASHES)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>0,'ErrorMessage'=>'没有搜索到学校','IsSuccess'=>false));
		}
	}

	
	
	//StudentGeDelivererList排行榜
	public function StudentGeDelivererList(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$bg_time = strtotime(TODAY);
		
		$TodayIncomeMoney = M('Running')->where(array('school_id'=>$school_id,'create_time'=>array(array('ELT',NOW_TIME),array('EGT',$bg_time)),'status'=>array('gt',0)))->sum('MoneyFreight');
		$Data['TodayIncomeMoney'] = round($TodayIncomeMoney/100,2);
		
		
		$RecentIncomeMoney = M('Running')->where(array('school_id'=>$school_id,'status'=>array('gt',0)))->order('rinnging_id desc')->sum('MoneyFreight');
		$Data['RecentIncomeMoney'] = round($RecentIncomeMoney/100,2);
		
		$BestDeliverers = M('RunningDelivery')->where(array('school_id'=>$school_id,'audit'=>2,'closed'=>0))->limit(0,10)->order('num desc')->select();
		
		
		
		foreach($BestDeliverers as $k4 => $v4){
			$Users = M('users')->find($v4['user_id']);
			$BestDeliverers[$k4]['NickName'] = $Users['nickname'];
			$RunningSchool = M('RunningSchool')->find($v4['school_id']);
			$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$v4['user_id']))->find();
			
			$BestDeliverers[$k4]['uid'] = $v4['user_id'];
			$BestDeliverers[$k4]['SchoolName'] = $RunningDelivery['Department'];
			$BestDeliverers[$k4]['AvatarUrl'] = config_weixin_img($Users['face']);
			
			$Delivery[$k4]['SchoolName'] = $RunningDelivery['Department'];
			$Delivery[$k4]['AvatarUrl'] = config_weixin_img($Users['face']);
			$money = (int)M('RunningMoney')->where(array('user_id'=>$v4['user_id']))->sum('money');
			$BestDeliverers[$k4]['MoneyIncome'] = round($money/100,2);
			$BestDeliverers[$k4]['CreditScore'] = 0;
			$BestDeliverers[$k4]['ErrandType'] = 0;
		}
		
		
		$BestDeliverers = list_sort_by($BestDeliverers,'MoneyIncome','desc');
		$Data['BestDeliverers'] = $BestDeliverers;
		
		$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$user_id))->find();
		if(!$RunningDelivery){
			$Data['IsAuthenticated'] = 0;
			$Data['info'] = '立即认证去抢单';
		}elseif($RunningDelivery && $RunningDelivery['audit'] == 1){
			$Data['IsAuthenticated'] = 1;
			$Data['info'] = '认证审核中';
		}elseif($RunningDelivery && $RunningDelivery['audit'] == 2){
			$Data['IsAuthenticated'] = 1;
			$Data['info'] = '立即去抢单';
		}elseif($RunningDelivery && $RunningDelivery['audit'] == 3){
			$Data['IsAuthenticated'] = 0;
			$Data['info'] = '认证审核失败';
		}
		
		
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	

		
	
	
	//OrderErrandOrderList订单列表
	public function OrderErrandOrderList(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		
		$school_id = $getallheaders['Sp-School-Id'];
		
		//p($school_id);die;
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'获取学校失败','IsSuccess'=>false));
		}
		
		
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		
		$cate_id = I('stype','','trim,htmlspecialchars');
		
		$delivery_id = I('delivery_id','','trim,htmlspecialchars');
		$errandlist = I('errandlist','','trim,htmlspecialchars');
		
		import('ORG.Util.Page2');
		
		if($delivery_id > 0){
			$map = array('closed'=>0,'school_id'=>$school_id,'orderType'=>1,'is_ele_pei'=>0,'OrderStatus'=>array('in',array(8,16,32,64,128)));
			$map['cate_id'] = $cate_id;
			$map['delivery_id'] = $delivery_id;
		}elseif($cate_id && $delivery_id <= 0){
			$map = array('closed'=>0,'school_id'=>$school_id,'orderType'=>1,'is_ele_pei'=>0,'OrderStatus'=>array('in',array(2,4,8,16,32,64,128)));
			$map['cate_id'] = $cate_id;
		}else{
			$map = array('closed'=>0,'school_id'=>$school_id,'orderType'=>1,'is_ele_pei'=>0,'OrderStatus'=>array('in',array(2,4,8,16,32,64,128)));
		}
		
		$count = M('Running')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();

		
		$list = M('Running')->where($map)->order(array('OrderStatus'=>'asc','running_id'=>'desc'))->limit($Page->firstRow .','.$Page->listRows)->select();
		
		if(!$list){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
		}
		
		foreach($list as $k2 => $v2){
			$list[$k2]['Id'] = $v2['running_id'];
			$list[$k2]['Code'] = $v2['running_id'];
			$list[$k2]['Status'] = $v2['OrderStatus'];
			$list[$k2]['Type'] = $v2['Type'];
			$cate = M('running_cate')->where(array('cate_id'=>$v2['cate_id']))->find();
			$list[$k2]['cateName'] = $cate['cate_name'] ? $cate['cate_name'] : '暂无分类';
			
			$Stype= M('RunningCate')->find($v2['cate_id']);
			$list[$k2]['Stype'] = $v2['Stype'];
			$list[$k2]['IsSecret'] = $this->getORderIsSecret($v2['title']);
			
			$list[$k2]['Money'] = round($v2['price']/100,2);
			$list[$k2]['MoneyFreight'] = round($v2['MoneyFreight']/100,2);
			$list[$k2]['MoneyTip'] = round($v2['MoneyTip']/100,2);
			$list[$k2]['MoneyFee'] = round($v2['price']/100,2);
			$list[$k2]['Serial'] = 0;//连续
			$list[$k2]['Remark'] = $this->findNumTHuan($v2['title'],$v2['running_id'],$user_id,$v2['Type'] == 2 ? 1 : 2);
			$list[$k2]['ShopName'] = '';
			$list[$k2]['ShopLogoSrc'] = '';
			$list[$k2]['IsAdvanceMoney'] = '';
			$list[$k2]['CommodityNames'] = '';
			$list[$k2]['IsForbiddenAccept'] = $this->IsForbiddenAccept($v2['running_id'],$user_id);
			$list[$k2]['NickName'] = $v2['name'];
			$list[$k2]['UserId'] = $v2['user_id'];
			$Users = M('users')->find($v2['user_id']);
			$list[$k2]['AvatarUrl'] = config_img($Users['face']);
			$list[$k2]['DelivererUserId'] = $v2['cid'];
			$list[$k2]['CreatedTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$list[$k2]['AcceptedTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$list[$k2]['ExpectTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$list[$k2]['DestinationAddress'] = $v2['addr'];
			$list[$k2]['CreatedTimeString'] = formatTime($v2['create_time']);
			$list[$k2]['Description'] = $v2['addr'].'-'.$this->utcUpdateDate($v2['ExpectTime']);
		}
		
		
		
		$cate = M('running_cate')->order('orderby asc')->where(array('school_id'=>$school_id,'is_show'=>0,'is_system'=>0))->limit(0,20)->select();
		$cates = array();
		foreach($cate as $k3 => $v3){
			$cates[$k3]['Value'] = $v3['cate_id'];
			$cates[$k3]['Name'] = $v3['cate_name'];
		}
		$Data['cates'] =$cates;
		$Data['list'] =$list ? $list : array();
		
		if($Data){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
	}
	
	
	
	
	
	//用户的评价列表
	public function pingList(){
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		
		$role = I('role','','trim,htmlspecialchars');//角色
		$uid = I('uid','','trim,htmlspecialchars');//会员ID
		
		import('ORG.Util.Page2');

        //当前学校
		$getallheaders = $this->getallheaders();
		$map['school_id'] = $getallheaders['Sp-School-Id'];
		$map['OrderStatus'] = 128;//已完成
		$map['closed'] = 0;
		if($role == 1){
			$map['user_id'] = $uid;
		}else{
			$map['delivery_id'] = $uid;
		}
		
		$count = M('Running')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();
        $p = $pageIndex;
		
		if($Page->totalPages < $p){
            $this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
        }
		
		
		$list = M('Running')->where($map)->order('running_id desc')->limit($Page->firstRow .','.$Page->listRows)->select();
		foreach($list as $k2 => $v2){
			$list[$k2]['uid'] = $v2['user_id'];
			$list[$k2]['content'] = $v2['content'] ? $v2['content'] : '什么已没有留下';
			$labels = @explode(",",$v2['labels']);
			$list[$k2]['labels'] = $labels;
			$list[$k2]['score'] = $v2['score'];
			$list[$k2]['endTime'] = date('Y-m-d H:i:s ',$v2['end_time']);
			$Users = M('users')->find($v2['user_id']);
			$list[$k2]['face'] = config_weixin_img($Users['face']);
			$list[$k2]['userName'] = config_user_name($Users['nickname']);
			$cate = M('running_cate')->where(array('cate_id'=>$v2['cate_id']))->find();
			$list[$k2]['cateName'] = $cate['cate_name'] ? $cate['cate_name'] : '暂无分类';
		}
		
		if($list){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($list)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
	}
	
	
	//我的订单列表
	public function OrderOrderList(){
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		
		$role = I('role','','trim,htmlspecialchars');//1他收获的评价当他是发单人，2他评价别人配送员或者商家
		
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'未登陆请稍后再试','IsSuccess'=>false));
		}
		
		import('ORG.Util.Page2');
		$map['school_id'] = $getallheaders['Sp-School-Id'];
		
		$map['closed'] = 0;
		if($role == 1){
			$map['user_id'] = $user_id;
		}else{
			$map['delivery_id'] = $user_id;
		}
		
		$count = M('Running')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();
        $p = $pageIndex;
		
		if($Page->totalPages < $p){
            $this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
        }
		
		$list = M('Running')->where($map)->order('running_id desc')->limit($Page->firstRow .','.$Page->listRows)->select();
		foreach($list as $k2 => $v2){
			
			$list[$k2]['uid'] = $v2['user_id'];
			$list[$k2]['Id'] = $v2['running_id'];
			
			$list[$k2]['Code'] = $v2['running_id'];
			$list[$k2]['Status'] = $v2['OrderStatus'];
			$list[$k2]['Type'] = $v2['Type'];
			
			$cate = M('running_cate')->where(array('cate_id'=>$v2['cate_id']))->find();
			$list[$k2]['cateName'] = $cate['cate_name'] ? $cate['cate_name'] : '暂无分类';
			
			$Stype= M('RunningCate')->find($v2['cate_id']);
			$list[$k2]['Stype'] = $v2['Stype'];
			$list[$k2]['IsSecret'] = $this->getORderIsSecret($v2['title']);
			
			$list[$k2]['Money'] = round($v2['price']/100,2);
			$list[$k2]['MoneyFreight'] = round($v2['MoneyFreight']/100,2);
			$list[$k2]['redpacket_money'] = round($v2['redpacket_money']/100,2);
			$list[$k2]['coupon_price'] = round($v2['coupon_price']/100,2);
			$list[$k2]['MoneyTip'] = round($v2['MoneyTip']/100,2);
			$list[$k2]['MoneyFee'] = round($v2['price']/100,2);
			$list[$k2]['Serial'] = 0;//连续
			$list[$k2]['Remark'] = $v2['title'];
			
			
			$Shop= M('shop')->find($v2['ShopId']);
			
			$list[$k2]['ShopName'] = $Shop['shop_name'];
			$list[$k2]['ShopLogoSrc'] = config_weixin_img($Shop['photo']);
			
			
			$RunningProducts = M('RunningProduct')->where(array('running_id'=>$v2['running_id']))->select();
			foreach($RunningProducts as $k => $v){
				$CommodityNames += '名称'.$val['product_name'] .'*数量'. $val['Quantity'].'单价：'. $val['Price'];
			}
			
			$list[$k2]['CommodityNames'] = $CommodityNames  ? $CommodityNames : $v2['title'] ? $v2['title'] : '未知错误';
			
			$list[$k2]['IsAdvanceMoney'] = '';
			
			$list[$k2]['IsForbiddenAccept'] = '';
			$list[$k2]['NickName'] = $v2['name'];
			$list[$k2]['UserId'] = $v2['user_id'];
			$Users = M('users')->find($v2['user_id']);
			$list[$k2]['AvatarUrl'] = config_weixin_img($Users['face']);
			$list[$k2]['DelivererUserId'] = $v2['cid'];
			$list[$k2]['CreatedTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$list[$k2]['AcceptedTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$list[$k2]['ExpectTime'] = date('Y-m-d H:i:s ',$v2['create_time']);
			$list[$k2]['DestinationAddress'] = $v2['addr'];
			$list[$k2]['CreatedTimeString'] = "今天 12:13";
			$list[$k2]['Description'] = $v2['addr'].'-'.$this->utcUpdateDate($v2['ExpectTime']);
		}
		
	
		if($list){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($list)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
	}
	
	
	
	
	//OrderErrandOrderPrepare  订单准备下单准备

			
	public function OrderErrandOrderPrepare(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'会员ID不存在','IsSuccess'=>false));
		}
		$users = M('users')->find($user_id);//查询会员
		if(!$users){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'会员信息不存在','IsSuccess'=>false));
		}
		
		
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'学校ID不存在','IsSuccess'=>false));
		}
		$RunningSchool = M('RunningSchool')->find($school_id);//查询学校
		if(!$RunningSchool){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'学校不存在','IsSuccess'=>false));
		}
		
		$cate_id = I('cate_id','','trim,htmlspecialchars');
		if(!$cate_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'分类ID不存在','IsSuccess'=>false));
		}
		
		
		$Coupons = M('coupon_download')->where(array('user_id'=>$user_id,'is_used'=>0))->limit(0,200)->select();
		foreach($Coupons as $k => $val){
			$Coupon = M('coupon')->where(array('coupon_id'=>$val['coupon_id']))->find();
			$Coupons[$k]['invalid'] = $val['is_used'] == 0 ? 0 : 1;
			$Coupons[$k]['Id'] = $Coupon['download_id'];
			$Coupons[$k]['Money'] = round($Coupon['reduce_price']/100,2);
			$Coupons[$k]['ExpiredTime'] = $Coupon['expire_date'];
			$Coupons[$k]['MoneyLimit'] = round($Coupon['full_price']/100,2);
			$Coupons[$k]['isSelected'] = 0;
			
			if($Coupon['expire_date'] <= TODAY){
				M('coupon_download')->where(array('download_id'=>$val['download_id']))->save(array('is_used'=>1));
				unset($Coupons[$k]);
			}
		}
		
		$Data['Coupons'] = $Coupons;
		$Data['CouponIds'] = $Coupons;
		
		//获取分类
		$cate = M('running_cate')->order('orderby asc')->where(array('school_id'=>$school_id,'is_show'=>0))->limit(0,30)->select();
		$cates = array();
		foreach($cate as $k3 => $v3){
			$cates[$k3]['Value'] = $v3['cate_id'];
			$cates[$k3]['Name'] = $v3['cate_name'];
		}
		$Data['cates'] =$cates;

		$runningCate = M('running_cate')->find($cate_id);
		$c = $runningCate ;//分类赋值
		$config = $this->config['running'];//跑腿配置赋值
		
		$Data['onMoneyTap'] =(int)$runningCate['onMoneyTap'];
		$Data['onExpressFeeLink'] =(int)$runningCate['onExpressFeeLink'];
		$Data['onExpressFeeLinkName'] = $runningCate['onExpressFeeLinkName'] ? $runningCate['onExpressFeeLinkName'] : '快递费用参考';
		
		$Data['onFile'] =(int)$runningCate['onFile'];
		
		
		
		
		$ErrandTimeRangeBegin = $config['ErrandTimeRangeBegin'] ? $config['ErrandTimeRangeBegin'] : '8:00';
		$ErrandTimeRangeEnd =  $config['ErrandTimeRangeEnd'] ? $config['ErrandTimeRangeEnd'] : '22:00';
		//配置项目时间
		$Data['ErrandTimeRangeBegin'] = $c['ErrandTimeRangeBegin'] ? $c['ErrandTimeRangeBegin'] : $ErrandTimeRangeBegin;//开始时间
		$Data['ErrandTimeRangeEnd'] =$c['ErrandTimeRangeEnd'] ? $c['ErrandTimeRangeEnd'] : $ErrandTimeRangeEnd;//开始时间
		$Data['ErrandTimeRangeDays'] = $c['ErrandTimeRangeDays'] ? $c['ErrandTimeRangeDays'] : 0;//差事时间范围天
		
		//p($Data);die;
		
		//配置运费说明
		$Data['FreightMoneyCaption'] = $RunningSchool['FreightMoneyCaption'] ? $RunningSchool['FreightMoneyCaption'] : $this->config['running']['FreightMoneyCaption'];//运费说明
		
		
		$MinFreightMoney = $RunningSchool['MinFreightMoney'] ? $RunningSchool['MinFreightMoney'] : $this->config['running']['MinFreightMoney'];
		
		$Data['MinFreightMoney'] = $runningCate['price']  ? round($runningCate['price']/100,2) : $MinFreightMoney;//最小运费
	
		$Data['NormalDeliveryAllowOrderTypes'] =$this->config['running']['NormalDeliveryAllowOrderTypes'];//正常递送允许订单类型
		$Data['ExpressCostArticleId'] =$runningCate['onExpressFeeLinkId'] ? $runningCate['onExpressFeeLinkId'] : $this->config['running']['ExpressCostArticleId'] ? $this->config['running']['ExpressCostArticleId'] : 1;//运费文章ID
		$Data['ErrandServiceArticleId'] =$this->config['running']['ErrandServiceArticleId'] ? $this->config['running']['ErrandServiceArticleId'] : 1;//跑腿文章ID
		
		$Data['wallet'] = round($users['money']/100,2);//余额
		
		
		
		//订阅消息ID
		$tmplIds['tid1'] = $this->config['wxapp']['tid1'];
		$tmplIds['tid2'] = $this->config['wxapp']['tid2'];
		$tmplIds['tid3'] = $this->config['wxapp']['tid3'];
		$Data['tmplIds'] = $tmplIds;
		
		
		//分类规格价格
		$Data['attr_id'] = NULL;
		$Data['attr_money'] = '0.00';
		$Data['attr_name'] = NULL;
		$attrs = M('running_cate_attr')->order('orderby asc')->where(array('cate_id'=>$cate_id))->limit(0,8)->select();
		if($attrs){
			$Data['attr_name'] =$attrs[0]['attr_name'];//代表支持规格
			$Data['attr_money'] = $attrs[0]['attr_money'];
			$Data['attr_id'] = $attrs[0]['attr_id'];
		}
		$Data['attrs'] =$attrs;
		
		
		
		$getUsersRedpacketData = $this->getUsersRedpacketData($Data['MinFreightMoney'],$user_id);
		$Data['redpacket_money'] = $getUsersRedpacketData['redpacket_money'];
		$Data['redpacket_id'] = $getUsersRedpacketData['redpacket_id'];
		$Data['redpacket_info'] = $getUsersRedpacketData['redpacket_info'];
		
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	//循环查找适配的红包
	public function getUsersRedpacketData($MinFreightMoney,$user_id){
		$redpacket = array();
		
		//循环查找适配的红包
		$packets = M('users_redpacket')->where(array('user_id'=>$user_id,'is_used'=>0))->order('redpacket_id desc')->limit(0,10)->select();
		foreach($packets as $k=>$v){
			$money = round($v['money']/100,2);
			if($MinFreightMoney > $money){
				$redpacket = $v;
				break;
			}
		}
		//p($redpacket);die;
		
		if($redpacket){
			//获取会员红包信息
			$Data['redpacket_money'] = round($redpacket['money']/100,2);
			$Data['redpacket_id'] = $redpacket['redpacket_id'];
			$Data['redpacket_info'] = $redpacket['info'];
			return $Data;
		}else{
			$Data['redpacket_money'] = 0;
			$Data['redpacket_id'] = NULL;
			$Data['redpacket_info'] = NULL;
			return $Data;
		}
	}
	
	
	
	//登录2020
	public function LoginLoginWechat(){
		
		$getallheaders = $this->getallheaders();
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$wechat = I('wechat','','trim,htmlspecialchars');
		$code = I('code','','trim,htmlspecialchars');
		$referrerId = I('referrerId','','trim,htmlspecialchars');
		
		$appid = $this->config['wxapp']['appid'];
		$secret = $this->config['wxapp']['appsecret'];
		$url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
		$res = $this->httpRequest($url);
		$Data = json_decode($res,true);  
		
		//p($Data);die;
		
		//p($url);die;
		//p($Data['errcode'].''.$Data['errmsg']);die;
		
		//p($Data);die;
		
		if(!$Data['openid']){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'微信认证失败'.$Data['errcode'].'--'.$Data['errmsg'],'IsSuccess'=>false,'Data'=>''));
		}
		
		
		 
		if($Data['openid']){
			
			//查询openid
			
			if($Data['unionid']){
				$connect = M('connect')->where(array('type'=>'weixin','unionid'=>$Data['unionid']))->order(array('create_time'=>'asc'))->find();
				
				
				if(!$connect){
					$connect = M('connect')->where(array('type'=>'weixin','openid'=>$Data['openid']))->order(array('create_time'=>'asc'))->find();
				}
			}else{
				$connect = M('connect')->where(array('type'=>'weixin','openid'=>$Data['openid']))->order(array('create_time'=>'asc'))->find();
				
				
			}
			
		
			$count = M('connect')->where(array('type'=>'weixin','openid'=>$Data['openid']))->count();
			if($count > 1){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'openid【'.$Data['openid'].'】存在多个请联系管理员处理','IsSuccess'=>false,'Data'=>''));
			}
			
			
			//查询会员信息
			if($connect['uid']){
				$u = M('users')->where(array('user_id'=>$connect['uid']))->find();
			}
			
			//$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'=uuu==【'.$connect['uid'].'】','IsSuccess'=>false,'Data'=>''));
			
			//p($Connect);die;
			
			
			//1有注册信息2有注册会员3有会员信息
			if($connect && $connect['uid'] && $u){
				
				//更新session_key
				M('connect')->where(array('connect_id'=>$connect['connect_id']))->save(array('unionid'=>$Data['unionid'],'rd_session'=>$Data['session_key']));
				
				//获取用户信息
				$Users = M('users')->find($connect['uid']);
				//输出用户信息
				$Users['id'] = $Users['user_id'];
				$Users['openid'] = $Data['openid'];
				$Users['unionid'] = $Data['unionid'];
				$Users['session_key'] = $Data['session_key'];
				$Users['SessionId'] = $Users['user_id'];
				$Users['Type'] = 'weixin';
				$Users['orderListDefaultDelivery'] = 1;
				$Users['Mobile'] = $Users['mobile'];
				
				
			}else{
				
				//新用户注册获取信息
				$array['unionid'] = $Data['unionid'];
				$array['openid'] = $Data['openid'];
				$array['type'] = 'weixin';
				$Users['orderListDefaultDelivery'] = 1;
				$array['session_key'] = $Data['session_key'];
				$array['create_time'] = time();
				$array['create_ip'] = get_client_ip();
				
				$connectName = '添加';
				if(!$connect){
					$connect_id = M('connect')->add($array);//新建表
					$connectName = '添加';
				}else{
					$c =  M('connect')->where(array('connect_id'=>$connect['connect_id']))->save($array);//更新表
					$connect_id = $connect['connect_id'];//新建表
					$connectName = '更新';
				}
				
				
			    if(!$connect_id){
					$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'【'.$connectName.'】connect数据表失败','IsSuccess'=>false,'Data'=>''));
				}
				
				
				$account = 'running_wxapp'.$connect_id;
					
				$arr = array(
				   'account' => $account,
				   'school_id' => $school_id,
				   'openid' => $Data['openid'], 
				   'unionid' => $Data['unionid'],   
				   'password' => 123456, 
				   'nickname' => $account, 
				   'create_time' => NOW_TIME, 
				   'create_ip' => get_client_ip()
				);
				
				//注册新用户
            	$user_id = D('Passport')->register($arr,$fid = '',$type = '1');//注册的时候新增学校ID
				if(!$user_id){
					$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'注册新用户失败管理员排查','IsSuccess'=>false,'Data'=>''));
				}
				
				//更新session_key
				M('connect')->where(array('connect_id'=>$connect_id))->save(array('uid'=>$user_id,'nickname'=>$account,'unionid'=>$Data['unionid'],'rd_session'=>$Data['session_key']));
				
				
				
				$Users = M('users')->find($user_id);
				$Users['openid'] = $Data['openid'];
				$Users['session_key'] = $Data['session_key'];
				$Users['id'] = $user_id;
				$Users['SessionId'] = $user_id;
				$Users['Type'] = 'weixin';
				$Users['Mobile'] = $Users['mobile'];
				
			}
			
			
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Users)));
		}
		
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'微信验证失败'.$Data['errcode'].''.$Data['errmsg'],'IsSuccess'=>false));
		
	}
	
	
	
	//UserUpdateUserInfo更新会员
	public function UserUpdateUserInfo(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		$school_id = $getallheaders['Sp-School-Id'];
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		
		
		$Users = M('users')->find($user_id);
		if(!$Users){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'查找会员信息【'.$user_id.'】失败','IsSuccess'=>false));
		}
		
	
		$connect = M('connect')->where(array('type'=>'weixin','uid'=>$user_id))->find();
		if(!$connect){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'connect表不存在','IsSuccess'=>false));
		}
		$session_key = $data['session_key'];
		if(!$session_key){
			$session_key = $connect['session_key'];
		}
		if(!$session_key){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'session_key不存在，清理缓存后重新试试','IsSuccess'=>false));
		}
		include APP_PATH .'/Lib/Action/App/jiemi/wxBizDataCrypt.php';
		$pc = new WXBizDataCrypt($this->config['wxapp']['appid'],$session_key);
		$errCode = $pc->decryptData($data['sencryptedData'],$data['iv'],$rest);
		$rest = json_decode($rest,true); 
		
		
		//绑定unionId
		if($rest['unionId']){
			$res['unionid'] = $rest['unionId'];
		}
		
	
		
		//更新推荐人
		$fuid = $data['fuid'];//获取推荐人信息
		$f = M('users')->find($fuid);
		
		
		
		//更新推荐人分销关系
		if(empty($Users['fuid1']) && $f){
			$res['fuid1'] = $f['user_id'];
			$res['fuid2'] = $f['fuid1'];
			$res['fuid3'] = $f['fuid2'];
			$res['fuid4'] = $f['fuid3'];
			$res['fuid5'] = $f['fuid4'];
			$res['fuid6'] = $f['fuid5'];
			$res['fuid7'] = $f['fuid6'];
			$res['fuid8'] = $f['fuid7'];
			$res['fuid9'] = $f['fuid8'];
			$res['fuid10'] = $f['fuid9'];
		}
		
		
		
		//获取会员的数据
		$res = $this->getUserData($user_id,$school_id);
		
		$res['user_id'] = $user_id;
		$res['face'] = $data['AvatarUrl'];
		$res['nickname'] = $data['NickName'] ? $data['NickName'] : $Users['nickname'];//解决昵称问题
		$res['Gender'] = $data['Gender'] ? $data['Gender'] : $rest['gender'];//解决性别问题
		
		
		
		
		
		M('users')->save($res);
		
		M('connect')->where(array('type'=>'weixin','uid'=>$user_id))->save(array('nickname'=>$res['nickname'],'headimgurl'=>$res['face'],'unionid'=>$rest['unionId']));
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($res),'rest'=>$rest));
	}
	

	//UserBindWechatMobile  绑定微信手机
	public function UserBindWechatMobile(){
		$data = I('data','','trim,htmlspecialchars');
		$iv = I('iv','','trim,htmlspecialchars');
		
		
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		if(!$users){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'会员不存在','IsSuccess'=>false));
		}
		
		
		$connect = M('connect')->where(array('type'=>'weixin','uid'=>$user_id))->find();
		if(!$connect){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'connect表不存在','IsSuccess'=>false));
		}
		
		$session_key = I('session_key','','trim,htmlspecialchars');
		if(!$session_key){
			$session_key = $connect['session_key'];
		}
		if(!$session_key){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'session_key不存在，清理缓存后重新试试','IsSuccess'=>false));
		}
		
		
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			//$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'获取学校ID不存在','IsSuccess'=>false));
		}
		
		
		
		include APP_PATH .'/Lib/Action/App/jiemi/wxBizDataCrypt.php';
	
		$pc = new WXBizDataCrypt($this->config['wxapp']['appid'],$session_key);
		$errCode = $pc->decryptData($data,$iv,$data);

		$res = json_decode($data,true);  
	
		$res['phoneNumber'] = $res['phoneNumber'] ? $res['phoneNumber'] : $users['mobile'];
	
	    if($res['phoneNumber']){
			$Data['phoneNumber'] = $res['phoneNumber'];
			
			$password = md5(substr($res['phoneNumber'],-6));
			//M('users')->where(array('user_id'=>$user_id))->save(array('mobile'=>$res['phoneNumber']));
			M('users')->where(array('user_id'=>$user_id))->save(array('mobile'=>$res['phoneNumber'],'password'=>$password));
			
			$data['mobile'] =  $res['phoneNumber'] ? $res['phoneNumber'] : '';
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'绑定失败【UserBindWechatMobile】','IsSuccess'=>false,'Data'=>''));
	}
	
	
	
	public function eleType(){
		
		$cate = D('Ele')->getEleCate();
		
	    $arr = array();
		$i = 1 ;
	    foreach($cate as $k => $v){
		   $i ++ ;
		   $arr[$k]['id'] = $k;
		   $arr[$k]['type_name'] = $v;
		   $arr[$k]['img'] = __HOST__.'/static/default/wap/image/ele/ele_cate_'.$i.'.png';	
	    } 
        $json_str = json_encode(array_values($arr));
        exit($json_str); 
	
	}
	
	
	//ShopList商家列表
	public function ShopList(){
		
		$cateId = I('cateId','','trim,htmlspecialchars');
		
		$keyword = I('keyword','','trim,htmlspecialchars');
		$pageIndex = I('pageIndex','0','trim,htmlspecialchars');
		
		
		$getallheaders = $this->getallheaders();
		$school_id = $getallheaders['Sp-School-Id'];
		
		import('ORG.Util.Page2');
		
		$map['closed'] = 0;
		$map['audit'] = 1;
		
		
		
		if($school_id){
			$map['school_id'] =$school_id;
		}
		if($cate_id){
			$map['cate'] =$cate_id;
		}
		if($keyword){
			$map['shop_name'] = array('LIKE','%' .$keyword.'%');
		}
		
		
		if($Page->totalPages < $p){
            $this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
        }
		
		$list = M('ele')->where($map)->order('shop_id desc')->select();
		foreach($list as $k2 => $v2){
			$shop = M('shop')->where(array('shop_id'=>$v2['shop_id']))->find();
			
			$list[$k2]['uid'] = $shop['user_id'];//商家用户ID
			$list[$k2]['img'] = config_weixin_img($shop['photo']);
			$list[$k2]['Id'] = $v2['shop_id'];
			$list[$k2]['score'] = D('Running')->getEleScore($v2['shop_id']);
			$list[$k2]['SinceMoney'] = round($v2['since_money']/100,2);
			$list[$k2]['Logistics'] = round($v2['logistics']/100,2);
			$list[$k2]['LogisticsFull'] = round($v2['logistics_full']/100,2);
			//营业时间
			$bsti = $this->closeshopele($v2['busihour']);
			if($v2['is_open'] == 0){
				$IsClosed = 1;
			}elseif($bsti == 1){
				$IsClosed = 1;
			}else{
				$IsClosed = 0;
			}
			$list[$k2]['IsClosed'] = $IsClosed;
			
			//分类筛选
			$cates = explode(',',$v2['cate']);
			$res = array_search($cateId,$cates);
			if($cateId && $res === false){
				unset($list[$k2]);
			}
		}
		
		//p($list);die;
		
		
		$count = count($list);
        $Page = new Page2($count,20);
        $show = $Page->show();
		$p = $pageIndex;
		
		
        if($Page->totalPages < $p){
            $this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
        }
		
        $list = array_slice($list, $Page->firstRow, $Page->listRows);
	
		$Data['list'] = $list;
		
		if($list){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无商家','IsSuccess'=>false));
		}
		
		
	}
	
	
		
	//ShopIndex筛选商家
	public function ShopIndex(){
		$id = I('id','','trim,htmlspecialchars');
		$type = I('type','','trim,htmlspecialchars');
		$groupId = I('groupId','','trim,htmlspecialchars');
		$scene = I('scene','','trim,htmlspecialchars');
		$search = I('search','','trim,htmlspecialchars');//搜索
		
		
		$getallheaders = $this->getallheaders();
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		if($id){
			//单商家模式
			$Shops = M('Ele')->where(array('audit'=>1,'closed'=>0,'school_id'=>$school_id,'shop_id'=>$id))->limit(0,20)->order(array('orderby'=>'asc','shop_id'=>'asc'))->select();
		}else{
			//多商家模式
			$Shops = M('Ele')->where(array('audit'=>1,'closed'=>0,'school_id'=>$school_id,'cate'=>$type))->limit(0,20)->order(array('orderby'=>'asc','shop_id'=>'asc'))->select();
		}
		
		
		
		if($Shops){
			foreach($Shops as $k4 => $v4){
				$Shop = M('shop')->find($v4['shop_id']);
				$Shops[$k4]['Id'] = $v4['shop_id'];
				$Shops[$k4]['Name'] = $v4['shop_name'];
				$Shops[$k4]['Address'] = $Shop['addr'];
				$Shops[$k4]['Description'] = '';
				$Shops[$k4]['LogoSrc'] = config_weixin_img($Shop['photo']);
				$Shops[$k4]['IntroSrc'] = 0;
				$Shops[$k4]['uid'] = $Shop['user_id'];
				//营业时间
				$bsti = $this->closeshopele($v4['busihour']);
				
				if($v4['is_open'] ==0){
					$IsClosed = 1;
				}elseif($bsti == 1){
					$IsClosed = 1;
				}else{
					$IsClosed = 0;
				}
				$Shops[$k4]['IsClosed'] = $IsClosed;
			
			}
		}
		
	
			
		$Data['Shops'] = $Shops ? $Shops : array();
		
		//颜色标题
	
		
		$GroupInfo['IsHideNavTitle'] = $id ? $Shops[0]['shop_name'] : '多商家列表';
		$GroupInfo['Name'] = $id ? $Shops[0]['shop_name'] : '多商家列表';
		$GroupInfo['NavForeColor'] = '#fff';
		$GroupInfo['NavBackgroundColor'] ='#06c1ae';
		$Data['GroupInfo'] =$GroupInfo;
		
		
		$Categories = M('EleCate')->where(array('shop_id'=>$Shops[0]['shop_id']))->order('cate_id asc')->select();
		foreach($Categories as $k3 => $v3){
			$Categories[$k3]['Id'] = $v3['cate_id'];
			$Categories[$k3]['Name'] = $v3['cate_name'];
			$count = (int)M('EleProduct')->where(array('cate_id'=>$v3['cate_id'],'num'=>array('gt',0),'closed'=>0))->count();
			if($count <= 0){
				unset($Categories[$k3]);
			}
		}
		$Categories = array_values($Categories);
		$Data['Categories'] = $Categories;
		
	
		$query['shop_id'] = $Shops[0]['shop_id'];
		$query['closed'] = 0;
		$query['num'] = array('gt',0);
		
		if($search){
			//搜索菜品
			$query['product_name'] = array('LIKE','%' .$search.'%');
		}
		
	
		$PopularCommodities = M('EleProduct')->where($query)->limit(0,300)->order('sold_num desc')->select();
		foreach($PopularCommodities as $k2 => $v2){
			$PopularCommodities[$k2]['Id'] = $v2['product_id'];
			$PopularCommodities[$k2]['CategoryId'] = $v2['cate_id'];
			$PopularCommodities[$k2]['Name'] = $v2['product_name'];
			$PopularCommodities[$k2]['PictureUrl'] = config_weixin_img($v2['photo']);
			$PopularCommodities[$k2]['SaleQuantity'] = $v2['sold_num'];
			$PopularCommodities[$k2]['Price'] = round($v2['price']/100,2);;
			$PopularCommodities[$k2]['PriceMarket'] = round($v2['cost_price']/100,2);
			$PopularCommodities[$k2]['Quantity'] = 0;
		}
		$Data['PopularCommodities'] = $PopularCommodities ? $PopularCommodities :array();
		
		
		
		
		$Data['CartCount'] = '';
		$Data['logistics'] = round($Shops[0]['logistics']/100,2);//logistics配送费
		$Data['FreightMoneyCaption'] = round($Shops[0]['since_money']/100,2);//起送价
		$Data['logistics_full'] = round($Shops[0]['logistics_full']/100,2);//满减配送费
		$Data['MinFreightMoney'] = round($Shops[0]['since_money']/100,2);//MinFreightMoney起送价
		
	
	//p($Data);die;
	
		if($Shops[0]){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
		
		$school = M('RunningSchool')->find($school_id);//查询单个城市的配送费
		
		
		
		
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据可能是以下原因，当前学校【'.$school['Name'].'】暂无商家/商家未审核/商家已关闭/商家ID错误','IsSuccess'=>false));
		
	}
		
	//commodityList不知道是什么东东
	public function ShopCommodityList(){
		
		$id = I('id','','trim,htmlspecialchars');
		$Ele = M('Ele')->where(array('shop_id'=>$id))->find();
		
		
		$list = M('EleProduct')->where(array('shop_id'=>$id,'num'=>array('gt',0),'closed'=>0))->limit(0,300)->order('sold_num desc')->select();
		foreach($list as $k => $v){
			$list[$k]['Id'] = $v['product_id'];
			$list[$k]['CategoryId'] = $v['cate_id'];
			$list[$k]['Name'] = $v['product_name'];
			$list[$k]['PictureUrl'] = config_weixin_img($v['photo']);
			$list[$k]['SaleQuantity'] = $v['sold_num'];
			$list[$k]['Price'] = round($v['price']/100,2);;
			$list[$k]['PriceMarket'] = round($v['cost_price']/100,2);
			$list[$k]['Quantity'] = 0;
		}
		
		/*
		$list['logistics'] = round($Ele['logistics']/100,2);//logistics配送费
		$list['FreightMoneyCaption'] = round($Ele['since_money']/100,2);//起送价
		$list['logistics_full'] = round($Ele['logistics_full']/100,2);//满减配送费
		$list['MinFreightMoney'] = round($Shops[0]['since_money']/100,2);//MinFreightMoney起送价
		*/
		//p($list);die;
		
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($list)));
	}	
	
	
	
	//ShopShopCategories
	public function ShopShopCategories(){
		$id = I('id','','trim,htmlspecialchars');
		
		$Ele = M('Ele')->where(array('shop_id'=>$id))->find();
		
		$Data['Shops'] ='';
		$Data['GroupInfo'] = '';
		
		
		
		//分类关联产品
		$Categories = M('EleCate')->where(array('shop_id'=>$id))->order('cate_id asc')->select();
		foreach($Categories as $k3 => $v3){
			$Categories[$k3]['Id'] = $v3['cate_id'];
			$Categories[$k3]['CategoryId'] = $v3['cate_id'];
			$Categories[$k3]['Name'] = $v3['cate_name'];

			$count = (int)M('EleProduct')->where(array('cate_id'=>$v3['cate_id'],'num'=>array('gt',0),'closed'=>0))->count();
			if($count <= 0){
				unset($Categories[$k3]);
			}
		}
		foreach($Categories as $k5 => $v5){
			$EleProducts = M('EleProduct')->where(array('cate_id'=>$v5['cate_id'],'num'=>array('gt',0),'closed'=>0))->limit(0,60)->order('sold_num desc')->select();
			foreach($EleProducts as $k4 => $v4){
				$EleProducts[$k4]['Id'] = $v4['product_id'];
				$EleProducts[$k4]['CategoryId'] = $v4['cate_id'];
				$EleProducts[$k4]['Name'] = $v4['product_name'];
				$EleProducts[$k4]['PictureUrl'] = config_weixin_img($v4['photo']);
				$EleProducts[$k4]['SaleQuantity'] = $v4['sold_num'];
				$EleProducts[$k4]['Price'] = round($v4['price']/100,2);;
				$EleProducts[$k4]['PriceMarket'] = round($v4['cost_price']/100,2);
				$EleProducts[$k4]['Quantity'] = 0;
			}
			$Categories[$k5]['Commodities'] = $EleProducts;
		}
		$Data['Categories'] = $Categories;
		
		
		
		$PopularCommodities = M('EleProduct')->where(array('shop_id'=>$id,'num'=>array('gt',0),'closed'=>0))->limit(0,60)->order('sold_num desc')->select();
		foreach($PopularCommodities as $k2 => $v2){
			$PopularCommodities[$k2]['Id'] = $v2['product_id'];
			$PopularCommodities[$k2]['CategoryId'] = $v2['cate_id'];
			$PopularCommodities[$k2]['Name'] = $v2['product_name'];
			$PopularCommodities[$k2]['PictureUrl'] = config_weixin_img($v2['photo']);
			$PopularCommodities[$k2]['SaleQuantity'] = $v2['sold_num'];
			$PopularCommodities[$k2]['Price'] = round($v2['price']/100,2);;
			$PopularCommodities[$k2]['PriceMarket'] = round($v2['cost_price']/100,2);
			$PopularCommodities[$k2]['Quantity'] = 0;
		}
		$Data['PopularCommodities'] = $PopularCommodities;
		
		$Data['CartCount'] = '';
		
		$Data['logistics'] = round($Ele['logistics']/100,2);//logistics配送费
		$Data['FreightMoneyCaption'] = round($Ele['since_money']/100,2);//起送价
		$Data['logistics_full'] = round($Ele['logistics_full']/100,2);//满减配送费
		$Data['MinFreightMoney'] = round($Ele['since_money']/100,2);//MinFreightMoney起送价
		
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
		
		
		
		if($list){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($list)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>false));
		}
	}
	
	//获取外卖营业时间段
	public function closeshopele($busihour = '00:00-12:30,12:32-23:59'){
        $timestamp = time();
        $now = date('G.i', $timestamp);
        $close = true;
        if(empty($busihour)){
            return false;
        }
        foreach(explode(',', str_replace(':', '.', $busihour)) as $period) {
            list($periodbegin, $periodend) = explode('-', $period);
            if ($periodbegin > $periodend && ($now >= $periodbegin || $now < $periodend) || $periodbegin < $periodend && $now >= $periodbegin && $now < $periodend) {
                $close = false;
            }
        }
        return $close;
    }
	
	
	//OrderTakeoutOrderPrepare外卖下单订单详情
	public function OrderTakeoutOrderPrepare(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		
		$Data['error'] = 1;
		
		if(!$user_id){
			$Data['errorMsg'] = '登陆状态失效请稍后再试';
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		if(!$users){
			$Data['errorMsg'] = '会员信息不存在';
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'会员信息不存在','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
		
		
		
		$groupId = I('groupId','','trim,htmlspecialchars');
		$takeoutPrepare = I('takeoutPrepare','','trim,htmlspecialchars');
		
		$shopId = I('shopId','','trim,htmlspecialchars');
		
	
		
		$Ele = M('Ele')->where(array('shop_id'=>$shopId))->find();
		if($Ele['is_open'] =! 1){
			$Data['errorMsg'] = '商家已打样';
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'商家已打样','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
		
		$busihour = $this->closeshopele($Ele['busihour']);
		if($busihour == 1){
			$Data['errorMsg'] = '商家当前不在营业时间内，商家营业时间【'.$Ele['busihour'].'】';
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'商家当前不在营业时间内，商家营业时间【'.$Ele['busihour'].'】','IsSuccess'=>true,'Data'=>json_encode($Data)));
        }
		
		
		
		$detail = M('shop')->where(array('shop_id'=>$shopId))->find();
		
		
		$detail['logistics'] = round($Ele['logistics']/100,2);//logistics配送费
		$detail['logistics_full'] = round($Ele['logistics_full']/100,2);//满减配送费
		$detail['FreightMoneyCaption'] = round($Ele['since_money']/100,2);//起送价
		
		$detail['Id'] = $detail['shop_id'];
		$detail['Name'] = $detail['shop_name'];
		$detail['ShopName'] = $detail['shop_name'];
		$detail['Address'] = $detail['addr'];
		$detail['Description'] = '';
		$detail['LogoSrc'] = config_weixin_img($detail['photo']);
		$detail['ShopLogoSrc'] = config_weixin_img($detail['photo']);
		$detail['IntroSrc'] = 0;
		$detail['IsClosed'] = $Ele['is_open'] == 1 ? false : true;
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		$school_id = $getallheaders['Sp-School-Id'];
		
		
			$AddressInfo = M('UserAddr')->where(array('user_id'=>$user_id,'type'=>1,'closed'=>0))->limit(0,20)->order('is_default desc')->find();
			$AddressInfo['Id'] = $AddressInfo['addr_id'];
			$AddressInfo['Address'] = $AddressInfo['addr'];
			$AddressInfo['Linkman'] = $AddressInfo['name'] ;
			$AddressInfo['Phone'] = $AddressInfo['mobile'];
		$detail['AddressInfo'] = $AddressInfo;
		
		$detail['MinFreightMoney'] = round($Ele['logistics']/100,2);
		$detail['AddressInfo'] = $AddressInfo;
		
		
		//会员余额
		$detail['wallet'] = round($users['money']/100,2);
		
	
		//加载购物车总金额
		$items = I('items','','trim');
		$items = json_decode($items,true); 
		foreach($items as $k => $v){
			$Price += $v['Price']*$v['Quantity'];
		}
		$Price = $Price+$detail['logistics'];
			
		
		//外卖下单用红包去获取红包
		$getUsersRedpacketData = $this->getUsersRedpacketData($Price,$user_id);
		$detail['redpacket_money'] = $getUsersRedpacketData['redpacket_money'];
		$detail['redpacket_id'] = $getUsersRedpacketData['redpacket_id'];
		$detail['redpacket_info'] = $getUsersRedpacketData['redpacket_info'];
		
		
		if($detail){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($detail)));
		}else{
			$Data['errorMsg'] = '数据格式错误';
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无数据','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
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
	
	
	
	//会员地址列表
	//RunningUserAddressAddressList
	public function UserAddressAddressList(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'学校ID不能为空','IsSuccess'=>false));
		}
		
		
		
	
		$List = M('UserAddr')->where(array('user_id'=>$user_id,'type'=>1,'closed'=>0,'school_id'=>$getallheaders['Sp-School-Id']))->limit(0,30)->order('addr_id desc')->select();
		//p($List);die;
		foreach($List as $k => $v){
			$List[$k]['Id'] = $v['addr_id'];
			$List[$k]['Linkman'] = $v['name'];
			$List[$k]['Phone'] = $v['mobile'];
			$List[$k]['Address'] = $v['addr'];
			
		}
		if($List){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($List)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无地址','IsSuccess'=>false));
	}
	
	//UserAddressCommonAddressList//地址列表
	//发布选择地址
	public function UserAddressCommonAddressList(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$List = M('UserAddr')->where(array('type'=>2,'closed'=>0,'school_id'=>$school_id))->limit(0,20)->order('addr_id desc')->select();
		foreach($List as $k => $v){
			$List[$k]['Id'] = $v['addr_id'];
			$List[$k]['Name'] = $v['name'];
			$List[$k]['Linkman'] = $v['name'];
			$List[$k]['Phone'] = $v['mobile'];
			$List[$k]['Address'] = $v['addr'];
		}
		
		

		$List2 = M('UserAddr')->where(array('user_id'=>$user_id,'closed'=>0,'type'=>1,'school_id'=>$school_id))->limit(0,10)->order('addr_id desc')->select();
		foreach($List2 as $k => $v){
			$List2[$k]['Id'] = $v['addr_id'];
			$List2[$k]['Name'] = $v['name'];
			$List2[$k]['Linkman'] = $v['name'];
			$List2[$k]['Phone'] = $v['mobile'];
			$List2[$k]['Address'] = $v['addr'];
		}
		
		
		if($List && $List2){
			$List = array_merge($List,$List2);
		}elseif($List && !$List2){
			$List = $List;
		}elseif(!$List && $List2){
			$List = $List2;
		}
		
		if($List){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($List)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'暂无地址','IsSuccess'=>false));
	}		
	
	
	//UserAddressAddressInfo地址详情
	public function UserAddressAddressInfo(){
		$id = I('id','','trim,htmlspecialchars');
		$detail = M('UserAddr')->where(array('addr_id'=>$id))->find();
		$detail['Id'] = $detail['addr_id'];
		$detail['Address'] = $detail['addr'];
		$detail['Linkman'] = $detail['name'];
		$detail['Phone'] = $detail['mobile'];
		if($detail){
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($detail)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'地址不存在','IsSuccess'=>false));
	}	
	
	
	
	
	
	//UserAddressSaveOrUpdateInf添加保存地址
	public function UserAddressSaveOrUpdateInfo(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
	
		$getMapChangeBaidu2 = getMapChangeBaidu2($data['Lat'],$data['Lng']);
		$lat2 = substr($getMapChangeBaidu2['lat'],0,strlen($getMapChangeBaidu2['lat'])-5);
		$lng2 = substr($getMapChangeBaidu2['lng'],0,strlen($getMapChangeBaidu2['lng'])-5);
		

		$data['user_id'] = $user_id;
		$data['addr'] = $data['Address'];
		$data['addr_id'] = $data['Id'];
		$data['type'] = 1;
		$data['name'] = $data['Linkman'];
		$data['mobile'] = $data['Phone'];
		$data['Gender'] = $data['Gender'];
		$data['SchoolId'] = $data['SchoolId'];
		$data['school_id'] = $data['SchoolId']?$data['SchoolId']:$school_id;
		$data['lat'] = $lat2;
		$data['lng'] = $lng2;
		
		if(!isMobile($data['mobile'])){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'手机号格式错误','IsSuccess'=>false));
        }
		
		
		
		if($data['addr_id']){
			$res = M('UserAddr')->save($data);
		}else{
			$res = M('UserAddr')->add($data);
		}
		
		
		
		if($res){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'更新失败','IsSuccess'=>false));
	}	
	
	
	
	
	//UserAddressDeleteInfo删除地址
	public function UserAddressDeleteInfo(){
		$id = I('id','','trim,htmlspecialchars');
		
		
		if(!$UserAddr= M('UserAddr')->find($id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'地址不存在','IsSuccess'=>false));
		}
		if($res = M('UserAddr')->where(array('addr_id'=>$id))->delete()){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'删除成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'删除失败','IsSuccess'=>false));
	}	


	//CouponList优惠券列表
	public function CouponList(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		$map['closed'] = 0;
		$map['user_id']= $user_id;
		$valid = I('valid','','trim,htmlspecialchars');
		
		if($valid == 'false'){
			$map['is_used']= 1;
		}else{
			$map['is_used']= 0;
		}
		//p($map);
		
		$Coupons = M('coupon_download')->where($map)->limit(0,200)->select();
		foreach($Coupons as $k => $val){
			$Coupon = M('coupon')->where(array('coupon_id'=>$val['coupon_id']))->find();
			$Coupons[$k]['Id'] = $val['download_id'];
			$Coupons[$k]['invalid'] = $val['is_used'] == 0 ? 0 : 1;
			$Coupons[$k]['Money'] = round($Coupon['reduce_price']/100,2);
			$Coupons[$k]['ExpiredTime'] = $Coupon['expire_date'];
			$Coupons[$k]['MoneyLimit'] = round($Coupon['full_price']/100,2);
			$Coupons[$k]['isSelected'] = 0;
		}
		$Data = $Coupons;
		//p($Coupons);die;
		if($Data){
			$this->ajaxReturn(array('SuccessCode'=>'','SuccessMessage'=>'查询成功','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}else{
			//写逻辑
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'为查询到红包','IsSuccess'=>false));
		}
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
	
	
	//OrderOrderCommit下单
	public function OrderOrderCommit(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$code = $this->gettCode();
	
	    $CouponIds = json_decode($data['CouponIds'], true); //提交的优惠券价格
		
		$data['school_id']= $getallheaders['Sp-School-Id'];//学校
		
		$data['user_id'] = $user_id;
		$data['Code'] = $code;
	
		
		$data['title']= $data['Remark'];//标题
		
		
		if($words = D('Sensitive')->checkWords($data['title'])){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'抱歉，标题含有敏感词：' . $words,'IsSuccess'=>false));
		} 
	
		if(!$data['cate_id']){
			//解决类型获取失败
			$running_cate = M('running_cate')->where(array('school_id'=>$data['school_id']))->find();
			$data['cate_id'] = $running_cate['cate_id'];
		}
		
		
		$data['Money'] = $data['Money']*100;//垫付金额

		$data['ExpiredMinutes'] = $data['ExpiredMinutes'];//过期时间
		$data['MoneyFreight'] = $data['MoneyFreight']*100;//运费
		$data['logistics'] = $data['ogistics']*100;//配送费
		
		
		
		$data['logistics_full'] = $data['logistics_full']*100;//满多少减去运费1
		$data['MoneyFreightFullMoney'] = $data['logistics_full']*100;//满多少减去运费2
		
		
		
		$data['MoneyPayment'] = $data['MoneyPayment']*100;//实际支付
		
		//红包逻辑开始
		$redpacket = M('users_redpacket')->where(array('redpacket_id'=>$data['redpacket_id']))->find();
		//判断是否能够使用红包
		$MoneyFreight2 = $data['MoneyFreight'];
		if($data['redpacket_money'] <= $data['MoneyFreight']){
			$data['redpacket_money'] = $redpacket['money'];//红包优惠金额
			$data['redpacket_info'] = $redpacket['info'];//红包说明
			$data['redpacket_id'] = $data['redpacket_id'];
			$MoneyFreight2 = $data['MoneyFreight'] -$data['redpacket_money'];
		}
		
		
		//不应该使用优惠券ID
		$data['attr_id'] = $data['attr_id'];//规格ID
		$data['attr_name'] = $data['attr_name'];//规格名字
		$data['attr_money'] = $data['attr_money']*100;//规格价格
		
		
		
		//判断是否能够使用优惠券
		if($data['coupon_price']*100 <= $MoneyFreight2){
			$data['coupon_price'] = $data['coupon_price']*100;//优惠券优惠金额
			$data['download_coupon_id'] = $data['download_coupon_id'];//红包金额
		}
		
		
		
		
		
		$data['price']= $data['Money'];
		
		//到店自提订单减去配送费
		if($data['orderType'] == 2){
			$data['MoneyFreight'] = 0;
		}
		$data['MoneyFreight'] = $data['MoneyFreight'];//实际支付费用
		$data['MoneyFreight'] = $data['MoneyFreight'] + $data['attr_money'];//实际支付费用 = 跑腿费 + 规格费用 - 红包费用
		
		
		$data['orderType'] = $data['orderType'] ? $data['orderType'] : 1;//订单类型1在线配送2自提
		
		$data['freight'] = $data['MoneyFreight'];//运费
		
		
		$data['need_pay'] = $data['MoneyPayment'];//实际支付费用
		$data['Weight']= $data['Weight'] ? $data['Weight'] : 5;//重量
		
		//p($data);die;
		
		$OrderAddressList = $data['OrderAddressList'];
		
		
		$users= M('users')->find($data['user_id']);
		
		
		if($data['Type'] == 2){
			
			$data['startAddress'] = serialize($OrderAddressList[0]);
			$data['endAddress'] = serialize($OrderAddressList[1]);
			$data['name']= $OrderAddressList[1]['Address'];
			$data['addr'] = $OrderAddressList[1]['Description'];
			$UserAddr= M('UserAddr')->find($OrderAddressList[1]['AddressId']);
			
			
			$data['mobile'] = $users['mobile'];
			
			
			
			
		}elseif($data['Type'] == 1){

			$data['endAddress'] = serialize($OrderAddressList[0]);
			$data['name']= $OrderAddressList[0]['Address'];
			$data['addr'] = $OrderAddressList[0]['Description'];
			$UserAddr= M('UserAddr')->find($OrderAddressList[0]['AddressId']);
			
			
			$data['mobile'] = $users['mobile'];
		}
		
		
		$data['lat']= $data['latitude'];//进度
		$data['lng'] = $data['longitude'];//纬度
		$data['CreatedTime'] = date('Y-m-d H:i:s ',time());
		
		$data['create_time'] = NOW_TIME;
		$data['create_ip'] = get_client_ip();
		
		$data['year']= date('Y',time());
		$data['month'] = date('Ym',time());
		$data['day'] = date('Ymd',time());
		
			
			
		$Items = $data['Items'];//菜品列表
		//餐饮订单的一些东西
		if($Items){
			$data['cate_id']= $data['cate_id'];
			$data['title']= '订餐订单'.$data['title'];
			
			$shop = M('shop')->find($data['ShopId']);
			$ele = M('ele')->find($data['ShopId']);
			
			//判断外卖商家打样
			if($ele['is_open'] != 1){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'对不起，该商家已打样','IsSuccess'=>false));
			}
			
			$data['is_ele_pei'] = $shop['is_ele_pei'];//订单配送方式
		}
		
		
		
		//p($data);die;
		
		if($running_id = M('Running')->add($data)){
			
			
			foreach($Items as $k => $v){
				$arr['Code'] = $code;
				$arr['CommodityId'] = $v['CommodityId'];
				$arr['Price'] = $v['Price']*100;
				
				//判断结算价格
				$EleProduct = M('EleProduct')->find($v['CommodityId']);
				if($EleProduct['settlement_price'] <= 0){
					$settlement_price = $arr['Price'];
				}elseif($EleProduct['settlement_price'] > $arr['Price']){
					$settlement_price = $arr['Price'];
				}else{
					$settlement_price = $EleProduct['settlement_price'];
				}
				$arr['settlement_price'] = $settlement_price;//结算费用暂时屏蔽错误
				
				$arr['school_id']= $getallheaders['Sp-School-Id'];//学校
				$arr['shop_id'] = $data['ShopId'];
				$arr['user_id'] = $user_id;
				$arr['addr_id'] = $OrderAddressList[0]['AddressId'];
				$arr['product_name'] = $EleProduct['product_name'];
				$arr['Quantity'] = $v['Quantity'];
				$arr['product_id'] = $v['CommodityId'];
				$arr['running_id'] = $running_id;
				$arr['OrderStatus'] = 1;
				$arr['orderType'] = $data['orderType'] ? $data['orderType'] : 1;
				$arr['create_time'] = NOW_TIME;
				M('RunningProduct')->add($arr);
			}
			
			 //插入附件
			if($data['file']){
				foreach($data['file'] as $k2 => $v2){
					M('RunningFile')->add(array(
						'running_id'=>$running_id,
						'school_id'=>$getallheaders['Sp-School-Id'],
						'file'=>$v2['file'],
						'origSize'=>$v2['origSize'],
						'types'=>$v2['types'],
						'name'=>$v2['name'],
						'k'=>$k2,
						'create_time' => NOW_TIME
					));
				}
			}
			
			
			//p($data);
			//p($data['MoneyPayment']);die;
			//插入支付表
			$logs = array(
				'user_id' => $user_id,
				'school_id' => $getallheaders['Sp-School-Id'],  
				'type' => 'running', 
				'types' => 1, 
				'code' => 'wxapp', 
				'order_id' => $running_id, 
				'need_pay' => $data['MoneyPayment'], 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip()
			);
			$logs['log_id'] = M('PaymentLogs')->add($logs);
			
			$Data['running_id'] = $running_id;
			$Data['log_id'] = $logs['log_id'];
			
			$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'下单成功','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}

		//写逻辑
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'下单失败','IsSuccess'=>false));
	}	
	
	
	//OrderOrderPayment支付
	public function OrderOrderPayment(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		$data['money'] = I('money','','trim,htmlspecialchars');
		$data['payment'] = I('payment','','trim,htmlspecialchars');
		
		
		
		
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		
		//如果有小费就加小费
		$money = $data['money'] ? $data['money']*100 : $detail['MoneyPayment'];
		
		
		if($data['money']){
			$types = 2;
		}else{
			$types = 1;
		}
		
		if($types == 2){
			$logs = D('Paymentlogs')->where(array('type'=>'running','order_id'=>$running_id,'types'=>$types))->find();
			if($logs){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前订单已经添加过小费了','IsSuccess'=>false));
			}
		}
		
		
		
		$logs = D('Paymentlogs')->where(array('type'=>'running','order_id'=>$running_id,'types'=>$types))->find();
        if(empty($logs)){
            $logs = array(
				'type' => 'running',
				'types' =>$types, 
				'info' => $data['money'] ? '加小费' : '跑腿支付',  
				'user_id' => $detail['user_id'], 
				'order_id' => $running_id,
				'school_id' => $school_id,  
				'code' => 'wxapp', 
				'need_pay' => $money, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(), 
				'is_paid' => 0
			);
			$logs['log_id'] = M('PaymentLogs')->add($logs);
        }else{
            $logs['need_pay'] = $money;
            D('Paymentlogs')->save($logs);
        }
		
		
		$Connect = M('connect')->where(array('type'=>'weixin','uid'=>$detail['user_id']))->find();
		
		
		
		include APP_PATH . 'Lib/Action/App/wxpay.php';
		
		
        $Payment= D('Payment')->getPayment('wxapp');
		if(!$Payment){
			$Payment= D('Payment')->getPayment('weixin');	
		}
		
		
        $out_trade_no = $logs['log_id'].'-'.time();

      
		
		$openid = D('Connect')->getWxappOpenid($user_id,$openid = '','running');
		
		
		//p($Connect);
		//p($this->config['wxapp']['appid'].'-1-'.$openid.'-2--'.$Payment['mchid'].'---'.$Payment['appkey'].'---'.$out_trade_no.'---'.$body.'---'.$money);die;
		
        $weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,'跑腿订单ID【'.$running_id.'】付款',$money);//支付接口
        $return = $weixinpay->pay();
		
		$return['TimeStamp'] = $return['timeStamp'];
        $return['NonceStr']= $return['nonceStr'];
        $return['Package']= $return['package'];
        $return['SignType']= $return['signType'];
        $return['PaySign']= $return['paySign'];
		$return['IsSuccess']= true;
		
		
		$return['order_id'] = $running_id;//支付ID
		$return['log_id'] = $logs['log_id'];//支付ID
		$return['money'] = $money;//支付金额
		
		if($return['package'] == 'prepay_id='){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'预支付失败--'.$return['rest']['return_msg'],'return'=>$return,'IsSuccess'=>false));
		}
		//p($return);die;
		if($return['rest']['return_code'] == 'SUCCESS' || $return['rest']['return_msg'] == 'OK'){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'请求成功请支付','IsSuccess'=>true,'Data'=>json_encode($return)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'请求支付失败请联系管理员','IsSuccess'=>false));
	}	
	
	
	//微信支付成功回调地址	 
    public function savepaylog(){
	
		$log_id = I('log_id','','trim,htmlspecialchars');
		
		$logsPaid = D('Payment')->logsPaid($log_id);//通过订单ID回调，其他错误后期在写
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'支付成功','IsSuccess'=>true,'Data'=>json_encode($Running)));
	}
	
	
	
	//微信支付成功回调地址2OrderPaymentCheck检测4月13日改版
    public function OrderPaymentCheck(){
	
		 $paymentCode = I('paymentCode','','trim,htmlspecialchars');//不知道做什么的
		 $id = I('id','','trim,htmlspecialchars');
		 $log_id = I('log_id','','trim,htmlspecialchars');
		
		 $logs = M('payment_logs')->where(array('type'=>'running','log_id'=>$log_id))->find();//支付日志记录
		 $order_id = $logs['order_id'];//订单ID
		 $id = $logs['order_id'];//订单ID
		 $log_id = $logs['log_id'];//支付ID
		 
		 D('Running')->updateOrder($logs['log_id'],$logs['order_id'],$logs['order_ids'],$logs['code'],$logs['need_pay']);//在线支付
		 
		 if($log_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'支付成功','IsSuccess'=>true,'Data'=>json_encode($Running)));
		 }else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'支付失败','IsSuccess'=>false));
		 }
	}
	
	
	
	
	//OrderOrderInfo订单详情
	public function OrderOrderInfo(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03030','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		$id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03030','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		$Users = M('users')->where(array('user_id'=>$detail['user_id']))->find();
		
		$detail['Id'] = $detail['running_id'];
		$detail['Code'] = $detail['running_id'];
		$detail['CreatedTime'] = $detail['CreatedTime'];
		$detail['Money'] = round($detail['Money']/100,2);
		$detail['MoneyFreight'] = round($detail['MoneyFreight']/100,2);
		$detail['MoneyTip'] = round($detail['MoneyTip']/100,2);//额外赏金
		$detail['MoneyPayment'] = round($detail['MoneyPayment']/100,2);
		
		$$detail['coupon_price'] = round($detail['coupon_price']/100,2);
		$detail['redpacket_money'] = round($detail['redpacket_money']/100,2);
		$detail['attr_money'] = round($detail['attr_money']/100,2);
		
		
		
		$detail['Role'] = $user_id == $detail['delivery_id'] ? '2' : 1;//如果当前会员ID是配送员则是配送员，否则就是发单人角色
	
		
		//订单已完成不受管辖
		//if($detail['OrderStatus'] < 128){
			
			
			if($detail['delivery_id'] > 0){
				if($detail['user_id'] != $user_id  && $detail['delivery_id'] != $user_id){
					$this->ajaxReturn(array('ErrorCode'=>'E03030','ErrorMessage'=>'当前订单已抢单可是配送员不是您','IsSuccess'=>false));
				}
			}else{
				if($detail['user_id'] != $user_id){
					$this->ajaxReturn(array('ErrorCode'=>'E03030','ErrorMessage'=>'当前订单已抢单，可是并不是您的订单','IsSuccess'=>false));
				}
			}
			
			
		//}
		
		
		
		
		$PaymentLimitSeconds = ($detail['create_time']+900)-time();//剩余时间
		if($detail['OrderStatus'] == 1){
			if($PaymentLimitSeconds <= 5){
				$res= M('Running')->where(array('running_id'=>$id))->save(array('OrderStatus'=>256));
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$id))->save(array('OrderStatus'=>2));
				$detail= M('Running')->find($id);
			}
		}
		
		
		//p($PaymentLimitSeconds);die;
		
		
		//地址判断两条件
		$Shop = M('shop')->where(array('shop_id'=>$detail['ShopId']))->find();
		$shopUser = M('users')->where(array('user_id'=>$Shop['user_id']))->find();
		
		if($detail['Type'] == 1){
			$Address[0]['Address'] = $Shop['shop_name'].'---'.$Shop['mobile'] ? $Shop['mobile'] : $shopUser['mobile'] ? $shopUser['mobile'] : $Shop['tel'];
			$Address[0]['Description'] = $Shop['addr'];
			$Address[1]['Address'] = $detail['name'].$detail['mobile'];
			$Address[1]['Description'] = $detail['addr'];
		}elseif($detail['Type'] == 2){
			
			$startAddress = unserialize($detail['startAddress']);
			$endAddress = unserialize($detail['endAddress']);
			
			$Address[0]['Address'] = $startAddress['Address'];
			$Address[0]['Description'] = $startAddress['Description'];
			
			$Address[1]['Address'] = $endAddress['Address'];
			$Address[1]['Description'] = $endAddress['Description'];
		}
		$detail['Addresses'] = $Address;
		
		
		
		//配送员手机
		$d= M('RunningDelivery')->find($detail['delivery_id']);
		$DUsers = M('users')->where(array('user_id'=>$d['user_id']))->find();
		
		
		$detail['Serial'] = $Shop['shop_name'] ? $Shop['shop_name'] : '未获取到商家名称';
		$detail['ExpectTimeString'] = $this->utcUpdateDate($detail['ExpectTime']);
		$detail['Status'] = $detail['OrderStatus'];
	
		
		$detail['PaymentLimitSeconds'] = ($detail['create_time']+900)-time();//剩余时间
		
		//获取订单配送模式
		if($detail['is_ele_pei'] == 0){
			$pei = 0;
		}else{
			$pei = 1;
		}
		
		
		
		if($pei == 0){
			//配送员信息，平台配送模式
			$detail['DelivererAvatarUrl'] = config_weixin_img($DUsers['face']);
			$detail['DelivererPicUrl1'] = config_weixin_img($d['PicUrl1']);//配送员收款二维码
			$RealName = $d['RealName'] ? $d['RealName'] : '配送员不存在';
			$detail['DelivererNickName'] = '【配送员】-'.$RealName;
			$detail['DelivererMobile'] = $d['phoneNumber'] ? $d['phoneNumber'] : $DUsers['mobile'];
		}else{
			//配送员信息商家自主配送模式
			$detail['DelivererAvatarUrl'] = config_weixin_img($Shop['photo']);
			$detail['DelivererPicUrl1'] = config_weixin_img($d['PicUrl1']);
			$detail['DelivererNickName'] = '商家【'.$Shop['shop_name'].'】自主配送';
			$detail['DelivererMobile'] = $Shop['mobile'] ? $Shop['mobile'] : $shopUser['mobile'] ? $shopUser['mobile'] : $Shop['tel'];
		}
		
		
		//会员信息
		$detail['uid'] = $detail['user_id'];
		$detail['DelivererUserId'] = $detail['delivery_id'];
		$detail['AvatarUrl'] = config_weixin_img($Users['face']);
		$detail['NickName'] = '【会员】-'.$Users['nickname'];
		$detail['Mobile'] = $detail['mobile'] ? $detail['mobile'] : $Users['mobile'];
		
		
		if($Shop){
			$detail['ShopLogoSrc'] = config_weixin_img($Shop['photo']);
			$detail['ShopName'] = $Shop['shop_name'] ? $Shop['shop_name'] : '未获取到商家名称';
		}
		
		
		$logs[0]['CreatedTime'] = date('Y-m-d H:i:s',$detail['create_time']);
		$logs[0]['Title'] = '用户下单';
		
		if($detail['pay_time']){
			$logs[1]['CreatedTime'] = date('Y-m-d H:i:s',$detail['pay_time']);
			$logs[1]['Title'] = '用户付款';
		}
		
		
		if($detail['update_time']){
			$logs[2]['CreatedTime'] = date('Y-m-d H:i:s',$detail['update_time']);
			$logs[2]['Title'] =  $pei == 0  ? '配送员接单' : '商家接单';
		}
		if($detail['delivery_end_time']){
			$logs[3]['CreatedTime'] = date('Y-m-d H:i:s',$detail['delivery_end_time']);
			$logs[3]['Title'] = $pei == 0  ? '配送员完成订单' : '商家完成订单';
		}
		if($detail['end_time']){
			$logs[4]['CreatedTime'] = date('Y-m-d H:i:s',$detail['end_time']);
			$logs[4]['Title'] = '用户订单完成';
		}
		$detail['Logs'] = $logs;
		
		
		
		//p($detail['Logs']);die;
		
		$Items = M('RunningProduct')->where(array('running_id'=>$id))->order('id desc')->select();
		foreach($Items as $k => $val){
			$EleProduct = M('EleProduct')->where(array('product_id'=>$val['product_id']))->find();
			$Items[$k]['PictureUrl'] = config_weixin_img($EleProduct['photo']);
			$Items[$k]['CommodityName'] = $val['product_name'];
			$Items[$k]['Quantity'] = $val['Quantity'];
			$Items[$k]['Money'] = round($val['Price']/100,2);
		}
		
		
		
		
		$detail['Items'] = $Items;
		
		//附件数量
		$detail['fileNum'] = (int)M('running_file')->where(array('running_id'=>$id))->count();
		//附件
		$file = M('running_file')->where(array('running_id'=>$id))->select();
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
		$detail['files'] = $file;
		
		//p($Items);die;
		
		//p($detail);die;
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($detail)));
		
		
	}	
	

	
	//OrderOrderInfoForAccept首页订单详情2
	public function OrderOrderInfoForAccept(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前登录状态失效暂时无法操作','IsSuccess'=>false));
		}
		
		
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		$detail['uid'] = $detail['user_id'];
		$detail['Id'] = $detail['running_id'];
		$detail['UserId'] = $detail['delivery_id'];
		$detail['Code'] = $detail['running_id'];
		$detail['CreatedTime'] = $detail['CreatedTime'];
		$detail['Money'] = round($detail['Money']/100,2);
		$detail['MoneyFreight'] = round($detail['MoneyFreight']/100,2);
		$detail['MoneyTip'] = round($detail['MoneyTip']/100,2);//额外赏金
		$detail['MoneyPayment'] = round($detail['MoneyPayment']/100,2);
		
		
		//地址
		$Shop= M('shop')->where(array('shop_id'=>$detail['ShopId']))->find();
		//地址判断两条件
		$Shop = M('shop')->where(array('shop_id'=>$detail['ShopId']))->find();
		if($detail['Type'] == 1){
			$Address[0]['Address'] = $Shop['shop_name'].'---'.$Shop['mobile'] ? $Shop['mobile'] : $Shop['tel'];
			$Address[0]['Description'] = $Shop['addr'];
			$Address[1]['Address'] = $this->findNumTHuan($detail['name'].$detail['mobile'],$id,$user_id,$type=2);;
			$Address[1]['Description'] = $detail['addr'];
		}elseif($detail['Type'] == 2){
			
			$startAddress = unserialize($detail['startAddress']);
			$endAddress = unserialize($detail['endAddress']);
			
			$Address[0]['Address'] = $this->findNumTHuan($startAddress['Address'],$id,$user_id,$type=2);
			$Address[0]['Description'] = $startAddress['Description'];
			
			$Address[1]['Address'] = $this->findNumTHuan( $endAddress['Address'],$id,$user_id,$type=2);
			$Address[1]['Description'] = $endAddress['Description'];
		}
		
		//p($Address);die;
		
		
		$detail['Addresses'] = $Address;
		
		$detail['Serial'] = $Shop['shop_name'] ? $Shop['shop_name'] : '未获取到商家名称';
		$detail['ExpectTimeString'] = $this->utcUpdateDate($detail['ExpectTime']);
		$detail['Status'] = $detail['OrderStatus'];
		$detail['PaymentLimitSeconds'] = ($detail['create_time']+900)-time();//剩余时间
		
		
		$Users= M('users')->where(array('user_id'=>$detail['user_id']))->find();
		$detail['AvatarUrl'] = config_weixin_img($Users['face']);
	
		
		
		if($detail['Type'] ==  1){
			$detail['ShopLogoSrc'] = config_weixin_img($Shop['photo']);
			$detail['ShopName'] = $Shop['shop_name'];
			$detail['CommodityNames'] = $Shop['addr'];
		}
		
		
		//菜品详情
		$Items = M('RunningProduct')->where(array('running_id'=>$id))->order('id desc')->select();
		foreach($Items as $k => $val){
			$EleProduct = M('EleProduct')->where(array('product_id'=>$val['product_id']))->find();
			$Items[$k]['PictureUrl'] = config_weixin_img($EleProduct['photo']);
			$Items[$k]['CommodityName'] = $val['product_name'];
			$Items[$k]['Quantity'] = $val['Quantity'];
			$Items[$k]['Money'] = round($val['Price']/100,2);
		}
		$detail['Items'] = $Items;
		
		
		
		$detail['IsSecret'] =  $this->getORderIsSecret($detail['title']);
		$detail['Remark'] = $this->findNumTHuan($detail['Remark'],$detail['running_id'],$user_id,$type=1);//不知道
		
		$RunningDelivery= M('RunningDelivery')->where(array('user_id'=>$user_id))->find();
		
		
		if(!empty($RunningDelivery) && $RunningDelivery['audit'] == 0){
			$detail['ForbiddenAcceptCause'] = '您已提交认证';
			$IsForbiddenAccept  = 0;
		}elseif($RunningDelivery['audit'] == 1){
			$detail['ForbiddenAcceptCause'] = '认证审核中';
			$IsForbiddenAccept  = 1;
		}elseif($RunningDelivery['audit'] == 2){
			$detail['ForbiddenAcceptCause'] = '认证已审核';
			$IsForbiddenAccept  = 0;
		}elseif($RunningDelivery['audit'] == 3){
			$detail['ForbiddenAcceptCause'] = '认证失败';
			$IsForbiddenAccept  = 1;
		}elseif(empty($RunningDelivery)){
			$detail['ForbiddenAcceptCause'] ='立即认证参与抢单';
			$IsForbiddenAccept  = 1;
		}
		
		//p($RunningDelivery);die;
		
		$detail['VerifiedStatus'] = $RunningDelivery['audit']  ? $RunningDelivery['audit'] : 0;//配送员验证状态
		
		if($detail['LimitDelivererGender'] == 1 && !empty($RunningDelivery)){
			if($RunningDelivery['Gender'] != $detail['LimitDelivererGender']){
				$IsForbiddenAccept  = 1;
			}
		}
		
		$detail['IsForbiddenAccept'] = $IsForbiddenAccept;//永许抢单
		
		
		
		//p($detail);die;
		
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($detail)));
		
		
	}	
	
	
	
	//OrderOrderCancel用户取消订单
	public function OrderOrderCancel(){
		
		$cancel = I('cancel','','trim,htmlspecialchars');//订单ID
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		if($detail['OrderStatus'] != 1){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前状态不能取消','IsSuccess'=>false));
		}
		if(!$res= M('Running')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>'512'))){
			
			D('Weixintmpl')->runningWxappNotice($running_id,$OrderStatus = 512,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
			
			
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'取消失败','IsSuccess'=>false));
		}
		$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'取消成功','IsSuccess'=>true,'Data'=>json_encode($res)));
	}	
	
	
	
	
	
	
	//orderFinishQuxiao用户取消配送员的订单
	public function orderFinishQuxiao(){
		
		
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		if(!$detail = M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		$res = D('Running')->runingSettlement($running_id,$detail['delivery_id'],$labels ='',$content='',$score=5);//结算封装函数
		//跑腿完成带评价
		if($res == true){
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>D('Running')->getError(),'IsSuccess'=>false));
		}
		
		
		//多余的逻辑
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		
		
		$giveup = I('giveup','','trim,htmlspecialchars');//取消原因ID
		$cause = I('cause','','trim,htmlspecialchars');//取消原因
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		$RunningDelivery= M('RunningDelivery')->where(array('user_id'=>$detail['delivery_id']))->find();
		
		
		$data['running_id'] = $running_id;
		$data['OrderStatus'] = 2;
		$data['LastGiveupId'] = $RunningDelivery['delivery_id'];
		$data['LastGiveupCause'] = '配送员：'.$RunningDelivery['RealName'].'抢单后被发单人取消';
		$data['LastGiveupTime'] = time();
	
		if($res= M('Running')->save($data)){
			if($detail['Type'] == 1){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>2));
			}
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'用户取消订单成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	
	
	//订单状态修改为64订单已完成解算【结算订单1】
	public function orderConfirmFinish(){
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		if(!$detail = M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		$res = D('Running')->runingSettlement($running_id,$detail['delivery_id'],$labels ='',$content='',$score=5);//结算封装函数
		//跑腿完成带评价
		if($res == true){
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>D('Running')->getError(),'IsSuccess'=>false));
		}
	}
	
	
	
	
	//订单状态修改为128买家发单人订单评论【结算订单2】
	public function OrderOrderComment(){
		
		$content = I('content','','trim,htmlspecialchars');//评价
		$labels = I('labels','','trim,htmlspecialchars');//标签
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		$score = I('score','','trim,htmlspecialchars');//评分
		
		if(!$detail = M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		$data['OrderStatus']= '128';
		$data['labels']= $labels ? $labels : '自动完成订单';
		$data['content']= $content ? $content : '自动完成订单';
		$data['score']= $score ? $score : '5';
		$data['end_time']= time();
		
		
		$res = D('Running')->where(array('running_id'=>$running_id))->save($data);//状态修改为已完成
		
		//跑腿完成带评价
		if($res == true){
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'评价完成成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
		}
	}
	
	
		
		
		
	
	//OrderOrderGiveup配送员放弃订单
	public function OrderOrderGiveup(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		if(!$RunningDelivery= M('RunningDelivery')->where(array('user_id'=>$user_id))->find()){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您不是配送员无法放弃订单','IsSuccess'=>false));
		}
		
		
		$giveup = I('giveup','','trim,htmlspecialchars');//取消原因ID
		$cause = I('cause','','trim,htmlspecialchars');//取消原因
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		$data['running_id'] = $running_id;
		$data['OrderStatus'] = 2;
		$data['LastGiveupId'] = $RunningDelivery['delivery_id'];
		$data['LastGiveupCause'] = '配送员：'.$RunningDelivery['RealName'].''.$cause;
		$data['LastGiveupTime'] = time();
	
		if($res= M('Running')->save($data)){
			if($detail['Type'] == 1){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>2));
			}
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'配送员放弃订单成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	//OrderOrderRefund用户订单退款用户订单取消，原路退款
	public function OrderOrderRefund(){
		
		$cancel = I('cancel','','trim,htmlspecialchars');//订单ID
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		if($detail['OrderStatus'] == '512'){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前状态不能申请取消','IsSuccess'=>false));
		}
		
		
		$commonRefundUser = D('Running')->commonRefundUser($running_id,$saveOrderStatus = '512',$refundInfo = '用户取消退款退款',2,$type = 1);//退款功能封装
		
		if($commonRefundUser){
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败.【'.D('Running')->getError().'】','IsSuccess'=>false));
	}
		
	
	
		
	//OrderOrderFinish配送员订单完成
	public function OrderOrderFinish(){
		
		$cancel = I('cancel','','trim,htmlspecialchars');//订单ID
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		if($res= M('Running')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>'32','delivery_end_time'=>time()))){
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>32));
			D('Weixintmpl')->runningWxappNotice($running_id,$OrderStatus = 32,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	
	//OrderOrderAccept配送员抢单接单
	public function OrderOrderAccept(){
		
		$latitude = I('latitude','','trim,htmlspecialchars');//经度
		$longitude = I('longitude','','trim,htmlspecialchars');//纬度
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'订单不存在','IsSuccess'=>false));
		}
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'会员不存在','IsSuccess'=>false));
		}
		$d = M('RunningDelivery')->where(array('user_id'=>$user_id))->find();
		if(!$d){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'找不到配送员数据','IsSuccess'=>false));
		}
		
		
		
		$school_id = $getallheaders['Sp-School-Id'];
		$school = M('RunningSchool')->where(array('school_id'=>$school_id))->find();
		if(!$school){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'抢单的学校不存在','IsSuccess'=>false));
		}
		if($school){
			if($school['is_deposit_qiang'] == 1){
				if($d['is_deposit'] != 1){
					$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您需要缴纳保证金后才能抢单','IsSuccess'=>false));
				}
			}
		}
		
		
		if($school_id != $d['school_id']){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您不能抢其他学校的订单','IsSuccess'=>false));
		}
		
		
		if($detail['LastGiveupId'] == $d['delivery_id']){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您不能重复抢单','IsSuccess'=>false));
		}
		
		
		if($d['audit'] !=2){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'配送员状态不正确','IsSuccess'=>false));
		}
		
		if($detail['user_id'] ==$d['user_id']){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'自己发的订单不能自己抢','IsSuccess'=>false));
		}
		
		
		if($detail['orderType'] == 2){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'到店自提订单类型不支持抢单','IsSuccess'=>false));
		}
		
		
		
		
		//外卖订单
		if($detail['Type'] == 1){
			if($detail['OrderStatus'] != 8){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前外卖订单类型商家还未确认接单，请等待商户接单后再点击配送【'.$detail['OrderStatus'].'】','IsSuccess'=>false));
			}
		}else{
			//配送基本订单
			if($detail['OrderStatus'] != 2){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前订单状态不支持抢单【'.$detail['OrderStatus'].'】','IsSuccess'=>false));
			}
		}
		
		//判断当前订单是否结算过
		$money = M('running_money')->where(array('running_id'=>$running_id,'order_id'=>$running_id))->find();
		if($money){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前订单重复操作，操作日志ID【'.$money['money_id'].'】','IsSuccess'=>false));
		}
		
		
		
		
		//限制性别抢单
		if($detail['LimitDelivererGender'] > 0){
			if($detail['LimitDelivererGender'] != $d['Gender']){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'不好意思当前订单限制性别抢单哦','IsSuccess'=>false));
			}
		}
		
		
		
		$interval_time = (int)$this->config['running']['tongshiTime'] ? (int)$this->config['running']['tongshiTime'] :'60';
		$num = (int)$this->config['running']['tongshiNum'] ? (int)$this->config['running']['tongshiNum'] :'10';
		
		//抢单时间限制
		$res = M('Running')->where(array('delivery_id' =>$d['delivery_id'],'OrderStatus'=>'16','closed'=>'0'))->order('update_time desc')->find();
		$cha = time() - $res['update_time'];
		if($cha < $interval_time){
			$second = $interval_time  -	$cha;
		}
		if($res && $cha < $interval_time){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作频繁请【'.$second .'】秒后再试','IsSuccess'=>false));
		}
		//抢单数量限制
		$count = M('Running')->where(array('delivery_id' =>$d['delivery_id'],'OrderStatus'=>'16','closed'=>'0'))->count();
		if($count && $count >= $num){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'已配置中订单的数量已经超过限制请先完成配送后再抢单','IsSuccess'=>false));
		}
		
		
		
		$cha = time() - $detail['pay_time'];
		
		
		
		//跑腿订单
		if($detail['Type'] == 2){
			//订单是已付款状态并超时
			if($detail['OrderStatus'] == 2 && $cha > ($detail['ExpiredMinutes']*60)){
				
				if($detail['OrderStatus'] == '512' || $detail['OrderStatus'] == '2048'){
					$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前订单【'.$running_id.'】状态【'.$detail['OrderStatus'].'】无法抢单','IsSuccess'=>false));
				}
				
				$commonRefundUser = D('Running')->commonRefundUser($running_id,$saveOrderStatus = '2048',$refundInfo = '超时无人接单退款',2,$type = 1);//超时无人接单退款功能封装
				
				if($commonRefundUser){
					$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'该订单已经超时无法抢单','IsSuccess'=>false));
				}
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败订单已执行退款请查看其它订单','IsSuccess'=>false));
				
			}
		}
			
			
		
		
		
		
		$data['delivery_id'] = $d['user_id'];
		$data['OrderStatus'] = 16;
		$data['update_time'] = time();
		$data['delivery_id'] = $d['user_id'];
		
		$getMapChangeBaidu2 = getMapChangeBaidu2($latitude,$longitude);
		$data['dlatitude'] = $getMapChangeBaidu2['lat'];
		$data['dlongitude'] = $getMapChangeBaidu2['lng'];
		
		//p($data);die;
		
		//接单
		if($res= M('Running')->where(array('running_id'=>$running_id))->save($data)){
			if($detail['Type'] == 1){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>16,'update_time'=>time()));
			}
			//新增接单数量
			M('RunningDelivery')->where(array('user_id'=>$user_id))->save(array('num'=>$d['num']+1));
			D('Sms')->runningAcceptUser($running_id);//配送员抢单短信通知买家
			
			
			D('Weixintmpl')->runningWxappNotice($running_id,$OrderStatus = 16,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($res)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}
	
	
	
		
	//SaveWechatFormId保存微信表单ID
	public function SaveWechatFormId(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$formId = I('formId','','htmlspecialchars');



		$data['formId'] =$formId;
		$data['form_id'] = $formId;
		$data['user_id'] = $user_id;
		$data['openid'] = '';
		$data['time'] = time();
		
		if($form_id= M('UserFormid')->add($data)){
			
			//大于等于7天的都删除
			$time=time()-60*60*24*7;
			$user_id = $data['user_id'];
			$formids = M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('ELT',$time)))->select();
			foreach($formids as $k=>$v){
				$res = M('user_formid')->where(array('id'=>$v['id']))->delete();
			}
			//查询大于等于7天的第一条
			$count=M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('EGT',$time)))->count();
			
			
			$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'count'=>$count,'Data'=>json_encode($data)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	
	//UploadFile上传保存
	public function UploadFile(){
		
		$model = I('model','','htmlspecialchars') !='' ? I('model','','htmlspecialchars') : $_GET['model'] ;
		import('ORG.Net.UploadFile');
		$upload = new UploadFile(); 
		$upload->maxSize = 3145728; 
		$upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); 
		$name = date('Y/m/d', NOW_TIME);
		$dir = BASE_PATH . '/attachs/' . $name . '/';
		if(!is_dir($dir)){
			mkdir($dir, 0755, true);
		}
		$upload->savePath = $dir;
		if(isset($this->config['attachs'][$model]['thumb'])) {
			$upload->thumb = true;
			if(is_array($this->config['attachs'][$model]['thumb'])) {
				$prefix = $w = $h = array();
				foreach($this->config['attachs'][$model]['thumb'] as $k=>$v){
					$prefix[] = $k.'_';
					list($w1,$h1) = explode('X', $v);
					$w[]=$w1;
					$h[]=$h1;
				}
				$upload->thumbPrefix = join(',',$prefix);
				$upload->thumbMaxWidth =join(',',$w);
				$upload->thumbMaxHeight =join(',',$h);
			}else{
				$upload->thumbPrefix = 'thumb_';
				list($w, $h) = explode('X', $this->config['attachs'][$model]['thumb']);
				$upload->thumbMaxWidth = $w;
				$upload->thumbMaxHeight = $h;
			}
		}
	
		if(!$upload->upload()){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>$upload->getErrorMsg(),'IsSuccess'=>false));
		}else{
			$info = $upload->getUploadFileInfo();
			if(!empty($this->config['attachs']['water'])){
				import('ORG.Util.Image');
				$Image = new Image();
				$Image->water(BASE_PATH . '/attachs/'. $name . '/thumb_' . $info[0]['savename'],BASE_PATH . '/attachs/'.$this->config['attachs']['water']);
			}
			if($upload->thumb) {
                $picurl =  $this->config['site']['host'].'/attachs/'.$name . '/thumb_' . $info[0]['savename'];
				
				$Data['picurl'] = $picurl;
				$Data['type'] = 1;
                $this->ajaxReturn(array('statusCode'=>'200','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($Data)));
           }else{
                $picurl = $this->config['site']['host'].'/attachs/'.$name . '/' . $info[0]['savename'];
				$Data['picurl'] = $picurl;
				$Data['type'] = 1;
				$this->ajaxReturn(array('statusCode'=>'200','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($Data)));
           }
		 }
	}
	
	
	
		
	//SendVerifySms验证短信
	public function SendVerifySms(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$this->ajaxReturn(array('ErrorCode'=>'1','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($user_id)));
	}	
	
		
	//StudentSaveStudentInfo入驻认证申请
	public function StudentSaveStudentInfo(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$session_key = I('session_key','','trim,htmlspecialchars');
		
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		$data['delivery_id'] = (int) $user_id;
		$data['user_id'] = $user_id;
		
		$data['SchoolId'] = $data['SchoolId'];
		$data['school_id'] = $data['SchoolId'];
		
		$data['StudentCode'] = $data['StudentCode'];
		$data['RealName'] = $data['RealName'];//名称
		$data['Major'] = $data['Major'];//学校
		$data['IdCode'] = $data['IdCode'];//身份证好
		$data['Gender'] = $data['Gender'];//性别
		
		$FileList = $data['FileList'];
		
		
		$data['FileList'] = serialize($FileList);
		
		
		$data['EnrollmentDate'] = $data['EnrollmentDate'];//入驻时间
		$data['Department'] = $data['Department'];//学校系
		$data['EncryptedData'] = $data['EncryptedData'];//加密东西
		$data['EncryptedIv'] = $data['EncryptedIv'];//解密IV
		
		$data['PicUrl0'] = $FileList ? $FileList : $FileList[0]['picurl'];
		$data['PicUrl1'] = $data['payCode'];
		
		$data['audit'] = 1;
		//解密手机开始
		$Users = M('users')->find($user_id);
		$Connect = M('connect')->where(array('type'=>'weixin','uid'=>$user_id))->find();
		
		$session_key = $session_key ? $session_key : $Connect['rd_session'];
		if(!$session_key ){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'session_key不存在','IsSuccess'=>false));
		}
		
		
		include APP_PATH .'/Lib/Action/App/jiemi/wxBizDataCrypt.php';
		$pc = new WXBizDataCrypt($this->config['wxapp']['appid'],$session_key);
		$errCode = $pc->decryptData($data['EncryptedData'],$data['EncryptedIv'],$datas);
		$res = json_decode($datas,true);  
		
		if(!$res['phoneNumber']){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'获取用户手机号失败请稍后再试','IsSuccess'=>false));
		}
		
		$data['phoneNumber'] = $res['phoneNumber'] ? $res['phoneNumber'] : $Users['mobile'];
		
		
		
		
		//解密手机结束	
		$data['create_time'] = NOW_TIME;
		$data['create_ip'] = get_client_ip();	
		
		$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$data['user_id']))->find();
		
		if($RunningDelivery){
			$res= M('RunningDelivery')->save($data);
			$SuccessMessage = '修改认证信息成功';
		}else{
			$res= M('RunningDelivery')->add($data);
			$SuccessMessage = '提交认证信息成功';
		}
		
		if($res){
			$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>$SuccessMessage,'IsSuccess'=>true,'Data'=>json_encode($res)));
		}else{
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'操作失败或者你已经提交过','IsSuccess'=>false));
		}
		
	}	
	
	
	
	public function notifyFlag2(){
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$res = M('users')->where(array('user_id'=>$user_id))->save(array('notifyFlag2'=>1));
		
		$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>'操作成功','count'=>$count,'IsSuccess'=>true,'Data'=>json_encode($res)));
	}
	
	
	//HomeMine个人中心
	public function HomeMine(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		//获取会员的数据
		$Users = $this->getUserData($user_id,$school_id);
		
		if($Users){
			$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>'操作成功','count'=>$count,'IsSuccess'=>true,'Data'=>json_encode($Users)));
		}
		
		$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	//个人中心保证金首页
	public function userDeposit(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'请求的学校不存在','IsSuccess'=>false));
		}
		
		//获取会员的数据
		$Users = $this->getUserData($user_id,$school_id);
		
		if($Users){
			$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($Users)));
		}
		
		$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	//个人中心红包列表
	public function userRedPacket(){
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'请求的学校不存在','IsSuccess'=>false));
		}
		
		//获取会员的数据
		$Users = $this->getUserData($user_id,$school_id);
		
		if($Users){
			$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($Users)));
		}
		
		$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	//个人中心保证金解冻
	public function userDepositThaw(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'请求的学校不存在','IsSuccess'=>false));
		}
		
		//获取会员的数据
		$Users = $this->getUserData($user_id,$school_id);
		if($Users['deposit'] == 0){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您的保证金为空','IsSuccess'=>false));
		}
		
		$Running = M('Running')->where(array('delivery_id'=>$user_id))->find();
		if($Running && $Running['OrderStatus'] <= 32){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您的跑腿订单号【'.$Running['running_id'].'】还未完成暂时无法解冻','IsSuccess'=>false));
		}
		
		$rest = M('RunningDelivery')->where(array('user_id'=>$user_id))->save(array('is_deposit'=>2));
		if($rest){
			$Data = $this->getUserData($user_id,$school_id);
			$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>'申请成功，等待管理员审核','IsSuccess'=>true,'Data'=>json_encode($Data)));
		}
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	
	//个人中心保证金缴纳
	public function userDepositPay(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'请求的学校不存在','IsSuccess'=>false));
		}
		
		$rd = M('RunningDelivery')->where(array('user_id'=>$user_id))->find();
		if(!$rd){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'您还不是配送员暂时无法缴纳保证金','IsSuccess'=>false));
		}
		if($rd){
			if($rd['is_deposit'] != 0){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'缴纳保证金状态不正确','IsSuccess'=>false));
			}
			$school = M('running_school')->where(array('school_id'=>$rd['school_id']))->find();
			if(!$school){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'学校不存在暂时无法缴纳','IsSuccess'=>false));
			}
			if($school && $school['deposit'] <= 0){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'当前学校暂时未设置冻结金','IsSuccess'=>false));
			}
			
			
			include APP_PATH . 'Lib/Action/App/wxpay.php';
			$Payment= D('Payment')->getPayment('wxapp');
			if(!$Payment){
				$Payment= D('Payment')->getPayment('weixin');	
			}
		
			$openid = D('Connect')->getWxappOpenid($user_id,$openid = '','deposit');
			$logs = array(
				'type' => 'deposit',
				'school_id'=>$school_id,
				'user_id' => $user_id, 
				'order_id' => 0, 
				'code' => 'wxapp', 
				'need_pay' => $school['deposit'], 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(), 
				'is_paid' => 0
			);
			
			$log_id = D('Paymentlogs')->add($logs);
			$Payment = D('Payment')->getPayment('wxapp');
			
			$money = $school['deposit'];
			$body = '配送员保证金付款';
			
			
			$out_trade_no = $log_id.'-'.time();
			
			//应该有问题
			$weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,$body,$money);//支付接口
			
			$return = $weixinpay->pay();
			$return['weixin_param']['timeStamp'] = $return['timeStamp'];
			$return['weixin_param']['nonceStr'] =$return['nonceStr'];
			$return['weixin_param']['paySign'] = $return['paySign'];
			
			
			$return['TimeStamp'] = $return['timeStamp'];
			$return['NonceStr']= $return['nonceStr'];
			$return['Package']= $return['package'];
			$return['SignType']= $return['signType'];
			$return['PaySign']= $return['paySign'];
			$return['IsSuccess']= true;
		
			$return['log_id'] =$log_id;
			
			if($return['package'] == 'prepay_id='){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'预支付失败--'.$return['rest']['return_msg'],'return'=>$return,'IsSuccess'=>false));
			}
			if($return['rest']['return_code'] == 'SUCCESS' || $return['rest']['return_msg'] == 'OK'){
				$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'请求成功请支付','IsSuccess'=>true,'Data'=>json_encode($return)));
			}
			
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'支付请求操作失败','IsSuccess'=>false));
		}
		
		$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'配送信息不存在','IsSuccess'=>false));
	}	
	
	
	
	//保证金支付后回调【后期开发这里不是很稳定】
    public function depositPayPaymentCheck(){
	
		 $log_id = I('log_id','','trim,htmlspecialchars');
		
		 if($log_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','SuccessMessage'=>'支付成功','IsSuccess'=>true,'Data'=>json_encode($log_id)));
		 }else{
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'支付失败','IsSuccess'=>false));
		 }
	}
	
	
	
	//用户信息
	public function getUserData($user_id = 0,$school_id = 0){
		
		$Users = M('users')->where(array('user_id'=>$user_id))->find();
		//用户跑腿信息
		$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$user_id))->find();
		
		
		
		$Users['delivery'] = $RunningDelivery;//配送员详情
		$Users['delivery_id'] = $RunningDelivery['delivery_id'];//查询是不是配送员
		$Users['VerifyStatus'] = $RunningDelivery['audit'];//认证状态
		$getDeliveryAudits = D('Running')->getDeliveryAudits();
		
		$Users['VerifyStatusName'] = $getDeliveryAudits[$RunningDelivery['audit']] ? $getDeliveryAudits[$RunningDelivery['audit']] : '未认证';//认证名字
		
		//配送员绑定的学校
		$school = M('running_school')->where(array('school_id'=>$school_id['school_id']))->find();
			
		
		$getDeliveryDeposit = D('Running')->getDeliveryDeposit();
		$Users['getDeliveryDepositName'] = $getDeliveryDeposit[$RunningDelivery['is_deposit']];//保证金状态
		$Users['deposit'] = round($RunningDelivery['deposit']/100,2);//保证金金额 
		$Users['deposit2'] = round($school['deposit']/100,2);//应该缴纳的保证金
		$Users['is_deposit'] = $RunningDelivery['is_deposit'];//保证金状态码
		
		
		
		//红包开始
		$packets = M('users_redpacket')->where(array('user_id'=>$user_id,'is_used'=>0))->order('redpacket_id desc')->select();
		foreach($packets as $k=>$v){
			$packets[$k]['money'] =round($v['money']/100,2);//红包金额替换
			$packets[$k]['CreatedTime'] = date('Y-m-d H:i:s ',$v['create_time']);
			$packets[$k]['Value'] =round($v['money']/100,2);//红包金额替换
		}
		
		$Users['packets'] = $packets;//红包列表
		$redPacketCount = (int)M('users_redpacket')->where(array('user_id'=>$user_id,'is_used'=>0))->sum('money');
		$Users['redPacketCount'] = round($redPacketCount/100,2);//红包总计多少金额
		//调用最远的一个红包
		$packet = M('users_redpacket')->where(array('user_id'=>$user_id,'is_used'=>0))->order('redpacket_id asc')->find();
		$Users['redPacket'] = round($packet['money']/100,2);//红包没实用的金额
		//红包结束
		
		$Users['Mobile'] = $Users['mobile'];//手机
		$Users['Balance'] = round($Users['money']/100,2);//余额
		$Users['CouponCount']  = (int)M('coupon_download')->where(array('user_id'=>$user_id,'is_used'=>0,'closed'=>0))->count();//优惠券重做
		$Users['CoinCount'] = (int)$Users['integral'];//金币
		$Users['ServiceDesc'] = '客服电话：'.$this->config['site']['tel'];
		
		
		$notifyFlag2 = $Users['notifyFlag2'];
		$Users['notifyFlag2'] = $notifyFlag2;//订阅消息开关
		$Users['tmplIds'] = $this->config['wxapp']['tid1'];//订阅消息ID
		
		//大于等于7天的都删除
		$time=time()-60*60*24*7;
		$formids = M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('ELT',$time)))->select();
		foreach($formids as $k=>$v){
			$res = M('user_formid')->where(array('id'=>$v['id']))->delete();
		}
		//查询大于等于7天的第一条
		$count=M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('EGT',$time)))->count();
		$Users['count'] = $count;
		return $Users;
	}
	




	
	//StudentGetStudentInfo认证信息
	public function StudentGetStudentInfo(){
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$RunningDelivery = M('RunningDelivery')->where(array('user_id'=>$user_id,'school_id'=>$school_id))->find();
		
		if($RunningDelivery){
			$this->ajaxReturn(array('SuccessCode'=>'200','SuccessMessage'=>'操作成功','IsSuccess'=>true,'Data'=>json_encode($RunningDelivery)));
		}
		
		$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'操作失败','IsSuccess'=>false));
	}	
	
	
	//UserAccountAccountInfo我的余额列表
	public function UserAccountAccountInfo(){
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		$Users = M('users')->where(array('user_id'=>$user_id))->find();
		
		import('ORG.Util.Page2');
		
		$map['user_id'] = $user_id;
		
		$count = M('UserMoneyLogs')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();
        $p = $pageIndex;
		if($Page->totalPages < $p){
            $list = array();
        }
		
		
		$list = M('UserMoneyLogs')->where($map)->limit($Page->firstRow .','.$Page->listRows)->select();
		foreach($list as $k => $v){
			$list[$k]['TradeName'] = tu_msubstr($v['intro'],0,62,false);//简短介绍;
			$list[$k]['MoneyOperate'] = round($v['money']/100,2);
			$list[$k]['CreatedTime'] = date('Y-m-d H:i:s ',$v['create_time']);
			$list[$k]['ImageSrc'] = config_weixin_img($Users['face']);
			$list[$k]['Way'] = $v['money'] > 0 ? 1 : 0;
		}
	
		
		$Data['MoneyUsable'] = round($Users['money']/100,2);
		
		$MonthOutlay = M('UserMoneyLogs')->where(array('user_id'=>$user_id,'money'=>array('lt',0)))->sum('money');
		$Data['MonthOutlay'] = round($MonthOutlay/100,2);
		$MonthIncome = M('UserMoneyLogs')->where(array('user_id'=>$user_id,'money'=>array('gt',0)))->sum('money');
		$Data['MonthIncome'] = round($MonthIncome/100,2);
		
		$Data['Logs'] = $list ? $list : array();
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	//UserAccountWithdrawPrepare我的提现日志,提现配置
	public function UserAccountWithdrawPrepare(){
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		
		$Shop = D('Shop')->where(array('user_id'=>$user_id))->find();
		$RunningSchool = M('RunningSchool')->where(array('school_id'=>$school_id))->find();//学校设置
		if($RunningSchool){
			//单学校配置
			if($Shop == ''){
				//会员提现设置
				$cash_money = $RunningSchool['user'] ? $RunningSchool['user'] : $this->config['cash']['user'] ? $this->config['cash']['user'] : '100';
				$cash_money_big = $RunningSchool['user_big'] ? $this->config['cash']['user_big'] : $this->config['cash']['user_big'] ? $this->config['cash']['user_big'] : '100000';
				$cash_commission = $RunningSchool['user_cash_commission'] ? $RunningSchool['user_cash_commission'] : $this->config['cash']['user_cash_commission'] ? $this->config['cash']['user_cash_commission'] : '10';
			}else{
				//商家提现设置
				$cash_money = $RunningSchool['shop'] ? $RunningSchool['shop'] : $this->config['cash']['shop'] ? $this->config['cash']['shop'] : '100';
				$cash_money_big = $RunningSchool['shop_big'] ? $RunningSchool['shop_big'] : $this->config['cash']['shop_big'] ? $this->config['cash']['shop_big'] : '10000';
				$cash_commission = $RunningSchool['shop_cash_commission'] ? $RunningSchool['shop_cash_commission'] : $this->config['cash']['shop_cash_commission'] ? $this->config['cash']['shop_cash_commission'] : '10';
			}
			
		}else{
			//全局配置
			if($Shop == ''){
				//会员提现设置
				$cash_money = $this->config['cash']['user'] ? $this->config['cash']['user'] : '100';
				$cash_money_big = $this->config['cash']['user_big'] ? $this->config['cash']['user_big'] : '100000';
				$cash_commission = $this->config['cash']['user_cash_commission'] ? $this->config['cash']['user_cash_commission'] : '10';
			}else{
				//商家提现设置
				$cash_money = $this->config['cash']['shop'] ? $this->config['cash']['shop'] : '100';
				$cash_money_big = $this->config['cash']['shop_big'] ? $this->config['cash']['shop_big'] : '10000';
				$cash_commission = $this->config['cash']['shop_cash_commission'] ? $this->config['cash']['shop_cash_commission'] : '10';
			}
		}

		
		$Users = M('users')->where(array('user_id'=>$user_id))->find();
		
		import('ORG.Util.Page2');
		
		$map['user_id'] = $user_id;
		
		$count = M('UsersCash')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();
        $p = $pageIndex;
		if($Page->totalPages < $p){
            $list = array();
        }
		$list = M('UsersCash')->where($map)->limit($Page->firstRow .','.$Page->listRows)->order('cash_id desc')->select();
		foreach($list as $k => $v){
			$list[$k]['Remark'] = tu_msubstr('用户提现',0,16,false);//简短介绍;
			$list[$k]['Money'] = round($v['money']/100,2);
			$list[$k]['CreatedTime'] = date('Y-m-d H:i:s ',$v['addtime']);
		}
		
		$Data['cashMoney'] = $cash_money;//单笔最低
		$Data['cashMoneyBig'] = $cash_money_big;//单笔最高
		$Data['cashCommission'] = $cash_commission;//单笔手续费
		
		$Data['Balance'] = round($Users['money']/100,2);
		$Data['MoneyUsable'] = round($Users['money']/100,2);
		$Data['Logs'] = $list ? $list : array();
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
		
	//UserAccountWithdraw申请提现
	public function UserAccountWithdraw(){
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}

		$school_id = $getallheaders['Sp-School-Id'];
		
        $Shop = D('Shop')->where(array('user_id'=>$user_id))->find();
		$RunningSchool = M('RunningSchool')->where(array('school_id'=>$school_id))->find();//学校设置
		
		
		if($RunningSchool){
			if($RunningSchool['is_cash'] !=1){
				$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'当前学校暂时没开启提现功能，请联系管理员','IsSuccess'=>false));
			}
		}else{
			if($this->config['cash']['is_cash'] !=1){
				$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'网站暂时没开启提现功能，请联系管理员','IsSuccess'=>false));
			}
		}
		
		
		//p($RunningSchool);die;
		
		
		
		if($RunningSchool){
			//单学校配置
			if($Shop == ''){
				//会员提现设置
				$cash_money = $RunningSchool['user'] ? $RunningSchool['user'] : $this->config['cash']['user'] ? $this->config['cash']['user'] : '100';
				$cash_money_big = $RunningSchool['user_big'] ? $RunningSchool['user_big'] : $this->config['cash']['user_big'] ? $this->config['cash']['user_big'] : '100000';
				$cash_commission = $RunningSchool['user_cash_commission'] ? $RunningSchool['user_cash_commission'] : $this->config['cash']['user_cash_commission'] ? $this->config['cash']['user_cash_commission'] : '0';
			}else{
				//商家提现设置
				$cash_money = $RunningSchool['shop'] ? $RunningSchool['shop'] : $this->config['cash']['shop'] ? $this->config['cash']['shop'] : '100';
				$cash_money_big = $RunningSchool['shop_big'] ? $RunningSchool['shop_big'] : $this->config['cash']['shop_big'] ? $this->config['cash']['shop_big'] : '10000';
				$cash_commission = $RunningSchool['shop_cash_commission'] ? $RunningSchool['shop_cash_commission'] : $this->config['cash']['shop_cash_commission'] ? $this->config['cash']['shop_cash_commission'] : '0';
			}
			
		}else{
			//全局配置
			if($Shop == ''){
				//会员提现设置
				$cash_money = $this->config['cash']['user'] ? $this->config['cash']['user'] : '100';
				$cash_money_big = $this->config['cash']['user_big'] ? $this->config['cash']['user_big'] : '100000';
				$cash_commission = $this->config['cash']['user_cash_commission'] ? $this->config['cash']['user_cash_commission'] : '0';
			}else{
				//商家提现设置
				$cash_money = $this->config['cash']['shop'] ? $this->config['cash']['shop'] : '100';
				$cash_money_big = $this->config['cash']['shop_big'] ? $this->config['cash']['shop_big'] : '10000';
				$cash_commission = $this->config['cash']['shop_cash_commission'] ? $this->config['cash']['shop_cash_commission'] : '0';
			}
		}
		
		
		
		$money = I('money','','trim,htmlspecialchars');
		$info = I('info','','trim,htmlspecialchars');
		$money = $money *100;
		
		if($money <100){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'提现金额不能低于1元','IsSuccess'=>false));
		}
		if($money < $cash_money * 100){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'提现金额小于最低提现额度' . $cash_money . '元','IsSuccess'=>false));
		}
		if($money > $cash_money_big * 100){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'您单笔最多能提现' . $cash_money_big . '元','IsSuccess'=>false));
		}
		
		
		
		
		$Users = M('users')->where(array('user_id'=>$user_id))->find();
		//p($money);
		//p($Users['money']);die;
		
		$UsersMoney =(int)$Users['money'];
		 
		if($UsersMoney <= 0){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'会员的余额账户为0或者为负数','IsSuccess'=>false));
		}
		if($money > $Users['money']){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'提现金额不合法','IsSuccess'=>false));
		}
		
		
		
		$alipay_real_name = I('alipay_real_name','','trim,htmlspecialchars');
		$alipay_account = I('alipay_account','','trim,htmlspecialchars');
		
		
		if($alipay_real_name || $alipay_account){
			if(empty($alipay_account)){
				$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'支付宝账户必须填写','IsSuccess'=>false));
			}
			if(empty($alipay_real_name)){
				$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'支付宝真实名字必须填写','IsSuccess'=>false));
			}
			
			if($alipay_real_name != 'undefined'){
				$code = 'alipay';
			}else{
				$code = 'weixin';
			}
		}else{
			$code = 'weixin';
		}
		
		
		
		//佣金设置
		if($cash_commission >= 0){
			//佣金
			$commission = intval(($money*$cash_commission)/100);//佣金
		}else{
			$commission = 0;//0佣金
		}
	
		
		$UsersCashFind = M('UsersCash')->where(array('user_id'=>$user_id,'type'=>user,'status'=>0))->find();
		if($UsersCashFind){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'当前会员的提现ID【'.$UsersCashFind['cash_id'].'】还没处理，请处理后再次申请提现','IsSuccess'=>false));
		}
		
		
		//提现数组
		$data['account'] = $Users['nickname'];
		$data['school_id'] = $school_id;
        $data['user_id'] = $user_id;
		$data['shop_id'] = 0;
        $data['money'] = $money - $commission;//实际到账
		$data['commission'] = $commission;//手续费
		$data['info'] = $info;
		$data['re_user_name'] = '未填写';
		$data['alipay_account'] = $alipay_account?$alipay_account : '未填写';
		$data['alipay_real_name'] = $alipay_real_name ? $alipay_real_name : '未填写';
		$data['bank_num'] = '未填写';
		$data['bank_realname'] = '未填写';
        $data['type'] = 'user';
        $data['addtime'] = NOW_TIME;
		$data['code'] = $code;
		
		
		//再次避免重复提现
		$intro = '【小程序】您原始余额【'.round($UsersMoney/100,2).'】元，申请提现'.round($money/100,2).'元，其中手续费：'.round($data['commission']/100,2).'元，实际应到账'.round($data['money']/100,2).'元';
		
		//再次验证，没有提现过的状态验证
		$logs = M('user_money_logs')->where(array('user_id'=>$user_id,'type'=>3,'money'=>$money,'intro'=>$intro,'type'=>user,'status'=>0))->find();
		if($logs){
			$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'操作失败提现重复ID或者有提现未处理【'.$logs['log_id'].'】','IsSuccess'=>false));
		}
		
		
		//写入数据库
		if($cash_id = M('UsersCash')->add($data)){
			
			
			//扣除资金
			D('Users')->addMoney($user_id,-$money,$intro,3,$school_id);
			
			
			$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($data)));
		}
		$this->ajaxReturn(array('ErrorCode'=>'180','ErrorMessage'=>'提现失败','IsSuccess'=>false));
	}
	
		
		
	//UserCoinCoinInfo我的金币记录
	public function UserCoinCoinInfo(){
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$pageSize = I('pageSize','','trim,htmlspecialchars');
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		$Users = M('users')->where(array('user_id'=>$user_id))->find();
		
		import('ORG.Util.Page2');
		
		$map['user_id'] = $user_id;
		
		$count = M('UserIntegralLogs')->where($map)->count();
        $Page = new Page2($count,10);
        $show = $Page->show();
        $p = $pageIndex;
		if($Page->totalPages < $p){
            $list = array();
        }
		$list = M('UserIntegralLogs')->where($map)->limit($Page->firstRow .','.$Page->listRows)->select();
		foreach($list as $k => $v){
			$list[$k]['Remark'] = tu_msubstr($v['intro'],0,12,false);//简短介绍;
			$list[$k]['Value'] = $v['integral'];
			$list[$k]['CreatedTime'] = date('Y-m-d H:i:s ',$v['create_time']);
		}
		
		$Data['TotalValue'] = $Users['integral'];
		$Data['RuleArticleId'] = 7;//金币规则文章
		$Data['Logs'] = $list ? $list : array();
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	
	//HuserSta个人中心
	public function fundSta(){
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		if(!$users){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'会员已被删除','IsSuccess'=>false));
		}
		
		$month = I('month','','trim,htmlspecialchars');
		
		$outNumStr = (int)M('user_money_logs')->where(array('user_id'=>$user_id,'month'=>$month,'money'=>array('lt',0)))->sum('money');
		$inNumStr = (int)M('user_money_logs')->where(array('user_id'=>$user_id,'month'=>$month,'money'=>array('gt',0)))->sum('money');
		$inNumStr = abs($inNumStr);
		$data['result']['outNumStr'] = round($outNumStr/100,2);
		$data['result']['inNumStr'] = round($inNumStr/100,2);
		
		
		$data['result']['fundStr'] = round($users['money']/100,2);
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($data)));
	}
	
	
	
	//getFundList我的余额日志列表2
	public function getFundList(){
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		$month = I('month','','trim,htmlspecialchars');
		
		$busiType = I('busiType','','trim,htmlspecialchars');
		$pageIndex = I('pageIndex','','trim,htmlspecialchars');
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		
		import('ORG.Util.Page4');
		
		$map['user_id'] = $user_id;
		if($month){
			$map['month'] = $month;
		}
		
		$count = M('UserMoneyLogs')->where($map)->count();
        $Page = new Page4($count,5);
        $show = $Page->show();
      
		$getMoneyTypes = D('Users')->getMoneyTypes();
		$list = M('UserMoneyLogs')->where($map)->order('create_time desc')->limit($Page->firstRow .','.$Page->listRows)->select();
	
		foreach($list as $k => $v){
			$list[$k]['symbol'] = $v['money'] > 0 ? '+' : '-';
			$list[$k]['varNumStr'] = round($v['money']/100,2);
			$list[$k]['createTimeStr'] = date('Y-m-d H:i:s ',$v['create_time']);
			$list[$k]['dataTypeStr'] = $v['log_id'].' - '.$getMoneyTypes[$v['type']];
		}

		$Data['rows'] = $list ? $list : array();
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	
	
	
	//getMonthSta我的余额日志列表3
	public function getMonthSta(){
		$month = I('month','','trim,htmlspecialchars');
		
		$busiType = I('busiType','','trim,htmlspecialchars');
		$negFlag = I('negFlag','','trim,htmlspecialchars');
		
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('ErrorCode'=>'E03018','ErrorMessage'=>'登陆状态失效请稍后再试','IsSuccess'=>false));
		}
		$map['user_id'] = $user_id;
		if($month){
			$map['month'] = $month;
		}
		
		if($negFlag == 1){
			$map['money'] = array('lt',0);
		}else{
			$map['money'] = array('gt',0);
		}
		
		$list = M('UserMoneyLogs')->where($map)->order('create_time desc')->select();
		
		$getMoneyTypes = D('Users')->getMoneyTypes();
		
		foreach($list as $k => $v){
			$list[$k]['sumNumStr'] = round($v['money']/100,2);
			$list[$k]['createTimeStr'] = date('Y-m-d H:i:s ',$v['create_time']);
			$list[$k]['dataTypeStr'] = $v['log_id'].''.$getMoneyTypes[$v['type']];
			$totalStr += $v['money'];
		}

		$Data['result']['list'] = $list ? $list : array();
		$Data['result']['totalStr'] = round($totalStr/100,2);
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	//getNotifyInfo我的接单设置
	public function getNotifyInfo(){
		
		$checkRefund = I('checkRefund','','trim,htmlspecialchars');
	
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'登陆状态失效请稍后再试'));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'学校不存在'));
		}

		
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		
		
		$catList = M('running_cate')->where(array('school_id'=>$school_id,'is_show'=>0,'is_system'=>0))->order('orderby asc')->select();
		foreach($catList as $k => $v){
			$catList[$k]['busiName'] = $v['cate_name'];
			$catList[$k]['id'] = $v['cate_id'];
			$catList[$k]['show'] = 1;
			$catList[$k]['ver'] = "1.1.15";
			
			$notify = M('running_cate_notify')->where(array('school_id'=>$school_id,'cate_id'=>$v['cate_id']))->find();
			if($notify){
				$catList[$k]['type'] = 1;
				$catMap[$k]['id']  = $v['cate_id'];
				$catList[$k]['checked']  = 1;
			}else{
				$catList[$k]['type'] = 0;
				$catMap[$k]['id'] = false;
				$catList[$k]['checked']  = 0;
			}
		}
		
		
		
		$result['catList']= $catList ? $catList : array();
		
		
		
		$result['catMap']= $catMap;
		
		$result['rebuildType']= $rebuildType  ? $rebuildType  : array();
		$result['wxImg']= config_weixin_img($this->config['site']['wxcode']);
		
		
		$subscribe = D('Weixin')->subscribe($user_id);
		if($subscribe){
			M('users')->where(array('user_id'=>$user_id))->save(array('bindFlag'=>1));
		}
		
		
		//这里判断是否关注
		$result['bindFlag']= $subscribe ? $subscribe : 0; //1没绑定0证明当前用户已经绑定临时可调整为1
		
		$result['notifyFrom']= (int)$users['notifyFrom'] ? (int)$users['notifyFrom'] : '1';//接单开始时间
		$result['notifyEnd']= (int)$users['notifyEnd'] ? (int)$users['notifyEnd'] : '1';;//接单开始时间
		
		//开启通知
		$result['notifyFlag']= $users['notifyFlag'] ? $users['notifyFlag'] : 0;//0不接受1接受判断当前会员
		
		
		$this->ajaxReturn(array('code'=>0,'result'=>$result));
	}
	
	
	
	//submitNotify保存接单通知
	public function submitNotify(){
		
		$checkRefund = I('checkRefund','','trim,htmlspecialchars');
		$ids = I('ids','','stripslashes');
	
		
		
		$notifyFlag = I('notifyFlag','','trim,htmlspecialchars');//1开启
		$notifyFrom = I('notifyFrom','','trim,htmlspecialchars');//结束时间
		$notifyEnd = I('notifyEnd','','trim,htmlspecialchars');//结束时间
		
		
		
	
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'登陆状态失效请稍后再试'));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'学校不存在'));
		}
		
		
		$ids = json_decode($ids,true);
		
		if(array($ids)){
			
			$i = 0;
			//删除全部自己的
			$res = M('running_cate_notify')->where(array('school_id'=>$school_id,'user_id'=>$user_id))->delete();
			
			foreach($ids as $val){
				$i++;
				$data['school_id'] = $school_id;
				$data['cate_id'] = $val;
				$data['user_id'] = $user_id;
				M('running_cate_notify')->add($data);
			}
		}
		
		//p($notifyFlag);
		//p($notifyFrom);
		//p($notifyEnd);die;
		
		$users = M('users')->where(array('user_id'=>$user_id))->save(array('notifyFlag'=>$notifyFlag,'notifyFrom'=>$notifyFrom,'notifyEnd'=>$notifyEnd));
		
		$message= '会员ID【'.$user_id.'】更新'.$i.'个';
		
		$this->ajaxReturn(array('code'=>0,'message'=>$message));
	}
	
	
	//getNotifyUrl获取接单关注连接
	public function getNotifyUrl(){
		
		$checkRefund = I('checkRefund','','trim,htmlspecialchars');
	
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'登陆状态失效请稍后再试'));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'学校不存在'));
		}
		
		//获取关注连接
		$result = $this->config['site']['host'].'/wap/news/authorize';
		
		$this->ajaxReturn(array('code'=>0,'result'=>$result));
	}
	
	
	
	//rechargeOrder会员充值
	public function rechargeOrder(){
		
		$money = I('money','','trim,htmlspecialchars');
	
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'登陆状态失效请稍后再试'));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'学校不存在'));
		}
		if(!$money){
			$this->ajaxReturn(array('code'=>1,'message'=>'金额不正确'));
		}
		

		$logs = array(
			'type' => 'money',
			'types' =>0, 
			'info' => '余额充值',  
			'user_id' => $user_id, 
			'order_id' => 0,
			'school_id' => $school_id,  
			'code' => 'wxapp', 
			'need_pay' => $money, 
			'create_time' => NOW_TIME, 
			'create_ip' => get_client_ip(), 
			'is_paid' => 0
		);
		$logId = M('PaymentLogs')->add($logs);
		
		include APP_PATH . 'Lib/Action/App/wxpay.php';
		
        $Payment= D('Payment')->getPayment('wxapp');
		if(!$Payment){
			$Payment= D('Payment')->getPayment('weixin');	
		}
		
		
        $out_trade_no = $logId.'-'.time();
      
		$openid = D('Connect')->getWxappOpenid($user_id,$openid = '','money');
		
		
        $weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,'余额充值ID【'.$logId.'】付款',$money);//支付接口
        $return = $weixinpay->pay();
		
		$return['timeStamp'] = $return['timeStamp'];
        $return['nonceStr']= $return['nonceStr'];
        $return['packageStr']= $return['package'];
        $return['signType']= $return['signType'];
        $return['paySign']= $return['paySign'];
		$return['tradeNo'] = $logId;//支付ID
		$return['recharge'] = round( $money/100,2);//支付金额
		
	
		
		if($return['package'] == 'prepay_id='){
			$this->ajaxReturn(array('code'=>1,'message'=>'预支付失败--'.$return['rest']['return_msg'],'return'=>$return));
		}
		
		
		if($return['rest']['return_code'] == 'SUCCESS' || $return['rest']['return_msg'] == 'OK'){
			
			$this->ajaxReturn(array('code'=>0,'result'=>$return));
		}
		$this->ajaxReturn(array('code'=>1,'message'=>'充值失败'));
		
		
	}
	
	
	//payResult支付结果，充值结果
	public function payResult(){
		
		$tradeNo = I('tradeNo','','trim,htmlspecialchars');
	
		$logs = M('payment_logs')->where(array('log_id'=>$tradeNo))->find();//支付日志记录
		
		if($logs['is_paid'] == '1'){
			$result['paid'] = $logs['is_paid'];
			
			$this->ajaxReturn(array('code'=>0,'result'=>$result));
		}
		$this->ajaxReturn(array('code'=>1,'message'=>'支付失败'));
	}
	
	
	
	//location保存试试定位
	public function location(){
		
		$userId = I('userId','','trim,htmlspecialchars');
		$lat = I('lat','','trim,htmlspecialchars');
		$lng = I('lng','','trim,htmlspecialchars');
		
		//地址转换
		$getMapChangeBaidu2 = getMapChangeBaidu2($lat,$lng);
		$lat2 = substr($getMapChangeBaidu2['lat'],0,strlen($getMapChangeBaidu2['lat'])-5);
		$lng2 = substr($getMapChangeBaidu2['lng'],0,strlen($getMapChangeBaidu2['lng'])-5);
	
		$users = M('users')->where(array('user_id'=>$userId))->find();
		if($users){
			$res = M('users')->where(array('user_id'=>$userId))->save(array('lat'=>$lat2,'lng'=>$lng2));
		}
		
		$delivery = M('running_delivery')->where(array('user_id'=>$userId))->find();
		if($delivery){
			$res2 = M('running_delivery')->where(array('user_id'=>$userId))->save(array('lat'=>$lat2,'lng'=>$lng2));
		}
		
		$data['user_id'] = $userId;
		$data['lat'] = $lat2;
		$data['lng'] = $lng2;
		$data['create_time'] = time();
		$id = M('running_delivery_position_logs')->add($data);
		
		$this->ajaxReturn(array('code'=>1,'message'=>'保存地址成功['.$id.']','data'=>$data));
	}
	
	
	
	//orderyuePay跑腿订单余额支付
	public function orderyuePay(){
		
		$logs_id = I('log_id','','trim,htmlspecialchars');
		$type = I('type','','trim,htmlspecialchars');
	
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		if(!$user_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'登陆状态失效请稍后再试'));
		}
		$school_id = $getallheaders['Sp-School-Id'];
		if(!$school_id){
			$this->ajaxReturn(array('code'=>1,'message'=>'学校不存在'));
		}
		
		if(empty($logs_id)){
			$this->ajaxReturn(array('code'=>1,'message'=>'支付ID不存在'));
        }
		
        if(!($detail = M('PaymentLogs')->find($logs_id))){
			$this->ajaxReturn(array('code'=>1,'message'=>'支付记录不存在'));
        }
        
		
		if($detail['need_pay'] <= 0){
			$this->ajaxReturn(array('code'=>1,'message'=>'支付金额有误'));
        }
		
		$user_id = $user_id ? $user_id : $detail['user_id'];
		
        $member = M('Users')->find($user_id);
        if($detail['is_paid']){
			$this->ajaxReturn(array('code'=>1,'message'=>'支付日志状态错误'));
        }
		
		if($member['money'] < $detail['need_pay']){
			$this->ajaxReturn(array('code'=>1,'message'=>'余额不足无法支付'));
		}
		
        
		$intro = '【余额支付】'.round($detail['need_pay']/100,2).'元，支付ID('.$logs_id.')，原始订单ID：【'.$detail['order_id'].'】';
		
	
		$member['money'] -= $detail['need_pay'];
		
		if(M('Users')->save(array('user_id'=>$user_id,'money'=>$member['money']))){
			M('UserMoneyLogs')->add(array(
				'user_id' => $user_id, 
				'money' => - $detail['need_pay'],
				'type' => $type,  
				'school_id' =>$school_id, 
				'type' => 1, 
				'month' => date('Ym',NOW_TIME), 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(), 
				'intro' => $intro
			));
		}
		
		M('PaymentLogs')->where(array('log_id'=>$logs_id))->save(array('code'=>'money'));//更新支付方式
		
		D('Payment')->logsPaid($logs_id,'','');//回调函数
		
		$this->ajaxReturn(array('code'=>0,'runningId'=>$detail['order_id'],'message'=>'支付成功'));
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
	
	
	
	//分类拼车拼团信息配置
 	public function ThreadSystem(){
		$res['color'] = $this->config['other']['color'] ? $this->config['other']['color'] : '#06c1ae';
		$res['total_num']  = (int)M('ThreadPost')->sum('views');
		$res['is_tzdz']  = (int)$this->config['wxapp']['is_tzdz'] ? (int)$this->config['wxapp']['is_tzdz'] : '0';//分类信息开启定位
		$res['is_qgb']  = (int)$this->config['wxapp']['is_qgb'] ? (int)$this->config['wxapp']['is_qgb'] : '0';//分类信息开启发布地区
		$res['is_pgzf']  = (int)$this->config['wxapp']['is_pgzf'] ? (int)$this->config['wxapp']['is_pgzf'] : '0';//分类信息不开启置顶2不开启1开启
		$res['is_bdtel']  = (int)$this->config['wxapp']['is_bdtel'];//贴吧分类信息开启电话图标显示是否拨打电话
		$res['is_ff']  = (int)$this->config['wxapp']['is_ff'] ? (int)$this->config['wxapp']['is_ff'] : '2';//贴吧分类信息开启电话图标显示图标样式
		$res['tzmc']  = $this->config['wxapp']['tzmc'] ? $this->config['wxapp']['tzmc'] : '信息';
		$res['is_pgzf']  = (int)$this->config['wxapp']['is_pgzf'] ? (int)$this->config['wxapp']['is_pgzf'] : '0';//是否支持苹果支付
		$res['pt_name']  = $this->config['site']['sitename'] ? $this->config['site']['sitename'] : '金桃cms';
		
		$res['is_pcfw']  = (int)$this->config['config']['pcfw'];//开启首页顺风车
		$res['is_pcqx']  = (int)$this->config['config']['pcfw'];//开启首页顺风车
		$res['pc_money']  = $this->config['config']['fabu_money'] ? $this->config['config']['fabu_money'] : 0;
		$res['pc_xuz']  = $this->config['config']['explain'] ? $this->config['config']['explain'] : '请在后台添加拼车说明';
		
		
		$json_str = json_encode($res);
        exit($json_str); 
	}
	
	//拼车流量主
	public function pincheLlz(){
		$res[0]['id'] = 7;
		$res[0]['name'] = '';
		$res[0]['src'] = '';
		$res[0]['status'] = '1';
		$res[0]['type'] = '2';
		echo json_encode($res);
		
	}
 

	//拼车首页广告
	public function pincheAd(){
		
		$school_id = I('schoolId','','trim');
		
		$site_id = $this->config['wxapp']['pinche_site_id'] ? $this->config['wxapp']['pinche_site_id'] : '85';
		if($school_id){
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0','school_id'=>$school_id))->select();
		}else{
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0'))->select();
		}
	
		foreach ($list as $k => $val){
			$list[$k]['type'] = 4;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
		$list = $list? $list : array();
        $json_str = json_encode($list);
        exit($json_str); 
	}	
	
	
	//拼车首页新闻
	public function pincheNews(){
		$list = M('Article')->where(array('audit'=>1,'closed' =>0,'city_id'=>$this->city_id))->limit(0,5)->order('istop desc')->select();
		foreach($list as $k => $val){
			$list[$k]['id'] = $val['article_id'];
			$list[$k]['type'] = 1;
		}
		
		$list = $list ? $list : array();
        $json_str = json_encode($list);
        exit($json_str); 

	}
	
	
	
	public function ThreadUrl2(){
		$json_str = json_encode($this->config['site']['host']);
        exit($this->config['site']['host']); 
		
	}
	public function ThreadUrl(){
        $json_str = json_encode($this->config['site']['host']);
        exit($this->config['site']['host']); 
		
	}
	
	public function ThreadViews(){
		$views = (int)D('Life')->sum('views');
		$json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$views);
        $json_str = json_encode($json_arr);
        exit($json_str); 
		
	}
	
	//贴吧数量
	public function ThreadNum(){
		$school_id = I('schoolId','','trim,htmlspecialchars');
		if($school_id){
			$count = M('ThreadPost')->where(array('audit'=>1,'closed'=>0))->count();
		}else{
			$count = M('ThreadPost')->where(array('audit'=>1,'closed'=>0,'school_id'=>$school_id))->count();
		}
		$json_str = json_encode($count);
        exit($json_str); 
	}
	
	//获取地图
	public function ThreadMap(){
		$op = I('op','','trim,htmlspecialchars');
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?location=".$op."&key=NNFBZ-UOPHJ-Y3KFO-KVKPN-GIRL7-2OFGX&get_poi=0&coord_type=1";
        $html = file_get_contents($url);
        echo  $html;
	}
	//信息置顶
    public function ThreadTop(){
		if($this->config['wxapp']['top_type_1']){
			$res[0]['id'] = 1;
			$res[0]['type'] = 1;
			$res[0]['money'] = $this->config['wxapp']['top_type_1'];
		}
		if($this->config['wxapp']['top_type_1'] && $this->config['wxapp']['top_type_2']){
			$res[0]['id'] = 1;
			$res[0]['type'] = 1;
			$res[0]['money'] = $this->config['wxapp']['top_type_1'];
			
			$res[1]['id'] = 2;
			$res[1]['type'] = 2;
			$res[1]['money'] = $this->config['wxapp']['top_type_2'];
		}
		if($this->config['wxapp']['top_type_1'] && $this->config['wxapp']['top_type_2'] && $this->config['wxapp']['top_type_3']){
			$res[0]['id'] = 1;
			$res[0]['type'] = 1;
			$res[0]['money'] = $this->config['wxapp']['top_type_1'];
			
			$res[1]['id'] = 2;
			$res[1]['type'] = 2;
			$res[1]['money'] = $this->config['wxapp']['top_type_2'];
			
			$res[2]['id'] = 3;
			$res[2]['type'] = 3;
			$res[2]['money'] = $this->config['wxapp']['top_type_3'];
		}
		$res = $res ? $res : array();
      	echo json_encode($res);
    }
	
	
	//查看二级分类下的标签
    public function ThreadLabel(){
        $type2_id = I('type2_id','','trim,htmlspecialchars');
        $res =  M('ThreadCateTag')->order(array('orderby' => 'asc'))->where(array('cate_id' =>$type2_id))->select();
		foreach($res as $k => $val){
			if($val['tag_id']){
				$res[$k]['id'] = $val['tag_id'];
				$res[$k]['click_class'] = $val['tag_id'];
			}
		}
		if($res){
			echo json_encode($res);
		}else{
			echo 1;
		}
   }
   
   
   //关键词
   public function ThreadGetSensitive(){
		$msg = '共产党,做爱';
		$res['content']=$msg;
		$res['id']=3;
		echo json_encode($res);
	 }
 
    //获取个人详情
	public function ThreadGetUserInfo(){
		$user_id= I('user_id','','trim');
		$res=M('users')->find($user_id);
		$res['state']=1;
		$res['money']=round($res['money']/100,2);
		$res['total_score']=(int)$res['integral'];
		$res['id'] =$res['user_id'];
		$res['user_name'] =$res['nickname']; 
		$res['user_tel'] =$res['mobile']; 
		$res['openid'] =M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->getField('open_id'); 
   		echo json_encode($res);
	}

	
	//信息首页
	public function ThreadAd(){
		
		
		$school_id = I('schoolId','','trim');
		
		$site_id = $this->config['wxapp']['thread_site_id'] ? $this->config['wxapp']['thread_site_id'] : '85';
		if($school_id){
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0','school_id'=>$school_id))->select();
		}else{
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0'))->select();
		}
	
		foreach ($list as $k => $val){
			$list[$k]['type'] = 8;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
		$list = $list? $list : array();
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	

	public function ThreadType(){
		$arr = M('ThreadCate')->limit(0,10)->order('orderby asc')->select();
	    foreach($arr as $k => $v){
		   $arr[$k]['id'] = $v['cate_id'];
		   $arr[$k]['type_name'] = $v['cate_name'];
		   $arr[$k]['money'] = round($v['money']/100,2);
		   $arr[$k]['img'] = config_weixin_img($v['photo']);
	    } 
        $json_str = json_encode($arr);
        exit($json_str); 
	}
	
	
	
	public function ThreadType2(){
		$cate_id = I('id','','trim');
		$arr = M('Thread')->where(array('cate_id'=>$cate_id))->limit(0,10)->select();
	    foreach($arr as $k => $v){
		   $arr[$k]['id'] = $v['thread_id'];
		   $arr[$k]['map'] = $v['thread_id'];
		   $arr[$k]['name'] = $v['thread_name'];
		   $arr[$k]['money'] = round($v['money']/100,2);
		   $arr[$k]['img'] = config_weixin_img($v['photo']);
	    } 
		$arr = $arr ? $arr : array();
        $json_str = json_encode($arr);
        exit($json_str); 
	}
	
	
	//保存SaveFormid
	public function SaveFormid(){
		$data['user_id'] = I('user_id','','trim');
		$data['form_id'] = I('form_id','','trim');
		$data['formId'] = $data['form_id'];
		$data['openid'] = I('openid','','trim');
		$data['time']=date('Y-m-d H:i:s');
		if(!$data['openid']){
			$connect = M('connect')->where(array('uid'=>$data['user_id']))->find();
			$data['openid'] = $connect['openid'];
		}
		if($data['form_id']!='the formId is a mock one' and $data['form_id'] && $data['user_id']){
   			$res = M('UserFormid')->add($data);
			echo  '1';
   	    }else{
			echo  '2';
		}
	}
	
	//商家列表
	public function ThreadStoreList(){
		$list = array();
        $json_str = json_encode($list);
        exit(json_encode($list)); 
	}
	
	
	//ThreadLlz
	public function ThreadLlz(){
		$list = array();
        $json_str = json_encode($list);
        exit(json_encode($list)); 
	}
	
	//分销
	public function ThreadFtXz(){
		$list = array();
        $json_str = json_encode($list);
        exit(json_encode($list)); 
	}
	//新闻列表
	public function ThreadNews(){
		$list = array();
        $json_str = json_encode($list);
        exit(json_encode($list)); 
	}
	
	//分类信息列表
	public function ThreadList(){
		
		import('ORG.Util.Page3');
        $map = array('audit'=>1,'closed'=>0);
		
		if($type_id = I('type_id','','trim')){
			$map['cate_id'] = $type_id;
        }
		
		
		$schoolId= I('schoolId','','trim');
		if($schoolId && $schoolId != 'undefined'){
			$map['school_id'] = $schoolId;
        }
		
		
		if($type2_id= I('type2_id','','trim')){
			$map['thread_id'] = $type2_id;
        }
		
		$count = M('ThreadPost')->where($map)->count();
        $Page = new Page($count,30);
        $show = $Page->show();
        $p = I('page');
        if($Page->totalPages < $p){
            $list = array();
            echo json_encode($list);die;
        }
	   
		$list = M('ThreadPost')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order(array('is_top'=>'desc','create_time'=>'desc'))->select();
        foreach($list as $k => $val){
			$Users = M('users')->find($val['user_id']);
			$list[$k]['id'] = $val['post_id'];
		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = (int)$val['is_top'];
			$list[$k]['givelike'] = (int)M('ThreadPostZan')->where(array('post_id'=>$val['post_id']))->count();
			$list[$k]['thumbs_ups'] = null;
			$list[$k]['user_tel'] = $val['mobile'] ? $val['mobile'] : $this->config['site']['tel'];
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = $Users['nickname'] ? $Users['nickname'] : '平台发布';
			$list[$k]['type_name'] = $this->getListThread($val['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$val['cate_id']))->getField('cate_name');//分类
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$val['cate_id']))->select();
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['sh_time'] = $val['create_time'] ? $val['create_time'] : time();
			$list[$k]['img'] = $this->getListPics($val['post_id']);
			$list[$k]['img1'] = $this->getListPics($val['post_id']);
			$list[$k]['details'] = cleanhtml($val['details']);
			$list[$k]['is_renzheng'] = (int)M('running_delivery')->where(array('user_id'=>$val['user_id']))->getField('audit');//是否认证
		}
		
		
		foreach($list as $k => $val){
			$data2[]=array(
			  'tz'=>$list[$k] ? $list[$k] : array(),
			  'label'=>array(),
			 );
		}
        $json_str = json_encode($data2);
        exit($json_str); 
		
	}

	//贴吧点赞
	public function ThreadLike(){
		//可用可不用
		$school_id = I('schoolId','','trim');
		$user_id = I('user_id','','trim');
		$post_id = I('information_id','','trim');
        if(empty($post_id)){
           echo '帖子不存在';
        }
		
		
		
		if(empty($user_id)){
           echo '会员ID不存在';
        }
		
		$res = M('ThreadPostZan')->where(array('post_id' => $post_id,'user_id' => $user_id,'create_ip' => get_client_ip()))->find();
        if($res){
             echo '请不要重复点赞';
        }else{
			
			$rest = M('ThreadPostZan')->add(array('post_id'=>$post_id,'user_id'=>$user_id,'create_time' => time(),'create_ip' => get_client_ip()));
			
			//p(M('ThreadPostZan')->getlastsql());die;
			
			
            if($rest){
				
                 D('Threadpost')->updateCount($post_id,'zan_num');
                  echo 1;
            }else{
                echo '点赞更新错误或者已点赞';
             }
        }
	}


	//分类信息详情
	public function ThreadPostInfo(){
		
		$post_id = I('id','','trim');
		$detail = M('ThreadPost')->find($post_id);
		
		M('ThreadPost')->where(array('post_id'=>$post_id))->setInc('views',1);//新增浏览量
		
		
        $Users = M('users')->find($detail['user_id']);
		$detail['id'] = $detail['post_id'];
		$detail['user'] = $Users;
		$detail['top'] = $detail['is_top'];
		$detail['givelike'] = (int)M('ThreadPostZan')->where(array('post_id'=>$post_id))->count();
		$detail['thumbs_ups'] = null;
		$detail['user_tel'] = $detail['mobile'] ? $detail['mobile'] : $this->config['site']['tel'];;
		$detail['user_img'] = config_weixin_img($Users['face']);			
		$detail['user_name'] = $Users['nickname'] ? $Users['nickname'] : '平台发布';
		$detail['type_name'] = $this->getListThread($detail['thread_id']);//分类
		$detail['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$detail['cate_id']))->getField('cate_name');//分类
		$detail['label'] = M('ThreadCateTag')->where(array('cate_id'=>$detail['cate_id']))->select();
		
		$detail['is_renzheng'] = (int)M('running_delivery')->where(array('user_id'=>$detail['user_id']))->getField('audit');//是否认证
		
		
		$detail['time'] = $detail['create_time'];
		$detail['time2'] = $detail['create_time'];
		$detail['sh_time'] = $detail['create_time'];
		$detail['img'] = $this->getListPics($detail['post_id']);
		$detail['img1'] = $this->getListPics($detail['post_id']);
		$detail['details'] = cleanhtml($detail['details']);
		$detail['address'] = $detail['address'];
		
		$dz = M('ThreadPostZan')->where(array('post_id'=>$post_id))->select();	
		if($dz){
			foreach($dz as $kk => $vv){
				$Users = M('users')->find($vv['user_id']);
				$dz[$kk]['user_img'] = config_weixin_img($Users['face']);		
			}
		}
		
		$pl = M('ThreadPostComments')->where(array('post_id'=>$post_id,'audit'=>1))->select();	
		
		if($pl){
			foreach($pl as $kkk => $vvv){
				$Users = M('users')->find($vvv['user_id']);
				$pl[$kkk]['id'] = $vvv['comment_id'];	
				$pl[$kkk]['name'] = config_user_name($Users['nickname'] ? $Users['nickname'] : $Users['account']);
				$pl[$kkk]['time'] = $vvv['create_time'];
				$pl[$kkk]['details'] = $vvv['contents'];	
				$pl[$kkk]['user_img'] = config_weixin_img($Users['face']);		
			}
		}
		$label = '';
		if($detail['tag']){
			$tags = explode(',',$detail['tag']);
			$label = M('ThreadCateTag')->where(array('tag_id'=>array('IN',$tags)))->select();
			foreach($label as $k2 => $v2){
				$label[$k2]['label_name'] = $v2['tagName'];		
			}
		}
		$data['tz']=$detail;
	    $data['dz']=$dz;
	    $data['pl']=$pl;
	    $data['label']=$label;
	    echo json_encode($data);
	}
	
	
	//分类信息红包list
	public function ThreadHongList(){
		$post_id = I('id','','trim');
		$data = array();
	    echo json_encode($data);
	}
	
	
	//获取openid
	public function ThreadOpenid(){
		$code = I('code','','trim,htmlspecialchars');
		$encryptedData = I('encryptedData','','trim,htmlspecialchars');
	    $iv = I('iv','','trim,htmlspecialchars');
		$nickName = I('nickName','','trim,htmlspecialchars');
		$avatarUrl = I('avatarUrl','','trim,htmlspecialchars');

		$url="https://api.weixin.qq.com/sns/jscode2session?appid=".$this->config['wxapp']['appid']."&secret=".$this->config['wxapp']['appsecret']."&js_code=".$code."&grant_type=authorization_code";
		
		$res = $this->httpRequest($url);
		$res = json_decode($res,true);  

		
		include APP_PATH .'/Lib/Action/App/jiemi/wxBizDataCrypt.php';
		$WXBizDataCrypt = new WXBizDataCrypt($this->config['wxapp']['appid'],$res['session_key']);
		$errCode = $WXBizDataCrypt->decryptData($encryptedData,$iv,$data);
		$rest = json_decode($data,true);  
		
		//如果不带unionId
		if(!$rest['unionId']){
			$arr['session_key'] = $res['session_key'];
			$arr['openid'] = $res['openid'];
			$arr['nickname'] = $nickName;
			$arr['face'] = $avatarUrl;
		}else{
			$arr['session_key'] = $res['session_key'];
			$arr['unionid'] = $rest['unionId'];
			$arr['openid'] = $rest['openId'];
			$arr['nickname'] = $rest['nickName'];
			$arr['face'] = $rest['avatarUrl'];
		}

		if(empty($arr['openid']) || empty($arr['nickname'])){
			echo '注册错误';
		}else{
			$res2 = $this->wxappRegister($arr);
			$res2['openid'] = $arr['openid'];
			$res2['session_key'] = $arr['session_key'];
			echo json_encode($res2);
		}
	}
	
	
	//登录
	public function ThreadLogin(){
		$openid = I('openid','','trim,htmlspecialchars');
		$user_id = I('user_id','','trim,htmlspecialchars');
		$session_key = I('session_key','','trim,htmlspecialchars');
		$face = I('img','','trim,htmlspecialchars');
		$nickname = I('name','','trim,htmlspecialchars');

		$user = M('users')->where(array('user_id'=>$user_id))->find();
	    $connect = M('connect')->where(array('type'=>'weixin','uid'=>$user_id))->find(); 	
		
		$fuid = I('fuid','','trim,htmlspecialchars');//推荐人ID
		$f =  M('users')->find($fuid);//推荐人的会员信息
		
		//更新推荐人分销关系需要没绑定过的关系加上推荐人有信息的时候再可以绑定
		if(empty($user['fuid1']) && $f){
			$array['user_id'] = $user_id;
			$array['fuid1'] = $f['user_id'];
			$array['fuid2'] = $f['fuid1'];
			$array['fuid3'] = $f['fuid2'];
			$array['fuid4'] = $f['fuid3'];
			$array['fuid5'] = $f['fuid4'];
			$array['fuid6'] = $f['fuid5'];
			$array['fuid7'] = $f['fuid6'];
			$array['fuid8'] = $f['fuid7'];
			$array['fuid9'] = $f['fuid8'];
			$array['fuid10'] = $f['fuid9'];
			$update = M('users')->where(array('user_id'=>$user_id))->save($array);
		}
		
		
		
		if($user){
			$user['id'] = $user['user_id'];
			$user['openid'] = $connect['open_id'] ? $connect['open_id'] : $connect['openid'];
			$user['img'] = config_weixin_img($user['face']);
			echo json_encode($user);
		}else{
			echo '登录错误';
		}
	}
	
	//注册2
	public function wxappRegister($res){
		//如果有unionid这里的开放平台可能不正确
		if($res['unionid']){
			$connect = M('connect')->where(array('type'=>'weixin','unionid'=>$res['unionid']))->find(); 	
		}else{
			$connect = M('connect')->where(array('type'=>'weixin','openid'=>$res['openid']))->find(); 	
		}
		if($connect['uid']){
			$users = M('users')->find($connect['uid']);
		}
		
		$data['unionid'] = $res['unionid'];
		$data['openid'] = $res['openid'];
        $data['type'] = 'weixin';
		$data['session_key'] = $res['session_key'];
		$data['rd_session'] = $rd_session = md5(time().mt_rand(1,999999999));
		
		if(!$connect || !$users['user_id']){
			$data['create_time'] = time();
            $data['create_ip'] = get_client_ip();
			$connect_id = M('connect')->add($data);//新建表
			
			
            $arr = array(
               'account' => 'wxapp'.$connect_id, 
               'password' => rand(1000, 9999),
			   'openid' => $res['openid'], 
               'unionid' => $res['unionid'], 
			   'password' => $res['face'],  
               'face' => $res['face'], 
               'nickname' => $res['nickname'], 
               'create_time' => NOW_TIME, 
               'create_ip' => get_client_ip()
            );
			
            $user_id = D('Passport')->register($arr,$fid = '',$type = '1');
			M('connect')->save(array('connect_id'=>$connect_id,'uid'=>$user_id,'headimgurl'=>$res['face'],'openid'=>$res['openid'],'nickname'=>$res['nickname']));
			$user = M('users')->find($user_id);
			$user['user_name'] = $user['nickname'];
			$user['name'] = $user['nickname'];
			$user['id'] = $user['user_id'];
			$user['img'] = config_weixin_img($user['face']);
			return $user;
		}else{
			M('connect')->where(array('connect_id'=>$connect['connect_id']))->save($data);
			$user = M('users')->find($connect['uid']);
			$user['user_name'] = $users['nickname'];
			$user['name'] = $user['nickname'];
			$user['id'] = $user['user_id'];
			$user['img'] = config_weixin_img($user['face']);
			return $user;
		}
		return true;
	}
	
	
	//getFxCode会员分销生成海报分销海报
	public function getFxCode(){
		$fuid = I('fuid', 0, 'trim,intval');
		$page = "pages/errand/_/index";//没发布先影藏
		$width = '430';
		
	
		
		$code = D('Api')->qrcodeWxapp($fuid,$page,$width,$parameter = 'fx_code',$fuid);
		
	
		
		//分销海报
		$poster = $this->config['config']['poster'];
		$poster = config_weixin_img($poster);
		
		$data['poster'] = $poster;
		$data['code'] = $code;
		$data['text1'] = $this->config['config']['text1'] ? $this->config['config']['text1'] : '后台基本设置里面设置1';
		$data['text2'] = $this->config['config']['text2'] ? $this->config['config']['text2'] : '后台基本设置里面设置2';
        $this->ajaxReturn(array('data'=>$data)); 
	}
	
	
	
	//分类信息海报
	public function ThreadPoster(){
		$id = I('id', 0, 'trim,intval');
		$page ="pages/thread/detial";//路径
		$width = '100';
        $img = $this->set_msg($id,$page,$width,$id);//scene,page,width
		$patch = BASE_PATH.'/attachs/'. 'weixin/'.date('Y/m/d/', NOW_TIME);
		$patch2 = '/attachs/'. 'weixin/'.date('Y/m/d/', NOW_TIME);
		$res = $this->buildWxappCode($id,$img,$patch,$patch2,$parameter='postId');
		
		
		//检测海报输出
		if(file_exists(BASE_PATH.$res)){
		   echo $res;
		   die;
		}else{
		   echo config_weixin_img($this->config['site']['wxappcode']);
		   die;
		}

	}
	


	//生成二维码函数  
	public function buildWxappCode($storeid,$img,$patch,$patch2,$parameter){
		$img = base64_encode($img);
		$base64_image_content = "data:image/jpeg;base64," .$img;
	
		
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            $type = $result[2];
			
            if(!file_exists($patch)){
                mkdir($patch, 0777);//设置权限
            }
            $name = "{$parameter}" . "_{$storeid}" . ".{$type}";
            $patch = $patch . $name;//路径
			$base64_decode = base64_decode(str_replace($result[1], '', $base64_image_content));
            file_put_contents($patch,$base64_decode);
        }
		
		
        return $this->config['site']['host'].$patch2. $name;
	}
	
	//获取token
	public function getaccess_token(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->config['wxapp']['appid'] . "&secret=" . $this->config['wxapp']['appsecret'] . "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data, true);
	
        return $data['access_token'];
    }
	
	//获取小程序码
	public function set_msg($storeid,$page,$width,$scene = ''){
        $access_token = $this->getaccess_token();
        $data2 = array("scene" =>$scene,"page"=>$page,"width" =>$width);
        $data2 = json_encode($data2);
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token . "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
        $data = curl_exec($ch);
        curl_close($ch);
		
        return $data;
     }
			
	//帖子收藏
    public function ThreadCollection(){
         $shop_id = I('store_id','','trim');
		 $user_id = I('user_id','','trim');
		 $post_id = I('information_id','','trim');
		
		 $getallheaders = $this->getallheaders();
		 $school_id = $getallheaders['Sp-School-Id'];
		
		
		 $rest = M('thread_post_collection')->where(array('post_id'=>$post_id,'user_id'=>$user_id))->find();
		 if($rest){
			  $res = M('thread_post_collection')->where(array('id'=>$rest['id']))->delete();
		 }else{
			 $res = M('thread_post_collection')->add(array('post_id'=>$post_id,'user_id'=>$user_id,'school_id'=>$school_id,'create_time'=>NOW_TIME,'create_ip'=>get_client_ip()));
			 $result = $res ? 1 : 2;
		 } 
		
		 echo $result; 
    }
	
	
	
	//查看贴吧是否收藏
	public function ThreadIsCollection(){
		  $user_id = I('user_id','','trim,htmlspecialchars');
		  $psot_id = I('information_id','','trim,htmlspecialchars');
		  $res =  M('thread_post_collection')->where(array('user_id'=>$user_id,'psot_id'=>$psot_id))->find();
		  if($res){
			echo '1';  
		  }else{
			 echo '2';  
		  }
	  }
  
  
    //我的收藏贴吧
	public function ThreadMyCollection(){
		$user_id = I('user_id','','trim,htmlspecialchars');
		import('ORG.Util.Page3');

		$count = M('thread_post_collection')->where(array('user_id'=>$user_id))->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('page');
       
		$list = M('thread_post_collection')->where(array('user_id'=>$user_id))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$post = M('thread_post')->find($val['post_id']);
			
			$Users = M('users')->find($val['user_id']);

		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = (int)$val['is_top'];
			$list[$k]['givelike'] = $post['zan_num'];
			$list[$k]['user_tel'] = $post['mobile'];
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = $Users['nickname'];
			$list[$k]['type_name'] = $this->getListThread($post['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$post['cate_id']))->getField('cate_name');//分类
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$post['cate_id']))->select();
			$list[$k]['time'] = $post['create_time'];
			$list[$k]['sh_time'] = $post['create_time'];
			$list[$k]['img'] = $this->getListPics($post['post_id']);
			$list[$k]['img1'] = $this->getListPics($post['post_id']);
			$list[$k]['details'] = cleanhtml($post['details']);
			
		}
	
		$list = $list ? $list : array();
        $json_str = json_encode($list);
        exit($json_str); 
	}	
	


   
   public function ThreadComments(){
	    $data['post_id'] = I('information_id','','trim'); 
		$data['user_id'] = I('user_id','','trim');
		$data['contents'] = I('details','','trim,htmlspecialchars');
		$data['audit'] = 0;
		$data['create_time'] = time();
        $data['create_ip'] = get_client_ip();
		
		if($comment_id = M('ThreadPostComments')->add($data)){
			D('Threadpost')->updateCount($post_id, 'reply_num');
            M('ThreadPost')->save(array('post_id' => $post_id, 'last_id' => $data['user_id'], 'last_time' =>$data['create_time']));
			D('Threadpost')->noticeUserMsg($post_id,$cid);
			echo $comment_id;
	    }else{
         echo '2';
       }
   }
  
  
  
  	
	//分类信息支付2
	public function threadPay(){
		include APP_PATH . 'Lib/Action/App/wxpay.php';
		$user_id = I('user_id');
		$openid = I('openid');
		$type = I('type');
		$money = I('money')*100;
		$schoolId= I('schoolId','','trim');
		
		$openid = D('Connect')->getWxappOpenid($user_id,$openid,'thread');
		
		
		$logs = array(
			'type' =>'thread',
			'user_id' => $user_id,
			'school' =>$schoolId,
			'order_id' => '',
			'code' => 'wxapp',
			'need_pay' => $money,
			'create_time' => NOW_TIME,
			'create_ip' => get_client_ip(),
			'is_paid' => 0
		);
		$log_id = D('Paymentlogs')->add($logs);
		
        $Payment = D('Payment')->getPayment('wxapp');
		$out_trade_no = $log_id.'-'.time();
	
		
        $weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,'贴吧发帖支付',$money);//支付接口
        $return = $weixinpay->pay();
		$return['weixin_param']['timeStamp'] = $return['timeStamp'];
		$return['weixin_param']['nonceStr'] =$return['nonceStr'];
		$return['weixin_param']['paySign'] = $return['paySign'];
		
		$return['logs'] = $logs;
		$return['log_id'] = $log_id;
		echo json_encode($return);
	}
	
	//保存日志
	public function SaveTzPayLog(){
		$this->ajaxReturn(array('code'=>'0','msg'=>'更新成功'));
	}
	
     //信息发帖
	public function ThreadPosting(){
		
		$data['log_id'] = I('log_id','','trim');//支付ID
		$data['lat'] = I('lat','','trim,htmlspecialchars');
		$data['lng'] = I('lng','','trim,htmlspecialchars');
		
		$school_id = I('schoolId','','trim');
		
		$data['school_id'] = $school_id ? $school_id : $this->config['site']['school_id'];
		
		$type = I('type','','trim,htmlspecialchars');
		if($type == 1 ){
			$day = 1;
		}elseif($type == 2){
			$day = 7;
		}elseif($type == 3){
			$day = 30;
		}else{
			$day = 0;
		}
		
		if($day > 0){
			$data['top_num'] = $day;
			$data['top_date'] = date('Y-m-d',NOW_TIME + $day * 86400);
			$data['is_top'] = 1;
		}
		
		$data['money'] = I('money','','trim,htmlspecialchars');
		$data['thread_id'] = I('type2_id','','trim,htmlspecialchars');
		$data['cate_id'] = I('type_id','','trim,htmlspecialchars');
		
		
		if($this->city_id){
			$city_id = 	$this->city_id;
		}else{
			$city_id = 	$this->config['site']['city_id'] ? $this->config['site']['city_id'] : 1;
		}
		$data['city_id'] = $city_id;
		
		
		
		$data['mobile'] = I('user_tel','','trim,htmlspecialchars');
		$data['address'] = I('address','','trim,htmlspecialchars');//地址
		$data['audit'] = (int)$this->config['wxapp']['is_thread_fabu_audit'];//后台配置是否审核
		$data['create_time'] = NOW_TIME;
		$data['create_ip'] = get_client_ip();
		$data['user_id'] = I('user_id','','trim');//会员
		
		$details = I('details','','trim,htmlspecialchars');//内容
	    $details2 = $details;//内容
	   
		$sz = I('sz','','trim,htmlspecialchars');//获取josn数据
		$a = json_decode(html_entity_decode($sz));//转义
		$sz2 = json_decode(json_encode($a),true);//转化数组
		if($sz2){
			foreach($sz2 as $val) {
				$label_ids[$val['label_id']] = $val['label_id'];
			}
			$tag = implode(',', $label_ids);
			$data['tag'] = $tag;
		}
			
			
		$data['title'] = niuMsubstr($details,0,60,false);//标题
		

		if($words = D('Sensitive')->checkWords($data['title'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'抱歉，帖子中含有敏感词：' . $words));
		} 
		
		
		
		//计算重复
		$post = M('thread_post')->where(array('user_id'=>$data['user_id'],'details'=>$details2))->find();
		if($post){
			$this->ajaxReturn(array('code'=>'0','msg'=>'请不要重复发布帖子'));
		}
		

		
		if($post_id = M('thread_post')->add($data)){
			
			
			
			$img = I('img','','trim,htmlspecialchars');//图片
			$imgs = @explode(',', $img);
			if($imgs['0']){
				M('thread_post')->where(array('post_id'=>$post_id))->save(array('photo'=>$imgs['0']));
			}
			
			$photos = @array_splice($imgs,1,9); 
			if(!empty($photos)){
				foreach($photos as $val){
					if(isImage($val) && $val != ''){
						$details = $details . '<img src='. config_img($val) .'>';
					}
				}
				foreach($photos as $val){
					M('thread_post_photo')->add(array('post_id'=>$post_id,'photo'=>$val));
				}
				//更新支付日志
				$res = M('payment_logs')->where(array('log_id'=>$data['log_id']))->save(array('order_id'=>$post_id,'city_id'=>$data['city_id']));
				
				M('thread_post')->where(array('post_id'=>$post_id))->save(array('details'=>$details2.''.$details));
			}else{
				M('thread_post')->where(array('post_id'=>$post_id))->save(array('details'=>$details2));
			}
			$this->ajaxReturn(array('code'=>'1','msg'=>'发布信息成功'));
		}
		$this->ajaxReturn(array('code'=>'0','msg'=>'发布信息失败'));
	}
	
	

	
	//我的分类信息
	public function ThreadMyPost(){
		import('ORG.Util.Page3');
		$user_id = I('user_id','','trim');
		
		
		if($user_id == 'undefined'){
			$list = $list ? $list : array();
			$json_str = json_encode($list);
			exit($json_str); 
		}
		
        $map = array('audit'=>1,'closed' =>0,'user_id'=>$user_id);
		
		
		$count = M('ThreadPost')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('page');
       
		$list = M('ThreadPost')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order(array('create_time'=>'desc'))->select();
        foreach($list as $k => $val){
			$Users = M('users')->find($val['user_id']);
			$list[$k]['id'] = $val['post_id'];
		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = (int)$val['is_top'];
			$list[$k]['givelike'] = $val['zan_num'];
			$list[$k]['user_tel'] = $val['mobile'];
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = $Users['nickname'];
			$list[$k]['type_name'] = $this->getListThread($val['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$val['cate_id']))->getField('cate_name');//分类
			$list[$k]['is_renzheng'] = (int)M('running_delivery')->where(array('user_id'=>$val['user_id']))->getField('audit');//是否认证
				
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$val['cate_id']))->select();
			$list[$k]['time'] = $val['create_time'];
			
			if($val['audit'] == 0){
				$state = 1;
			}elseif($val['audit'] ==''){
				$state = 1;
			}elseif($val['audit'] ==1){
				$state = 2;
			}
		
			$list[$k]['state'] = $state;
			
			$list[$k]['sh_time'] = $val['create_time'];
			$list[$k]['img'] = $this->getListPics($val['post_id']);
			$list[$k]['img1'] = $this->getListPics($val['post_id']);
			$list[$k]['details'] = cleanhtml($val['details']);
		}
		
	
		
		if($list){
			foreach($list as $k =>$val){
				$data2[]=array(
				  'tz'=>$list[$k],
				  'label'=>array(),
				 );
			}	
		}
		
		$list = $list ? $list : array();
        $json_str = json_encode($list);
        exit($json_str); 
		
	}

	
	public function ThreadDelPost(){
		$post_id = I('id','','trim');
		$res = M('ThreadPost')->where(array('post_id'=>$post_id))->save(array('closed'=>1));
		
		if($res){
		    exit(json_encode(1));
		}else{
		    exit(json_encode(2));		
		}
	}
	
	//获取贴吧分类信息频道有问题
	public function getListThread($thread_id){
		$thread = M('Thread')->where(array('thread_id'=>$thread_id))->find();
		return $thread['thread_name'] ? $thread['thread_name'] : '未知分类';
	}
	
	
	
	//获取贴吧分类信息列表图片开始
	public function getListPics($post_id){
		$list = M('ThreadPostPhoto')->where(array('post_id'=>$post_id))->select();
		if($list){
			foreach($list as $k => $val){
				$photos[$k] = config_weixin_img($val['photo']);;
			}
		}
		$thread_post = M('ThreadPost')->find($post_id);
		if($thread_post['photo']){
			$photo = config_weixin_img($thread_post['photo']);
			if($photos){
				array_unshift($photos,$photo);
			}else{
				$photos[] = $photo;
			}
			
		}
		if($photos){
			$res = implode(",",$photos);
			return "".$res ."";	
		}else{
			return "";	
		}
	}
	
	
	
	
	//顺风车广告
	public function CarAd(){
		
		$school_id = I('schoolId','','trim');
		
		$site_id = $this->config['wxapp']['pinche_site_id'] ? $this->config['wxapp']['pinche_site_id'] : '57';
		if($school_id){
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0','school_id'=>$school_id))->select();
		}else{
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0'))->select();
		}
		 
		
		foreach ($list as $k => $val){
			$list[$k]['type'] = 4;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	
	//GetUserInfo 更新会员
	public function GetUserInfo(){
		$getallheaders = $this->getallheaders();
		$user_id = $getallheaders['Sp-Session-Id'];
		$school_id = $getallheaders['Sp-School-Id'];
		
		$request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true); 
		
		$Users = M('Users')->find($user_id);
		
		$res['user_id'] = $user_id;
		$res['face'] = $data['AvatarUrl'];
		$res['nickname'] = $data['NickName'] ? $data['NickName'] : $Users['nickname'];//解决昵称问题
		$res['Gender'] = $data['Gender'];
		M('Users')->save($res);
		
		M('Connect')->where(array('type'=>'weixin','uid'=>$user_id))->save(array('nickname'=>$res['nickname'],'headimgurl'=>$res['face']));
		
		$this->ajaxReturn(array('ErrorCode'=>'','ErrorMessage'=>'','IsSuccess'=>true,'Data'=>json_encode($Data)));
	}
	
	
	//拼车标签
	public function CarTag(){
		$typename = I('typename','','trim');
		
		$res['label']= array();
		$time = time()+1800;
		$res['time']= date("H:i",$time);
        $json_str = json_encode($res);
        exit($json_str); 
	}
	
	
	//拼车列表
	public function CarList(){
		
	
		$school_id = I('schoolId','','trim');
		
		import('ORG.Util.Page3');
        $map = array('closed'=>0,'start_time'=>array('EGT',TODAY),'school_id'=>$school_id);
		
		if($user_id = I('user_id','','trim')){
            $map['user_id'] = $user_id;
        }
		
		
		
		$count = M('pinche')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $page = I('page');
		
		//p($count);die;
		
        if($Page->totalPages < $page){
            $json_str = json_encode(array());
        	exit($json_str); 
        }
		
		$list = M('pinche')->where($map)->order(array('start_time'=>'asc','top_time'=>'desc','create_time' =>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
		
		//p($list);die;
		
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['pinche_id'];
			if($val['cate_id'] == 1){
				$num = $val['num1'] ? $val['num1'] :'1';
			}elseif($val['cate_id'] == 2){
				$num = $val['num2'] ? $val['num2'] :'2';
			}elseif($val['cate_id'] == 3){
				$num = $val['num3'] ? $val['num3'] :'3';
			}elseif($val['cate_id'] == 4){
				$num = $val['num4'] ? $val['num4'] :'4';
			}
			
			//判断时间是否过期
			
			$svctime = $val['start_time'].''.$val['start_time_more'];
			if(false == strtotime($val['start_time_more'])){
				$svctime = $val['start_time'];
			}else{
				$svctime = $val['start_time'].''.$val['start_time_more'];
			}
		
		
			$time = strtotime($svctime)+600;
			$list[$k]['is_open'] = ($time < time()) ? 2 : '1';
			
			
			$list[$k]['class3'] = $num;
			$list[$k]['num'] = $val['num'];
			$list[$k]['start_place'] = $val['goplace'] ? $val['goplace'] : '未知';
			$list[$k]['end_place'] = $val['toplace'];
			$list[$k]['link_tel'] = $val['mobile'];
			$list[$k]['other'] = $val['details'];
			$list[$k]['typename'] = $this->getPincheCate[$val['cate_id']];
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['times'] = date('Y-m-d H:i:s', $val['create_time'] ? $val['create_time'] : time());
			$list[$k]['start_time1'] = $val['create_time'];
			$list[$k]['start_time2'] = $val['create_time'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
		}


		if($list){
			$list = list_sort_by($list,'is_open', $by = 'asc');	
		}
	
		
		foreach($list as $k => $val){
			$data2[]=array(
			  'tz'=>$list[$k],
			  'label'=>array(),
			 );
		}
		
        $json_str = json_encode($data2);
        exit($json_str); 
		
	}
	
	//我的拼车
	public function MyCar(){
		
		import('ORG.Util.Page3');
		
        $map = array('closed' => 0);
		if($user_id = I('user_id','','trim')){
            $map['user_id'] = $user_id;
        }
		
		
		$count = M('pinche')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('page');
        if($Page->totalPages < $p){
            $json_str = json_encode(array());
        	exit($json_str); 
        }
		
		$list = M('pinche')->where($map)->order('pinche_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['pinche_id'];
			if($val['cate_id'] == 1){
				$num = $val['num1'] ? $val['num1'] :'1';
			}elseif($val['cate_id'] == 2){
				$num = $val['num2'] ? $val['num2'] :'2';
			}elseif($val['cate_id'] == 3){
				$num = $val['num3'] ? $val['num3'] :'3';
			}elseif($val['cate_id'] == 4){
				$num = $val['num4'] ? $val['num4'] :'4';
			}
			
			//判断时间是否过期
			$svctime = $val['start_time'].''.$val['start_time_more'];
			if(false == strtotime($val['start_time_more'])){
				$svctime = $val['start_time'];
			}else{
				$svctime = $val['start_time'].''.$val['start_time_more'];
			}
			//拼车的时间
			$time = strtotime($svctime)+600;
			
			
			
			$list[$k]['is_open'] = ($time < time()) ? 2 : '1';
			
			$list[$k]['num'] = $val['num'];
			$list[$k]['start_place'] = $val['goplace'] ? $val['goplace'] : '未知';
			$list[$k]['end_place'] = $val['toplace'];
			$list[$k]['link_tel'] = $val['mobile'];
			$list[$k]['link_name'] = $val['name'] ? $val['name'] : '未知';
		
			$list[$k]['other'] = $val['details'];
			$list[$k]['typename'] = $this->getPincheCate[$val['cate_id']];
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['times'] = date('Y-m-d H:i:s', $val['create_time'] ? $val['create_time'] : time());
			$list[$k]['start_time1'] = $val['create_time'];
			$list[$k]['start_time2'] = $val['create_time'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
		}
	
		$list = $list ? $list : array();
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	
	
	//拼车详情
	public function CarInfo(){
		$pinche_id = I('id','','trim');
		$detail = M('pinche')->find($pinche_id);
		$detail['img'] = config_weixin_img($detail['photo']);
		$detail['typename'] = $this->getPincheCate[$detail['cate_id']];
		$detail['start_time1'] = $detail['create_time'];
		$detail['start_time2'] = $detail['create_time'];
		$detail['time'] = $detail['create_time'];
		$Users = M('users')->find($detail['user_id']);
		$detail['user'] = $Users;
		$detail['user_name'] = config_user_name($Users['nickname']);
		$detail['user_img'] = config_weixin_img($Users['face']);
		if($detail['cate_id'] == 1){
			$num = $detail['num1'] ? $detail['num1'] :'1';
		}elseif($detail['cate_id'] == 2){
			$num = $detail['num2'] ? $detail['num2'] :'2';
		}elseif($detail['cate_id'] == 3){
			$num = $detail['num3'] ? $detail['num3'] :'3';
		}elseif($detail['cate_id'] == 4){
			$num = $detail['num4'] ? $detail['num4'] :'4';
		}
		
		//判断时间是否过期
		$svctime = $detail['start_time'].''.$detail['start_time_more'];
		$time = strtotime($svctime)+600;
		$detail['is_open'] = ($time < time()) ? 2 : '1';
	
		
		
		$detail['num'] = $detail['num'];
		$detail['start_place'] = $detail['goplace'] ? $detail['goplace'] : '未知';
		$detail['tj_place'] = $detail['middleplace'] ? $detail['middleplace'] : '未填写';
		$detail['end_place'] = $detail['toplace'];
		$detail['link_tel'] = $detail['mobile'];
		$detail['other'] = $detail['details'];
			
			
 		$data['pc']=$detail;
     	$data['tag']=array();
        $json_str = json_encode($data);
        exit($json_str); 
		
	}


	//拼车列表2
	public function TypeCarList(){
		
		$getallheaders = $this->getallheaders();
		$school_id = $getallheaders['Sp-School-Id'];
		
		import('ORG.Util.Page3');
		$typename = I('typename','','trim,htmlspecialchars');
		$cate_id = array_search($typename,$this->getPincheCate);
		
		
        $map = array('closed' =>0,'cate_id'=>$cate_id);
		$count = M('pinche')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $var = 'page';
        $p = I('page');
		
        if($Page->totalPages < $p){
            $json_str = json_encode(array());
        	exit($json_str); 
        }
		
		
		$list = M('pinche')->where($map)->order('pinche_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['pinche_id'];
			$list[$k]['is_opens'] = ($val['start_time'] >= TODAY) ? 1 : '2';
			$list[$k]['start_place'] = $val['goplace'] ? $val['goplace'] : '未知';
			$list[$k]['end_place'] = $val['toplace'];
			$list[$k]['link_tel'] = $val['mobile'];
			$list[$k]['other'] = $val['details'];
			if($val['cate_id'] == 1){
				$num = $val['num1'] ? $val['num1'] :'1';
			}elseif($val['cate_id'] == 2){
				$num = $val['num2'] ? $val['num2'] :'2';
			}elseif($val['cate_id'] == 3){
				$num = $val['num3'] ? $val['num3'] :'3';
			}elseif($val['cate_id'] == 4){
				$num = $val['num4'] ? $val['num4'] :'4';
			}
			$list[$k]['num'] = $num;
			$list[$k]['other'] = $val['details'];
			
			$list[$k]['is_open'] = $val['closed'];
			$list[$k]['typename'] = $this->getPincheCate[$val['cate_id']];
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['start_time1'] = $val['create_time'];
			$list[$k]['start_time2'] = $val['create_time'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
		}
		foreach($list as $k => $val){
			$data2[]=array(
			  'tz'=>$list[$k],
			  'label'=>array(),
			 );
		}
		
        $json_str = json_encode($data2);
        exit($json_str); 
		
	}

	//发布拼车
	public function car(){
		
		//支付日志
		$data['log_id'] = I('log_id','','trim,htmlspecialchars');
		$data['num'] = I('num','','trim,htmlspecialchars');
		
		$school_id = I('school_id','','trim,htmlspecialchars');
		$data['school_id'] = $school_id;
		
		$typename = I('typename','','trim,htmlspecialchars');
		$cate_id = I('cate_id','','trim,htmlspecialchars');
		$data['cate_id'] =$cate_id;
		if(empty($data['cate_id'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'类型错误'));
        }
		
		
		$data['city_id'] = I('city_id','','trim') ? I('city_id','','trim') : $this->config['site']['city_id'];
        
		$data['user_id'] = I('user_id','','trim');		
        $data['start_time'] = I('start_time','','trim,htmlspecialchars');
       
		
		$data['start_time_more'] = I('start_time_more','','trim,htmlspecialchars');
		if(empty($data['start_time_more'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'出发时间不能为空'));
        }
			
			
		//判断时间是否过期
		$svctime = $data['start_time'].''.$data['start_time_more'];
		$time = strtotime($svctime);
		
		$time2 = time() + 900;
		
	    $cha = $time2 - $time;
		
		if($time < $time2){
			//$this->ajaxReturn(array('code'=>'0','msg'=>'时间已过期'));
		}
		
		
		
		$data['goplace'] = I('start_place','','trim,htmlspecialchars');
        if(empty($data['goplace'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'出发地不能为空'));
        }
        $data['toplace'] = I('end_place','','trim,htmlspecialchars');
        if(empty($data['toplace'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'目的地不能为空'));
        }
		$data['middleplace'] = I('tj_place','','trim,htmlspecialchars');
		$data['num_1'] = I('num','','trim');
		$data['num_2'] = I('num','','trim');
		$data['num_3'] = I('num','','trim');
		$data['num_4'] = I('num','','trim');
		
		$data['name'] = I('link_name','','trim,htmlspecialchars');
        $data['mobile'] = I('link_tel','','trim,htmlspecialchars');
		if(empty($data['mobile'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'手机不能为空'));
        }
        if(!ismobile($data['mobile'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'手机格式不正确'));
        }
		
		
		$data['name'] = I('link_name','','trim,htmlspecialchars');
		$data['details'] = I('other','','trim,htmlspecialchars') ? I('other','','trim,htmlspecialchars') : '未填写说明';
		$data['star_lat'] = I('star_lat','','trim,htmlspecialchars');
        $data['star_lng'] = I('star_lng','','trim,htmlspecialchars');
        $data['end_lat'] = I('end_lat','','trim,htmlspecialchars');
        $data['end_lng'] = I('end_lng','','trim,htmlspecialchars');
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] =  get_client_ip();
	
        if($pinche_id = M('pinche')->add($data)){
			$this->ajaxReturn(array('code'=>'1','msg'=>'发布成功'));
        }
		$this->ajaxReturn(array('code'=>'0','msg'=>'发布失败'));
	}
	
	
	
	//拼车支付
	public function pinchePay(){
		
		include APP_PATH . 'Lib/Action/App/wxpay.php';
		$user_id = I('user_id');
		$openid = I('openid');
		$money = I('money')*100;
		$schoolId= I('schoolId','','trim');
		
		$openid = D('Connect')->getWxappOpenid($user_id,$openid,'pinche');
		
		
		$logs = array(
			'type' =>'pinche',
			'user_id' => $user_id,
			'school_id' =>$schoolId,
			'order_id' => '',
			'code' => 'wxapp',
			'need_pay' => $money,
			'create_time' => NOW_TIME,
			'create_ip' => get_client_ip(),
			'is_paid' => 0
		);
		$log_id = D('Paymentlogs')->add($logs);
		
        $Payment = D('Payment')->getPayment('wxapp');
		$out_trade_no = $log_id.'-'.time();
	
		
        $weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,'拼车发布支付',$money);//支付接口
        $return = $weixinpay->pay();
		$return['weixin_param']['timeStamp'] = $return['timeStamp'];
		$return['weixin_param']['nonceStr'] =$return['nonceStr'];
		$return['weixin_param']['paySign'] = $return['paySign'];
		
		$return['log_id'] = $log_id;
		echo json_encode($return);
	}
	
	//删除拼车
	public function DelCar(){
		$car_id = I('car_id','','trim');
		if($car_id){
			$res = M('pinche')->where(array('pinche_id'=>$car_id))->delete();
			echo 1;
		}else{
			echo '删除失败';
		}
	}
	
	
	//拼团广告
	public function collageAd(){
		
		$school_id = I('schoolId','','trim');
		
		$site_id = $this->config['wxapp']['collage_site_id'] ? $this->config['wxapp']['collage_site_id'] : '86';
		if($school_id){
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0','school_id'=>$school_id))->select();
		}else{
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0'))->select();
		}
	
		foreach ($list as $k => $val){
			$list[$k]['type'] = 13;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
		$list = $list? $list : array();
        $json_str = json_encode($list);
        exit($json_str);
		
	}
	
	
	//拼团分类
     public function GroupType(){
		
	
		$res=M('group_type')->order(array('num ASC'))->select();
		foreach($res as $k => $val){
			$res[$k]['img'] = config_weixin_img($val['img']);
		}
		$res = $res ? $res : array();
		echo json_encode($res);
     }


	 //保存拼团商品
     public function SaveGroupGoods(){
        	
		$shop_id = I('store_id','','trim');
		$id = I('id','','trim');
		$uniacid = I('uniacid','','trim');

		$store = M('shop')->find($shop_id);
		
		$data['state']=1;//拼团开启审核
		
		
		$data['logo']=I('logo','','trim');
		$data['name']=I('name','','trim');
		$data['img']=I('img','','trim');
		$data['type_id']=I('type_id','','trim');
		$data['inventory']=I('inventory','','trim');
		$data['start_time']=strtotime(I('start_time','','trim'));
		$data['end_time']=strtotime(I('end_time','','trim'));
		$data['xf_time']=strtotime(I('xf_time','','trim'));		
		$data['pt_price']=I('pt_price','','trim');
		$data['y_price']=I('y_price','','trim');
		$data['dd_price']=I('dd_price','','trim');
		$data['ycd_num']=I('ycd_num','','trim');
		$data['ysc_num']=I('ysc_num','','trim');
		$data['people']=I('people','','trim');
		$data['is_shelves']=I('is_shelves','','trim');
		$data['store_id']=I('store_id','','trim');
		$data['details']=html_entity_decode(I('details','','trim'));
		$data['details_img']=I('details_img','','trim');
		$data['num']=I('num','','trim');
		$data['introduction']=I('introduction','','trim');
		$data['time']=time();
		$data['cityname']=I('cityname','','trim');
		$data['uniacid']=$uniacid;
		
		if($id){
			$res=M('group_goods')->where(array('id'=>$id))->save($data);
		}else{
			$res=M('group_goods')->add($data);
		}	
		if($res){
			echo '1';
		}else{
			echo '2';
		}
	}

	
	//拼团商品
	public function GroupGoods(){

		$uniacid = I('uniacid','','trim');
		$type_id = I('type_id','','trim');
		$shop_id = I('store_id','','trim');
		$display = I('display','','trim');
		$cityname = I('cityname','','trim');//城市
		
		$p = I('page','','trim');
		$pagesize = I('pagesize','','trim');
		
		import('ORG.Util.Page3');
		$time=time();
		
		$map = array('is_shelves'=> 1,'state'=>2,'end_time' => array('EGT',$time));
		if($type_id){
			$map['type_id'] = $type_id;
		}
		if($shop_id){
			$map['shop_id'] = $shop_id;
		}
		if($display){
			$map['display'] = $display;
		}
		
		$count =M('group_goods')->where($map)->count();
		$Page = new Page($count,5);
		$show = $Page->show();
		if($Page->totalPages < $p){
            $json_str = json_encode(array());
        	exit($json_str); 
        }
		
		$list = M('group_goods')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['shop'] = M('shop')->find($val['shop_id']);
			$list[$k]['logo'] = config_weixin_img($val['logo']);
			
			$res = $this->UpdateGroup($val['goods_id']);//更新拼团状态传商品ID
		}
		
		$list = $list ? $list : array();
		echo json_encode($list);
	}
	
	
	//拼团获取列表图片开始
	public function getShopListDetailsPics($shop_id){
		$list = M('shop_pic')->where(array('shop_id'=>$shop_id,'audit'=>1))->limit(0,30)->select();
		if($list){
			foreach($list as $k => $val){
				$photos[$k] = config_weixin_img($val['photo']);;
			}
		}
		$shop = M('shop')->find($shop_id);
		if($shop['photo']){
			$photo = config_weixin_img($shop['photo']);
			if($photos){
				array_unshift($photos,$photo);
			}else{
				$photos[] = $photo;
			}
			
		}
		return $photos;	
	}
	
	
	
	//拼团商家详情
	public function StoreInfo(){
		$shop_id = I('id','','trim');
		$detail = M('shop')->find($shop_id);
		$detail['id'] = $detail['shop_id'];
		$detail['store_name'] = $detail['shop_name'];
		$detail['vr_link'] = $detail['panorama_url'];
		$detail['address'] = $detail['addr'];
		$detail['fx_num'] = (int)M('ShopFavorites')->where(array('shop_id'=>$detail['shop_id']))->count();
		$detail['views'] = $detail['view'];
		$detail['coordinates'] = $detail['lat'].','.$detail['lng'];
		$details = M('shop_details')->where(array('shop_id'=>$shop_id))->find();
		
		$detail['start_time'] = '营业时间';
		$detail['end_time'] = $details['business_time'] ? $details['business_time'] : '全天营业';
		
		$detail['details'] = $details['details'];
		$detail['detail'] = $details; 
		$detail['img'] = $this->getShopListDetailsPics($detail['shop_id']);//商家图片获取
		
		$detail['announcement'] = cleanhtml($details['details'],120,false); 
		
		
		$detail['logo'] = config_weixin_img($detail['photo']);
		$detail['photo'] = config_weixin_img($detail['photo']);
		$detail['qrcode'] = config_weixin_img($detail['qrcode']);
		$detail['weixin_logo'] = config_weixin_img($detail['qrcode']);
		
		$detail['skzf'] = 1; //刷卡支付
		$detail['wifi'] = 1; //wifi
		
	
		$data['store'][]=$detail;
		
        $data['pl']= array();
	    echo json_encode($data);
	}
	
	
	//拼团商品详情
	public function  GoodsInfo(){
		
		
		$uniacid = I('uniacid','','trim');
		$goods_id = I('goods_id','','trim');
		
		
		$res = $this->UpdateGroup($goods_id);//更新拼团状态传商品ID
		
		$goods = M('group_goods')->find($goods_id);
		$goods['shop'] = M('shop')->find($goods['shop_id']);
		$goods['logo'] = config_weixin_img($goods['logo']);
		
		
		$thumb = unserialize($goods['details_img']);
		if($thumb){
			foreach($thumb as $k => $v){
				$thumb[$k] = config_weixin_img($v);
			}
		}
		$photo = config_weixin_img($res['photo']);
		if($thumb){
			array_unshift($thumb,config_weixin_img($res['photo']));
		}else{
			$thumb[] = $photo ;
		}
		$thumb = implode(",",$thumb);
		$thumb =  "".$thumb ."";
		$goods['img'] = $thumb;
		
		
		$group = M('group')->where(array('goods_id'=>$goods_id))->select();
		foreach($group as $k => $val){
			$users = M('users')->find($val['user_id']);
			$group[$k]['name'] = $users['nickname'];
			$group[$k]['num'] = $val['kt_num'] - $val['yg_num'];
			$group[$k]['img'] = config_weixin_img($users['face']);
		}
		
		
		$goods['user'] = M('users')->find($group['user_id']);	
		$goods['img'] = config_weixin_img($goods['logo']);

			
		$goodsInfo['goods']=$goods;
		$goodsInfo['group']=$group;
		echo json_encode($goodsInfo);
	}
	
	
	//拼团详情
	public function GroupInfo(){
		$group_id=I('group_id','','trim');
		
		$group = M('group')->find($group_id);
		$users = M('users')->find($group['user_id']);
		$group['name'] = $users['nickname'];
		$group['img'] = config_weixin_img($users['face']);
		$group['user'] = M('users')->find($group['user_id']);
		$group = $group ? $group : array();
		echo json_encode($group);
	
	}
	
	//查看团员信息
	public function GetGroupUserInfo(){
		$group_id=I('group_id','','trim');
		
		$group = M('group_order')->where(array('group_id'=>$group_id))->select();
		foreach($group as $k => $val){
			$users = M('users')->find($val['user_id']);
			$group[$k]['name'] = $users['nickname'];
			$group[$k]['img'] = config_weixin_img($users['face']);
		}
		$group = $group ? $group : array();
		echo json_encode($group);
	}
	
	
	//下单
	public function SaveGroupOrder(){
		$uniacid = I('uniacid','','trim');
		$goods_id = I('goods_id','','trim');
		$goods_num = I('goods_num','','trim');
		$type = I('type','','trim');
		$group_id = I('group_id','','trim');
		
		$school_id = I('school_id','','trim');
	
		
		$data['user_id']=I('user_id','','trim');
		if(!$data['user_id']){
			echo '商品已销售完毕或拼团已失效';die;
		}
		
		$good= M('group_goods')->find($goods_id);	
		
		if($good['inventory']>=$goods_num ){
			if($type ==1){
				$data['order_num']=date('YmdHis',time()).rand(1111,9999);//订单号
				$data['user_id']=I('user_id','','trim');
				$data['goods_id']=I('goods_id','','trim');
				$data['group_id']=0;
				$data['school_id']=$school_id;
				$data['logo']=I('logo','','trim');
				$data['store_id']=I('store_id','','trim');
				$data['shop_id']=I('store_id','','trim');
				$data['goods_name']=I('goods_name','','trim');
				$data['goods_type']=I('goods_type','','trim');
				$data['goods_name']=I('goods_name','','trim');
				$data['price']=I('price','','trim');
				$data['goods_num']=I('goods_num','','trim');
				$data['money']=I('money','','trim');
				$data['receive_name']=I('receive_name','','trim');
				$data['receive_tel']=I('receive_tel','','trim');
				$data['receive_addr']=I('receive_addr','','trim');
				$data['receive_address']=I('receive_address','','trim');
				$data['note']=I('note','','trim');
				$data['time']=time();
				$data['xf_time']=I('xf_time','','trim');
				$data['uniacid']=$uniacid;
				$data['pay_type']=I('pay_type','','trim');
				$data['state']=1;
				$res= M('group_order')->add($data);
				if($res){
					echo $res;
				}else{
					echo '下单失败';
				}
		}
		if($type==2){
			//生产团
			if($group_id ==''){
				$data2['store_id']=I('store_id','','trim');
				$data2['shop_id']=I('store_id','','trim');
				$data2['school_id']=$school_id;
				$data2['goods_id']=I('goods_id','','trim');
				$data2['goods_logo']=I('goods_logo','','trim');
				$data2['goods_name']=I('goods_name','','trim');
				$data2['kt_num']=I('kt_num','','trim');		
				$data2['kt_time']=time();
				$data2['dq_time']=I('dq_time','','trim');
				$data2['state']=0;
				$data2['user_id']=I('user_id','','trim');
				$data2['uniacid']=$uniacid;
				
				$rst= M('group')->add($data2);
				$group_ids=$rst;
			}else{
				$group= M('group')->where(array('id'=>$group_id))->find();
			}
		
		
			
			if($group_id==''&&$rst or $group_id&&$group['state']==1){
				$data['order_num']=date('YmdHis',time()).rand(1111,9999);//订单号
				$data['user_id']=I('user_id','','trim');
				$data['goods_id']=I('goods_id','','trim');
				$data['group_id']=$group_id?$group_id:$group_ids;
				$data['logo']=I('logo','','trim');	
				$data['store_id']=I('store_id','','trim');	
				$data['school_id']=$school_id;
				$data['shop_id']=I('store_id','','trim');	
				$data['goods_name']=I('goods_name','','trim');	
				$data['goods_type']=I('goods_type','','trim');	
				$data['goods_name']=I('goods_name','','trim');	
				$data['price']=I('price','','trim');	
				$data['goods_num']=I('goods_num','','trim');	
				$data['money']=I('money','','trim');	
				$data['receive_name']=I('receive_name','','trim');	
				$data['receive_tel']=I('receive_tel','','trim');	
				$data['receive_address']=I('receive_address','','trim');	
				$data['note']=I('note','','trim');	
				$data['time']=time();
				$data['xf_time']=I('xf_time','','trim');	
				$data['uniacid']=$uniacid;
				$data['pay_type']=I('pay_type','','trim');	
				$data['state']=1;
				$res= M('group_order')->add($data);
			if($res){
				echo $res;
			}else{
				echo '下单失败';
			}
		}else{
			//没有剩余
			echo '商品已销售完毕或拼团已失效';
		}
	}
	}else{
		echo '商品已销售完毕或拼团已失效';
	}
	
	}
	
	//拼团支付
	public function GroupPay(){
		include APP_PATH . 'Lib/Action/App/wxpay.php';
		$order_id = I('order_id');
		$openid = I('openid');
		
		$school_id = I('school_id','','trim');//学校
		
        $Payment = D('Payment')->getPayment('wxapp');
		$body = "拼团订单".$order_id."付款";
		
		$order =M('group_order')->find($order_id);
		$shop =M('shop')->find($order['shop_id']);
		
		
		$money = I('money');
		
		
		$logs = D('Paymentlogs')->getLogsByOrderId('group',$order_id);
        if(empty($logs)){
            $logs = array(
				'type' => 'group',
				'user_id' => $order['user_id'], 
				'shop_id' => $shop['shop_id'], 
				'city_id' => $shop['city_id'], 
				'school_id' => $school_id, 
				'area_id' => $shop['area_id'], 
				'business_id' => $shop['business_id'], 
				'order_id' => $order_id, 
				'code' => 'wxapp', 
				'need_pay' => $money * 100, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(), 
				'is_paid' => 0
			);
            $logs['log_id'] = D('Paymentlogs')->add($logs);
        }else{
            $logs['need_pay'] = $money * 100;
            $logs['code'] = 'wxapp';
            D('Paymentlogs')->save($logs);
        }
		
		
		$out_trade_no = $logs['log_id'].'-'. time();
		
		$openid = D('Connect')->getWxappOpenid($order['user_id'],$openid,'group');
		
		//应该有问题
        $weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,$body,$money*100);//支付接口
		
        $return = $weixinpay->pay();
		$return['weixin_param']['timeStamp'] = $return['timeStamp'];
		$return['weixin_param']['nonceStr'] =$return['nonceStr'];
		$return['weixin_param']['paySign'] = $return['paySign'];
		
		$return['log_id'] = $logs['log_id'];
		$return['order_id'] = $order_id;
		
		echo json_encode($return);
	}
	
	
	//拼团分销二维码
	public function GoodsCode(){
		$goods_id = I('goods_id', 0, 'trim,intval');
		$group_id = I('group_id', 0, 'trim,intval');
		$id = $goods_id.'&'.$group_id;
		$page="pages/collage/info";
		$width = '400';
		$res = D('Api')->qrcodeWxapp($id,$page,$width,$parameter='groupId',$goods_id,$id);
		echo $res;
	}
	
	//拼团发送模板消息
	public function PtMessage(){
		$group_id=I('group_id','','trim');
		$order = M('group_order')->where(array('group_id'=>$group_id))->select();
		foreach($order as $key => $value){
			$this->setPtMessage($value['user_id'],$group_id,$value['order_num'],$value['goods_name'],$value['goods_num'],$value['price'],$value['pay_time']);
		}
		return true;
	}
	
	//拼团发送模板消息
	public function setPtMessage($user_id,$group_id,$order_num,$goods_name,$goods_num,$price,$pay_time){
		
		$group=M('group_order')->where(array('id'=>$group_id))->find();
		$store=M('shop')->where(array('shop_id'=>$group['store_id']))->find();
		$user=M('users')->where(array('user_id'=>$user_id))->find();
		$time=time()-60*60*24*7;
		$pay_time=date('Y-m-d H:i:s',$pay_time);
		
		
		$group=M('group_order')->where(array('id'=>$group_id))->find();
		
		
		$form=M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('egt',$time)))->find();
		
		$res["pt_tid"] = $this->config['config']['pintuan'];
		
		
		$formwork ='{
			"touser": "'.$user["openid"].'",
			"template_id": "'.$res["pt_tid"].'",
			"page":"pages/collage/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value":"'.$order_num.'",
					"color": "#173177"
				},
				"keyword2": {
					"value": "'.$goods_name.'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$group['kt_num'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value":"'.$goods_num.'",
					"color": "#173177"
				},
				"keyword5": {
					"value":"'.$price.'",
					"color": "#173177"
				},
				"keyword6": {
					"value":"'.$price.'",
					"color": "#173177"
				},
				"keyword7": {
					"value":"'.$pay_time.'",
					"color": "#173177"
				},
				"keyword8": {
					"value":"'.$store['store_name'].'",
					"color": "#173177"
				},
				"keyword9": {
					"value":"'.$store['address'].'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"
		}';
		$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
		$data = curl_exec($ch);
		curl_close($ch);
		$res=M('user_formid')->where(array('id'=>$form['id']))->delete();
	}

	
	//我的团购拼团订单
	public function MyGroupOrder(){
		
		M('group_order')->where(array('xf_time'=>array('elt',time()),'state'=>2))->save(array('state'=>5,'cz_time'=>time()));//更新
		
		
		import('ORG.Util.Page3');
		$user_id = I('user_id', 0, 'trim,intval');
		$state = I('state', 0, 'trim,intval');
		$type = I('type', 0, 'trim,intval');
		$page = I('page', 0, 'trim,intval');
		$pagesize = I('pagesize', 0, 'trim,intval');
		
		$map = array('user_id' =>$user_id,'state'=>array('neq',1));
	
		if($state){
			$map['state'] = $state;
		}
		if($type){
			//单独够订单
			$map['type'] = $type;
		}
		$count = M('group_order')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = $page;
		
		$list = M('group_order')->where($map)->order(array('id'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['group'] = M('group')->find($val['group_id']);
			$list[$k]['shop'] = M('shop')->find($val['shop_id']);
			$list[$k]['goods_name'] = $val['goods_name'];
			$list[$k]['store_name'] = $list[$k]['shop']['shop_name'];
			$list[$k]['pay_time'] = $val['time'] ?  $val['time'] :  $val['pay_time'];
			$list[$k]['goods_cost'] = round($val['mall_price']/100,2);
			$list[$k]['lb_imgs'] = config_weixin_img($val['photo']);
		}
		
		
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	
	//拼团订单详情
	public function GroupOrderInfo(){
		$order_id = I('order_id', 0, 'trim,intval');
		$detail = M('group_order')->find($order_id);
		$detail['group'] = M('group')->find($detail['group_id']);
		echo json_encode($detail);
	}
	
	
	//拼团订单二维码
	public function OrderCode(){
		$order_id = I('order_id', 0, 'trim,intval');
		$page="pages/collage/yz_code";
		$width = '400';
		$res = D('Api')->qrcodeWxapp($order_id,$page,$width,$parameter='groupOrderId',$order_id);
		echo $res;
	}
	
	
	//拼团核销订单
	public function GroupVerification(){
		$order_id = I('order_id', 0, 'trim,intval');
		$store_id = I('store_id', 0, 'trim,intval');
		$user_id= I('user_id', 0, 'trim,intval');
		
		$order=M('group_order')->find($order_id);
		$store=M('shop')->find($store_id);
		
		
		if($order['store_id']==$store_id || $store['user_id']==$user_id){
			if($order['state']==3 or $order['state']==5){
				echo '已经核销过了或订单已失效';
			}else{
				$res=M('group_order')->where(array('id'=>$order_id))->save(array('state'=>3,'cz_time'=>time()));
				if($res){
					echo '核销成功';
				}else{
					echo '核销失败';
				}
			}	
		}else{
			echo '暂无核销权限';
		}
	}
	
	//拼团失败退款
	public function UpdateGroup($id = 0){
		$order_id = I('order_id', 0, 'trim,intval');
		$store_id = I('store_id', 0, 'trim,intval');
		$user_id= I('user_id', 0, 'trim,intval');
		$goods_id= I('goods_id', 0, 'trim,intval');
		
		$goods_id = $goods_id ? $goods_id : $id;
		
	
		$dir = file_exists(BASE_PATH.'/Lib/Payment/cert/apiclient_key.pem');
		if($dir == false){
			$this->error = '证书必须配置';
			return false;
		}
		$payment = D('Payment')->getPayment('wxapp');
		if(!$payment){
			$payment = D('Payment')->getPayment('weixin');
		}
		if(empty($payment['appid'])){
			$this->error = 'appid不能为空';
			return false;
		}
		if(empty($payment['mchid'])){
			$this->error = 'mchid不能为空';
			return false;
		}
		
		if(empty($goods_id)){
			$this->error = 'goods_id不能为空';
			return false;
		}
		
		
		$ids=M('group')->where(array('dq_time'=>array('elt',time()),'state'=>1,'goods_id'=>$goods_id))->select();
		
		
        foreach($ids as $key => $val){
			
			$goods = M('group_goods')->where(array('id'=>$val['goods_id']))->select();
			
			//商品结束时间小于现在的时间
			if($goods['end_time'] <= time()){
				
				
				$orders=M('group_order')->where(array('id'=>$val['group_id'],'state'=>2,'pay_type'=>1))->select();
				
				foreach($orders as $k => $v){
					
					
					$logs = M('PaymentLogs')->where(array('type'=>'group','order_id'=>$v['id'],'is_paid'=>1))->find();
					if(empty($logs['return_trade_no'])){
						$this->error = '商户订单号错误';
						return false;;
					}
					$connect = M('connect')->where(array('type'=>'weixin','uid'=>$logs['user_id']))->find();
					if(empty($connect['openid'])){
						$this->error = '当前会员的openid不存在';
						return false;
					}
			
					include (APP_PATH . 'Lib/Payment/WxPayPubHelper/WxPayPubHelper.php');
					//调用请求接口基类
					$Redpack = new Refund_pub();
					$Redpack->setParameter('transaction_id',$logs['return_trade_no']);//商户订单号
					$Redpack->setParameter('out_trade_no',$logs['return_order_id']);//商户订单号
					$Redpack->setParameter('out_refund_no',$v['id']);//商户退款单号
					$Redpack->setParameter('total_fee',$logs['need_pay']);//订单金额
					$Redpack->setParameter('refund_fee',$logs['need_pay']);//退款金额
					$Redpack->setParameter('op_user_id',$connect['openid']);//操作员，会员的openid
					$Redpack->setParameter('appid', $payment['appid']);
					$Redpack->setParameter('mch_id', $payment['mchid']);
					$result = $Redpack->getResult();
					$result = (array)$result;
					
					//p($result);die;
					if(is_array($result) && $result['result_code'] == 'SUCCESS'){
						//退款成功
						M('group_order')->where(array('id'=>$val['group_id'],'pay_type'=>1))->save(array('state'=>4));
						
						$data['out_refund_no'] = $result['out_refund_no'];//退款单号
						$data['refund_id'] = $result['refund_id'];//微信退款单号
						$data['refund_fee'] = $result['refund_fee'];//退款金额
						$data['settlement_refund_fee'] = $result['settlement_refund_fee'];//应结退款金额
						$data['refund_time'] = time();
						$data['refund_info'] = $info;
						$data['is_paid'] = 4;
						M('PaymentLogs')->where(array('type'=>$type,'order_id'=>$order_id))->save($data);
						return true;
					}else{
						$this->error = '操作失败:原因【'.$result['return_msg'] .''.$result['err_code_des'].'】';
						return false;
					}	
				}
				$group=M('group')->where(array('id'=>$val['id']))->save(array('state'=>3));
			}	
		
		}
		return true;
	}
	
	
    //广告详情
	public function GetAdInfo(){
		$ad_id = I('ad_id', 0, 'trim,intval');
		$ad = M('ad')->find($ad_id);
		$ad['type'] = 4;
		$ad['id'] = $ad['ad_id'];
		$ad['nav_name'] = $ad['title'];
		$ad['img'] = config_weixin_img($ad['photo']);
		$ad['logo'] = config_weixin_img($ad['photo']);
		echo json_encode($ad);
	}
	
	
	
	
	//优惠券广告
	public function couponAd(){
		$school_id = I('schoolId','','trim');
		
		$site_id = $this->config['wxapp']['coupon_site_id'] ? $this->config['wxapp']['coupon_site_id'] : '86';
		if($school_id){
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0','school_id'=>$school_id))->select();
		}else{
			$list = M('ad')->where(array('site_id'=>$site_id,'closed'=>'0'))->select();
		}
	
		foreach($list as $k => $val){
			$list[$k]['type'] = 14;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
		$list = $list? $list : array();
        $json_str = json_encode($list);
        exit($json_str);
		
	}
	
	
	
	//优惠券分类
	public function CouponType(){
		$type_id = I('type_id','','htmlspecialchars');
		$arr = M('ShopCate')->where(array('parent_id'=>$type_id))->limit(0,8)->select();
		$kk = 0;
		foreach($arr as $k => $val){
			$kk ++ ;
			$arr[$k]['type_name'] = $val['cate_name'];
			$arr[$k]['id'] = $val['cate_id'];
			$arr[$k]['map'] = $val['cate_id'];
			$arr[$k]['img'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($arr);
        exit($json_str); 
	}
	
	
	
	
	//优惠券列表
	public function CouponList2(){
		
		$shop_id = I('store_id','','htmlspecialchars');
		$type_id = I('type_id','','htmlspecialchars');
		$schoolId = I('schoolId','','htmlspecialchars');
	
		$page = I('page','','trim');
		$pagesize = I('pagesize','','trim');
		
		import('ORG.Util.Page3');
		$time=time();
		
		$map = array('audit'=>1,'closed'=> 0,'expire_date'=>array('EGT', TODAY));
		if($type_id){
            $catids = D('Shopcate')->getChildren($type_id);
            $map['cate_id'] = array('IN', $catids);
		}	
		
		if($shop_id){
			$map['shop_id'] = $shop_id;
		}
		if($this->city_id){
			$map['city_id'] = $city_id;
		}
		
		
		$count =M('coupon')->where($map)->count();
		$Page = new Page($count,5);
		$show = $Page->show();
		$p = $page;
		
		$list = M('coupon')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['id'] = $val['coupon_id'];
			$list[$k]['store_logo'] = config_weixin_img($val['photo']);
			$Shop = M('shop')->find($val['shop_id']);
			$list[$k]['name'] = $val['title'];
			$list[$k]['store_name'] = $Shop['shop_name'] ? $Shop['shop_name'] : '平台';
			$list[$k]['end_time'] =  $val['expire_date'];
			$list[$k]['reduce'] =  '减'.round($val['reduce_price']/100,2);
			$list[$k]['full'] =  round($val['full_price']/100,2);
			$list[$k]['money'] =  round($val['money']/100,2);
			$list[$k]['surplus'] = $count;
			$list[$k]['store_id'] = $val['shop_id'];
			$list[$k]['number'] = $val['num'];
			$list[$k]['rate'] = $rate;
		}
		$list = $list ? $list :array();
		echo json_encode($list);
		
	}
	
	
	
	//最新领券
	public function ZbCoupons(){
		$res = M('coupon_download')->where(array('is_used'=>0,'closed'=>0))->limit(0,10)->order('download_id desc')->select();
		foreach($res as $k => $val){
			$users = M('Users')->where(array('user_id'=>$val['user_id']))->find();
			$res[$k]['user_name'] = $users['nickname'] ? $users['nickname'] : $users['account'] ? $users['account'] : '网友';
			$res[$k]['time2'] = formatTime($val['create_time']);
			$res[$k]['coupon_name'] = M('coupon')->where(array('coupon_id'=>$val['coupon_id']))->getField('title');
		}
		echo json_encode($res);
	}

	//优惠券详情
	public function CouponInfo(){
		$coupon_id = I('coupon_id','','htmlspecialchars');
		$res = M('coupon')->find($coupon_id);
		$res['id'] = $res['coupon_id'];
		$res['coupon_img'] = config_weixin_img($res['photo']);
		
		$res['name'] =  $res['title'];
		$res['store_name'] = $Shop['shop_name'] ? $Shop['shop_name'] : '平台';
		$res['end_time'] =  $res['expire_date'];
		$res['reduce'] =  round($res['reduce_price']/100,2);
		$res['full'] =  round($res['full_price']/100,2);
		$res['money'] =  round($res['money']/100,2);//购买金额
		
		$res['surplus'] = $res['num'];
		$res['number'] = $res['num'];
		$res['lqrs'] = $res['downloads'];
		$res['rate'] = $rate;
		$res['details'] =  $res['intro'];
		$ShopDetails = M('ShopDetails')->find($res['shop_id']);
		$store['start_time'] = $ShopDetails['business_time'];
		$data['store'] = $store;
		echo json_encode($res);
	}
	
	
	//领取优惠券
	public function LqCoupon(){
		
		$coupon_id = I('coupons_id','','htmlspecialchars');
		$user_id = I('user_id','','htmlspecialchars');
		$lq_money = I('lq_money','','htmlspecialchars');
		
		$detail = M('coupon')->find($coupon_id);
		$Users = M('Users')->find($user_id);
		
		if(!$detail){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券不存在'));
        }
		
		if(!$Users){
			$this->ajaxReturn(array('code'=>0,'msg'=>'会员信息不存在'));
        }
		
		if($detail['expire_date'] < TODAY){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券已过期'));
        }
		if($detail['num'] <= 0){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券没库存了'));
        }
		
		
		$count =(int) M('coupon_download')->where(array('coupon_id'=>$coupon_id,'user_id'=>$user_id,'closed'=>0,'is_used'=>0))->count();
		if($count){
			$this->ajaxReturn(array('code'=>0,'msg'=>'不要重复领取'));
		}
		
        if($detail['limit_num']){
            if($count+1 > $detail['limit_num']){
				$this->ajaxReturn(array('code'=>0,'msg'=>'不能多次领取'));
            }
        }
		
		$code = D('Coupondownload')->getCode();
		
		
		$data = array(
            'user_id' => $user_id,
            'shop_id' => $detail['shop_id'],
            'coupon_id' => $coupon_id,
			'money' => $detail['money'],
			'school_id'=>$detail['school_id'],
			'status' => 0,
            'create_time' => time(),
            'mobile' => $Users['mobile'],
            'create_ip' => get_client_ip(),
            'code' => $code,
			'closed' => $detail['money'] > 0 ? 1 : 0,
        );
		
		
        if($download_id = M('coupon_download')->add($data)){
            D('Coupon')->updateCount($coupon_id, 'downloads');
            D('Coupon')->updateCount($coupon_id,'num',-1);
			$this->ajaxReturn(array('code'=>1,'msg'=>'领取成功','download_id'=>$download_id));
        }
		$this->ajaxReturn(array('code'=>0,'msg'=>'领取失败'));
	}
	
	
	//优惠券支付
	public function Pay5(){
		
		include APP_PATH . 'Lib/Action/App/wxpay.php';
		
		$download_id = I('download_id','','htmlspecialchars');
		$download = M('coupon_download')->where(array('download_id'=>$download_id))->find();
		
		$openid = I('openid','','htmlspecialchars');
		$user_id = I('user_id','','htmlspecialchars');
		$coupon_id = I('coupons_id','','htmlspecialchars');
		$money = I('money')*100;
		
	
	
		$openid = D('Connect')->getWxappOpenid($user_id,$openid,'coupon');
		
		
		
		$logs = array(
			'type' => 'coupon',
			'school_id'=>$download['school_id'],
			'user_id' => $user_id, 
			'order_id' => $download_id, 
			'code' => 'wxapp', 
			'need_pay' => $money, 
			'create_time' => NOW_TIME, 
			'create_ip' => get_client_ip(), 
			'is_paid' => 0
		);
		
		$log_id = D('Paymentlogs')->add($logs);
		
		
        $Payment = D('Payment')->getPayment('wxapp');
		$body = '优惠券付款';
		$out_trade_no = $log_id.'-'.time();
		
		
		//应该有问题
        $weixinpay = new WeixinPay($this->config['wxapp']['appid'],$openid,$Payment['mchid'],$Payment['appkey'],$out_trade_no,$body,$money);//支付接口
		
        $return = $weixinpay->pay();
		$return['weixin_param']['timeStamp'] = $return['timeStamp'];
		$return['weixin_param']['nonceStr'] =$return['nonceStr'];
		$return['weixin_param']['paySign'] = $return['paySign'];
		
		$return['log_id'] = $log_id;
		$return['download_id'] = $download_id;
		
		echo json_encode($return);
	}
	
	
	
	//我的优惠券
	public function MyCoupons(){
		import('ORG.Util.Page3');
		$user_id = I('user_id', 0, 'trim,intval');
		$page = I('page', 0, 'trim,intval');
		$pagesize = I('pagesize', 0, 'trim,intval');
		$map = array('user_id'=>$user_id,'closed'=>0);
		$count = M('coupon_download')->where($map)->count();
        $Page = new Page($count,20);
        $show = $Page->show();
        $p = $page;
		
		$list = M('coupon_download')->where($map)->order('download_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $k => $val){
			$Coupon = M('coupon')->find($val['coupon_id']);
			$list[$k]['id'] = $val['download_id'];
			$list[$k]['coupons_id'] = $val['coupon_id'];
			$list[$k]['store_id'] = $Coupon['shop_id'];
			$list[$k]['store_logo'] = config_weixin_img($Coupon['photo']);
			$Shop = M('shop')->find($Coupon['shop_id']);
			$list[$k]['state'] =  $val['is_used'] == 1 ? 1 : 2;
			$list[$k]['store_name'] = $Shop['shop_name'] ? $Shop['shop_name'] : '平台';
			$list[$k]['coupons_name'] = $Coupon['title'];
			$list[$k]['end_time'] =  $Coupon['expire_date'];
			$list[$k]['reduce'] =  round($Coupon['reduce_price']/100,2);
			$list[$k]['full'] =  round($Coupon['full_price']/100,2);
			$list[$k]['money'] =  round($Coupon['money']/100,2);
		}
		$list = $list ? $list :array();
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	//我的优惠券
	public function MyCoupons2(){
		import('ORG.Util.Page3');
		$user_id = I('user_id', 0, 'trim,intval');
		$store_id = I('store_id', 0, 'trim,intval');
		$page = I('page', 0, 'trim,intval');
		$pagesize = I('pagesize', 0, 'trim,intval');
		
		$map = array('user_id'=>$user_id,'store_id'=>$$store_id,'closed'=>0);
		$count = M('coupon_download')->where($map)->count();
        $Page = new Page($count,20);
        $show = $Page->show();
        $p = $page;
		if($Page->totalPages < $page){
		    die(0);
	    }
		
		$list = M('coupon_download')->where($map)->order('download_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($list as $k => $val){
			$Coupon = M('coupon')->find($val['coupon_id']);
			$list[$k]['store_id'] = $Coupon['shop_id'];
			$list[$k]['store_logo'] = config_weixin_img($Coupon['photo']);
			$Shop = M('shop')->find($Coupon['shop_id']);
			$list[$k]['state'] =  $val['is_used'] == 1 ? 1 : 2;
			$list[$k]['store_name'] = $Shop['shop_name'] ? $Shop['shop_name'] : '平台';
			$list[$k]['coupons_name'] = $Coupon['title'];
			$list[$k]['end_time'] =  $Coupon['expire_date'];
			$list[$k]['reduce'] =  round($Coupon['reduce_price']/100,2);
			$list[$k]['full'] =  round($Coupon['full_price']/100,2);
			$list[$k]['money'] =  round($Coupon['money']/100,2);
		}
		$list = $list ? $list :array();
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	//优惠券二维码
	public function CouponCode(){
		$id = I('id','','htmlspecialchars');
		$page="pages/coupon/hexiao";
		$width = '400';
		$res = D('Api')->qrcodeWxapp($id,$page,$width,$parameter='downloadId',$id);
		echo $res;
	}
	
	
	//优惠券核销详情
	public function MyCouponsInfo(){
		$download_id = I('id','','htmlspecialchars');
		if(empty($download_id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'ID不存在'));
		}
		$detail = M('coupon_download')->where(array('download_id'=>$download_id))->find();
		if(empty($detail)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券不存在'));
		}
		if($detail['is_used'] != 0){
			$this->ajaxReturn(array('code'=>1,'msg'=>'优惠券状态不正确'));
		}
		if($detail['is_used'] != 0){
			$this->ajaxReturn(array('code'=>1,'msg'=>'优惠券已核销'));
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'未核销'));
		}
	}
	
	
	
	
	//核销优惠券
	public function HxCoupon(){
		$download_id = I('id','','htmlspecialchars');
		if(empty($download_id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'ID不存在'));
		}
		$detail = M('coupon_download')->where(array('download_id'=>$download_id))->find();
		
		$user_id = I('user_id','','htmlspecialchars');
		if(empty($user_id)){
			$this->ajaxReturn(array('code'=>0,'msg'=>'核销员信息不存在'));
		}
		
		if($detail['is_used'] != 0){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券已核销'));
		}
		
		if($detail['closed'] != 0){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券状态不正确'));
		}
		
		$coupon = M('coupon')->where(array('coupon_id'=>$detail['coupon_id']))->find();
		if($coupon['expire_date'] < TODAY){
			$this->ajaxReturn(array('code'=>0,'msg'=>'优惠券已过期'));
        }
		
		$school = M('running_school')->where(array('school_id'=>$coupon['school_id']))->find();
		if(!$school){
			$this->ajaxReturn(array('code'=>0,'msg'=>'学校不存在'));
        }
		
		$shop = M('shop')->where(array('shop_id'=>$detail['shop_id']))->find();
		$uid = $shop['user_id'];
		$hxName = '商家管理员核销';
		
		
		if(!$uid){
			$uid = $school['user_id'];
			$hxName = '学校管理员核销';
		}
		
		
		
		if(!$uid){
			$this->ajaxReturn(array('code'=>0,'msg'=>'您没权限核销'));
        }
		
	
		$result = M('coupon_download')->save(array('download_id'=>$detail['download_id'],'is_used' =>1,'status' =>2,'used_time'=>time(),'used_ip'=>get_client_ip()));
		if($result){
			$result['state'] == 1;
			$this->ajaxReturn(array('code'=>1,'msg'=>$hxName.'核销成功,点击左上角返回首页'));
		}else{
			$this->ajaxReturn(array('code'=>0,'msg'=>'核销失败'));
		}
	}
	
	
	
		
	//删除我的优惠券
	public function DelCoupon(){
		$coupon_id = I('coupon_id','','htmlspecialchars');
		$res = M('coupon_download')->delete($coupon_id);
        $json_str = json_encode($res);
        exit($json_str); 
	}
	
	
	//获取菜品详情
	public function getProductInfo($product_id){
		$val = M('ele_product')->find($product_id);
		$val['good'] = $val['cate_id'].'.'.$product_id;
		$val['bad'] = 0;
		$val['is_must'] = 0;
		
		$val['cate_id'] = ''.$val['cate_id'].'';
		$val['cate_str'] = ''.$val['cate_id'].'';
		$val['cate_ids'][0] = ''.$val['cate_id'].'';
		
		$val['price'] = round($val['price']/100,2);
		$val['oldprice'] = "0.00";
		$val['package_price'] = "0.00";
		
		$val['photo'] = config_weixin_img($val['photo']);
		$val['is_spec'] = $val['is_options'];
		$val['title'] = $val['product_name'];
		$val['sales'] = $val['sold_num'];
		$val['sale_sku'] = $val['num'];
		$val['shop_id'] = $val['shop_id'];

		$specs = M('ele_product_options')->where(array('product_id' =>$val['product_id']))->limit(0,20)->select();
		foreach($specs as $k3 => $v3){
			 $specs[$k3]['spec_id'] =$v3['id'];
			 $specs[$k3]['spec_name'] =$v3['name'];
			 $specs[$k3]['spec_photo'] = config_weixin_img($val['photo']);
			 $specs[$k3]['sale_sku'] = $v3['total'];
			 $specs[$k3]['oldprice'] = "0.00";
			 $specs[$k3]['price'] = round($v3['price']/100,2);
			 $specs[$k3]['package_price'] = round($v3['tableware_price']/100,2);
		}
		$val['specs']['title'] = $val['product_name'];
		$val['specs'] = $specs;
		
		//第二个规格
		if($val['is_options'] == 1){
			$val['specification'][0]['key'] = '=选择规格=';
			$val['specification'][0]['val'][0] = '默认规格';
		}else{
			$val['specification']= array();
		}
		return $val;
	}
	
	//搜索商家
	public function hotsearch(){
		$hot = M('keyword')->select();
		foreach($hot as $k => $v){
			$hots[$k] = $v['keyword'];
		}
		$data['hots'] = $hots;
		$this->ajaxReturn(array('error'=>'0','message'=>"success",'data'=>$data));
	}
	
	
	//搜索外卖商家
	public function shopsearch(){
		
	  import('ORG.Util.Page3');
		
	  $title = I('title','','htmlspecialchars');
	  $page = I('page','','htmlspecialchars');
	  
	  
	  
	  $map = array('closed'=>0);
	  if($title){
         $map['product_name'] = array('LIKE','%'.$title.'%');
      }
	  $shopIds1 = $shopIds2 = array();
	  $products= M('ele_product')->where($map)->order(array('product_id'=>'asc'))->limit(0,200)->select();
	  foreach($products as $k2 => $v2){
		  $shopIds1[$v2['shop_id']] = $v2['shop_id'];
	  }
	  
	  
	  $map2 = array('audit'=> 1);
	  if($title){
         $map2['shop_name'] = array('LIKE','%'.$title.'%');
      }
	  $eles = M('ele')->where($map2)->order(array('shop_id'=>'asc'))->limit(0,200)->select();
	  foreach($eles as $k3 => $v3){
		  $shopIds2[$v3['shop_id']] = $v3['shop_id'];
	  }
	  
	
	  $shop_ids = @array_merge($shopIds1,$shopIds2);
	  // p($shop_ids);die;
	  $query['shop_id'] = array('in',$shop_ids);
	  
	  
	  $count = M('ele')->where($query)->count();
	  $Page = new Page($count,20);
	  $show = $Page->show();
	  $p = $page;
	  if($Page->totalPages < $page){
		  $this->ajaxReturn(array('error'=>'1','message'=>"暂更多数据",'data'=>array()));
	  }
	  
	  $items = M('ele')->where($query)->limit($Page->firstRow.','.$Page->listRows)->order(array('orderby'=>'asc'))->select();
	  foreach($items as $k =>$v){
		  $shop = M('shop')->where(array('shop_id'=>$v['shop_id']))->find();
		  $items[$k]['min_amount'] = round($v['since_money']/100,2);
		  $items[$k]['reduceEd_freight'] = round($v['logistics']/100,2);
		  $items[$k]['freight'] = round($v['logistics']/100,2);
		  $items[$k]['logo'] = config_weixin_img($shop['photo']);
		  $items[$k]['title'] = $v['shop_name'];
		  $items[$k]['totalnum'] = $v['sold_num'];
		  $items[$k]['pei_time'] = $v['distribution'];
		  
		  $items[$k]['yyst'] = $v['is_open'] == 1 ? 1: 0;
		  $items[$k]['is_ziti'] = 1;
		  $items[$k]['is_refund'] = 1;
		  $items[$k]['peiType']['label'] = $v['is_ele_pai'] == 1 ? '平台配送' : '商家配送';
		  $items[$k]['tips_label'] = '';
		  
		
		  $products = M('ele_product')->where(array('shop_id'=>$v['shop_id']))->order(array('product_id'=>'desc'))->limit(0,10)->select();
		  foreach($products as $k2 => $v2){
			  $products[$k2] = $this->getProductInfo($v2['product_id']);
		  }
		  $items[$k]['products'] = $products;
	  }
	
	  
	  $data['items'] = $items;
	  $this->ajaxReturn(array('error'=>'0','message'=>"success",'data'=>$data));
	}
	
	
	//获取直播保存缓存
	private function _write_file($livevideo_server, $is_redis){
		$day_time = strtotime( date('Y-m-d'.' 00:00:00'));
		

		if(empty($livevideo_server) ){
			$res = $this->getWxRoomInfo(D('Weixintmpl')->getaccess_token());
			if($res){
				S($inc_key,$res);
			}
			return $res;
		}else{
			$expire_time = $livevideo_server->expire_time ? $livevideo_server->expire_time : 0;
			if(time() - $expire_time > 300 || !$expire_time ){
				$res = $this->getWxRoomInfo(D('Weixintmpl')->getaccess_token());
				if($res){
					S($inc_key, $res);
				}
				return $res;
			}else{
				$total = $livevideo_server->total;
				$page = $livevideo_server->page;
				if($total>$page*50){
					$page += 1;
					$resPage = $this->getWxRoomInfo(D('Weixintmpl')->getaccess_token(),$page);
					$res = (object) array('page' => $page);
					$res->expire_time = time();
					$res->room_info = array_merge($livevideo_server->room_info, $resPage->room_info);
					S($inc_key, $res);
					return $res;
				}
				return $livevideo_server;
			}
		}
	}
	
	
	
	
	//获取直播房间列表
	private function getWxRoomInfo($access_token, $page=1){
		if(!$access_token){
			return '';
			die();
		}
		$start = 50*($page-1);
		$url = 'http://api.weixin.qq.com/wxa/business/getliveinfo?access_token='.$access_token;
		$param = array(
			"start" => $start,
			"limit" => 50
		);
		$res = $this->_post($url, $param);
		$res = json_decode($res);
		if($res->errcode == 0) {
			$res->page = $page;
			$res->expire_time = time();
			return $res;
		}else if($res->errcode == 1){
			return "";
		}
	}
	
	
	
	
	//直播请求
	private function _post($url,$data=array()){
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   curl_setopt($ch, CURLOPT_POST, 1);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	   curl_setopt($ch, CURLOPT_HEADER, 0);
	   curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	   $output = curl_exec($ch);
	   curl_close($ch);
	   return $output;
	}
	
	
	//获取直播列表
	public function getRoominfo(){
	
		$page = I('page','','htmlspecialchars');

		$showTabbar = true;

		$day_time = strtotime( date('Y-m-d'.' 00:00:00') );
		$livevideo_server = S('_inc_livevideo_'.$day_time);

		$res = $this->_write_file($livevideo_server,0);
	
		
		$list = array();
		if($res){
			$room_info = $res->room_info;
			if(count($room_info) > $page*10){
				$list = array_slice($room_info,($page-1)*10, 10);
			}else{
				$list = array_slice($room_info, ($page-1)*10);
			}
		}
		
		$share['name'] = $this->config['wxapp']['live_nav_name'];
		$share['title']= $this->config['wxapp']['live_share_title'];
		$share['image']= config_weixin_img($this->config['wxapp']['live_share_image']);
		
		if($list){
			foreach ($list as $key => &$val){
				$val->start_time = date('Y-m-d H:i', $val->start_time);
				$val->end_time = date('Y-m-d H:i', $val->end_time);
			}
			echo json_encode( array('code' => 0, 'data'=>$list, 'share'=>$share,'showTabbar'=>$showTabbar));
			die();
		} else{
			echo json_encode( array('code' => 1, 'showTabbar'=>$showTabbar) );
			die();
		}
		
	}
	
	
	public function getReplay(){
	
		$roomid = I('room_id','','intval,htmlspecialchars');

		if(!$roomid){
			echo json_encode( array('code' => 1, 'msg'=>'直播间id错误') );
			die();
		}

		$roomInfo = D('Weixin')->getRoomInfo($roomid);

		if($roomInfo && $roomInfo['live_replay']){
			$live_replay = unserialize($roomInfo['live_replay']);
			$roomInfo['goods_list'] = json_decode($roomInfo['goods'], true);
			unset($roomInfo['goods']);
			unset($roomInfo['live_replay']);
			echo json_encode( array('code' => 0, 'data'=>$live_replay, 'roominfo'=>$roomInfo, 'from'=>'sql') );
			die();
		}else{
			$res = D('Weixin')->syncLiveReplay($roomid);

			if($res){
				echo json_encode( array('code' => 0, 'data'=>$res, 'roominfo'=>$roomInfo, 'from'=>'wechat') );
				die();
			}else{
				echo json_encode( array('code' => 1, 'msg'=>'暂无回放') );
				die();
			}
		}
	}


	
	
}