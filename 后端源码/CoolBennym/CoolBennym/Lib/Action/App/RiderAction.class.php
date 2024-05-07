<?php
class  RiderAction extends CommonAction{
	 //构架函数
	protected function _initialize(){
		
		$this->config = $config = D('Setting')->fetchAll();
		$this->siteUrl = $config['site']['host'];
		$this->siteDelivery = $config['delivery'];
		$this->attachs = $config['site']['host'].'/attachs/rider/';
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
	
	
	
	//自动登录+注册
	public function openid(){
		
		$sign = I('sign','','trim,htmlspecialchars');
		$code = I('code','','trim,htmlspecialchars');
		
		$appid = $this->config['wxapp']['appid'];
		$secret = $this->config['wxapp']['appsecret'];
		$url="https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
		$res = $this->httpRequest($url);
		$Data = json_decode($res,true);  
	
		
		if(!$Data['openid']){
			$this->ajaxReturn(array('errno'=>0,'message'=>'微信认证失败'.$Data['errcode'].'--'.$Data['errmsg'],'data'=>''));
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
				$this->ajaxReturn(array('errno'=>0,'message'=>'openid【'.$Data['openid'].'】存在多个请联系管理员处理','data'=>''));
			}
			
			//查询会员信息
			if($connect['uid']){
				$u = M('users')->where(array('user_id'=>$connect['uid']))->find();
			}
			
			//1有注册信息2有注册会员3有会员信息
			if($connect && $connect['uid'] && $u){
				//更新session_key
				M('connect')->where(array('connect_id'=>$connect['connect_id']))->save(array('unionid'=>$Data['unionid'],'rd_session'=>$Data['session_key']));
				//获取用户信息
				$Users = M('users')->find($connect['uid']);
				$Users['openid'] = $Data['openid'];
				$Users['id'] = $Users['user_id'];
				$Users['fanid'] = $Users['user_id'];
				$Users['user_from'] = 1;
				$data['openid'] = $Data['openid'];
				$data['sessionid'] = $Users['user_id'];
				$data['userinfo'] = $Users;
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
					$this->ajaxReturn(array('errno'=>0,'message'=>'【'.$connectName.'】connect数据表失败','data'=>''));
				}
				$account = 'rider_wxapp'.$connect_id;
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
            	$user_id = D('Passport')->register($arr,$fid = '',$type = '1');//注册的时候新增学校ID
				if(!$user_id){
					$this->ajaxReturn(array('errno'=>0,'message'=>'注册新用户失败管理员排查','data'=>''));
				}
				M('connect')->where(array('connect_id'=>$connect_id))->save(array('uid'=>$user_id,'nickname'=>$account,'unionid'=>$Data['unionid'],'rd_session'=>$Data['session_key']));
				$Users = M('users')->find($user_id);
				$Users['openid'] = $Data['openid'];
				$Users['id'] = $user_id;
				$Users['fanid'] = $user_id;
				$Users['user_from'] = 1;
				
				$data['openid'] = $Data['openid'];
				$data['sessionid'] = $user_id;
				$data['userinfo'] = $Users;
			}
			$this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>$data));
		}
		$this->ajaxReturn(array('errno'=>0,'message'=>'微信验证失败'.$Data['errcode'].''.$Data['errmsg'],'data'=>''));
		
	}
	
	

	
	
	public function getSetting(){
		$data['acceptorder_template_id']="VhPG0u1hecUkMd-zOOpyAlytesbu2VIss575mZYBWpA";
		$data['audit_rider_tpl']= "H-fIvJCz5pp01nbhcxSjSgVnf6B45Mcnx3TU2qbujLA";
		$data['endcode_switch']= 1;
		$data['gaode_key']= "fa48d9e9d49c6909224d1c161ae10643";
		$data['getcode_switch']= 0;
		$data['is_can_accept']= 1;
		$data['order_record_icon']= $this->attachs."index_icon03.png";
		$data['port']= "9502";
		$data['program_background']= "#ffffff";
		$data['program_font']="#000000";
		$data['r_program_verify_switch']= 0;
		$data['receiving_order_icon']= $this->attachs."index_icon06.png";
		$data['resource_url']= $this->attachs;
		$data['rider_auth_bg']= $this->attachs."auth_bj.jpg";
		$data['rider_bondmoney']= "1";
		$data['rider_gaode_key']= "fa48d9e9d49c6909224d1c161ae10643";
		$data['rider_index_bg']= $this->attachs."index_icon09.png";
		$data['rider_map_icon']= $this->attachs."rider_map_icon.png";
		$data['rider_program_background']= "#ffffff";
		$data['rider_program_font']= "#000000";
		$data['rider_program_title']= $this->config['site']['sitename'];
		$data['rider_share_img']= $this->attachs."rider_share_img.png";
		$data['rider_tencent_key']= "F2RBZ-32WHX-3XS4Z-ZRN5P-V6ZUO-6JFNB";
		$data['rider_tipbgm']= $this->attachs."tip_bgm.mp3";
		$data['robbed_map_icon']=$this->attachs."index_icon05.png";
		$data['socket_domain']= "wss://".$this->config['delivery']['wss'].":9502";
		$data['space']= $this->attachs."space.mp3";
		$data['tencent_key']= "66BBZ-CSLW4-UPBUE-D5XNE-YYPDV-AHB4Q";
		$data['tip_bgm_url']= $this->attachs."tip_bgm.mp3";
		$data['to_robbed_icon']= $this->attachs."index_icon04.png";
		$data['user_auth_bg']= $this->attachs."auth_bg.png";
		$data['user_program_title']=$this->config['site']['sitename'];
		$data['user_share_img']= "";

		$wechat_withdraw[0]['name'] = "支付宝";
		$wechat_withdraw[0]['status'] = true;
		$wechat_withdraw[0]['type'] = 0;
		
		$wechat_withdraw[1]['name'] = "微信";
		$wechat_withdraw[1]['status'] = true;
		$wechat_withdraw[1]['type'] = 2;
		
		$wechat_withdraw;
		$data['wechat_withdraw']= $wechat_withdraw;
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }
	
	
	public function getUserId(){
	   $state = I('state','','trim,htmlspecialchars');
	   $state = explode("-",$state);
	   return $state[1];
    }
	
	
	public function getRiderOrderData($running_id){
		
		$user_id = $this->getUserId();

		$v = M('running')->find($running_id);
		
		if($v['Type'] == 1){
			//外卖订单
			$Shop = M('shop')->where(array('shop_id'=>$v['ShopId']))->find();
			$begin_address= $Shop['addr'];
			$begin_detail= $Shop['addr'];
			$begin_lat= $Shop['lat'];
			$begin_lng= $Shop['lng'];
			$begin_phone= $Shop['mobile'];
			$begin_username= $Shop['shop_name'];
			
			$startAddress = unserialize($v['startAddress']);
			$endAddress = unserialize($v['endAddress']);
			
			$endAddr = M('user_addr')->where(array('addr_id'=>$endAddress['AddressId']))->find();
			$end_address  = $endAddr['addr'];
			$end_detail = $endAddr['addr'];
			$end_lat = $endAddr['lat'];
			$end_lng = $endAddr['lng'];
			$end_phone = $endAddr['mobile'];
			$end_username = $endAddr['name'];
				
			
		}elseif($v['Type'] == 2){
			//正常
			$startAddress = unserialize($v['startAddress']);
			$endAddress = unserialize($v['endAddress']);
			$startAddr = M('user_addr')->where(array('addr_id'=>$startAddress['AddressId']))->find();
			$endAddr = M('user_addr')->where(array('addr_id'=>$endAddress['AddressId']))->find();
			
			if($startAddr){
				$begin_address  = $startAddr['addr'];
				$begin_detail = $startAddr['addr'];
				$begin_lat = $startAddr['lat'];
				$begin_lng = $startAddr['lng'];
				$begin_phone = $startAddr['mobile'];
				$begin_username = $startAddr['name'];
			}else{
				$begin_address  = $v['addr'];
				$begin_detail = $v['addr'];
				$begin_lat = $v['lat'];
				$begin_lng = $v['lng'];
				$begin_phone = $v['mobile'];
				$begin_username = $v['name'];
			}
			
			if($endAddr){
				$end_address  = $endAddr['addr'];
				$end_detail = $endAddr['addr'];
				$end_lat = $endAddr['lat'];
				$end_lng = $endAddr['lng'];
				$end_phone = $endAddr['mobile'];
				$end_username = $endAddr['name'];
			}else{
				$end_address  = $v['addr'];
				$end_detail = $v['addr'];
				$end_lat = $v['lat'];
				$end_lng = $v['lng'];
				$end_phone = $v['mobile'];
				$end_username = $v['name'];
			}
		}
		
		$data['uid']=$user_id;
		$data['accept_time']=0;
		$data['add_time']= date('Y-m-d H:i:s ',$v['create_time']);
		$data['audio']= "";
		$data['begin_address']= $begin_address;
		$data['begin_detail']= $begin_detail;
		$data['begin_lat']= $begin_lat;
		$data['begin_lng']= $begin_lng;
		$data['begin_phone']= $begin_phone;
		$data['begin_username']= $begin_username;
		
		
		$data['budget_price']= "0.00";//预算价格
		$data['car_name']= "";
		$data['car_type']= "0";
		$data['category_id']= "0";
		$data['change_price']= "0.00";//更改价格
		$data['cube']= "0.00";//立方
		$data['cube_price']="0.00";//立方价格
		
		
		
		
		$data['end_address']= $end_address;
		$data['end_detail']= $end_detail;
		$data['end_floor']= "0";
		$data['end_lat']= $end_lat;
		$data['end_lng']= $end_lng;
		$data['end_phone']= $end_phone;
		$data['end_username']= $end_username;
		
		$data['extension_number']= "0";
		$data['floor_price']= "0.00";
		$data['get_time']=0;
		$data['goodsname']= $v['title'];
		$data['goto_time']= 0;
		$data['id']= $v['running_id'];
		$data['imgs']= "";
		$data['is_discuss']="0";
		$data['is_picture_order']= "0";
		$data['line_id']="0";
		$data['load_switch']= "0";
		
		$cate = M('running_cate')->where(array('cate_id'=>$v['cate_id']))->find();
		$data['orderType']= $cate['cate_name'] ? $cate['cate_name'] : '暂无分类';//分类
		
		$data['order_code']= $v['running_id'];
		$data['order_id']=$v['running_id'];
		$data['order_type']=$v['Type'];
		$data['paymen']= "1";
		$data['postage']="0.00";
		$data['remark']=$v['title'];
		
		
		$delivery = M('running_delivery')->where(array('user_id'=>$user_id))->find();
		
		/*
		$info2 = get_dist_info($delivery['lat'],$delivery['lng'],$begin_lat,$begin_lng);//距离您距取货地址
		$info = get_dist_info($begin_lat,$begin_lng,$end_lat,$end_lng);//距离您多少KM
		$data['distance']= $info['juli_value'];
		$data['rider_distance']= $info2['juli_value'];
		*/
		
		$info2 = amapDistance2($delivery['lng'],$delivery['lat'],$begin_lng,$begin_lat);//距离您距取货地址
		$info = amapDistance2($begin_lng,$begin_lat,$end_lng,$end_lat);//距离您多少KM
		$data['distance']= $info['juliKm'];
		$data['rider_distance']= $info2['juliKm'];
		
		
		$data['small_price']= "0.00";
		$data['snap_item']= [];
		
		if($v['OrderStatus'] == 2){
			$status = 2;
		}elseif($v['OrderStatus'] == '16'){
			$status = 3;
		}elseif($v['OrderStatus'] == '32'){
			$status = 4;
		}elseif($v['OrderStatus'] == '4'){
			$status = 8;
		}
		
		$data['status']= $status;
		
		$data['total_price']= round($v['MoneyFreight']/100,2);
		$data['weight']=$v['Weight'];
		$data['__get_time']= "立即服务";
		$data['_get_time']= 0;
		$data['_goto_time']= 0;
		return $data;
	}
	
	
	
	
	public function getRiderOrder(){
	   	$user_id = $this->getUserId();
	   
		$page2 = I('page','','trim,htmlspecialchars');
		$status = I('status','','trim,htmlspecialchars');
		
		
		$map = array('closed'=>0,'orderType'=>1,'is_ele_pei'=>0);
		if($status == '-1'){
			//首页配送中
			$map['delivery_id']=$user_id;
			$map['OrderStatus']=array('in',array(16,32));
		}elseif($status == '2'){
			$map['OrderStatus']=array('in',array(2,8,16));
		}elseif($status == '3'){
			$map['delivery_id']=$user_id;
			$map['OrderStatus']=array('in',array(16,32));
		}elseif($status == '4'){
			$map['delivery_id']=$user_id;
			$map['OrderStatus']=array('in',array(64,128));
		}elseif($status == '5'){
			$map['delivery_id']=$user_id;
			$map['OrderStatus']=array('in',array(8,16,32,64,128));
		}
		
		
		import('ORG.Util.Page3');
		
		
		$count = M('Running')->where($map)->count();
        $Page = new Page($count,3);
        $show = $Page->show();
		if($Page->totalPages < $page2){
            $this->ajaxReturn(array('errno'=>0,'message'=>'暂无数据','data'=>''));
        }
		
		
		$list = M('Running')->where($map)->order(array('OrderStatus'=>'asc','running_id'=>'desc'))->limit($Page->firstRow .','.$Page->listRows)->select();
		
		foreach($list as $k2 => $v2){
			$list[$k2] = $this->getRiderOrderData($v2['running_id']);
		}
		
		$data  = $list;

        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }
	
	//收支明细
	public function riderCashList(){
	   	$user_id = $this->getUserId();
	   
		$page2 = I('page','','trim,htmlspecialchars');
		$time = I('time','','trim,htmlspecialchars');
		$time = @explode("-",$time);
		
		$time1 = $time[1] < 10 ? '0'.$time[1] : $time[1];
		$times = $time[0].''.$time1;
		
		$map = array('user_id'=>$user_id);
		if($times > 0){
			$map['month'] = $times;
		}
		
		import('ORG.Util.Page3');
		
		$count = M('user_money_logs')->where($map)->count();
        $Page = new Page($count,10);
        $show = $Page->show();
		if($Page->totalPages < $page2){
            $this->ajaxReturn(array('errno'=>0,'message'=>'暂无数据','data'=>''));
        }
		
		
		$list = M('user_money_logs')->where($map)->order(array('log_id'=>'desc'))->limit($Page->firstRow .','.$Page->listRows)->select();
		
		foreach($list as $k => $v){
			$getMoneyTypes = D('Users')->getMoneyTypes();
			$list[$k]['title'] = $getMoneyTypes[$v['type']];
			$list[$k]['amount'] = round($v['money']/100,2);
			$list[$k]['add_time'] = date('Y-m-d H:i:s ',$v['create_time']);;
		}
		$data = $list;
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }
	
	
	
	
	
	public function getRiderId(){
       $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>''));
    }
	
	
	
	
	
	public function riderIsAccept(){
		$user_id = $this->getUserId();
		
		$bg_time = strtotime(TODAY);
		$count = (int)M('Running')->where(array('closed'=>0,'orderType'=>1,'is_ele_pei'=>0,'delivery_end_time' => array(array('ELT',NOW_TIME), array('EGT',$bg_time)),'OrderStatus'=>array('in',array(64,128)),'delivery_id'=>$user_id))->count();
		$waitcount = (int)M('Running')->where(array('closed'=>0,'orderType'=>1,'is_ele_pei'=>0,'OrderStatus'=>array('in',array(2,8,16))))->count();
		$price = (int)M('running_money')->where(array('create_time' => array(array('ELT',NOW_TIME), array('EGT',$bg_time)),'user_id'=>$user_id))->sum('money');
		
		$data['count']= $count;//今日完成
		$data['price']= round($price/100,2);//间日收入
		$data['rider_id']= 10;
		$data['type']= "1";
		$data['waitcount']= $waitcount;//代抢订单
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>$data));
    }
	
	
	//订单统计
	public function riderOrderCount(){
		$user_id = $this->getUserId();
		
		$bg_time = strtotime(TODAY);
	 	$str = '-1 day';
        $bg_time_yesterday = strtotime(date('Y-m-d', strtotime($str)));
		$month = date('Ym',time());//当前月份
		
		
		$yester_income = (int)M('running_money')->where(array('create_time' => array(array('ELT', $bg_time), array('EGT', $bg_time_yesterday)),'user_id'=>$user_id))->sum('money');
		$total_income = (int)M('running_money')->where(array('create_time' => array(array('ELT', $bg_time), array('EGT', $bg_time_yesterday)),'user_id'=>$user_id))->sum('money');
		$month_income = (int)M('running_money')->where(array('month' =>$month,'user_id'=>$user_id))->sum('money');
		
		
		$data['total_income']= round($total_income/100,2);
		$data['yester_income']= round($yester_income/100,2);
		$data['month_income']= round($month_income/100,2);
		
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>$data));
    }
	
	
	//配送员详情
	public function riderInfo(){
		$user_id = $this->getUserId();
		
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		$data['avatar']= config_weixin_img($users['face']);
		$data['id']=$users['user_id'];
		$data['madou']="0";
		$data['mobile']=$users['mobile'];
		$data['notify_count']=0;
		$data['orderCount']= 0;
		$data['real_name']= $users['nickname'];
		$data['score']=100;
		$data['valid_money']=round($users['money']/100,2);
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>$data));
    }
	
	
	//check
	public function check(){
		$user_id = $this->getUserId();
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>''));
    }
	
	
	//更新配送员详情
	public function updateRiderInfo(){
		$user_id = $this->getUserId();
		
		$nickName = I('nickName','','trim,htmlspecialchars');
		$avatarUrl = I('avatarUrl','','trim,htmlspecialchars');
		$sex = I('sex','','trim,htmlspecialchars');
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>''));
    }
	
	
	//我的钱包
	public function RiderWallet(){
		$user_id = $this->getUserId();
		$users = M('users')->where(array('user_id'=>$user_id))->find();
		
		$data['bond_money']='0.00';
		$data['invalid_money']='0.00';
		$data['valid_money']=round($users['money']/100,2);
		$data['money']=round($users['money']/100,2);
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'','data'=>$data));
    }
	
	
	
	
	
	
	public function setRiderLocation(){
		$lat = I('lat','','trim,htmlspecialchars');
		$lng = I('lng','','trim,htmlspecialchars');
		$address = I('address','','trim,htmlspecialchars');
		
		$user_id = $this->getUserId();
		
		
		$res = M('running_delivery')->where(array('user_id'=>$user_id))->save(array('lat'=>$lat,'lng'=>$lng));
		
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
    }
	
	//调用联系客服文章
	public function getRiderAgreement(){
		
        $list = M('article')->where(array('closed' => 0,'cate_id'=>4))->order(array('article_id' => 'desc'))->limit(0,20)->select();
		foreach($list as $k => $v){
			$list[$k]['content'] = cleanhtml($v['details'],500,true);
		}
		
		$content['question']  = $list;
		$content['phone']  = $this->config['site']['tel'];
		$content['time']  = "9:00~17:00";
		
		$data['content'] = $content;
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }
	
	
	//调用服务手册
	public function riderHandbookList(){
        $list = M('article')->where(array('closed' => 0,'cate_id'=>3))->order(array('article_id' => 'desc'))->limit(0,20)->select();
		foreach($list as $k => $v){
			$list[$k]['content'] = cleanhtml($v['details'],500,true);
			$list[$k]['id'] = $v['article_id'];
			$list[$k]['icon'] = '';
		}
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$list));
    }
	
	//调用服务手册详情
	public function riderHandbookDetail(){
		$id = I('id','','trim,htmlspecialchars');
        $data = M('article')->where(array('closed' => 0,'id'=>$id))->find();
		$data['content'] = cleanhtml($data['details'],500,true);
		$data['id'] = $data['article_id'];
		$data['icon'] = '';
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }
	
	//推荐列表
	public function riderInviteLog(){
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
    }
	
	//获取海报
	public function getRiderPoster(){
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
    }
	
	
	//奖惩记录
	public function riderSanction(){
		$type = I('type','','trim,htmlspecialchars');
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
    }
	
	
	//接单设置
	public function riderAcceptSetting(){
		$user_id = $this->getUserId();


		$order_type[0]['name'] =  "跑腿";
		$order_type[0]['status'] =  true;
		$order_type[0]['value'] =  0;

		$data['order_type'] = $order_type;
		
		$data['yuyue'] = 1;
		$data['shishi'] = 1;
		$data['is_technician'] = false;
		$data['is_freight_driver'] = false;
		$data['is_driver'] = false;
		
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }
	
	//抢单
	public function acceptOrder(){
		
		$running_id = I('order_id','','trim,htmlspecialchars');
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('errno'=>0,'message'=>'订单不存在','data'=>''));
		}
		
		$user_id = $this->getUserId();
		if(!$user_id){
			$this->ajaxReturn(array('errno'=>0,'message'=>'登陆状态失效请稍后再试','data'=>''));
		}
		
		
		$d = M('RunningDelivery')->where(array('user_id'=>$user_id))->find();
		if(!$d){
			$this->ajaxReturn(array('errno'=>0,'message'=>'找不到配送员数据','data'=>''));
		}
		
		
		if($detail['LastGiveupId'] == $d['delivery_id']){
			$this->ajaxReturn(array('errno'=>0,'message'=>'您不能重复抢单','data'=>''));
		}
		
		
		if($d['audit'] !=2){
			$this->ajaxReturn(array('errno'=>0,'message'=>'配送员状态不正确','data'=>''));
		}
		
		if($detail['user_id'] ==$d['user_id']){
			$this->ajaxReturn(array('errno'=>0,'message'=>'自己发的订单不能自己抢','data'=>''));
		}
		
		
		if($detail['orderType'] == 2){
			$this->ajaxReturn(array('errno'=>0,'message'=>'到店自提订单类型不支持抢单','data'=>''));
		}
		
		
		//外卖订单
		if($detail['Type'] == 1){
			if($detail['OrderStatus'] != 8){
				$this->ajaxReturn(array('errno'=>0,'message'=>'当前外卖订单类型商家还未确认接单，请等待商户接单后再点击配送【'.$detail['OrderStatus'].'】','data'=>''));
			}
		}else{
			//配送基本订单
			if($detail['OrderStatus'] != 2){
				$this->ajaxReturn(array('errno'=>0,'message'=>'当前订单状态不支持抢单【'.$detail['OrderStatus'].'】','data'=>''));
			}
		}
		
		//判断当前订单是否结算过
		$money = M('running_money')->where(array('running_id'=>$running_id,'order_id'=>$running_id))->find();
		if($money){
			$this->ajaxReturn(array('errno'=>0,'message'=>'当前订单重复操作，操作日志ID【'.$money['money_id'].'】','data'=>''));
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
			$this->ajaxReturn(array('errno'=>0,'message'=>'操作频繁请【'.$second .'】秒后再试','data'=>''));
		}
		//抢单数量限制
		$count = M('Running')->where(array('delivery_id' =>$d['delivery_id'],'OrderStatus'=>'16','closed'=>'0'))->count();
		if($count && $count >= $num){
			$this->ajaxReturn(array('errno'=>0,'message'=>'已配置中订单的数量已经超过限制请先完成配送后再抢单','data'=>''));
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
					$this->ajaxReturn(array('errno'=>0,'message'=>'该订单已经超时无法抢单','data'=>''));
				}
				$this->ajaxReturn(array('errno'=>0,'message'=>'操作失败订单已执行退款请查看其它订单','data'=>''));
				
			}
		}
		
		$data['delivery_id'] = $d['user_id'];
		$data['OrderStatus'] = 16;
		$data['update_time'] = time();
		$data['delivery_id'] = $d['user_id'];
		
		//接单
		if($res= M('Running')->where(array('running_id'=>$running_id))->save($data)){
			if($detail['Type'] == 1){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>16,'update_time'=>time()));
			}
			M('RunningDelivery')->where(array('user_id'=>$user_id))->save(array('num'=>$d['num']+1));
			D('Sms')->runningAcceptUser($running_id);//配送员抢单短信通知买家
			D('Weixintmpl')->runningWxappNotice($running_id,$OrderStatus = 16,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
			$data['order_id'] = $running_id;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}
		$this->ajaxReturn(array('errno'=>0,'message'=>'操作失败','data'=>''));
		
    }
	
	
	
	//配送员取消订单
	public function cancelAccept(){
		
		$user_id = $this->getUserId();
		if(!$user_id){
			$this->ajaxReturn(array('errno'=>0,'message'=>'登陆状态失效请稍后再试','data'=>''));
		}
		
		if(!$RunningDelivery= M('RunningDelivery')->where(array('user_id'=>$user_id))->find()){
			$this->ajaxReturn(array('errno'=>0,'message'=>'您不是配送员无法放弃订单','data'=>''));
		}
		
		$running_id = I('id','','trim,htmlspecialchars');//订单ID
		
		if(!$detail= M('Running')->find($running_id)){
			$this->ajaxReturn(array('errno'=>0,'message'=>'订单不存在','data'=>''));
		}
		
		$data['running_id'] = $running_id;
		$data['OrderStatus'] = 2;
		$data['LastGiveupId'] = $RunningDelivery['delivery_id'];
		$data['LastGiveupCause'] = '配送员：'.$RunningDelivery['RealName'].'放弃';
		$data['LastGiveupTime'] = time();
		
	
		if($res= M('Running')->save($data)){
			if($detail['Type'] == 1){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>2));
			}
			
			$data['order_id'] = $id;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));//不知道写什么逻辑
		}
		$this->ajaxReturn(array('errno'=>0,'message'=>'操作失败订单','data'=>''));
	}	
	
	
	

	
	
	//确认取件
	public function rider_update_order(){
		$user_id = $this->getUserId();
		$running_id = I('id','','trim,htmlspecialchars');
		$goods_code = I('goods_code','','trim,htmlspecialchars');//收件码
		$goods_img = I('goods_img','','trim,htmlspecialchars');
		$end_img = I('end_img','','trim,htmlspecialchars');
		
		$lat = I('lat','','trim,htmlspecialchars');
		$lng = I('lng','','trim,htmlspecialchars');
		
		$type = I('type','','trim,htmlspecialchars');//1是确认完成订单
		
		if($type == 1){
			
			if(!$detail= M('Running')->find($running_id)){
				$this->ajaxReturn(array('errno'=>0,'message'=>'订单不存在','data'=>''));
			}
			
			if($res= M('Running')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>'32','delivery_end_time'=>time()))){
				$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>32));
				D('Weixintmpl')->runningWxappNotice($running_id,$OrderStatus = 32,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
				$data['order_id'] = $id;
				$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));//不知道写什么逻辑
			}
			
		}
		
		
		$data['order_id'] = $id;
		//不知道写什么逻辑
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
	}
	
	
	
	
	
	
	//申请提现
	public function riderWithdraw(){
		$user_id = $this->getUserId();
		$money = I('money','','trim,htmlspecialchars');
		$des = I('des','','trim,htmlspecialchars');
		$type = I('type','','trim,htmlspecialchars');
		$is_cash = I('is_cash','','trim,htmlspecialchars');


		$money = $money *100;
		$data['code'] = $type == 2 ? 'weixin' : $type == 0 ? 'alipay' : 'blank';
		
		
		if(!$user_id){
			 $this->ajaxReturn(array('errno'=>0,'message'=>'登陆状态失效请稍后再试','data'=>''));
		}
		
        if(!$detail = M('users')->find($user_id)){
			$this->ajaxReturn(array('errno'=>0,'message'=>'会员信息不存在','data'=>''));
		}
		
		if($this->config['cash']['is_cash'] !=1){
			$this->ajaxReturn(array('errno'=>0,'message'=>'网站暂时没开启提现功能，请联系管理员','data'=>''));
		}
		

		//会员提现设置
		$cash_money = $this->config['cash']['user'] ? $this->config['cash']['user'] : '100';
		$cash_money_big = $this->config['cash']['user_big'] ? $this->config['cash']['user_big'] : '100000';
		$cash_commission = $this->config['cash']['user_cash_commission'] ? $this->config['cash']['user_cash_commission'] : '0';
	
		
		if($money <100){
			$this->ajaxReturn(array('errno'=>0,'message'=>'提现金额不能低于1元','data'=>''));
		}
		if($money < $cash_money * 100){
			$this->ajaxReturn(array('errno'=>0,'message'=>'提现金额小于最低提现额度' . $cash_money . '元','data'=>''));
		}
		if($money > $cash_money_big * 100){
			$this->ajaxReturn(array('errno'=>0,'message'=>'您单笔最多能提现' . $cash_money_big . '元','data'=>''));
		}
		
		$Users = M('users')->where(array('user_id'=>$user_id))->find();
		$UsersMoney =(int)$Users['money'];
		 
		if($UsersMoney <= 0){
			$this->ajaxReturn(array('errno'=>0,'message'=>'会员的余额账户为0或者为负数','data'=>''));
		}
		if($money > $Users['money']){
			$this->ajaxReturn(array('errno'=>0,'message'=>'提现金额不合法','data'=>''));
		}
		
		$alipay_real_name = $des;
		$alipay_account = $des;
	
		//佣金设置
		if($cash_commission >= 0){
			$commission = intval(($money*$cash_commission)/100);//佣金
		}else{
			$commission = 0;//0佣金
		}
		
		$UsersCashFind = M('UsersCash')->where(array('user_id'=>$user_id,'type'=>user,'status'=>0))->find();
		if($UsersCashFind){
			$this->ajaxReturn(array('errno'=>0,'message'=>'当前会员的提现ID【'.$UsersCashFind['cash_id'].'】还没处理，请处理后再次申请提现','data'=>''));
		}
		
		//提现数组
		$data['account'] = $Users['nickname'];
        $data['user_id'] = $user_id;
		$data['shop_id'] = 0;
        $data['money'] = $money - $commission;//实际到账
		$data['commission'] = $commission;//手续费
		$data['info'] = $des;
		$data['re_user_name'] = '未填写';
		$data['alipay_account'] = $alipay_account?$alipay_account : '未填写';
		$data['alipay_real_name'] = $alipay_real_name ? $alipay_real_name : '未填写';
		$data['bank_num'] = '未填写';
		$data['bank_realname'] = '未填写';
        $data['type'] = 'user';
        $data['addtime'] = NOW_TIME;
		$data['code'] = $code;
		
		
		//再次避免重复提现
		$intro = '【跑腿小程序】您原始余额【'.round($UsersMoney/100,2).'】元，申请提现'.round($money/100,2).'元，其中手续费：'.round($data['commission']/100,2).'元，实际应到账'.round($data['money']/100,2).'元';
		
		//再次验证，没有提现过的状态验证
		$logs = M('user_money_logs')->where(array('user_id'=>$user_id,'type'=>3,'money'=>$money,'intro'=>$intro,'type'=>user,'status'=>0))->find();
		if($logs){
			$this->ajaxReturn(array('errno'=>0,'message'=>'操作失败提现重复ID或者有提现未处理【'.$logs['log_id'].'】','data'=>''));
		}
		
		//写入数据库
		if($cash_id = M('UsersCash')->add($data)){
			//扣除资金
			D('Users')->addMoney($user_id,-$money,$intro,3,$school_id = 0);
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}
		$this->ajaxReturn(array('errno'=>0,'message'=>'提现失败','data'=>''));
    }
	
	
	//充值账户
	public function riderRecharge(){
		
		$user_id = $this->getUserId();
		if(!$user_id){
			$this->ajaxReturn(array('errno'=>0,'message'=>'登陆状态失效请稍后再试','data'=>''));
		}
		
		$money = I('money','','trim,htmlspecialchars');
		$money = $money * 100 ;
		if(!$money){
			$this->ajaxReturn(array('errno'=>0,'message'=>'金额不正确','data'=>''));
		}
		


		$logs = array(
			'type' => 'money',
			'types' =>0, 
			'info' => '配送员充值',  
			'user_id' => $user_id, 
			'order_id' => 0,
			'school_id' => 0,  
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
	
	
		if($return['package'] == 'prepay_id='){
			$this->ajaxReturn(array('errno'=>0,'message'=>'预支付失败--'.$return['rest']['return_msg'],'data'=>''));
		}
		
		
		if($return['rest']['return_code'] == 'SUCCESS' || $return['rest']['return_msg'] == 'OK'){
			$pay_params['appId']= $this->config['wxapp']['appid'];
			$pay_params['nonceStr']=$return['nonceStr'];
			$pay_params['package']= $return['package'];
			$pay_params['paySign']= $return['paySign'];
			$pay_params['signType']= $return['signType'];
			$pay_params['timeStamp']=$return['timeStamp'];
			$data['pay_params'] = $pay_params;
			
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}
		$this->ajaxReturn(array('errno'=>0,'message'=>'充值失败','data'=>''));
		
    }
	
	
	
	
	//培训地点暂时无
	public function getTrainInfo(){
		$data['name'] = $this->siteDelivery['train_name'];
		$data['address'] = $this->siteDelivery['train_address'];
		$data['phone'] = $this->siteDelivery['train_phone'];
		$data['morn'] = $this->siteDelivery['train_morn'];
		$data['after'] = $this->siteDelivery['train_after'];
        $this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
    }


	//修改手机号
	public function riderEditMobile(){
		
		$user_id = $this->getUserId();
		
		$is_send = I('is_send','','trim,htmlspecialchars');
		$mobile = I('mobile','','trim,htmlspecialchars');
		$code = I('code','','trim,htmlspecialchars');
		
		if($is_send == 1){
			$randstring = rand_string(4,1);
			D('Sms')->sms_yzm($mobile,$randstring);//发送短信
			$data['randstring'] = $randstring;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}else{
			
			if($this->config['sms']['dxapi'] == 'bo'){
				$rest = M('sms_bao')->where(array('mobile'=>$mobile))->order('sms_id desc')->find();
				$mobile2 = $rest['mobile'];
				$intro = unserialize($rest['intro']);
			}else{
				$rest = M('dayu_sms')->where(array('mobile'=>$mobile))->order('sms_id desc')->find();
				$mobile2 = $rest['mobile'];
				$intro = unserialize($rest['intro']);
			}
			
			if($mobile2 != $mobile){
				$this->ajaxReturn(array('errno'=>0,'message'=>'手机号请求不一致','data'=>''));
			}
			
			$code2 = $intro['code'];
			if(!$code){
				$this->ajaxReturn(array('errno'=>0,'message'=>'验证码必须要填写','data'=>''));
			}
			if($code != $code2){
				$this->ajaxReturn(array('errno'=>0,'message'=>'验证码错误','data'=>''));
			}
			
			$r = M('users')->where(array('user_id'=>$user_id))->save(array('mobile2'=>$mobile));
			if($r){
				$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
			}else{
				$this->ajaxReturn(array('errno'=>0,'message'=>'修改失败','data'=>''));
			}
		}
    }



	//登录
	public function riderLogin(){
		$user_id = $this->getUserId();
		
		$mobile = I('mobile','','trim,htmlspecialchars');
		$smscode = I('smscode','','trim,htmlspecialchars');
		$is_send = I('is_send','','trim,htmlspecialchars');
		
		
		if($is_send == 1){
			$randstring = rand_string(4,1);
			D('Sms')->sms_yzm($mobile,$randstring);//发送短信
			$data['randstring'] = $randstring;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}else{
		
			if($this->config['sms']['dxapi'] == 'bo'){
				$rest = M('sms_bao')->where(array('mobile'=>$mobile))->order('sms_id desc')->find();
				$mobile2 = $rest['mobile'];
				$intro = unserialize($rest['intro']);
			}else{
				$rest = M('dayu_sms')->where(array('mobile'=>$mobile))->order('sms_id desc')->find();
				$mobile2 = $rest['mobile'];
				$intro = unserialize($rest['intro']);
			}
			
			if($mobile2 != $mobile){
				$this->ajaxReturn(array('errno'=>0,'message'=>'手机号请求不一致','data'=>''));
			}
			
			$code2 = $intro['code'];
			if(!$smscode){
				$this->ajaxReturn(array('errno'=>0,'message'=>'验证码必须要填写','data'=>''));
			}
			if($smscode != $code2){
				$this->ajaxReturn(array('errno'=>0,'message'=>'验证码错误','data'=>''));
			}
			
			//登录成功
			$data['mobile'] = $mobile;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	
	//注册
	public function riderRegister(){
		
		$mobile = I('mobile','','trim,htmlspecialchars');
		$smscode = I('smscode','','trim,htmlspecialchars');
		$is_send = I('is_send','','trim,htmlspecialchars');
		$username = I('username','','trim,htmlspecialchars');
		$invite_code = I('invite_code','','trim,htmlspecialchars');//推荐人ID
		
		
		
		if($is_send == 1){
			$randstring = rand_string(4,1);
			D('Sms')->sms_yzm($mobile,$randstring);//发送短信
			$data['randstring'] = $randstring;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}else{
			if($this->config['sms']['dxapi'] == 'bo'){
				$rest = M('sms_bao')->where(array('mobile'=>$mobile))->order('sms_id desc')->find();
				$mobile2 = $rest['mobile'];
				$intro = unserialize($rest['intro']);
			}else{
				$rest = M('dayu_sms')->where(array('mobile'=>$mobile))->order('sms_id desc')->find();
				$mobile2 = $rest['mobile'];
				$intro = unserialize($rest['intro']);
			}
			
			if($mobile2 != $mobile){
				$this->ajaxReturn(array('errno'=>0,'message'=>'手机号请求不一致','data'=>''));
			}
			
			$code2 = $intro['code'];
			if(!$smscode){
				$this->ajaxReturn(array('errno'=>0,'message'=>'验证码必须要填写','data'=>''));
			}
			if($smscode != $code2){
				$this->ajaxReturn(array('errno'=>0,'message'=>'验证码错误','data'=>''));
			}
			
			//登录成功
			$data['mobile'] = $mobile;
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
		}
		
		
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	
	
	
	
	//注册商圈
	public function register_business(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	
	//注册验证权限
	public function riderAuth(){
		$user_id = $this->getUserId();
		
		$name = I('name','','trim,htmlspecialchars');
		$sex = I('sex','','trim,htmlspecialchars');
		$idcard = I('idcard','','trim,htmlspecialchars');
		$address_detail= I('address_detail','','trim,htmlspecialchars');
		$upload_photos = I('upload_photos','','trim,htmlspecialchars');
		
		$data['business_type'] = 'jz';
		
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$data));
	}
	
	
	
	//培训地点
	public function getTrain(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	//培训时间
	public function getTrainTime(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	

	//培训城市
	public function city(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	//关于我
	public function riderMessage(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	
	
	//我的装备
	public function riderMyEquip(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	
	//获取setFormid
	public function setFormid(){
		$user_id = $this->getUserId();
		$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>''));
	}
	
	
	public function uploadify(){
		
       $model = I('model','','htmlspecialchars') !='' ? I('model','','htmlspecialchars') : $_GET['model'] ;
       $yun = $this->superUpload($model);
        if($yun){
            foreach ($yun as $pk => $pv){
                $picurl = $pv['url'];
            }
			$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$picurl));
        }else{
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
			if(isset($this->_CONFIG['attachs'][$model]['thumb'])) {
				$upload->thumb = true;
				if(is_array($this->_CONFIG['attachs'][$model]['thumb'])) {
					$prefix = $w = $h = array();
					foreach($this->_CONFIG['attachs'][$model]['thumb'] as $k=>$v){
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
					list($w, $h) = explode('X', $this->_CONFIG['attachs'][$model]['thumb']);
					$upload->thumbMaxWidth = $w;
					$upload->thumbMaxHeight = $h;
				}
			}
			if(!$upload->upload()){
				$this->ajaxReturn(array('errno'=>0,'message'=>$upload->getErrorMsg(),'data'=>''));
			}else{
				$info = $upload->getUploadFileInfo();
				if(!empty($this->_CONFIG['attachs']['water'])){
					import('ORG.Util.Image');
					$Image = new Image();
					$Image->water(BASE_PATH . '/attachs/'. $name . '/thumb_' . $info[0]['savename'],BASE_PATH . '/attachs/'.$this->_CONFIG['attachs']['water']);
				}
				if($upload->thumb) {
					$picurl =  $this->_CONFIG['site']['host'].'/attachs/'.$name . '/thumb_' . $info[0]['savename'];
					$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$picurl));
			   }else{
					$picurl = $this->_CONFIG['site']['host'].'/attachs/'.$name . '/' . $info[0]['savename'];
					$this->ajaxReturn(array('errno'=>0,'message'=>'success','data'=>$picurl));
			   }
			 }
		}
    }
	
	//调用云存储
    public function superUpload($model){
        import('ORG.Net.Upload');
        $upinfo = M("uploadset")->where("status = 1")->find();
        if(!empty($upinfo) && $upinfo['type'] != 'Local'){
            $conf = json_decode($upinfo['para'], true);
            $superup = new Upload(array('exts'=>'jpeg,jpg,gif,png'), $upinfo['type'], $conf);
            $upres = $superup->upload();
            return  $upres;
        }else{
            return false;
        }
    }
	
	
	
}