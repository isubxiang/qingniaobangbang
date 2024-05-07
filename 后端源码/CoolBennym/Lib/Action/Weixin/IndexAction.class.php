<?php
class IndexAction extends CommonAction{
	
	 public function index(){
		//file_put_contents('1.txt', var_export(111, true));
        $data = $this->weixin->request();
        switch ($data['MsgType']){
            case 'event':
                if($data['Event'] == 'subscribe'){
                    if(isset($data['EventKey']) && !empty($data['EventKey'])) {
                        $this->events();
                    }else{
                        $this->event();
                    }
                }
                if($data['Event'] == 'SCAN'){
                    $this->scan();
                }
				if($data['Event'] == 'CLICK'){
					$data['Content'] = $data['EventKey'];
                    $this->keyword($data);
                }
                break;
            case 'location':
                $this->location($data);
                break;
			case 'text':
                $this->keyword($data);
                break;
            default:
                $this->keyword($data);
                break;
        }
    }
	
	
	
    private function location($data){
        $lat = addcslashes($data['Location_X']);
        $lng = addcslashes($data['Location_Y']);
        $list = D('Shop')->where(array('audit' => 1, 'closed' => 0))->order(" (ABS(lng - '{$lng}') +  ABS(lat - '" . $lat . '\') )  asc ')->limit(0, 10)->select();
        if(!empty($list)){
            $content = array();
            foreach ($list as $item){
                $content[] = array($item['shop_name'], $item['addr'], $this->getImage($item['photo']), __HOST__ . '/wap/shop/detail/shop_id/' . $item['shop_id'] . '.html');
            }
            $this->weixin->response($content, 'news');
        }else{
            $this->weixin->response('很抱歉没有合适的商家推荐给您', 'text');
        }
    }
	
	
	
	private function keyword($data){
        if(empty($data['Content'])){
            return;
        }
        if($this->shop_id == 0){
            $key = explode(' ', $data['Content']);
            $keyword = D('Weixinkeyword')->checkKeyword($key[0]);
            if($keyword){
			 switch ($keyword['type']){
                    case 'text':
                        $this->weixin->response($keyword['contents'], 'text');
                        break;
                    case 'news':
                        $content = array();
                        $content[] = array(
                            $keyword['title'],
                            $keyword['contents'],
                            $this->getImage($keyword['photo']),
                            $keyword['url'],
                        );
                        $this->weixin->response($content, 'news');
                        break;
                }
            } else {
                $this->event();
            }
        }else{
           $keyword = D('Shopweixinkeyword')->checkKeyword($this->shop_id, $data['Content']);
            if($keyword){
                switch ($keyword['type']){
                    case 'text':
                        $this->weixin->response($keyword['contents'], 'text');
                        break;
                    case 'news':
                        $content = array();
                        $content[] = array(
                            $keyword['title'],
                            $keyword['contents'],
                            $this->getImage($keyword['photo']),
                            $keyword['url'],
                        );
                        $this->weixin->response($content, 'news');
                        break;
                }
            }else{
                $this->event();
            }
        }
    }
	
	
	
	public function curl_grab_page($url,$data,$proxy='',$proxystatus='',$ref_url=''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($proxystatus == 'true'){
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		if(!empty($ref_url)){
			curl_setopt($ch, CURLOPT_HEADER, TRUE);
			curl_setopt($ch, CURLOPT_REFERER, $ref_url);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		ob_start();
		return curl_exec($ch);
		ob_end_clean();
		curl_close ($ch);
		unset($ch);
	}
	
	
	public function getAccessToken($appId,$appSecret){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appId&secret=$appSecret";
		$res = json_decode($this->httpGet($url));
		return $res->access_token;

    }


   public function httpGet($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);
		$res = curl_exec($curl);
		curl_close($curl);
		return $res;
	  }
  

    //响应用户的事件
    private function event(){
        if($this->shop_id == 0){
			if($this->_CONFIG['weixin']['user_auto'] == 1 && $this->_CONFIG['weixin']['user_add'] == 1){
				$data = $this->weixin->request();
				$token = $this->getAccessToken($this->_CONFIG['weixin']["appid"], $this->_CONFIG['weixin']["appsecret"]);				  
			}
			
			if($this->_CONFIG['weixin']['type'] == 1){
				$this->weixin->response($this->_CONFIG['weixin']['description'], 'text');//发送文字回复
			}else{
				$content[] = array(
					$this->_CONFIG['weixin']['title'], 
					$this->_CONFIG['weixin']['description'], 
					$this->getImage($this->_CONFIG['weixin']['photo']), 
					$this->_CONFIG['weixin']['linkurl']
				);
				file_put_contents('event_shop.txt', var_export($content, true));
				$this->weixin->response($content, 'news');//发送图文回复
			}
			//相应用户事件结束
        }else{
            $data['get'] = $_GET;
            $data['post'] = $_POST;
            $data['data'] = $this->weixin->request();
            $weixin_msg = unserialize($this->shopdetails['weixin_msg']);
            if($weixin_msg['type'] == 1){
                $this->weixin->response($weixin_msg['description'], 'text');
            }else{
                $content[] = array(
					$weixin_msg['title'], 
					$weixin_msg['description'], 
					$this->getImage($weixin_msg['photo']), 
					$this->_CONFIG['weixin']['linkurl']
				);
                $this->weixin->response($content, 'news');//发送商家图片文字简介
            }
        }
    }
	
	
	//扫码相应事件
    private function events(){
        $data['get'] = $_GET;
        $data['post'] = $_POST;
        $data['data'] = $this->weixin->request();
        if (!empty($data['data'])) {
            $datas = explode('_', $data['data']['EventKey']);
            $id = $datas[1];
			
			$uDate = D('Users')->find($id);
			if($uDate){
 				$token = $this->getAccessToken($this->_CONFIG['weixin']["appid"], $this->_CONFIG['weixin']["appsecret"]);	
			}else{		
				if(!($detail = D('Weixinqrcode')->find($id))){
					die;
				}
				$type = $detail['type'];
				if($type == 1){
					$shop_id = $detail['soure_id'];
					$shop = D('Shop')->find($shop_id);
					$content[] = array(
						$shop['shop_name'], 
						$shop['addr'], 
						$this->getImage($shop['photo']), __HOST__ . '/shop/detail/shop_id/' . $shop_id . '.html');
						
						file_put_contents('events_shop.txt', var_export($$content, true));
					$result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
					if (!empty($result)) {
						$user_id = $result['uid'];
					
						if (!($fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $shop_id))->find())) {
							$dataf = array('user_id' => $user_id, 'shop_id' => $shop_id, 'create_time' => NOW_TIME, 'create_ip' => get_client_ip());
							D('Shopfavorites')->add($dataf);
							D('Shop')->updateCount($shop_id, 'fans_num');
						} else {
							if($fans['closed'] == 1){
								D('Shopfavorites')->save(array('favorites_id' => $fans['favorites_id'], 'closed' => 0));
							}
						}
					}
					$this->weixin->response($content, 'news');
				}elseif($type == 2){
					$tuan_id = $detail['soure_id'];
					$tuan = D('Tuan')->find($tuan_id);
					$content[] = array($tuan['title'], $tuan['intro'], $this->getImage($tuan['photo']), __HOST__ . '/wap/tuan/detail/tuan_id/' . $tuan_id . '.html');
					$result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
					if(!empty($result)){
						$user_id = $result['uid'];
					
						if(!($fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $tuan['shop_id']))->find())) {
							$dataf = array('user_id' => $user_id, 'shop_id' => $tuan['shop_id'], 'create_time' => NOW_TIME, 'create_ip' => get_client_ip());
							D('Shopfavorites')->add($dataf);
							D('Shop')->updateCount($tuan['shop_id'], 'fans_num');
						}else{
							if($fans['closed'] == 1){
								D('Shopfavorites')->save(array('favorites_id' => $fans['favorites_id'], 'closed' => 0));
							}
						}
					}
					$this->weixin->response($content, 'news');
				}elseif($type == 3){
					//购物
					$goods_id = $detail['soure_id'];
					$goods = D('Goods')->find($goods_id);
					$shops = D('Shop')->find($goods['shop_id']);
					$content[] = array($goods['title'], $shops['shop_name'], $this->getImage($goods['photo']), __HOST__ . '/wap/mall/detail/goods_id/' . $goods_id . '.html');
					$result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
					if(!empty($result)){
						$user_id = $result['uid'];
						
						if(!($fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $goods['shop_id']))->find())) {
							$dataf = array('user_id' => $user_id, 'shop_id' => $goods['shop_id'], 'create_time' => NOW_TIME, 'create_ip' => get_client_ip());
							D('Shopfavorites')->add($dataf);
							D('Shop')->updateCount($goods['shop_id'], 'fans_num');
						}else{
							if($fans['closed'] == 1){
								D('Shopfavorites')->save(array('favorites_id' => $fans['favorites_id'], 'closed' => 0));
							}
						}
					}
					$this->weixin->response($content, 'news');
				}
			}
		}
    }
	
	
	
    public function scan(){
        $data['data'] = $this->weixin->request();
        if(!empty($data['data'])){
            $id = $data['data']['EventKey'];
            if(!($detail = D('Weixinqrcode')->find($id))){
                die;
            }
            $type = $detail['type'];
            if($type == 1){
                $shop_id = $detail['soure_id'];
                $shop = D('Shop')->find($shop_id);
                $content[] = array($shop['shop_name'], $shop['addr'], $this->getImage($shop['photo']), __HOST__ . '/wap/shop/detail/shop_id/' . $shop_id . '.html');
                $result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
                if(!empty($result)){
                    $user_id = $result['uid'];
                
                    if(!($fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $shop_id))->find())) {
                        $dataf = array('user_id' => $user_id, 'shop_id' => $shop_id, 'create_time' => NOW_TIME, 'create_ip' => get_client_ip());
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($shop_id, 'fans_num');
                    }else{
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id' => $fans['favorites_id'], 'closed' => 0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
				
				
            }elseif($type == 2){
                $tuan_id = $detail['soure_id'];
                $tuan = D('Tuan')->find($tuan_id);
                $content[] = array($tuan['title'], $tuan['intro'], $this->getImage($tuan['photo']), __HOST__ . '/wap/tuan/detail/tuan_id/' . $tuan_id . '.html');
                $result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
                if(!empty($result)){
                    $user_id = $result['uid'];
                    if(!($fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $tuan['shop_id']))->find())){
                        $dataf = array('user_id' => $user_id, 'shop_id' => $tuan['shop_id'], 'create_time' => NOW_TIME, 'create_ip' => get_client_ip());
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($tuan['shop_id'], 'fans_num');
                    }else{
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id' => $fans['favorites_id'], 'closed' => 0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
				
				
				
            }elseif($type == 3){
                $goods_id = $detail['soure_id'];
                $goods = D('Goods')->find($goods_id);
                $shops = D('Shop')->find($goods['shop_id']);
                $content[] = array($goods['title'], $shops['shop_name'], $this->getImage($goods['photo']), __HOST__ . '/wap/mall/detail/goods_id/' . $goods_id . '.html');
                $result = D('Connect')->getConnectByOpenid('weixin', $data['data']['FromUserName']);
                if(!empty($result)){
                    $user_id = $result['uid'];
                    if(!($fans = D('Shopfavorites')->where(array('user_id' => $user_id, 'shop_id' => $goods['shop_id']))->find())){
                        $dataf = array('user_id' => $user_id, 'shop_id' => $goods['shop_id'], 'create_time' => NOW_TIME, 'create_ip' => get_client_ip());
                        D('Shopfavorites')->add($dataf);
                        D('Shop')->updateCount($goods['shop_id'], 'fans_num');
                    }else{
                        if($fans['closed'] == 1){
                            D('Shopfavorites')->save(array('favorites_id' => $fans['favorites_id'], 'closed' => 0));
                        }
                    }
                }
                $this->weixin->response($content, 'news');
            }
        }
    }
	
	
	
    private function getImage($img){
		return config_weixin_img($img);
    }
}