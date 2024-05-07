<?php
class WeixinmsgModel extends CommonModel{
    protected $pk = 'msg_id';
    protected $tableName = 'weixin_msg';
	
	
	//构造
	protected function _initialize(){
        parent::_initialize();
		$this->config = D('Setting')->fetchAll();
    }
	
	
	
	
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
	
	
	
	//万能订单公众号微信模板消息通知
    public function wxTmplRunningMsg($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		
		$v = M('running')->where(array('running_id'=>$running_id))->find();
		
		//发送订阅消息
		if($v['OrderStatus'] == 16){
			D('Weixintmpl')->runningWxappNoticeNewTmplMsg16($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}elseif($v['OrderStatus'] == 32){
			D('Weixintmpl')->runningWxappNoticeNewTmplMsg32($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}elseif($v['OrderStatus'] == 64){
			D('Weixintmpl')->runningWxappNoticeNewTmplMsg64($running_id,$OrderStatus,$user_id,$type,$openid,$form_id);
		}
		
		
		
		//发送公众号消息
		
		$user_id = $user_id ? $user_id :  $v['user_id'];
		
		$connect = M('connect')->where(array('uid'=>$user_id,'type'=>'weixin'))->find();
		$openid = $connect['open_id'];
		
		
		if($type == 1){
			$typeName = '[尊敬的会员]';
		}else{
			$typeName = '[尊敬的商家]';
		}
		
		$title = $v['title'] ? $v['title'] : $v['Remark'];
		
		$remark = $typeName.'订单状态【'.$this->_OrderStatus[$OrderStatus].'】通知';
			
				
		$user_id = $this->getUserId($running_id,$type);
		
		$first = '订单状态【'.$this->_OrderStatus[$OrderStatus].'】状态码【'.$OrderStatus.'】';

		$array = array(
			'url' => $this->getUrl($running_id,$type), 
			'first' => $first, 
			'remark' => $remark, 
			'keyword1' => $this->_OrderStatus[$OrderStatus],
			'keyword2' => date("Y-m-d H:i:s",time()), 
		);
		$data = $this->wxTmplRunningMsgData($array);
		$this->net($user_id,'OPENTM406085250',$data);
		return true;
    }
	
	
	//wxTmplRunningMsg推送组合
	public function wxTmplRunningMsgData($data=null){
		return array(
			'touser'=> '',
			'url'=> $data['url'],
			
			'miniprogram' => array(
				'appid' => $this->config['wxapp']['appid'],
				'pagepath' => 'pages/errand/_/index',
			),
			
			'template_id'  => '',
			
			
			'topcolor'=> $data['topcolor'],
			'data'=> array(
				'first'=>array('value'=>$data['first'],'color'=>'#000000'),
				'keyword1'=>array('value'=>$data['keyword1'],'color'=>'#000000'), 
				'keyword2'=>array('value'=>$data['keyword2'],'color'=>'#000000'),
				'remark' =>array('value'=>$data['remark'],'color'=>'#000000')
			)
		);
	}
	
	
	
	//获取发送微信模板消息的主体
	public function getUserId($running_id,$type){
		
		$v = M('running')->where(array('running_id'=>$running_id))->find();
		
		if($type == 1){
			return $v['user_id'];
		}else{
			$s = M('shop')->where(array('shop_id'=>$v['ShopId']))->find();
			return $s['user_id'];;
		}
	}
	
	//获取订单支付信息的URL
	public function getUrl($running_id,$type){
		if($type == 1){
			return $this->config['site']['host'].'/wap/news/index/role/1/running_id/'.$running_id;
		}else{
			return $this->config['site']['host'].'/seller';
		}
	}
	
	
	//公众号抢单配送发送微信模板消息wxTmplRunningDeliveryMsg
	public function wxTmplRunningDeliveryMsg($running_id,$OrderStatus,$user_id= '',$type=1,$openid='',$form_id=''){
		
		$running = M('running')->where(array('running_id'=>$running_id))->find();
		$delivery = M('running_delivery')->where(array('user_id'=>$user_id))->find();
		
		
		$user_id = $user_id ? $user_id :  $delivery['user_id'];
		
		$array = array(
			'url' => $this->config['site']['host'].'/wap/news/index/role/2/running_id/'.$running_id,
			'first' => '尊敬的【'.$delivery['RealName'].'】，配送中心有新的订单号【'.$running_id.'】等您去抢单哦',
			'remark' => '请尽快前去抢单，不然就被别人捷足先登了哦',
			'keyword1'  => $running_id,
			'keyword2' => date("Y-m-d H:i:s",time()),
	   );
	 
	  file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$user_id.'_wxTmplRunningDeliveryMsg.txt', var_export($array,true));
	   
	   $rest = M('weixin_msg')->where(array('running_id'=>$running_id,'user_id'=>$user_id,'type'=>'running'))->find(); 
	   if(!$rest){
		   
		  file_put_contents(BASE_PATH.'/Tudou/Lib/Model/'.$user_id.'_send_wxTmplRunningDeliveryMsg.txt', var_export($array,true));
		   
		  $data = $this->wxTmplRunningDeliveryMsgData($array);
		  $return = $this->net($user_id,'OPENTM400045127',$data,$running_id,'running'); 
	   }
     
       return true;
    }
	
	
	//wxTmplRunningDeliveryMsgData推送组合
	public function wxTmplRunningDeliveryMsgData($data=null){
		return array(
			'touser'=> '',
			'url'=> $data['url'],
			
			'miniprogram' => array(
				'appid' => $this->config['wxapp']['appid'],
				'pagepath' => 'pages/errand/_/index',
			),
			
			'template_id'  => '',
			'topcolor'=> $data['topcolor'],
			'data'=> array(
				'first'=>array('value'=>$data['first'],'color'=>'#000000'),
				'keyword1'=>array('value'=>$data['keyword1'],'color'=>'#000000'), 
				'keyword2'=>array('value'=>$data['keyword2'],'color'=>'#000000'), 
				'remark' =>array('value'=>$data['remark'],'color'=>'#000000')
			)
		);
	}		
	
	
	
	//发送微信模板消息方法
	public function net($uid,$serial=null,$data=null,$running_id,$type = 'running'){
		
		$uid = (int)$uid;
		$openid = M('connect')->where(array('type'=>'weixin'))->getFieldByUid($uid,'open_id'); 
		
		if($openid){
			
            $template_id = D('Weixintmpl')->getFieldBySerial($serial,'template_id');//支付成功模板
			
			
			$data['template_id'] = trim($template_id);
            $data['touser']  = $openid;
			$msg = array();
			$msg['user_id'] = $uid;
			$msg['open_id'] = $openid;
			$msg['running_id'] = $running_id;
			$msg['order_id'] = $running_id;
			$msg['type'] = $type;
			$msg['serial'] = $serial;
			$msg['template_id'] = $data['template_id'];
			
			$html = '【公众号模板消息】uid_【'.$uid.'】open_id_【'.$openid.'】template_id_【'.$data['template_id'].'】<br>';
			
			foreach ($data['data'] as $v) {
				$html .= $v['value'].'<br>';
			}
			$msg['comment'] = $html;
			$msg['create_time'] = time();
			$msg['create_ip'] = get_client_ip();
			
			
			if($msg_id = D('Weixinmsg')->add($msg)){
				return D('Weixin')->tmplmesg($data,$msg_id);
			};
		}
		return true;
	}
	
	
	
}