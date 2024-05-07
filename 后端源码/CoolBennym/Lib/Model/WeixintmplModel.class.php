<?php
class WeixintmplModel extends CommonModel{
	protected $pk   = 'tmpl_id';
    protected $tableName =  'weixin_tmpl';
	
	
	protected $_validate = array(
		array('title','2,20','模板标题2至10个字符',Model::MUST_VALIDATE,'length',Model::MODEL_BOTH),
		array('serial','/^\w{3,}$/','请输入正确的模板库编号',Model::MUST_VALIDATE,'regex',Model::MODEL_BOTH),
		array('status','0,1','状态值不合法,必须0或1',Model::MUST_VALIDATE,'in',Model::MODEL_BOTH),
		array('sort','/^\d{1,4}$/','排序值不合法',Model::MUST_VALIDATE,'regex',Model::MODEL_BOTH),
	);
	
	protected $_OrderStatus = array(
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
	
	
	
	//构造
	protected function _initialize(){
        parent::_initialize();
		$this->config = D('Setting')->fetchAll();
		import("@/Net.Curl");
        $this->curl = new Curl();
    }
	
	
		

	
	
	//获取Formid
	public function getUserFormid($user_id = 0){
		$time=time()-60*60*24*7;
		
		//大于等于7天的都删除
		$formids = M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('ELT',$time)))->select();
		foreach($formids as $k=>$v){
			$res = M('user_formid')->where(array('id'=>$v['id']))->delete();
		}
		//查询大于等于7天的第一条
		$form=M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('EGT',$time)))->order('time desc')->find();
		//p($form);die;
		return $form;
	}

	//获取Formid数量
	public function getUserFormIdCount($user_id = 0){
	
	//大于等于7天的都删除
		$time=time()-60*60*24*7;
		$formids = M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('ELT',$time)))->select();
		foreach($formids as $k=>$v){
			$res = M('user_formid')->where(array('id'=>$v['id']))->delete();
		}
		//查询大于等于7天的第一条
		$count=(int)M('user_formid')->where(array('user_id'=>$user_id,'time'=>array('EGT',$time)))->count();
		return $count-1;
	}	

	
	//批量推送给配送员
	public function runningNoticeDelivery($running_id = 0){
		$detail = M('running')->where(array('running_id'=>$running_id))->find();
		
	
		//如果是到店自提就不管了
		if($detail['orderType'] == 2){
			return true;
		}
		//如果商家配送不能抢单
		if($detail['is_ele_pei'] == 1){
			return true;
		}
	
		
		//限制男女
		$map = array('audit'=>2,'closed'=>0,'school_id'=>$detail['school_id']);
		if($detail['LimitDelivererGender'] == 2){
			$map['Gender'] == 2;
		}elseif($detail['LimitDelivererGender'] == 1){
			$map['Gender'] == 1;
		}
		

		$list = M('running_delivery')->where($map)->select();
		$i = 0;
		foreach($list as $k=>$v){
			
			$connect = M('connect')->where(array('uid'=>$v['user_id'],'type'=>'weixin'))->find();
			
			//会员开启
			$users = M('users')->where(array('user_id'=>$v['user_id']))->find();
			$notifyFlag = $users['notifyFlag'];
			//类目开启
			$notify = M('running_cate_notify')->where(array('cate_id'=>$detail['cate_id'],'user_id'=>$v['user_id']))->find();
			//时间开启对比
			$notifyFrom = (int)rtrim($users['notifyFrom'],":00");
			$notifyEnd = (int)rtrim($users['notifyEnd'],":00");
			$time = date('H',time()); //当前时间
			if($time >= $notifyFrom && $time <= $notifyEnd){
				$times = 1;
			}elseif($notifyEnd == 0){
				$times = 1;
			}else{
				$times = 0;
			}
			
			if($notifyFlag && $notify && $times){
				$i++;
				file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$i.'_sendWxappNotice.txt', var_export($v,true));
				D('Weixintmpl')->sendWxappNotice($detail,$connect['openid'],$form='',$this->config['wxapp']['formid1'],$v['user_id'],$v);//循环发送给配送员
			}
			
		}
		return true;
	}
	
	
	//批量发送给配送员【小程序模板消息】【已废弃】
	public function sendWxappNoticeTmplMsg($detail,$openid,$form,$formid1,$user_id = 0,$v = array()){
		
		$keyword3 = $detail['title'] ? $detail['title'] : $detail['Remark'];
		
		$school = M('running_school')->find($detail['school_id']);
		$cate = M('running_cate')->find($detail['cate_id']);
		
		$count = $this->getUserFormIdCount($user_id);//获取Form剩余次数
		
	    $formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$formid1.'",
			"page":"pages/errand/_/index",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "订单号：'.$detail['running_id'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value":"尊敬的【'.$v['RealName'].'】，配送中心有新的订单等您去抢单哦",
					"color": "#173177"
				},
				"keyword3": {
					"value": "订单内容登录小程序查看，当前剩余模板消息通知次数【'.$count.'】次，请及时到个人中心点击增加收信息次数",
					"color": "#173177"
				},
				"keyword4": {
					"value": "订单学校【'.$school['Name'].'】，订单类别【'.$cate['cate_name'].'】",
					"color": "#173177"
				},
				"keyword5": {
					"value":  "'.date('Y-m-d H:i:s').'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
	
		
		if($form['form_id']){
			$send = $this->send($formwork,$formid1,$user_id,$openid,$form['form_id']);//推送封装
		}
		
		return $send;
	}
	
	
	//批量发送给配送员模板消息【订阅消息】【暂时没启用，后期可能废弃】
	public function sendWxappNoticeNewTmplMsg($detail,$openid,$form,$formid1,$user_id = 0,$v = array()){
		
		
		$title = $detail['title'] ? $detail['title'] : $detail['Remark'];
		
		$school = M('running_school')->where(array('school_id'=>$detail['school_id']))->find();
		$cate = M('running_cate')->where(array('cate_id'=>$detail['cate_id']))->find();
		
		$thing2 = '学校【'.$school['Name'].'】类别【'.$cate['cate_name'].'】';
		
		$formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$this->config['wxapp']['tid1'].'",
			"page":"pages/errand/_/index",
			
			"data": {
			  "character_string4": {
				  "value": "pei-'.$running_id.'"
			  },
			  "thing3": {
				  "value": "配送中心有新的订单"
			  },
			  "phrase9": {
				  "value": "'.$this->_OrderStatus[$detail['OrderStatus']].'"
			  } ,
			  "thing2": {
				  "value": "'.tu_msubstr($thing2,0,20,false).'"
			  } ,
			  "time5": {
				  "value": "'.date('Y-m-d H:i:s').'"
			  }
		   }
		}';

		if($this->config['wxapp']['tid1']){
			$send = $this->send($formwork,$formid1,$user_id,$openid,$form['form_id']);//推送封装【订阅消息】
		}
		
		return $send;
	}
	
	
	
	
	
	//批量发送给配送员模板消息【中间件】
	public function sendWxappNotice($detail,$openid,$form,$formid1,$user_id = 0,$v = array()){
		
		$tpmlMsgType = (int)$this->config['wxapp']['tpmlMsgType'];
		if($tpmlMsgType == 0){
			$this->runningWxappNoticeTmplMsg($detail,$openid,$form,$formid1,$user_id,$v);
		}elseif($tpmlMsgType == 1){
			//订阅消息不给配送员通知【已废弃】
			//$this->runningWxappNoticeNewTmplMsg($detail,$openid,$form,$formid1,$user_id,$v);
		}elseif($tpmlMsgType == 2){
			//配送员公众号模板消息通知
			D('Weixinmsg')->wxTmplRunningDeliveryMsg($detail['running_id'],$detail['OrderStatus'],$user_id,1,$openid='',$form_id='');
		}
		return false;
	}
	
	
	
	//后台批量模板消息推送循环通知【小程序模板消息】【已废弃】
    public function weixin_admin_push($detail,$user_id){
		
		$users = M('users')->find($user_id);
		$connect = M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->find();
		$openid = $openid ? $openid : $connect['openid'];
		
		$form=$this->getUserFormid($user_id);//获取Form
		
		$formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$this->config['wxapp']['formid2'].'",
			"page":"'.$detail['url'].'",
			"form_id":"'.$form['form_id'].'",
			"data": {
				"keyword1": {
					"value": "'.$detail['push_id'].'",
					"color": "#173177"
				},
				"keyword2": {
					"value":"消息通知",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$users['nickname'].'",
					"color": "#173177"
				},
				"keyword4": {
					"value": "'.niuMsubstr($detail['intro'],0,60,false).'",
					"color": "#173177"
				},
				"keyword5": {
					"value":  "'.date('Y-m-d H:i:s',time()).'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
		if($form['form_id']){
			$send = $this->send($formwork,$this->config['wxapp']['formid2'],$user_id,$openid,$form['form_id']);//推送封装【模板消息小程序】
		}
		return $send;
    }
	
	
	
	
	//订单通用通知模板消息中间件
	public function runningWxappNotice($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		
		$tpmlMsgType = (int)$this->config['wxapp']['tpmlMsgType'];
		if($tpmlMsgType == 0){
			$this->runningWxappNoticeTmplMsg($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}elseif($tpmlMsgType == 1){
			$this->runningWxappNoticeNewTmplMsg($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}elseif($tpmlMsgType == 2){
			//公众号万能模板消息
			D('Weixinmsg')->wxTmplRunningMsg($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}
	    return false;
	 }
	 
	 
	 
	 
	//订单通用通知【订阅消息】
	public function runningWxappNoticeNewTmplMsg($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		if($running['OrderStatus'] == 16){
			$this->runningWxappNoticeNewTmplMsg16($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}elseif($running['OrderStatus'] == 32){
			$this->runningWxappNoticeNewTmplMsg32($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}elseif($running['OrderStatus'] == 64){
			$this->runningWxappNoticeNewTmplMsg64($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}
	    return true;
	 }
	 
	 
	
	
	//订单配送通知【订阅消息】【16】
	public function runningWxappNoticeNewTmplMsg16($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		 
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		
		$user_id = $user_id ? $user_id :  $running['user_id'];
		
		$connect = M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->find();
		$openid = $openid ? $openid : $connect['openid'];
		
		$delivery = M('running_delivery')->where(array('delivery_id'=>$running['delivery_id']))->find();
		
		$title = $running['title'] ? $running['title'] : $running['Remark'];
		
	    $formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$this->config['wxapp']['tid1'].'",
			"page":"pages/order/_/info?id='.$running_id.'",
			
			"data": {
			  "character_string1": {
				  "value": "tu16-'.$running_id.'"
			  },
			  "name4": {
				  "value": "'.tu_msubstr($delivery['RealName'],0,20,false).'"
			  },
			  "phone_number10": {
				  "value": "'.$delivery['phoneNumber'].'"
			  } ,
			  "thing8": {
				  "value": "'.tu_msubstr($title,0,20,false).'"
			  } ,
			  "date3": {
				  "value": "'.date('Y-m-d H:i:s').'"
			  }
		    }
		 }';
		if($this->config['wxapp']['tid1']){
			$send = $this->send($formwork,$this->config['wxapp']['tid1'],$user_id,$openid,$form_id);//推送封装【一次性订阅消息】
		}
	    return $send;
	 }
	 
	 
	 
	 
	//订单送达成功通知【订阅消息】【32】
	public function runningWxappNoticeNewTmplMsg32($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		 
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		
		$user_id = $user_id ? $user_id :  $running['user_id'];
		
		$connect = M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->find();
		$openid = $openid ? $openid : $connect['openid'];
		
		$delivery = M('running_delivery')->where(array('delivery_id'=>$running['delivery_id']))->find();
		
		$title = $running['title'] ? $running['title'] : $running['Remark'];
		
	    $formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$this->config['wxapp']['tid2'].'",
			"page":"pages/order/_/info?id='.$running_id.'",
			
			"data": {
			  "number1": {
				  "value": "tu32-'.$running_id.'"
			  },
			  "phrase2": {
				  "value": "'.$this->_OrderStatus[$OrderStatus].'"
			  },
			  "name4": {
				  "value": "'.tu_msubstr($delivery['RealName'],0,20,false).'"
			  } ,
			  "phone_number5": {
				  "value": "'.$delivery['phoneNumber'].'"
			  } ,
			  "time3": {
				  "value": "'.date('Y-m-d H:i:s').'"
			  }
		    }
		 }';
		if($this->config['wxapp']['tid2']){
			$send = $this->send($formwork,$this->config['wxapp']['tid2'],$user_id,$openid,$form_id);//推送封装【一次性订阅消息】
		}
	    return $send;
	 }
	 
	 
	//订单完成通知【订阅消息】【64】
	public function runningWxappNoticeNewTmplMsg64($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		 
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		
		$user_id = $user_id ? $user_id :  $running['user_id'];
		
		$connect = M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->find();
		$openid = $openid ? $openid : $connect['openid'];
		
		$delivery = M('running_delivery')->where(array('delivery_id'=>$running['delivery_id']))->find();
		
		$title = $running['title'] ? $running['title'] : $running['Remark'];
		
		if($running['Type'] == 2){
			$thing10 = '跑腿订单';
		}else{
			$thing10 = '订餐订单';
		}
		
	    $formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$this->config['wxapp']['tid3'].'",
			"page":"pages/order/_/info?id='.$running_id.'",
			
			"data": {
			  "number1": {
				  "value": "tu64-'.$running_id.'"
			  },
			  "thing9": {
				  "value": "'.$this->_OrderStatus[$OrderStatus].'"
			  },
			  "thing10": {
				  "value": "'.$thing10.'"
			  } ,
			  "thing5": {
				  "value": "您的订单已经完成"
			  } ,
			  "time6": {
				  "value": "'.date('Y-m-d H:i:s').'"
			  }
		    }
		 }';
		if($this->config['wxapp']['tid3']){
			$send = $this->send($formwork,$this->config['wxapp']['tid3'],$user_id,$openid,$form_id);//推送封装【一次性订阅消息】
		}
	    return $send;
	 }
	 
	
	
	//订单通用通知【小程序模板消息】【已废弃】
	public function runningWxappNoticeTmplMsg($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		 
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		$user_id = $user_id ? $user_id :  $running['user_id'];
		
		$connect = M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->find();
		$openid = $openid ? $openid : $connect['openid'];
		
		$form=$this->getUserFormid($user_id);//获取Form
		$form_id = $form_id ? $form_id : $form['form_id'];
		
		if($running['delivery_id']){
			$delivery = M('running_delivery')->where(array('delivery_id'=>$running['delivery_id']))->find();
			$intro = '配送员姓名：'.$delivery['RealName'].'配送员电话：'.$delivery['phoneNumber'];
		}else{
			$intro = '订单备注';
		}
		
		$keyword3 = $running['title'] ? $running['title'] : $running['Remark'];
		
	    $formwork ='{
			"touser": "'.$openid.'",
			"template_id": "'.$this->config['wxapp']['formid1'].'",
			"page":"pages/order/_/info?id='.$running_id.'",
			"form_id":"'.$form_id.'",
			"data": {
				"keyword1": {
					"value": "'.$running_id.'",
					"color": "#173177"
				},
				"keyword2": {
					"value":"'.$this->_OrderStatus[$OrderStatus].'",
					"color": "#173177"
				},
				"keyword3": {
					"value": "'.$keyword3.'",
					"color": "#173177"
				},
				"keyword4": {
					"value": "'.$intro.'",
					"color": "#173177"
				},
				"keyword5": {
					"value":  "'.date('Y-m-d H:i:s').'",
					"color": "#173177"
				}
			},
			"emphasis_keyword": "keyword1.DATA"  
		}';
		if($form_id){
			$send = $this->send($formwork,$this->config['wxapp']['formid1'],$user_id,$openid,$form_id);//推送封装【模板消息小程序】
		}
	    return $send;
	 }
	
	
	//主体发送
	public function send($formwork = '',$formid = '',$user_id = '',$openid = '',$form_id = 0){
        $sendMessage = $this->sendMessage($formwork);
	    $sendMessage= json_decode($sendMessage,true);
	    $formworks= json_decode($formwork,true);
		
		//p($sendMessage);die;
		
	    $arr['template_id'] = $formid;//支付成功模板
		$arr['user_id'] = $user_id;
		$arr['open_id'] = $openid;
		$arr['serial'] = $sendMessage['errcode'].'--'.$sendMessage['errmsg'];
		$arr['info'] = $arr['serial'];
		$html = '';
		foreach($formworks['data'] as $v){
			$html .= $v['value'].'<br>';
		}
		$arr['comment'] = $html;
		$arr['create_time'] = time();
		$arr['create_ip'] = get_client_ip();
		
		if($arr){
			$msg_id = M('weixin_msg')->add($arr);
		}
		
		$rest=M('user_formid')->where(array('form_id'=>$form_id))->delete();
		
		return array('msg_id'=>$msg_id,'sendMessage'=>$sendMessage);
    }
	
	

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


	public function sendMessage($formwork){
	    $access_token = $this->getaccess_token();
	   
	    $tpmlMsgType = (int)$this->config['wxapp']['tpmlMsgType'];
		if($tpmlMsgType == 0){
			$url = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=".$access_token."";
		}else{
			$url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=".$access_token."";
		}

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
	    curl_setopt($ch, CURLOPT_POST,1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,$formwork);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}
	
	
	//删除公众号模板消息【公众号模板消息】
	public function deleteUseAutoTemplate(){
		@set_time_limit(0);
		
		
		$re_access_token =$this->getaccess_token();
		$send_url ="https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token={$re_access_token}";
		
		$data = array();
		$data['offset'] = 0;
		$data['count'] = 20;
		
		$result_json = $this->sendhttps_post($send_url, json_encode($data));
		
		$result = json_decode($result_json, true);
		
		$del_title_arr = array('订单配送通知','订单送达成功通知','订单完成通知','订单修改通知','新订单提醒');
		$del_template_arr = array();
		
		if($result['errcode'] == 0){
			foreach( $result['list'] as $val ){
				if( in_array($val['title'], $del_title_arr)){
					$del_template_arr[] = $val['template_id'];
				}
			}
		}
		
		if(!empty($del_template_arr)){
			foreach($del_template_arr as $vv){
				$send_url ="https://api.weixin.qq.com/cgi-bin/wxopen/template/del?access_token={$re_access_token}";
				$data = array();
				$data['template_id'] = $vv;
				$result_json = $this->sendhttps_post($send_url, json_encode($data));
			}
		}
	}
	
	//这里是公众号的模板消息添加【公众号模板消息】
	public function mangeTemplateAuto($type = 1){
		
		$delete = $this->deleteUseAutoTemplate();
		//p($delete);die;
		@set_time_limit(0);
		
		$re_access_token = $this->getaccess_token();
		$send_url ="https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token={$re_access_token}";
		
		
		$arr = $data = array();
		
		$arr['id'] = 'OPENTM406085250';
		$arr['keyword_id_list'] = array(1,13,3,6,77,44,50);//这里有问题
		$data[0]['type'] = $type;
		$data[0]['name'] = '订单修改通知';
		$data[0]['serial'] = 'OPENTM406085250';
		$data[0]['template_id'] = $result['template_id'];
		$result_json = $this->sendhttps_post($send_url,json_encode($data));
		$result = json_decode($result_json,true);
		if($result['errcode'] == 0){
			$data[0]['template_id'] =  $result['template_id'];
		}
		
		
		$arr['id'] = 'OPENTM406085250';
		$arr['keyword_id_list'] = array(1,13,3,6,77,44,50);//这里有问题
		$data[1]['type'] = $type;
		$data[1]['name'] = '新订单提醒';
		$data[1]['serial'] = 'OPENTM400045127';
		$result_json = $this->sendhttps_post($send_url,json_encode($data));
		$result = json_decode($result_json, true);
		if($result['errcode'] == 0){
			$data[1]['template_id'] =  $result['template_id'];
		}
		
		
		$i = 0;
		if(is_array($data)){
			foreach($data as $v){
				$i++;
				M('weixin_tmpl')->add($v);
			}
		}
		$this->ajaxReturn(array('code'=>1,'msg'=>'已添加{$i}条公众号模板消息'));
	}	
	
	
	
	//这里是公众号的模板消息添加【订阅消息】
	public function autoSubscribeTemplateConfig($type = 1){
		
		$delete = $this->deleteUseAutoTemplate();
		//p($delete);die;
		@set_time_limit(0);
		
		
		$re_access_token = $this->getaccess_token();
		
		$category_url = "https://api.weixin.qq.com/wxaapi/newtmpl/getcategory?access_token={$re_access_token}";
		
	    $result_category =array();
		$result_category_json = $this->curl_category($category_url);
		
		$result_category = json_decode($result_category_json, true);	
		
		
		$name = array_column($result_category['data'],'name');
	
		$found_supermarket = in_array("线下超市/便利店", $name);
		$found_clothing = in_array("快递、物流", $name);
		
		if(empty($found_supermarket) && empty($found_clothing)){
			if(empty($found_supermarket)){
				$this->ajaxReturn(array('code'=>0,'msg'=>'请在微信公众平台添加 "线下超市/便利店" 类目'));
			}
			if(empty($found_clothing)){
				$this->ajaxReturn(array('code'=>0,'msg'=>'请在微信公众平台添加 "快递、物流 " 类目'));
			}
		}else{
		
		
			$del_url = "https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate?access_token={$re_access_token}";
			$send_url ="https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token={$re_access_token}";

		
			$data = $arr = array();
			
			//订单完成通知
			$data['tid'] = '3676';
			$data['kidList'] = array(1,9,10,5,6);
			$data['sceneDesc'] ='订单完成通知';
			//先删除再添加
			$arr = array();
			$arr['priTmplId'] = M('weixin_tmpl')->where(array('type'=>$type,'title'=>$data['sceneDesc']))->find();
			if(!empty($arr['priTmplId'])){
				$result_del_json = $this->curl_datas($del_url,$arr);
				$result_del = json_decode($result_del_json, true);			
			}
			$arr[0]['type'] = $type;
			$arr[0]['name'] = '订单完成通知';
			$arr[0]['serial'] = '快递、物流';
			$result_json = $this->curl_datas($send_url,$data);
			$result = json_decode($result_json, true);
			if($result['errcode'] == 0){
				$arr[0]['template_id'] = $result['priTmplId'];
			}
			
			
			//订单送达成功通知
			$data['tid'] = '3569';
			$data['kidList'] = array(1,2,4,5,3);
			$data['sceneDesc'] ='订单送达成功通知';
			//先删除再添加
			$arr = array();
			$arr['priTmplId'] = M('weixin_tmpl')->where(array('type'=>$type,'title'=>$data['sceneDesc']))->find();
			if(!empty($arr['priTmplId'])){
				$result_del_json = $this->curl_datas($del_url,$arr);
				$result_del = json_decode($result_del_json, true);			
			}
			$arr[0]['type'] = $type;
			$arr[0]['name'] = '订单送达成功通知';
			$arr[0]['serial'] = '快递、物流';
			$result_json = $this->curl_datas($send_url,$data);
			$result = json_decode($result_json, true);
			if($result['errcode'] == 0){
				$arr[0]['template_id'] = $result['priTmplId'];
			}
			
			//订单配送通知
			$data['tid'] = '1128';
			$data['kidList'] = array(1,4,10,8,3);
			$data['sceneDesc'] ='订单配送通知';
			$arr = array();
			$arr['priTmplId'] = M('weixin_tmpl')->where(array('type'=>$type,'title'=>$data['sceneDesc']))->find();
			if(!empty($arr['priTmplId'])){
				$result_del_json = $this->curl_datas($del_url,$arr);
				$result_del = json_decode($result_del_json, true);			
			}
			$arr[0]['type'] = $type;
			$arr[0]['name'] = '订单配送通知';
			$arr[0]['serial'] = '线下超市/便利店';
			$result_json = $this->curl_datas($send_url,$data);
			$result = json_decode($result_json, true);
			if($result['errcode'] == 0){
				$arr[0]['template_id'] = $result['priTmplId'];
			}
		
			$i = 0;
			if(is_array($data)){
				foreach($data as $v){
					$i++;
					M('weixin_tmpl')->add($v);
				}
			}
			$this->ajaxReturn(array('code'=>1,'msg'=>'已添加{$i}订阅号模板消息'));
	    }	
	}
	
	
	public function curl_category($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
		    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$handles = curl_exec($ch);
		curl_close($ch);
		return $handles;
	}
	
	
	public function curl_datas($url,$data,$timeout=30){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
		    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		}
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		$handles = curl_exec($ch);
		curl_close($ch);
		return $handles;
	}
	
	
	public function sendhttp_get($url){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,array());
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}


	public function sendhttps_post($url,$data){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
		curl_setopt($curl,CURLOPT_POST,1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($curl);
		if(curl_errno($curl)){
		  return 'Errno'.curl_error($curl);
		}
		curl_close($curl);
		return $result;
	}
	
	
	

}