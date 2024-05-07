<?php
class NewsAction extends CommonAction{
	
	
	protected function _initialize(){
        parent::_initialize();
		//获取全局配置 
		$this->config = D('Setting')->fetchAll();
    }
	
	
	
	
	
	//测试通知
	public function tz($running_id = 0){
		$running_id = (int) $this->_param('running_id');
		$v = M('running')->where(array('running_id'=>$running_id))->find();
		$a = D('Weixintmpl')->runningWxappNotice($running_id,$v['OrderStatus'],$user_id= '',$type=1,$openid='',$form_id='');
		p($a);die;
	}
	
	//测试通知2
	public function tz2($running_id = 0){
		$running_id = (int) $this->_param('running_id');
		$a = D('Weixintmpl')->runningNoticeDelivery($running_id);
		p($a);die;
	}
	
	
	
	//打印
	public function dayin($running_id = 0){
		$running_id = (int) $this->_param('running_id');
		$a = D('Running')->combinationElePrint($running_id);
		p($a);die;
	}
	
	//结算
	public function jiesuan($running_id = 0,$running_id = 0){
		$running_id = (int) $this->_param('running_id');
		$delivery_id = (int) $this->_param('delivery_id');
		$a = D('Running')->runingSettlement($running_id,$delivery_id,$labels='测试',$content='测试',$score = 5);
		p($a);die;
	}
	
	//首页
	public function index(){
		$role = (int) $this->_param('role');
		$this->assign('role',$role);
		$running_id = (int) $this->_param('running_id');
		$this->assign('running_id',$running_id);
		
		$src = 'pages/errand/_/index';
		$this->assign('src',$src);
		$this->display();
	}
	//公众号授权
	public function authorize(){
		 
	
		$appid = $this->config['weixin']['appid'];
		
		$IS_WEIXIN =  is_weixin();
		$url = urlencode(__HOST__.U('wap/news/wxstart'));
		
		
		if($IS_WEIXIN && $act != 'wxstart'){
			$state = md5(uniqid(rand(),TRUE));
			session('state',$state);
			if(!empty($_SERVER['REQUEST_URI'])){
				$backurl = $_SERVER['REQUEST_URI'];
			}else{
				$backurl = U('news/index');
			}
			cookie('backurl',$backurl);
			$login_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid .'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';
			header("location:{$login_url}");
			echo $login_url;
			die;
		}
    }
	
	
	public function wxstart(){
		$appid = $this->config['weixin']['appid'];
		$appsecret = $this->config['weixin']['appsecret'];
		
		$state = session('state');
        if($_REQUEST['state']){
            import('@/Net.Curl');
            $curl = new Curl();
            if(empty($_REQUEST['code'])){
                $this->error('授权后才能登陆', U('passport/login'));
            }
            $token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid .'&secret='.$appsecret.'&code='.$_REQUEST['code'].'&grant_type=authorization_code';
            $str = $curl->get($token_url);
            $params = json_decode($str, true);
            if(!empty($params['errcode'])){
                echo '<h3>error:</h3>' . $params['errcode'];
                echo '<h3>msg  :</h3>' . $params['errmsg'];
                die;
            }
            if(empty($params['openid'])){
                $this->error('登录失败',U('news/index'));
            }
            $info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$params['access_token'].'&openid='.$params['openid'].'&lang=zh_CN';
            $info = $curl->get($info_url);
            $info = json_decode($info, true);
            $data = array(
				'type' => 'weixin', 
				'open_id' => $params['openid'], 
				'token' => $params['refresh_token'], 
				'unionid' => $info['unionid'], 
				'nickname' => $info['nickname'], 
				'headimgurl' => $info['headimgurl']
			);
            $this->wxAutoRegistr($data);
        }
    }
	
	
	//自动注册
    private function wxAutoRegistr($data){
		
		//p($data);die;
		if(!$data['unionid']){
			$this->error('获取unionid失败');
	    }
		if(!$data['open_id']){
			$this->error('获取open_id失败');
	    }
		
		$connect = M('connect')->where(array('unionid'=>$data['unionid']))->order(array('create_time'=>'asc'))->find();
	    if(!$connect){
			$this->error('获取connect信息失败，请先进去小程序后再来绑定');
	    }
		$users = M('users')->where(array('user_id'=>$connect['uid']))->find(); 
		if(!$users){
			$this->error('获取users信息失败，行先进去小程序登录后再来绑定');
	    }
		
		M('connect')->save(array('connect_id'=>$connect['connect_id'],'open_id' =>$data['open_id']));// 注册成功智能跳转
		M('users')->save(array('user_id'=>$connect['uid'],'open_id' =>$data['open_id']));// 注册成功智能跳转
		
		header('Location:' . U('wap/news/index'));die;
    }
	
	
	//成功后跳转
	public function goToUrl($url){
		$backurl = cookie('backurl');
		if($backurl){
			$res = strpos($backurl,'login');
			if($res){
				cookie('backurl',null);
				header('Location:' . U('wap/news/index'));die;
			}else{
				cookie('backurl',null);
				header('Location:' .$backurl);die;
			}
		}else{
			header('Location:' . U('wap/news/index'));die;
		}
	}
		
	
    public function info($article_id = 0){

        if($article_id = (int) $article_id){
            if(!$detail = M('Article')->find($article_id)){
                $this->error('没有该文章');
            }
			if($detail['closed'] != 0 ){
            	$this->error('该文章不存在');
            }	
			$detail['shop'] = D('Shop')->where(array('shop_id'=>$detail['shop_id']))->find();
            $this->assign('detail', $detail);
			
            $this->display();
        }else{
            $this->error('没有该文章');
        }
    }
	

    public function detail($article_id = 0){

        if($article_id = (int) $article_id){
            if(!$detail = M('Article')->find($article_id)){
                $this->error('没有该文章');
            }
			if($detail['closed'] != 0 ){
            	$this->error('该文章不存在');
            }	
			$detail['shop'] = D('Shop')->where(array('shop_id'=>$detail['shop_id']))->find();
            $this->assign('detail', $detail);
			
            $this->display();
        }else{
            $this->error('没有该文章');
        }
    }
	
	//上传附件
	public function files(){
		$this->display();
	}

}