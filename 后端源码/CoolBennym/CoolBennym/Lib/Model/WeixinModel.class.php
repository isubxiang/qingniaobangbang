<?php


class WeixinModel {

    /**
     * 微信推送过来的数据或响应数据
     * @var array
     */
    private $data = array();
    private $token = 'weixintoken'; 
    private $access_token = '';
    private $config = array();
    private $curl = null;

    /**
     * 构造方法，用于实例化微信SDK
     * @param string $token 微信开放平台设置的TOKEN
     */
    public function __construct() {
        import("@/Net.Curl");
        $this->curl = new Curl();
    }
    
    public function mass($data,$shop_id = 0){
        $token = $this->getToken($shop_id);
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$token.'&type=thumb';
        $result = $this->curl->post($url,array('media'=>'@' . realpath (realpath(__ROOT__).config_img($data['photo']))));
        $result = json_decode($result,true);
        if($result['errcode']){
             return  $result['errcode'].$result['errmsg'];
        }
        $msg['articles']= array(
            array(
                'thumb_media_id' => $result['thumb_media_id'],
                'author'=> $_SERVER['HTTP_HOST'],
                'title'=> urlencode($data['title']),  
                'content_source_url'=> $data['url'],
                'content'=> urlencode($data['contents']),
                'show_cover_pic'=> 1,
            ),
        );
		$msg= urldecode(json_encode($msg));
        $result2 = $this->curl->post('https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$token,$msg);
		$result2 = json_decode($result2,true);
        if($result2['errcode']){
             return  $result2['errcode'].$result2['errmsg'];
        }else{
			$datas = array();
			$datas['media_id'] = $result2['media_id'];
			$datas['thumb_media_id'] = $result2['media_id'];
			$datas['author'] = $_SERVER['HTTP_HOST'];
			$datas['title'] = $data['title'];
			$datas['content_source_url'] = $data['url'];
			$datas['content'] = $data['contents'];
			$datas['show_cover_pic'] =1;
			$datas['create_time'] =$result2['created_at'];
			if(D('WeixinMass')->add($datas)){
				return true;
			}
			return '素材添加成功，可是写入数据库失败';
		}
        return true;
    }
    
  
    //判断是否关注公众号接口
	public function subscribe($uid){
		$config = D('Setting')->fetchAll();
		
		if(empty($uid)){
			return false;
		}
		
		$open_id = D('Connect')->where(array('uid'=>$uid,'type'=>'weixin'))->getField('open_id');
		if(empty($open_id)){
			return false;
		}
		$token = $this->getSiteToken();
		
		if(empty($token)){
			return false;
		}
		
		
		$info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid='.$open_id;
		$info = $this->curl->get($info_url);
		$info = json_decode($info,true);

		
		if(@$info['subscribe'] == 1){
			return true;
		}else{
			$this->error = $info['errmsg'];
			return false;
		}
		return false;
	}
	

    public function tmplmesg($data,$msg_id){
        $site_token = $this->getSiteToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$site_token}";
		
		
		//p(json_encode($data));die;
		
        $result = $this->curl->post($url, json_encode($data));
		
		
        $result = (array)json_decode($result);
        if($result['errcode']){
			D('Weixinmsg')->where(array('msg_id' => $msg_id))->save(array('status' => $result['errcode'],'info'=>$result['errmsg']));
            return true;//忽略报错
        }else{
			D('Weixinmsg')->where(array('msg_id' => $msg_id))->save(array('status' => $result['errcode'],'info'=>$result['errmsg']));
			return true;
		}
        return true;
    }
	
	
	
    /*
     * 账号后台模板ID 
     * @param  string $short_id 模板库模板ID
     * @return string 账号后台模板ID
     */
    public function getTmplId($short_id){
        $site_token = $this->getSiteToken();
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$site_token}";
        $result = $this->curl->post($url, json_encode(array('template_id_short'=>$shop_id)));
        $result = (array)json_decode($result);
        if($result['errcode']){
            return false;
        }
        return $result['template_id'];
    }

    public function getToken($shop_id=0) {
        
        if(!$shop_id) return  $this->getSiteToken();
        return $this->getShopToken($shop_id);
    }
	
    //获取商家的TOKEN
    private function getShopToken($shop_id){ 
        if(!$data = D('Shopweixinaccess')->getToken($shop_id)){
            $details = D('Shopdetails')->find($shop_id);
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' .$details['app_id'] . '&secret=' .$details['app_key'];
            $result = $this->curl->get($url);
            $result = json_decode($result, true);
            if(!empty($result['errcode'])){
				return false;
			}else{
				$data = $result['access_token'];
            	D('Shopweixinaccess')->setToken($shop_id, $data);
			}
        }
        return $data;
    }

	

	public function admin_wechat_client($shop_id=0){
        static $clients = array();
		if($weixin_admin = D('Shopdetails')->find($shop_id)){
			include_once "Tudou/Lib/Action/Weixin/wechat.class.php";
			$client = new WechatClient($weixin_admin['app_id'], $weixin_admin['app_key']);
		}
        return $client;
    }
    
    
	
	
	//获取主站的TOKEN
	public function getSiteToken(){
		$this->config = D('Setting')->fetchAll();
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' .$this->config['weixin']['appid'] . '&secret=' .$this->config['weixin']['appsecret'];
		$data = json_decode(file_get_contents(BASE_PATH."/access_token2.json"));
		//file_put_contents('data.txt', var_export($data, true));
		if($data->expire_time < time()) {
		    $result = $this->curl->get($url);
            $result = json_decode($result, true);
			if(!empty($result['errcode'])){
				return false;
			}else{
				$data->expire_time = time() + 7200;
				$data->access_token = $result['access_token'];
				$fp = fopen(BASE_PATH."/access_token.json2", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
				return $result['access_token'];
			}
		}
		return $data->access_token;
    }
	
	
	
    
    public function getCode($soure_id,$type){ 
		//生成二维码
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->getSiteToken();
        $str = "";
        $detail = D('Weixinqrcode')->where(array('soure_id'=>$soure_id,'type'=>$type))->find();
        if(!empty($detail)){
            $str = $detail['id'];
        }else{
            $id = D('Weixinqrcode')->add(array('soure_id'=>$soure_id,'type'=>$type));
            $str = $id;
        }
        
        $data = array(
            'action_name' => 'QR_LIMIT_SCENE',
            'action_info' =>array(
                'scene' => array(
                    'scene_id' => $str,
                ),
            ),
        );
        $datastr = json_encode($data);
        $result = $this->curl->post($url, $datastr);
        $result = json_decode($result, true);
        
        if ($result['errcode']) {
            return false;
        }
        $ticket = urlencode($result['ticket']);
        $imgurl = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=". $ticket;
        return $imgurl;
    }
    
     //自定义菜单接口
    public function weixinmenu($data,$shop_id = 0 ) {
        $datas = array();
        foreach($data['button'] as $key => $val){
            if(!empty($val)){
				if($val['types'] == 1){
					$local = array(
						'type' => 'view',
						'name' => urlencode($val['name']),
						'url' => $val['url'],
                	);
				}elseif($val['types'] == 2){
					$local = array(
						'type' => 'click',
						'name' => urlencode($val['name']),
						'key' => urlencode($val['key']),
                	);
				}elseif($val['types'] == 3){
					$local = array(
						'type' => 'miniprogram',
						'name' => urlencode($val['name']),
						'url' => $val['url'],
						'appid' => $val['appid'],
						'pagepath' => $val['pagepath'],
                	);
				}else{
					$local = array(
						'type' => 'view',
						'name' => urlencode($val['name']),
						'url' => $val['url'],
                	);
				}
				
                foreach($data['child'][$key] as $k => $v){
                    if(!empty($v['name'])){
						if($v['types'] == 1){
							 $local['sub_button'][] = array(
								'type' => 'view',
								'name' => urlencode($v['name']),
								'url' => $v['url'],
							);
						}elseif($v['types'] == 2){
							 $local['sub_button'][] = array(
								'type' => 'click',
								'name' => urlencode($v['name']),
								'key' => urlencode($v['key']),
							);
						}elseif($v['types'] == 3){
							 $local['sub_button'][] = array(
								'type' => 'miniprogram',
								'name' => urlencode($v['name']),
								'url' => $v['url'],
								'appid' => $v['appid'],
								'pagepath' => $v['pagepath'],
							);
						}else{
							$local['sub_button'][] = array(
								'type' => 'view',
								'name' => urlencode($v['name']),
								'url' => $v['url'],
							);
						}
                    }
					
                }
                $datas[] = $local;
            }
        }

        $datastr = urldecode(json_encode(array('button'=>$datas)));
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getToken($shop_id);
        $result = $this->curl->post($url, $datastr);
        $result = json_decode($result, true);
        if($result['errcode'] != 0){
            return $result['errcode'].'错误原因：'.$result['errmsg'];
        }
        return true;
    }


    //此TOKEN 是由网站分配
    public function init($token){

        if(!empty($_GET['echostr'])){
            exit($_GET['echostr']);
        }else{
            $xml = file_get_contents("php://input");
            if(!empty($xml)){
                $xml = new SimpleXMLElement($xml);

                $xml || exit;

                foreach($xml as $key => $value){
                    $this->data[$key] = strval($value);
                }
            }
        }
    }

  
    public function request(){
        return $this->data;
    }

 
    public function response($content, $type = 'text', $flag = 0){
        $this->data = array(
            'ToUserName' => $this->data['FromUserName'],
            'FromUserName' => $this->data['ToUserName'],
            'CreateTime' => NOW_TIME,
            'MsgType' => $type,
        );

        $this->$type($content);
        $this->data['FuncFlag'] = $flag;
        $xml = new SimpleXMLElement('<xml></xml>');
        $this->data2xml($xml, $this->data);
        exit($xml->asXML());
    }

  
    private function text($content) {
        $this->data['Content'] = $content;
    }

 
    private function music($music) {
        list(
			$music['Title'],
			$music['Description'],
			$music['MusicUrl'],
			$music['HQMusicUrl']
		) = $music;
        $this->data['Music'] = $music;
    }

   
    private function news($news){
        $articles = array();

        foreach ($news as $key => $value){
            list(
                    $articles[$key]['Title'],
                    $articles[$key]['Description'],
                    $articles[$key]['PicUrl'],
                    $articles[$key]['Url']
                    ) = $value;
            if ($key >= 9) {
                break;
            } 
        }
        $this->data['ArticleCount'] = count($articles);
        $this->data['Articles'] = $articles;
    }

  
    private function data2xml($xml, $data, $item = 'item') {
        foreach ($data as $key => $value) {
            is_numeric($key) && $key = $item;
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
                if (is_numeric($value)) {
                    $child = $xml->addChild($key, $value);
                } else {
                    $child = $xml->addChild($key);
                    $node = dom_import_simplexml($child);
                    $node->appendChild($node->ownerDocument->createCDATASection($value));
                }
            }
        }
    }

    
   public function auth($token) {
        $data = array($_GET['timestamp'], $_GET['nonce'], $token);
        $sign = $_GET['signature'];
        sort($data);
        $signature = sha1(implode($data));
        return $signature === $sign;
    }
	
	
	//获取直播列表
	public  function syncRoomList(){
		$accessToken = $this->getAccessToken();
		if(empty($accessToken)){
			return array('errcode'=>40001, 'accessToken为空');
		}
		$page = 1;
		$start = 0;
		$pageSize = 30;
		$param = array(
			"start" => $start,
			"limit" => $pageSize
		);
		$roomIds = array();
		$url = 'http://api.weixin.qq.com/wxa/business/getliveinfo?access_token=' . $accessToken;

		$model = M('lionfish_comshop_wxlive');
		S('_inc_live_expirtime_', time());


		while(true){
			$response = $this->_post($url, $param);
			$result = json_decode($response, true);
			
			

			$roomReqNum = S('_inc_live_roominfo_reqnum_');
			$num = intval($roomReqNum) + 1;
			S('_inc_live_roominfo_reqnum_', $num);


			if($result['errcode'] != 0){
				if($result['errcode'] == 1){
					return array('errcode'=>$result['errcode'], 'msg'=>'直播间列表为空');
				}

				if($result['errcode'] == 48001){
					return array('errcode'=>$result['errcode'], 'msg'=>'小程序没有直播权限');
				}
				return array('errcode'=>$result['errcode'], 'msg'=>$result['errmsg']);
			}


			foreach($result['room_info'] as $room){
				$roomId = (int) $room['roomid'];
				$roomIds[] = $roomId;

				$wxlive =  M('wxlive')->where(array('roomid'=>$roomId))->find();
				
				$updateData = array(
					'name' => (string) $room['name'], 
					'cover_img' => (string) $room['cover_img'], 
					'live_status' => (int) $room['live_status'], 
					'start_time' => (int) $room['start_time'], 
					'end_time' => (int) $room['end_time'], 
					'anchor_name' => (string) $room['anchor_name'], 
					'anchor_img' => (string) $room['anchor_img'], 
					'share_img' => (string) $room['share_img'], 
					'goods' => json_encode($room['goods']
				));
				

				//p($updateData);die;
				
				
				if(empty($wxlive)){
					
					
					$insertData = array_merge($updateData, array('roomid' => $roomId));
					M('wxlive')->add($insertData);
					
					
					if($room['live_status'] == '103'){
						$this->syncLiveReplay($room['roomid']);
					}
					continue;
				}

				$live_replay_lv = unserialize($wxlive['live_replay']);
				if(!empty($wxlive) && empty($live_replay_lv) && $room['live_status']=='103'){
					$this->syncLiveReplay($room['roomid']);
				}
				M('wxlive')->where( array('roomid' => $room['roomid'] ) )->save($updateData);
			}

			if($result['total'] < $pageSize*$page){
				break;
			}

			$page++;
			unset($room);
		}

		unset($result);

		$result = M('wxlive')->where('roomid not in ( ' . implode(',', $roomIds) . ')' )->delete();
	}




	public function syncLiveReplay($room_id){
		$accessToken = $this->getAccessToken();

		if(!$accessToken) {
			return '';
			die();
		}

		$url = 'http://api.weixin.qq.com/wxa/business/getliveinfo?access_token='.$accessToken;
		$param = array(
			"action" => "get_replay",
			"room_id" => $room_id,
			"start" => 0,
			"limit" => 1
		);

		$res = $this->_post($url, $param);
		$res = json_decode($res);

		$replayReqNum = S('_inc_live_replay_reqnum_');
		$num = intval($replayReqNum) + 1;
		
		S('_inc_live_replay_reqnum_', $num);

		if($res->errcode == 0){
			$live_replay = $res->live_replay;
			$updateData = array('live_replay'=>serialize($live_replay));
			M('wxlive')->where(array('roomid'=>$room_id))->save($updateData);
			return $live_replay;
		}else{
			//代表未创建直播房间
			return '';
		}
	}


	function getRoomInfo($roomid){
		$res = M('wxlive')->where(array('roomid'=>$roomid))->find();
		return $res;
	}

	
	private function getAccessToken(){
		$token = D('Weixintmpl')->getaccess_token();
		return $token;
	}

	private function _post($url, $data=array()){
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
	


}