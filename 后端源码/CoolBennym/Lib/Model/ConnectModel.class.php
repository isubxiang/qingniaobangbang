<?php
class  ConnectModel extends Model{
    protected $pk   = 'connect_id';
    protected $tableName =  'connect';
    
	
	
    public function getConnectByOpenid($type,$open_id){
		$data = M('connect')->where(array('type'=>$type,'uid'=>$user_id))->order(array('create_time'=>'asc'))->find();  
   		return $data;    
    }
	
	
    public function getConnectByUid($uid){
		$data = M('connect')->where(array('uid'=>$user_id))->order(array('create_time'=>'asc'))->find();   
		
        return $data;     
    }
	
	

    public function user_info($client_id,$openid,$access_token){
        $url = 'https://graph.qq.com/user/get_user_info?oauth_consumer_key='.$client_id.'&access_token='.$access_token.'&openid='.$openid.'&format=json';
        $str = $this->visit_url($url);
        $arr = json_decode($str,true);
      return $arr;

		
		
    }

    public function wx_user_info($openid,$access_token){
       $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid;
       $str = $this->visit_url($url);
       $arr = json_decode($str,true);
     return $arr;
	

		
		
    }
	
	
    public function wx_user_autoinfo($openid,$access_token){
	    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $str = $this->visit_url($url);
        $arr = json_decode($str,true);
        return $arr;	
    }
	
	
	

    public function visit_url($url){
        static $cache = 0;
        if($cache === 1){
            $str = $this->curl($url);
        }elseif($cache === 2){
            $str = $this->openssl($url);
        }else{
            if(function_exists('curl_init')){
                $str = $this->curl($url);
                $cache = 1;
            }elseif(function_exists('openssl_open') && ini_get("allow_fopen_url")=="1"){
                $str = $this->openssl($url);
                $cache = 2;
            }else{
                die('请开启php配置中的php_curl或php_openssl');
            }
        }
        return $str;
    }
	
	
    private function curl($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_URL,$url);
        $str = curl_exec($ch);
        curl_close($ch);
        return $str;
    }



    private function openssl($url){
        $str = file_get_contents($url);//取得页面内容
        return $str;
    }
	
	
	public function getWxappOpenid($user_id,$openid,$type = 'running'){
		if($user_id){
			$connect = M('connect')->where(array('type'=>'weixin','uid'=>$user_id))->order(array('create_time'=>'asc'))->find();
			return $connect['openid'] ? $connect['openid'] : $openid;
			
		}else{
			return $openid;
		}
    }
	
	
	
	
	
	//是否绑定
    public function check_connect_bing($user_id,$type_id){
		if($type_id ==1){
			$type = 'weixin';
		}elseif($type_id ==2){
			$type = 'qq';
		}elseif($type_id ==3){
			$type = 'weibo';
		}
        $detail = M('connect')->where(array('uid'=>$user_id,'type'=>$type))->find();
		
        return $detail;
    }
	
	
	
}