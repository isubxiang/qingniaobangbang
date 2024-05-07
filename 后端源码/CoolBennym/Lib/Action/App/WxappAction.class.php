<?php



class WxappAction extends CommonAction{
	
	protected function _initialize(){
        parent::_initialize();
        $this->getPincheCate = D('Pinche')->getPincheCate();
        $this->assign('getPincheCate',$this->getPincheCate);
    }
	
	public function Url2(){
		$config = D('Setting')->fetchAll();
		$json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$config['site']['imgurl']);
        $json_str = json_encode($json_arr);
        exit($json_str); 
		
	}
	
	
	public function Url(){
		$config = D('Setting')->fetchAll();
		$json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$config['site']['host']);
        $json_str = json_encode($json_arr);
        exit($json_str); 
		
	}
	
	public function Views(){
		$views = D('Life')->sum('views');
		$json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$views);
        $json_str = json_encode($json_arr);
        exit($json_str); 
		
	}
    public function Num(){
		$count = M('ThreadPost')->where(array('audit' => 1, 'closed' => 0))->count();
		$this->ajaxReturn(array('status' => '1', 'msg' => '获取成功','data'=>$count));
		
	}
	
	public function GetNav(){
		$list = M('Navigation') ->where(array('status' =>6,'closed'=>0))->order(array('orderby' => 'asc'))->select();
		foreach($list as $k => $val){
			$list[$k]['id'] = $val['nav_id'];
			$list[$k]['title'] = $val['nav_name'];
			$list[$k]['photo'] = config_weixin_img($val['photo']);
		}
		$json_str = json_encode($list);
        exit($json_str); 
		
	}
	
	
	public function type(){
		$cate = D('Lifecate')->getChannelMeans();
		
	    $arr = array();
	    foreach($cate as $k => $v){
		   $arr[$k]['id'] = $k;
		   $arr[$k]['type_name'] = $v;
		   $kk = $k ;
		   $arr[$k]['img'] = __HOST__.'/static/default/wap/image/life/life_cate_'.$kk.'.png';	
	    } 
		
		$arr = array_values($arr);
        $json_str = json_encode($arr);
        exit($json_str); 
		
	}
	
	
    public function map(){
		$config = D('Setting')->fetchAll();
		$res['mapkey'] = $config['wxapp']['qqmap_key'];
		$op = I('op','','trim,htmlspecialchars');
        $url = "https://apis.map.qq.com/ws/geocoder/v1/?location=".$op."&key=".$res['mapkey']."&get_poi=0&coord_type=1";
        $html = file_get_contents($url);
        echo  $html;
	
		
	}
	
	
	//首页广告
	public function Ad(){
		$list = D('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach ($list as $k => $val){
			$list[$k]['type'] = 1;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = strpos($val['photo'],"http")===false ?  __HOST__.$val['photo'] : $val['photo'];
		}
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	

	
	
	//获取列表图片开始
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
			return false;
		}
	}
	
	
	
	//帖子列表
	public function List2(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		if($cate_id = I('cate_id','','trim')){
			$map['cate_id'] = $cate_id;
        }
		
		if($keyword = I('keywords','','htmlspecialchars')){
            $map['title'] = array('LIKE', '%' . $keyword . '%');
			$this->assign('keyword', $keyword);
			$linkArr['keyword'] = $keyword;
        }
		
		
		$count = M('ThreadPost')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('ThreadPost')->where($map)->order(array('post_id' =>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$Users = M('Users')->find($val['user_id']);
			$list[$k]['id'] = $val['post_id'];
		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = $val['is_fine'] == 1 ? 1 :'0';
			$list[$k]['givelike'] = $val['zan_num'];
			$list[$k]['user_tel'] = $val['mobile'] ? $val['mobile'] : $Users['mobile'] ? $Users['mobile'] : $this->_CONFIG['site']['tel'];;
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = config_user_name($Users['nickname']?$Users['nickname']:'未设置昵称');
			$list[$k]['type_name'] = $this->getListThread($val['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$val['cate_id']))->getField('cate_name');//分类
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$val['cate_id']))->select();
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['sh_time'] = $val['create_time'];
			$list[$k]['img'] = $this->getListPics($val['post_id']);
			$list[$k]['img1'] = $this->getListPics($val['post_id']);
			$list[$k]['details'] = cleanhtml($val['details']);
		}
		
	
		foreach($list as $k => $val){
			$data2[]=array(
			  'tz'=>$list[$k],
			  'label'=>array(),
			 );
		}
        $json_str = json_encode($data2 ? $data2 : array());
        exit($json_str); 
		
	}
	
	
	//获取频道有问题
	public function getListThread($thread_id){
		$thread = M('Thread')->where(array('thread_id'=>$thread_id))->find();
		return $thread['thread_name'];
	}
	

	
	
	public function news(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		$count = D('Article')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if($Page->totalPages < $p){
            die('0');
        }
		$list = D('Article')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['type'] = 1;
			$list[$k]['id'] = $val['article_id'];
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	public function Storelist(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		$count = D('Shop')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if ($Page->totalPages < $p) {
            die('0');
        }
		$list = D('Shop')->where(array('closed'=>'0'))->order(array('shop_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			
			$list[$k]['id'] = $val['shop_id'];
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['img'] = strpos($val['photo'],"http")===false ?  __HOST__.$val['photo'] : $val['photo'];
		}
	
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	

	
 	public function System(){
		$config = D('Setting')->fetchAll();
		$res['config'] = $config;
		$res['many_city'] = 2;
		$res['more'] = 1;//兼容外卖
		$res['typeset'] = 1;//兼容外卖
		$res['is_yue'] = 1;//兼容外卖，平台是否余额支付
		
		
		$res['color'] = $config['other']['color'];
		$res['tel'] = $config['site']['tel'];
		$res['pt_name'] = $config['site']['sitename'];	
		
		//兼容外卖
		$res['bq_logo'] = config_weixin_img($config['site']['logo']);
		$res['bq_name'] = $config['site']['sitename'];
		$res['support'] = $config['site']['description'];
		
		
		
		$res['gd_key'] = $config['wxapp']['gd_key'];
		$res['qqmap_key'] = $config['wxapp']['qqmap_key'];		
		$City = D('City')->find($config['site']['city_id']);
		$res['city_name'] = $City['name'];
		$res['city_id'] = $config['site']['city_id'];
		$res['total_num']  = M('ThreadPost')->sum('views');
		
		
		
		
		$list = D('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach($list as $k => $val){
			$photos[$k] = config_weixin_img($val['photo']);;
		}
		$photo = implode(",",$photos);
		$photo =  "".$photo ."";	
		$res['gs_img'] = $photo;
		$res['kp_img'] = $photo;
		$res['model']  = 1;
		$json_str = json_encode($res);
        exit($json_str); 
	}
	
	
	//贴吧点赞
	public function Like(){
		$user_id = I('user_id','','trim');
		$post_id = I('information_id','','trim');
		
        if(empty($post_id)){
           return json(array('code' => '0', 'msg' => '话题不存在'));
        }
        if($res = M('ThreadPostZan')->where(array('post_id' => $post_id, 'create_ip' => get_client_ip()))->find()) {
             $this->ajaxReturn(array('code' => '0', 'msg' => '您已经点过赞了'));
        }else{
            if(M('ThreadPostZan')->add(array('post_id' => $post_id, 'user_id' => $user_id, 'create_time' => time(), 'create_ip' => get_client_ip()))) {
                 D('Threadpost')->updateCount($post_id, 'zan_num');
                 $this->ajaxReturn(array('code' => '1', 'msg' => '点赞成功'));
            }else{
               $this->ajaxReturn(array('code' => '0', 'msg' => '点赞失败'));
             }
        }
		
	}
	
	public function openid(){
		$this->ajaxReturn(array('status' => '1', 'msg' => '获取成功','data'=>883));
		
	}
	
	public function Nav(){
		$this->ajaxReturn(array('status' => '1', 'msg' => '获取成功','data'=>883));
		
	}
	
	public function Login(){
		$this->ajaxReturn(array('status' => '1', 'msg' => '获取成功','data'=>883));
		
	}
	
	
	//新怎广告
	public function ZxAd(){
		$list = D('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach ($list as $k => $val){
			$list[$k]['type'] = 3;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
  //资讯分类
  public function ZxType(){
	  $res = M('ArticleCate')->where(array('parent_id'=>'0'))->limit(0,10)->select();
	  foreach ($res as $k => $val){
			$res[$k]['type_name'] = $val['cate_name'];
			$res[$k]['id'] = $val['cate_id'];
		}
      echo json_encode($res);
  }

  
  //资讯列表
  public function ZxList(){
	    import('ORG.Util.Page');
		$map = array('audit' => 1, 'closed' => 0);
		if($cate_id = I('cate_id','','trim')){
           $map['type_id'] = $type_id;
        }
		$cat = I('cate_id','','trim');       
        if($cat){
            $catids = D('ArticleCate')->getChildren($cat);
            if(!empty($catids)) {
                $map['cate_id'] = array('IN', $catids);
            }
        }
		$count = M('Article')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Article')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['article_id'];
			$list[$k]['type'] = 1;
			$list[$k]['name'] = $val['title'];
			$list[$k]['pt_name'] = $val['source'];
			$list[$k]['time'] = $this->getMicrotimeFormat($val['create_time']);
			$list[$k]['yd_num'] = $val['views'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['imgs'] = $this->getArticlePhotos($val['article_id']);
		}
		echo json_encode($list);
	}
	
	public function getMicrotimeFormat($time){  
        if(strstr($time,'.')){
            sprintf("%01.3f",$time);
            list($usec, $sec) = explode(".",$time);
            $sec = str_pad($sec,3,"0",STR_PAD_RIGHT);
        }else{
            $usec = $time;
            $sec = "000"; 
        }
        $date = date("Y-m-d H:i:s.x",$usec);
        return str_replace('x', $sec, $date);
     }
	 
	public function getArticlePhotos($article_id){
		$list = M('ArticlePhotos')->where(array('article_id'=>$article_id))->select();
		foreach($list as $k => $val){
			$photos[$k] = config_weixin_img($val['photo']);
		}
		$Article = M('Article')->find($article_id);
		if($Article['photo']){
			$photo = config_weixin_img($Article['photo']);
			if($photos){
				array_unshift($photos,$photo);
			}else{
				$photos[] = $photo;
			}
		}
		if(count($photos) > 1){
			$res = implode(",",$photos);
			return "".$res ."";
		}else{
			return false;
		}
		
	}
	
  
  //资讯详情
  public function ZxInfo(){
	  $article_id = I('id','','trim');  
	  D('Article')->updateCount($article_id,'views');
      $res = M('Article')->find($article_id);
	  $res['id'] = $article_id;
	  $res['content'] = cleanhtml($res['details']);
	  $res['time'] = $this->getMicrotimeFormat($res['create_time']);
	  $res['cerated_time'] = formatTime($res['create_time']);
	  $res['img'] = config_weixin_img($res['photo']);
	  $res['imgs'] = $this->getArticlePhotos($article_id);
	  $res['type'] = 2;
	  $res['name'] = $res['source'];
	  $res['pt_name'] = $res['source'];
	  $res['yd_num'] = $res['views'];
	  $res['dz']= 1;
      echo json_encode($res);
  }


  
   //评论列表
  public function ZxPlList(){
	  $article_id = I('zx_id','','trim');  
	  $res = M('ArticleComment')->where(array('post_id'=>$article_id,'parent_id'=>0))->order('comment_id desc')->limit(0,30)->select(); 
	  foreach($res as $k => $val){
		  $Users = M('Users')->find($val['user_id']);
		  $res[$k]['user'] = $Users;
		  $res[$k]['user_img'] = config_weixin_img($Users['face']);
		  $res[$k]['name'] = config_user_name($Users['nickname'] ? $Users['nickname'] : '无昵称');
		  $res[$k]['cerated_time'] = formatTime($val['create_time']);
	  }
      echo json_encode($res);
  }
  
   //喜欢列表
  public function ZxLikeList(){
	  $article_id = I('zx_id','','trim');  
	  $res = M('ArticleDonate')->where(array('article_id'=>$article_id))->limit(0,30)->select(); 
	  foreach($res as $k => $val){
		  $Users = M('Users')->find($val['user_id']);
		  $res[$k]['user'] = $Users;
		  $res[$k]['user_img'] = config_weixin_img($Users['face']);
	  }
      echo json_encode($res);
  }
  
  //点赞
  public function ZxLike(){
	  $article_id = I('zx_id','','trim');  
      $detail = M('Article')->find($article_id);
      if(empty($detail)){
		$this->ajaxReturn(array('code'=>'0','msg'=>'该文章已删除'));
      }
	  D('Article')->updateCount($article_id, 'zan');
	  $this->ajaxReturn(array('code'=>'1','msg'=>'点赞成功'));
  }
  
  //回复新闻
  public function ZxPl(){
	  $data['user_id'] = I('user_id','','trim');
	  $data['post_id'] = I('zx_id','','trim');
	  $data['article_id'] = $data['post_id'];
	  $data['content'] = I('content','','trim,htmlspecialchars');

	  $Users = M('Users')->find($data['user_id']);
	  $data['nickname'] = $Users['nickname'];
	  $data['zan'] =0;
	  $data['audit'] = 1;
	  $data['create_time'] = NOW_TIME;
	  $data['create_ip'] = get_client_ip();
	  if(M('ArticleComment')->add($data)){
		echo '1';
	  }else{
		echo '2'; 
	  }
    } 
	
	
	
	//保存定位城市
	public function SaveHotCity(){
		//保存定位城市，这里不想去保存啊
		$data=array();
	    $data['user_id']=I('user_id','','trim');
		$data['cityname']=I('cityname','','trim,htmlspecialchars');
		$data['time']=time();
		if(!$res = M('CitySelectLogs')->where(array('cityname'=>$data['cityname'],'user_id'=>$data['user_id']))->find()){
			if(M('city_select_logs')->add($data)){
			 	echo  '1';	
			}else{
				echo  '2';
			}
		}
	}
	
	//获取城市
	public function GetCity(){
	  $res = M('CitySelectLogs')->where(array('user_id'=>$data['user_id']))->find();
	  if(!$res){
		 echo json_encode(1); 
	  }else{
		  echo json_encode($res);
	  }
	}
	
	//获取城市
	public function GetCitys(){
		$arr = M('City')->field('first_letter')->group('first_letter')->select();
		
		$citylists = array();
		foreach($arr as $val){ 
			$a = strtoupper($val['first_letter']);
			$list = M('City')->where(array('first_letter'=>$val['first_letter']))->select();
			$citylists[$a][]['city'] = $list;
			$citylists[$a][]['name'] = $a;
		}	
		//p($citylists);die;
	    echo json_encode($citylists);
	}
	
	//商城分类
	public function GoodsType(){
		$arr = M('GoodsCate')->where(array('parent_id'=>0))->limit(0,10)->select();
		$kk = 0;
		foreach($arr as $k => $val){
			$kk ++ ;
			$arr[$k]['type_name'] = $val['cate_name'];
			$arr[$k]['id'] = $val['cate_id'];
			$arr[$k]['img'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($arr);
        exit($json_str); 
	}
	
	 //商城选择子分类
     public function GoodsType2(){
		$type_id = I('type_id','','htmlspecialchars');
		$arr = M('GoodsCate')->where(array('parent_id'=>$type_id))->limit(0,60)->select();
		$kk = 0;
		foreach($arr as $k => $val){
			$kk ++ ;
			$arr[$k]['name'] = $val['cate_name'];
			$arr[$k]['id'] = $val['cate_id'];
			$arr[$k]['map'] = $val['cate_id'];
			$arr[$k]['img'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($arr);
        exit($json_str); 
    }
	
	
	
	//未知东西
	public function news(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		$count = D('Article')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if($Page->totalPages < $p){
            die('0');
        }
		$list = D('Article')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['type'] = 1;
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	
	
	
	//商城首页广告
	public function Ad(){
		$list = D('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach ($list as $k => $val){
			$list[$k]['type'] = 2;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	//商城首页商品列表
	public function GoodsList(){
		import('ORG.Util.Page');
		
		
		$map = array('audit' => 1,'closed' => 0,'end_date' => array('EGT', TODAY));
		
		if($keyword = I('keywords','','htmlspecialchars')){
            $map['title|intro'] = array('LIKE', '%' . $keyword . '%');
        }
	
		//分类
		$cate_id = (int) I('goodstype2_id','','trim');
		$cat = (int) I('goodstype_id','','trim');
        if($cate_id){
			if($cate_id){
				$map['cate_id'] = $cate_id;
			}
        }else{
			$catids = D('Goodscate')->getChildren($cat);
            if(!empty($catids)){
                $map['cate_id'] = array('IN', $catids);
            }else{
                $map['cate_id'] = $cate_id;
            }
		}
		
		$count = M('Goods')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
	
        $p = I('p');
		
      
		$list = M('Goods')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['goods_id'];
			$list[$k]['goods_name'] = $val['title'];
			$list[$k]['goods_cost'] = round($val['mall_price']/100,2);
			$list[$k]['lb_imgs'] = config_weixin_img($val['photo']);
			$list[$k]['price'] = round($val['mall_price']/100,2);
		}
		
        $json_str = json_encode($list);
        exit($json_str);  
	}
	
	
	//商品列表
	public function StoreGoodList(){
		import('ORG.Util.Page');
		$shop_id = I('store_id','','trim');
		$map = array('audit' => 1,'shop_id'=>$shop_id, 'closed' => 0,'end_date' => array('EGT', TODAY));
		$count = M('Goods')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Goods')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['goods_id'];
			$list[$k]['goods_name'] = $val['title'];
			$list[$k]['goods_cost'] = round($val['mall_price']/100,2);
			$list[$k]['lb_imgs'] = config_weixin_img($val['photo']);
			$list[$k]['price'] = round($val['mall_price']/100,2);
		}
        $json_str = json_encode($list ? $list : 'null');
        exit($json_str); 
	}
	
	//获取商品列表图片开始
	public function getGoodsPics($goods_id){
		$list = M('GoodsPhotos')->where(array('goods_id'=>$goods_id))->limit(0,5)->select();
		if($list){
			foreach($list as $k => $val){
				$photos[$k] = $val['photo'];
			}
		}
		$Goods = M('Goods')->find($goods_id);
		if($Goods['photo']){
			$photo = config_weixin_img($Goods['photo']);
			if($photos){
				@array_unshift($photos,$photo);
			}else{
				$photos[] = $photo;
			}
			
		}
		if($photos){
			$res = implode(",",$photos);
			return "".$res ."";
		}else{
		    return false;
		}
	}
	
	
	
	public function getSpec($goods_id){
		$list = M('TpSpecGoodsPrice')->where(array('goods_id'=>$goods_id))->select(); //获取商品规格参数     
        foreach($list as $k => $val){
			$list[$k]['spec_id'] = $val['key'];
			$list[$k]['spec_name'] = $val['key_name'];
		 }
        return $list;
   }
	
	//商品详情
	public function GoodInfo(){
		$goods_id = I('id','','trim');
		$user_id = I('user_id','','trim');
		$detail = M('Goods')->find($goods_id);
		$detail['id'] = $goods_id;	
		$detail['price'] = round($detail['mall_price']/100,2);
		$detail['goods_name'] =$detail['title'];
		$detail['goods_cost'] = round($detail['mall_price']/100,2);
		$detail['goods_num'] = $detail['num'];
		$detail['lb_imgs'] = config_weixin_img($detail['photo']);			
		$detail['imgs'] = $this->getGoodsPics($goods_id);
		
	
		
		$detail['goods_details'] = cleanhtml($detail['details']);
		
		
		
		$detail['freight'] = 0;
		$detail['quality'] = $detail['is_vs2'];
		$detail['free'] = $detail['is_vs4'];
		$detail['service'] = $detail['is_vs1'];
		$detail['weeks'] = $detail['is_vs3'];
		$detail['address'] = $this->getUserdefaultAddress($user_id);
		
		$res = $this->getSpec($goods_id); //获取商品规格参数     
		$data['good'] = $detail;
		$data['address'] = $detail['address'];
	    $data['spec'] = $res ;
		
	    echo json_encode($data);
	}
	
	//订单页面商家详情
	public function StoreInfo(){
		$shop_id = I('id','','trim');
		$detail = M('Shop')->find($goods_id);
		$data['shop'] = $detail;
		$data['shop']['store_name'] = $detail['shop_name'];
		$data['shop']['address'] =  $detail['addr'];
	    echo json_encode($data);
	}
	
	
	
	public function getUserdefaultAddress($uid){
		
			$obj = M('Paddress');
			$Count = M('Paddress')->where(array('user_id' => $uid,'closed' => 0))->count();
			if($Count == 0) {
				return false; 
			}else{
				$count = M('Paddress')->where(array('user_id' => $uid, 'default' => 1,'closed' => 0))->count();
				if ($count == 0) {
					$Paddress = M('Paddress')->where(array('user_id' => $uid,'closed' => 0)) -> order("id desc")->find();
					return $Paddress; 
				} else {
					$Paddress = M('Paddress')->where(array('user_id' => $uid, 'default' => 1,'closed' => 0))->find();
					return $Paddress; 
				}
			}
		 return $Paddress; 
	}
	
	
	
	
	//点击购买
	public function addorder(){
		$goods_id = I('good_id','','trim');
		$user_id = I('user_id','','trim');
		$shop_id = I('store_id','','trim');
		$money = I('money','','trim');
		$address_id = I('address','','trim');
		$num = I('num','','trim');
		$note = I('note','','trim');
		$Goods = M('Goods')->find($goods_id);
		$data = array(
			'goods_id' => $goods_id, 
			'shop_id' => $shop_id, 
			'user_id' => $user_id, 
			'address_id' => $address_id, 
			'total_price' => $money*100,
			'need_pay' => $money*100,  
			'mobile_fan' => 0, 
			'express_price' => 0, //单个商品运费总价
			'is_mobile' => 1, 
			'create_time' => NOW_TIME, 
			'create_ip' => get_client_ip(),
		);
		if($order_id = M('Order')->add($data)){
			$arr = array(
				'order_id' => $order_id, 
				'goods_id' => $goods_id, 
				'shop_id' => $shop_id, 
				'cate_id' => $Goods['cate_id'], 
				'weight' => $Goods['weight'], 
				'num' => $num, 
				'kuaidi_id' => $address_id, 
				'price' => $money*100, 
				'total_price' => $money*100, 
				'mobile_fan' => 0, 
				'express_price' => 0, //单个商品运费总价
				'is_mobile' => 1, 
				'js_price' => 0, 
				'create_time' => NOW_TIME, 
				'create_ip' => get_client_ip(),
			);
            M('OrderGoods')->add($arr);
         }
		echo json_encode($order_id);
	}
	
	
	
	//查看我的订单
  	public function MyOrder(){
		import('ORG.Util.Page');
	  	$user_id = I('user_id','','trim');
		$map = array('user_id' => $user_id,'closed' =>0);
		$count = M('Order')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Order')->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$Shop = M('Shop')->find($val['shop_id']);
			$list[$k]['id'] = $val['order_id'];
			$list[$k]['store_id'] = $val['shop_id'];
			$list[$k]['state'] = $val['status'];
			$list[$k]['shop_name'] = $Shop['shop_name'];
			$list[$k]['complete_time'] = $val['update_time'];
			$list[$k]['money'] = round($val['total_price']/100,2);//原价
			$list[$k]['good_money'] = round($val['need_pay']/100,2);//实际付款
			$list[$k]['freight'] = round($val['express_price']/100,2);//运费
			$list[$k]['good_num'] = M('OrderGoods')->where(array('order_id'=>$val['order_id']))->sum('num');
			
			$arr = M('OrderGoods')->where(array('order_id'=>$val['order_id']))->select();
			foreach($arr as $k2 => $v2){
				$Goods = M('Goods')->find($v2['goods_id']);
				$arr[$k2]['good_name'] = $Goods['title'];
				$arr[$k2]['good_money'] = round($v2['price']/100,2);
				$arr[$k2]['good_img'] = config_weixin_img($Goods['photo']);
			}
			
			$list[$k]['goods'] = $arr;
		}
       echo json_encode($list);
  }
  
  
	//会员中心商城订单详情
	public function OrderInfo(){
		$order_id = I('order_id','','trim');
		$detail = M('Order')->find($order_id);
		$Shop = M('Shop')->find($detail['shop_id']);
		$detail['id'] = $detail['order_id'];
		$detail['state'] = $detail['status'];
		$detail['shop_name'] = $Shop['shop_name'];
		$detail['complete_time'] = $detail['update_time'];
		$detail['money'] = round($detail['total_price']/100,2);//原价
		$detail['need_pay'] = round($detail['need_pay']/100,2);//实际付款
		$detail['freight'] = round($detail['express_price']/100,2);//运费
		$detail['order_num'] = $order_id;
		$detail['time'] = $detail['create_time'];
		
		$detail['good_num'] = M('OrderGoods')->where(array('order_id'=>$detail['order_id']))->sum('num');
		
		
		$address = M('Paddress')->where(array('id'=>$detail['address_id']))->find();
		$detail['user_name'] = $address['xm'];	
		$detail['tel'] = $address['tel'];	
		$detail['address'] = $address['area_str'].''.$address['info'];
		
	
			
		$arr = M('OrderGoods')->where(array('order_id'=>$detail['order_id']))->select();
		foreach($arr as $k2 => $v2){
			$Goods = M('Goods')->find($v2['goods_id']);
			$shop = M('Shop')->find($v2['shop_id']);
			$arr[$k2]['store_name'] = $shop['shop_name'];
			$arr[$k2]['good_name'] = $Goods['title'];
			$arr[$k2]['good_money'] = round($v2['price']/100,2);
			$arr[$k2]['good_img'] = config_weixin_img($Goods['photo']);
		}
		
		$detail['goods'] = $arr;
		echo json_encode($detail);
	  }
	
	
	
	//申请退款
	 public function TuOrder(){
		$order_id = I('order_id','','trim');
        $Order = M('Order')->where('order_id =' . $order_id)->find();
		if(!$Order){
            $this->ajaxReturn(array('code'=>'0','msg'=>'订单不存在'));
        }
		//检测配送状态
		if(false == D('Order')->orderDelivery($order_id,$type ='4')){
			$this->ajaxReturn(array('code'=>'0','msg'=>D('Order')->getError()));
		}
		if($Order['status'] != 1){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单状态不正确'));
		}
        if(M('Order')->where('order_id',$order_id)->setField('status',4)){
			$this->ajaxReturn(array('code'=>'1','msg'=>'成功'));
		}else{
			$this->ajaxReturn(array('code'=>'0','msg'=>'失败'));
		}
	 }
	 
	 
	 
	//小程序确认收货
	 public function CompleteOrder(){
		$order_id = I('order_id','','trim');
		if(!($detail = M('Order')->find($order_id))){
			 $this->ajaxReturn(array('code'=>'0','msg'=>'该订单不存在'));
        }
		//检测配送状态
		$shop = M('Shop')->find($detail['shop_id']);
          if($shop['is_ele_pei'] == 1){
             $do = M('DeliveryOrder')->where(array('type_order_id' => $order_id, 'type' => 0))->find();
             if($do['status'] != 8){
				$this->ajaxReturn(array('code'=>'0','msg'=>'配送状态不正确'));
             }
        }
        if(M('Order')->save(array('order_id' => $order_id,'closed' => 3))){
			D('Order')->overOrder($order_id);
			$this->ajaxReturn(array('code'=>'1','msg'=>'操作成功'));
        }else{
			$this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
		}
	 }
	 
	//小程序用户删除订单
	public function DelOrder(){
		$order_id = I('order_id','','trim');
		if(!($detail = M('Order')->find($order_id))){
			 $this->ajaxReturn(array('code'=>'0','msg'=>'该订单不存在'));
        }
		
		//检测配送状态
		$shop =  M('Shop')->find($detail['shop_id']);
          if($shop['is_ele_pei'] == 1){
             $do = M('DeliveryOrder')->where(array('type_order_id' => $order_id, 'type' => 0))->find();
             if($do['status'] == 2 || $do['status'] == 8){
				$this->ajaxReturn(array('code'=>'0','msg'=>'配送员都接单了无法取消订单'));
             }else{
				M('DeliveryOrder')->where(array('type_order_id' => $order_id, 'type' => 0))->setField('closed', 1);//没接单就关闭配送
			}
        }
        if(M('Order')->save(array('order_id' => $order_id,'closed' => 1))){
			D('Order')->del_order_goods_closed($order_id);//更新状态
			D('Order')->del_goods_num($order_id);//取消后加库存
            if($detail['use_integral']) {
                  D('Users')->addIntegral($detail['user_id'], $detail['can_use_integral'], '取消商城购物，订单号：' . $detail['order_id'] . '积分退还');
            }
			$this->ajaxReturn(array('code'=>'1','msg'=>'操作成功'));
        }else{
			$this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
		}
	  }
	
	
	//订单列表
	public function orderList(){
		$rd_session = $this->_get('rd_session');
        $user = $this->checkLogin($rd_session);
        $this->uid = $user['uid'];

		$s = I('aready', '', 'trim,intval');
        $Eleorder = D('Eleorder');
        import('ORG.Util.Page');
        $map = array('user_id' => $this->uid, 'closed' => 0);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['order_id'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if(isset($_GET['st']) || isset($_POST['st'])) {
            $st = (int) $this->_param('st');
            if ($st != 999) {
                $map['status'] = $st;
            }
            $this->assign('st', $st);
        } else {
            $this->assign('st', 999);
        }
        $count = $Eleorder->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if ($Page->totalPages < $p) {
            die('0');
        }
        $list = $Eleorder->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = $order_ids = $addr_ids = $shop_ids = array();
        foreach ($list as $k => $val) {
            $order_ids[$val['order_id']] = $val['order_id'];
            $addr_ids[$val['addr_id']] = $val['addr_id'];
            $user_ids[$val['user_id']] = $val['user_id'];
            $shop_ids[$val['shop_id']] = $val['shop_id'];
			if($delivery_order = D('DeliveryOrder')->where(array('type_order_id'=>$val['order_id'],'type'=>1,'closed'=>0))->find()){
               $list[$k]['delivery_order'] = $delivery_order;
            }
        }
        $shops = D('Shop')->itemsByIds($shop_ids);
        //产品
        $products = D('Eleorderproduct')->where(array('order_id' => array('IN', $order_ids)))->select();
        $product_ids = array();
        foreach ($products as $val) {
            $product_ids[$val['product_id']] = $val['product_id'];
        }
        $eleproducts = D('Eleproduct')->itemsByIds($product_ids);
        foreach($products as $k=>$v){
        	foreach($eleproducts as $e){
        		if($v['product_id'] == $e['product_id']){
        			$products[$k]['product_name'] = $e['product_name'];
        			$photo = $e['photo'];
            		$products[$k]['photo'] = strpos($photo,"http")===false ?  __HOST__.$val['photo'] : $photo ;
        		}
        	}
        }
        foreach($list as $k=>$v){
        	$list[$k]['shop_name'] = $shops[$v['shop_id']]['shop_name'];
        	$list[$k]['total_price'] = round($v['total_price']/100,2);
        	$list[$k]['pay_time'] = date("Y-m-d H:i:s",$v['pay_time']);
        	foreach($products as $p){
        		if($v['order_id']==$p['order_id']){
        			$list[$k]['products'][] = $p;
        		}
        	}
        }	
      	$json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$list);
        $json_str = json_encode($json_arr);
        exit($json_str); 
       
	}
	
    //订单详情
    public function detail(){
        $rd_session = $this->_get('rd_session');
        $user = $this->checkLogin($rd_session);
        $this->uid = $user['uid'];
        $order_id = $this->_get('order_id');
        if (empty($order_id) || !($detail = D('Eleorder')->find($order_id))) {
            exit(json_encode(array('status'=>-1,'msg'=>'订单不存在','data'=>'')));
        }
        if ($detail['user_id'] != $this->uid) {
            exit(json_encode(array('status'=>-1,'msg'=>'不要操作别人的订单','data'=>'')));
        }
        $ele_products = D('Eleorderproduct')->where(array('order_id' => $order_id))->select();
        $product_ids = array();
        foreach ($ele_products as $k => $val) {
            $product_ids[$val['product_id']] = $val['product_id'];
        }
       
        $products = D('Eleproduct')->itemsByIds($product_ids);
        
        foreach ($ele_products as $k => $v) {
            $ele_products[$k]['total_price'] = round($v['total_price']/100,2);
            $ele_products[$k]['product_name'] = $products[$v['product_id']]['product_name'];
        }
        $detail['tableware_price']=round($detail['tableware_price']/100,2);
        $detail['full_reduce_price']=round($detail['full_reduce_price']/100,2);
        $detail['logistics']=round($detail['logistics']/100,2);
        $detail['total_price']=round($detail['total_price']/100,2);
        $detail['need_pay']=round($detail['need_pay']/100,2);
        $detail['create_time'] = date('Y-m-d H:i:s',$detail['create_time']);

        $detail['product'] = $ele_products;
        $detail['ele'] = D('Ele')->where(array('shop_id' => $detail['shop_id']))->find();
        $detail['shop'] = D('Shop')->where(array('shop_id' => $detail['shop_id']))->find();
        $detail['delivery_order'] = D('DeliveryOrder')->where(array('type_order_id'=>$order_id,'type'=>1,'closed'=>0))->find();
        $detail['wait_time_minutes'] = D('Eleorder')->get_wait_time_minutes($order_id);
        //优惠多少？
        $detail['cut_money_total'] = $detail['total_price'] - $detail['need_pay'];
        $detail['addr'] = D('Useraddr')->find($detail['addr_id']);
        $json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$detail);
        $json_str = json_encode($json_arr);
        exit($json_str); 
    }


//顺风车广告
	public function Ad(){
		$list = M('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach ($list as $k => $val){
			$list[$k]['type'] = 4;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	//拼车标签
	public function CarTag(){
		$res= '';
        $json_str = json_encode($res);
        exit($json_str); 
	}
	
	
	//拼车列表
	public function CarList(){
		import('ORG.Util.Page');
        $map = array('closed' => 0);
		
		if($user_id = I('user_id','','trim')){
            $map['user_id'] = $user_id;
        }
		
		$count = M('Pinche')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Pinche')->where($map)->order('pinche_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
			$list[$k]['class3'] = $num;
			$list[$k]['is_opens'] = ($val['start_time'] >= TODAY) ? 1 : '2';
			$list[$k]['num'] = $num;
			$list[$k]['start_place'] = $val['goplace'] ? $val['goplace'] : '未知';
			$list[$k]['end_place'] = $val['toplace'];
			$list[$k]['link_tel'] = $val['mobile'];
			$list[$k]['other'] = $val['details'];
			$list[$k]['is_open'] = $val['closed'];
			$list[$k]['typename'] = $this->getPincheCate[$val['cate_id']];
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['times'] = date('Y-m-d H:i:s', $val['create_time'] ? $val['create_time'] : time());
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
	
	//我的拼车
	public function MyCar(){
		import('ORG.Util.Page');
        $map = array('closed' => 0);
		if($user_id = I('user_id','','trim')){
            $map['user_id'] = $user_id;
        }
		
		$count = M('Pinche')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Pinche')->where($map)->order('pinche_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
			$list[$k]['class3'] = $num;
			$list[$k]['is_opens'] = ($val['start_time'] >= TODAY) ? 1 : '2';
			$list[$k]['num'] = $num;
			$list[$k]['start_place'] = $val['goplace'] ? $val['goplace'] : '未知';
			$list[$k]['end_place'] = $val['toplace'];
			$list[$k]['link_tel'] = $val['mobile'];
			$list[$k]['other'] = $val['details'];
			$list[$k]['is_open'] = $val['closed'];
			$list[$k]['typename'] = $this->getPincheCate[$val['cate_id']];
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['times'] = date('Y-m-d H:i:s', $val['create_time'] ? $val['create_time'] : time());
			$list[$k]['start_time1'] = $val['create_time'];
			$list[$k]['start_time2'] = $val['create_time'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
		}
	
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	
	
	//拼车详情
	public function CarInfo(){
		$pinche_id = I('id','','trim');
		$detail = M('Pinche')->find($pinche_id);
		$detail['is_open'] = $detail['closed'];
		$detail['img'] = config_weixin_img($detail['photo']);
		$detail['typename'] = $this->getPincheCate[$detail['cate_id']];
		$detail['start_time1'] = $detail['create_time'];
		$detail['start_time2'] = $detail['create_time'];
		$detail['time'] = $detail['create_time'];
		$Users = M('Users')->find($detail['user_id']);
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
		$detail['is_opens'] = ($detail['start_time'] >= TODAY) ? 1 : '2';
		$detail['num'] = $num;
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
		import('ORG.Util.Page');
		$typename = I('typename','','trim,htmlspecialchars');
		$cate_id = array_search($typename,$this->getPincheCate);
		
		
        $map = array( 'closed' =>0,'cate_id'=>$cate_id);
		$count = M('Pinche')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $var = 'page';
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Pinche')->where($map)->order('pinche_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
		$typename = I('typename','','trim,htmlspecialchars');
		$data['cate_id'] = array_search($typename,$this->getPincheCate);
		if(empty($data['cate_id'])){
			return json(array('code'=>'0','msg'=>'类型错误'));
        }
		$data['city_id'] = I('city_id','','trim');
        if(empty($data['city_id'])){
			return json(array('code'=>'0','msg'=>'城市id错误'));
        }
		$data['user_id'] = I('user_id','','trim');		
        $data['start_time'] = I('start_time','','trim,htmlspecialchars');
        if(empty($data['start_time'])){
			return json(array('code'=>'0','msg'=>'出发日期不能为空'));
        }
		$data['goplace'] = I('start_place','','trim,htmlspecialchars');
        if(empty($data['goplace'])){
			return json(array('code'=>'0','msg'=>'出发地不能为空'));
        }
        $data['toplace'] = I('end_place','','trim,htmlspecialchars');
        if(empty($data['toplace'])){
			return json(array('code'=>'0','msg'=>'目的地不能为空'));
        }
		$data['middleplace'] = I('tj_place','','trim,htmlspecialchars');
		$data['num_1'] = I('num','','trim');
		$data['num_2'] = I('num','','trim');
		$data['num_3'] = I('num','','trim');
		$data['num_4'] = I('num','','trim');
		$name = I('name','','trim,htmlspecialchars');
        $data['mobile'] = I('link_tel','','trim,htmlspecialchars');
		if(empty($data['mobile'])){
			return json(array('code'=>'0','msg'=>'手机不能为空'));
        }
        if(!ismobile($data['mobile'])){
			return json(array('code'=>'0','msg'=>'手机格式不正确'));
        }
		$data['details'] = I('other','','trim,htmlspecialchars');
		$data['star_lat'] = I('star_lat','','trim,htmlspecialchars');
        $data['star_lng'] = I('star_lng','','trim,htmlspecialchars');
        $data['end_lat'] = I('end_lat','','trim,htmlspecialchars');
        $data['end_lng'] = I('end_lng','','trim,htmlspecialchars');
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] =  get_client_ip();
	
        if($pinche_id = M('Pinche')->add($data)){
			return json(array('code'=>'1','msg'=>'发布成功'));
        }
		return json(array('code'=>'0','msg'=>'发布失败'));
	}

	public function news(){
		$list = M('Article')->where(array('audit' => 1, 'closed' => 0))->limit(0,5)->select();
		foreach($list as $k => $val){
			$list[$k]['id'] = $val['article_id'];
			$list[$k]['type'] = 3;
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	//商家分类
	public function ShopType(){
		$arr = D('Shopcate')->where(array('parent_id'=>0))->limit(0,10)->select();
		$kk = 0;
		foreach($arr as $k => $val){
			$kk ++ ;
			$arr[$k]['type_name'] = $val['cate_name'];
			$arr[$k]['id'] = $val['cate_id'];
			$arr[$k]['img'] = __HOST__.'/static/default/wap/image/life/life_cate_'.$kk.'.png';
		}
        $json_str = json_encode($arr);
        exit($json_str); 
	}
	
	
	 //商家入驻时候选择分类
     public function StoreType2(){
		$type_id = I('type_id','','htmlspecialchars');
		
		$arr = M('ShopCate')->where(array('parent_id'=>$type_id))->limit(0,60)->select();
		$kk = 0;
		foreach($arr as $k => $val){
			$kk ++ ;
			$arr[$k]['name'] = $val['cate_name'];
			$arr[$k]['id'] = $val['cate_id'];
			$arr[$k]['map'] = $val['cate_id'];
			$arr[$k]['img'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($arr);
        exit($json_str); 
      }
	
	//未知东西
	public function news(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		$count = D('Article')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if($Page->totalPages < $p){
            die('0');
        }
		$list = D('Article')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['type'] = 1;
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	//商家广告
	public function Ad(){
		$list = D('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach ($list as $k => $val){
			$list[$k]['type'] = 2;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	//商家列表
	public function StoreList(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		$storetype_id = I('storetype_id','','trim');
		if($storetype2_id = I('storetype2_id','','trim')){
            $map['cate_id'] = $storetype2_id;
        }
		
		if($keyword = I('keywords','','htmlspecialchars')){
            $map['shop_name|addr'] = array('LIKE', '%' . $keyword . '%');
        }
		$count = M('Shop')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Shop')->order(array('shop_id' =>'desc'))->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['shop_id'];
			$list[$k]['views'] = $val['view'];
			$list[$k]['store_name'] = $val['shop_name'];
			$list[$k]['address'] = $val['addr'];
			$list[$k]['coordinates'] = $val['lat'].','.$val['lng'];
			$list[$k]['details'] = M('ShopDetails')->find($val['shop_id']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	//商家分类列表
	public function TypeStoreList(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
        if($cat = (int) I('storetype_id')){
            $catids = D('Shopcate')->getChildren($cat);
            $map['cate_id'] = array('IN', $catids);
        }
		$count = M('Shop')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('Shop')->order(array('shop_id' => 'desc'))->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['shop_id'];
			$list[$k]['views'] = $val['view'];
			$list[$k]['address'] = $val['addr'];
			$list[$k]['store_name'] = $val['shop_name'];
			$list[$k]['coordinates'] = $val['lat'].','.$val['lng'];
			$list[$k]['details'] = M('ShopDetails')->find($val['shop_id']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	

	
	//商家详情
	public function StoreInfo(){
		$shop_id = I('id','','trim');
		$detail = M('Shop')->find($shop_id);
		$detail['id'] = $detail['shop_id'];
		$detail['store_name'] = $detail['shop_name'];
		$detail['vr_link'] = $detail['panorama_url'];
		$detail['address'] = $detail['addr'];
		$detail['views'] = $detail['view'];
		$detail['coordinates'] = $detail['lat'].','.$detail['lng'];
		$details = M('ShopDetails')->where(array('shop_id'=>$shop_id))->find();
		$detail['details'] = cleanhtml($details['details']);
		$detail['detail'] = $details; 
		$detail['announcement'] = cleanhtml($details['details']); 
		$detail['logo'] = config_weixin_img($detail['photo']);
		$detail['ad'] = $this->getShopListPics($detail['shop_id']);//商家图片获取
		$detail['img'] = $this->getShopListPics($detail['shop_id']);//商家图片获取
		$detail['img1'] = $this->getShopListPics($detail['shop_id']);//商家图片获取
		$data['store'][]=$detail;
		
		$list = M('ShopDianping')->where(array('shop_id'=>$detail['shop_id'],'closed'=>0))->limit(0,30)->select();
		foreach($list as $k => $val){
			$list[$k]['id'] = $val['dianping_id'];
			$Users = M('Users')->find($val['user_id']);
			$list[$k]['user_img'] = config_weixin_img($Users['face']);
			$list[$k]['name'] = config_user_name($Users['nickname']);
			$list[$k]['details'] = cleanhtml($val['contents']);
			$list[$k]['reply'] = cleanhtml($val['reply'] ? $val['reply'] : '暂无回复');
		}
		
        $data['pl']= $list;
	    echo json_encode($data);
	}
	
	
	
	//获取列表图片开始
	public function getShopListPics($shop_id){
		$list = M('ShopPic')->where(array('shop_id'=>$shop_id,'audit'=>1))->limit(0,30)->select();
		if($list){
			foreach($list as $k => $val){
				$photos[$k] = config_weixin_img($val['photo']);
			}
		}
		

		$Shop = M('Shop')->find($shop_id);
		if($Shop['photo']){
			$photo = config_weixin_img($Shop['photo']);
			if($photos){
				@array_unshift($photos,$photo);
			}else{
				$photos[] = $Shop['photo'];
			}
			
		}
		
		
		if($Shop['logo']){
			$logo = config_weixin_img($Shop['logo']);
			if($photos){
				@array_unshift($photos,$logo);
			}else{
				$photos[] = $logo;
			}
			
		}
		if($photos){
			$res = implode(",",$photos);
			return "".$res ."";
		}else{
			return false;
		}
		
		
	}
	
	//获取商品列表图片开始
	public function getGoodsPics($goods_id){
		$list = M('GoodsPhotos')->where(array('goods_id'=>$goods_id))->limit(0,5)->select();
		if($list){
			foreach($list as $k => $val){
				$photos[$k] = $val['photo'];
			}
		}
		$Goods = M('Goods')->find($goods_id);
		if($Goods['photo']){
			$photo = config_weixin_img($Goods['photo']);
			if($photos){
				@array_unshift($photos,$photo);
			}else{
				$photos[] = $photo;
			}
			
		}
		if($photos){
			$res = implode(",",$photos);
			return "".$res ."";
		}else{
		    return false;
		}
	}
	
	
	
	//sjdlogin
	public function sjdlogin(){
		$user_id = I('user_id','','trim');
		$res = M('Shop')->where('user_id',$user_id)->find();
		//1入驻到期2已入住
		if($res){
			$res['time_over'] =0;
		}else{
			$res['time_over'] =0;
		}
	
        $json_str = json_encode($res);
        exit($json_str); 
	}
	
	//商品列表
	public function StoreGoodList(){
		$shop_id = I('store_id','','trim');
		$list = M('Goods')->where(array('audit'=>1,'shop_id'=>$shop_id,'closed'=>0))->limit(0,6)->select();
		foreach($list as $k => $val){
			$list[$k]['id'] = $val['goods_id'];
			$list[$k]['is_show'] = 1;
			$list[$k]['goods_name'] = $val['title'];
			$list[$k]['goods_cost'] = round($val['mall_price']/100,2);
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['imgs'] = config_weixin_img($val['photo']);
			$list[$k]['imgs2'] = $this->getGoodsPics($val['goods_id']);//商品图片获取
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	
	//收藏
    public function Collection(){
         $shop_id = I('store_id','','trim');
		 $user_id = I('user_id','','trim');
		 $information_id = I('information_id','','trim');
		 $ShopFavorites = M('ShopFavorites')->where(array('shop_id'=>$shop_id,'user_id'=>$user_id))->find();
         if($ShopFavorites){
             M('ShopFavorites')->delete($ShopFavorites['favorites_id']);
         }else{
			 $data = array('shop_id'=>$shop_id,'user_id'=>$user_id,'create_time'=>NOW_TIME,'create_ip'=>get_client_ip());
			 $res = M('ShopFavorites')->add($data);
             if($res){
                echo '1';
             }else{
                echo '2';
             }
          } 
    }
	
	
	//商家点评列表
	public function dianping(){
		import('ORG.Util.Page');
		$shop_id = I('store_id','','trim');
        $map = array('audit' => 1, 'closed' => 0);
		$count = M('ShopDianping')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('ShopDianping')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['dianping_id'];
			$Users = M('Users')->find($val['user_id']);
			$list[$k]['user_img'] = config_weixin_img($Users['face']);
			$list[$k]['name'] = config_user_name($Users['nickname']);
			$list[$k]['details'] = cleanhtml($val['contents']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	//百度地图转换为谷歌地图
	public function getBaiduChangeMap($lat,$lng){
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
		return $lat.','.$lng;
	}
	
	//腾讯地图转换为百度地图
	public function getMapChangeBaidu($lat,$lng){
		$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
		return $lat.','.$lng;
	}
	
	
	
		
	
	  
   //请求给商家评论
   public function StoreComments(){
		$data['shop_id'] = I('store_id','','trim'); 
		$data['user_id'] = I('user_id','','trim');
		$data['contents'] = I('details','','trim,htmlspecialchars');
		$data['score'] = I('score','','trim,htmlspecialchars');
		$data['cost']=20;
		$data['create_time']=time();
		$data['create_ip']=get_client_ip();
		$data['show_date'] = date('Y-m-d', time());
		if($dianping_id = M('ShopDianping')->add($data)){
			 D('Shop')->updateCount($shop_id, 'score_num');
             D('Users')->updateCount($this->uid, 'ping_num');
             D('Shopdianping')->updateScore($shop_id);
		     D('Users')->prestige($data['user_id'],'dianping_shop');
			 echo $dianping_id;
	    }else{
         echo '2';
       }
     }
	
	 
	 //商家回复点评
     public function Reply(){
		 $id = I('id','','trim');
		 $reply = I('reply','','trim,htmlspecialchars');
		 $res = M('ShopDianping')->where(array('dianping_id'=>$id))->update(array('reply'=>$reply));
		 if($res){
			 echo '1';
		 }else{
			 echo '2';
		 }
     }
	
	
	 
	//生成二维码函数  
	public function buildWxappCode($storeid,$img,$patch,$patch2,$parameter){
		$config = D('Setting')->fetchAll(); 
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
        echo $config['site']['host'].$patch2. $name;
	}
	 
	
	
	//分享商家海报
	public function StoreCode(){
		$config = D('Setting')->fetchAll(); 
		$storeid = I('store_id', 0, 'trim,intval');
		$page = "tudoucms/pages/sellerinfo/sellerinfo";//路径
		$width = '430';
        $img = $this->set_msg($storeid,$page,$width);//scene,page,width
        $patch = BASE_PATH.'/attachs/'. 'weixin/'.date('Y/m/d/', NOW_TIME);
		$patch2 = '/attachs/'. 'weixin/'.date('Y/m/d/', NOW_TIME);
		$res = $this->buildWxappCode($storeid,$img,$patch,$patch2,$parameter = 'shopId');
        echo $res;
	}
	
	
	
	//获取token
	public function getaccess_token(){
        $config = D('Setting')->fetchAll();    
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $config['wxapp']['appid'] . "&secret=" . $config['wxapp']['appsecret'] . "";
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
	public function set_msg($storeid,$page,$width){
        $access_token = $this->getaccess_token();
        $data2 = array("scene" =>$storeid,"page"=>$page,"width" =>$width);
        $data2 = json_encode($data2);
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token . "";
		
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data2);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
     }

	
	//帖子评论成功模板消息后期整合这里还有问题
	public function StorehfMessage(){
		$access_token = $this->getaccess_token();
		
		$openid = I('sopenid', 0, 'trim,intval');
		$form_id = I('form_id', 0, 'trim,intval');
		$dianping_id = I('pl_id', 0, 'trim,intval');
		$tid = 'X_qWTKEukwsJquCS0JRJHSOE_rsGmhndV9gjEXFN4TQ';
		
		$Connect = M('Connect')->where(array('open_id'=>$openid))->find();
		
		$ShopDianping = M('ShopDianping')->find($dianping_id);
	    $time=date("Y-m-d H:i:s",$res['time']);
		
		
	    $formwork ='{
			 "touser": "'.$openid.'",
			 "template_id": "'.$tid.'",
			 "page":"tudoucms/pages/sellerinfo/sellerinfo?id='.$id.'",
			 "form_id":"'.$form_id.'",
			 "data": {
			   "keyword1": {
				 "value": "'.$ShopDianping['contents'].'",
				 "color": "#173177"
			   },
			   "keyword2": {
				 "value":"'.$Connect['nickname'].'",
				 "color": "#173177"
			   },
			   "keyword3": {
				 "value": "'.$time.'",
				 "color": "#173177"
			   }
			  
			 }   
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
	   return $data;
	}
	
 	//不知道是什么
     public function IsCollection(){
		 echo '1';
     }
	 
	 
	
	 
	 //商家入驻时候选择分类
     public function storetype(){
        $arr = M('ShopCate')->where(array('parent_id'=>0))->limit(0,20)->select();
		$kk = 0;
		foreach($arr as $k => $val){
			$kk ++ ;
			$arr[$k]['type_name'] = $val['cate_name'];
			$arr[$k]['id'] = $val['cate_id'];
			$arr[$k]['img'] = __HOST__.'/static/default/wap/image/life/life_cate_'.$kk.'.png';
		}
        $json_str = json_encode($arr);
        exit($json_str); 
      }
	
	//商家入驻费用
	public function InMoney(){
		$config = D('Setting')->fetchAll();
		if($config['shop']['shop_apply_prrice']){
			$res[] = array(
				'id' => 3,
				'type' => 3,
				'money' => round($config['shop']['shop_apply_prrice']/100,2),
			);
			echo json_encode($res);
		}else{
			$res[] = array(
				'id' => 1,
				'type' => 2,
				'money' => 0,
			);
			echo json_encode($res);
		}
	}


	//解密小程序专用
	public function Jiemi(){
		  include APP_PATH . 'application/app/controller/jiemi/wxBizDataCrypt.php';
		  $config = D('Setting')->fetchAll();
		  $appid = $config['config']['wxapp'];
		  $sessionKey = I('sessionKey','','trim,htmlspecialchars');
		  $encryptedData = I('data','','trim,htmlspecialchars');
		  $iv = I('iv','','trim,htmlspecialchars');;
		  $pc = new WXBizDataCrypt($appid, $sessionKey);
		  $errCode = $pc->decryptData($encryptedData, $iv, $data );
		  if($errCode == 0){
			  print($data . "\n");
		  }else{
			  print($errCode . "\n");
		  }
	 }
	 
	 //商家入驻页面
     public function Store(){
		 $data['city_id'] = I('city_id','','trim');//城市id
         $data['user_id'] = I('user_id','','trim');//用户id
         $data['shop_name']= I('store_name','','trim,htmlspecialchars');//商家名称
		 if(empty($data['shop_name'])){
           $this->ajaxReturn(array('code' => '0', 'msg' => '商家名称必填'));
         }
		 
		 
		 $storetype_id = I('storetype_id','','trim');//行业分类id
         $data['cate_id'] = I('storetype_id','','trim');//之行业分类id
       
         $data['contact']=I('keywordcontact','','trim,htmlspecialchars');//关键字
		 
         $data['tel']=I('tel','','trim,htmlspecialchars');//电话
		 if(empty($data['tel'])){
           $this->ajaxReturn(array('code' => '0', 'msg' => '商家电话必填'));
         }
		 
		 
		 
		 $data['addr']= I('address','','trim,htmlspecialchars');//地址
		 
		 
         $data['photo']=I('logo','','trim');//商家photo
		 if(empty($data['photo'])){
           $this->ajaxReturn(array('code' => '0', 'msg' => '商家logo必填'));
         }
		 
		 $data['logo']=I('logo','','trim');//商家logo
         $data['panorama_url']=I('vr_link','','trim');//vr
		 
         $data['service_weixin_qrcode']=I('weixin_logo','','trim');//老板微信
		 
         $start_time = I('start_time','','trim');
         $end_time = I('end_time','','trim');
		 
		 
         $data['create_time'] = NOW_TIME;
         $data['create_ip'] = get_client_ip();
         $details=I('details','','trim,htmlspecialchars');//商家简介
		 if(empty($details)){
           $this->ajaxReturn(array('code' => '0', 'msg' => '商家详情必须选择'));
         }
         $coordinates = I('coordinates','','trim,htmlspecialchars');//坐标
		 if(empty($coordinates)){
           $this->ajaxReturn(array('code' => '0', 'msg' => '坐标必须选择'));
         }
		 
		 
		 $coordinates2 = explode(',',$coordinates);

		 $data['lat'] = htmlspecialchars($coordinates2['0']);
		 $data['lng'] = htmlspecialchars($coordinates2['1']);
        
       
         if($shop_id = M('Shop')->add($data)){
			D('Shop')->buildShopQrcode($shop_id,15);//生成商家二维码
			
			$ads = explode(',',I('ad','','trim'));
			foreach($ads as $val){
				M('ShopPic')->where(array('shop_id'=>$shop_id))->add(array('photo'=>$val));
			}

			$imgs = explode(',',I('img','','trim'));

			foreach($imgs as $val){
				if($val != ''){
					$arrs .= '<img src='. config_img($val) .'>';
				}
			}
			
			$data['details'] = $details .'<br>'. $arrs;		
			$arr = array(
				'details' => $data['details'], 
				'business_time' => $start_time.''.$end_time,
			);
            D('Shopdetails')->upDetails($shop_id,$arr);
            $this->ajaxReturn(array('code' => '1', 'msg' => '入驻成功等待审核'));
         }else{
             $this->ajaxReturn(array('code' => '0', 'msg' => '入住失败'));
         }
    }
	 
	 
	//商家入驻模板消息后期开发
	public function rzmessage(){
		$access_token = $this->getaccess_token();
		echo '1';
	}
	
	public function ThreadViews(){
		$views = M('ThreadPost')->sum('views');
		$json_arr = array('status'=>1,'msg'=>'获取成功','data'=>$views);
        $json_str = json_encode($json_arr);
        exit($json_str); 
		
	}
	
	//信息首页
	public function ThreadAd(){
		$list = M('Ad')->where(array('site_id'=>'57','closed'=>'0'))->select();
		foreach ($list as $k => $val){
			$list[$k]['type'] = 8;
			$list[$k]['id'] = $val['ad_id'];
			$list[$k]['img'] = config_weixin_img($val['photo']);
			$list[$k]['logo'] = config_weixin_img($val['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	
	
	public function Threadtype(){
		$cate = M('ThreadCate')->limit(0,10)->select();
	    $arr = array();
		$i = 0 ;
	    foreach($cate as $k => $v){
		   $i ++ ;
		   $arr[$k]['id'] = $v['cate_id'];
		   $arr[$k]['type_name'] = $v['cate_name'];
		  //$arr[$k]['img'] = config_weixin_img($v['photo']);
		   $arr[$k]['img'] = __HOST__.'/static/default/wap/image/life/life_cate_'.$i.'.png';	
	    } 
        $json_str = json_encode($arr);
        exit($json_str); 
		
	}
	
	public function Threadtype2(){
		$cate_id = I('id','','trim');
		$arr = M('Thread')->where(array('cate_id'=>$cate_id))->limit(0,10)->select();
	    foreach($arr as $k => $v){
		   $arr[$k]['id'] = $v['thread_id'];
		   $arr[$k]['map'] = $v['thread_id'];
		   $arr[$k]['name'] = $v['thread_name'];
		   $arr[$k]['money'] = 0;
		   $arr[$k]['img'] = config_weixin_img($v['photo']);
	    } 
        $json_str = json_encode($arr);
        exit($json_str); 
	}
	


	//分类信息列表2
	public function PostList(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		if($thread_id = I('type2_id','','trim')){
			$map['thread_id'] = $thread_id;
        }
		$count = M('ThreadPost')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('ThreadPost')->where($map)->order('post_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$Users = M('Users')->find($val['user_id']);
			$list[$k]['id'] = $val['post_id'];
		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = $val['is_fine'] == 1 ? 1 :'0';
			$list[$k]['givelike'] = $val['zan_num'];
			$list[$k]['user_tel'] = $val['mobile'] ? $val['mobile'] : $Users['mobile'] ? $Users['mobile'] : $this->_CONFIG['site']['tel'];
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = config_user_name($Users['nickname']?$Users['nickname']:'未知');
			$list[$k]['type_name'] = $this->getListThread($val['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$val['cate_id']))->getField('cate_name');//分类
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$val['cate_id']))->select();
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['sh_time'] = $val['create_time'];
			$list[$k]['img'] = $this->getListPics($val['post_id']);
			$list[$k]['img1'] = $this->getListPics($val['post_id']);
			$list[$k]['details'] = cleanhtml($val['details']);
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
	
	
	//分类信息列表
	public function List2(){
		import('ORG.Util.Page');
        $map = array('audit' => 1, 'closed' => 0);
		if($type_id = I('type_id','','trim')){
			$map['cate_id'] = $type_id;
        }
		
		if($cate_id = I('type2_id','','trim')){
			$map['cate_id'] = $cate_id;
        }
		
		$count = M('ThreadPost')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('ThreadPost')->where($map)->order('post_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
        foreach($list as $k => $val){
			$Users = M('Users')->find($val['user_id']);
			$list[$k]['id'] = $val['post_id'];
		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = $val['is_fine'] == 1 ? 1 :'0';
			$list[$k]['givelike'] = $val['zan_num'];
			$list[$k]['user_tel'] = $val['mobile'] ? $val['mobile'] : $Users['mobile'] ? $Users['mobile'] : $this->_CONFIG['site']['tel'] ;
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = config_user_name($Users['nickname']?$Users['nickname']:'未知');
			$list[$k]['type_name'] = $this->getListThread($val['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$val['cate_id']))->getField('cate_name');//分类
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$val['cate_id']))->select();
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['sh_time'] = $val['create_time'];
			$list[$k]['img'] = $this->getListPics($val['post_id']);
			$list[$k]['img1'] = $this->getListPics($val['post_id']);
			$list[$k]['details'] = cleanhtml($val['details']);
		}
		foreach($list as $k => $val){
			$data2[]=array(
			  'tz'=>$list[$k],
			  'label'=>array(),
			 );
		}
        $json_str = json_encode($data2 ? $data2 : array());
        exit($json_str); 
		
	}

	//获取频道有问题
	public function getListThread($thread_id){
		$thread = M('Thread')->where(array('thread_id'=>$thread_id))->find();
		$res = $thread['thread_name'] ? $thread['thread_name'] : '无主题';
		return $res;
	}
	
	//贴吧点赞
	public function Like(){
		$user_id = I('user_id','','trim');
		$post_id = I('information_id','','trim');
		
        if(empty($post_id)){
           $this->ajaxReturn(array('code' => '0', 'msg' => '话题不存在'));
        }
        if($res = M('ThreadPostZan')->where(array('post_id' => $post_id, 'create_ip' => get_client_ip()))->find()) {
             $this->ajaxReturn(array('code' => '0', 'msg' => '您已经点过赞了'));
        }else{
            if(M('ThreadPostZan')->add(array('post_id' => $post_id, 'user_id' => $user_id, 'create_time' => time(), 'create_ip' => get_client_ip()))) {
                 D('ThreadPost')->updateCount($post_id, 'zan_num');
                 $this->ajaxReturn(array('code' => '1', 'msg' => '点赞成功'));
            }else{
               $this->ajaxReturn(array('code' => '0', 'msg' => '点赞失败'));
             }
        }
		
	}

	//获取列表图片开始
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
			return false;
		}
	}
	
	
	
	//分类信息
	public function PostInfo(){
		$post_id = I('id','','trim');
		$detail = M('ThreadPost')->find($post_id);
		
        $Users = M('Users')->find($detail['user_id']);
		$detail['id'] = $detail['post_id'];
		$detail['user'] = $Users;
		$detail['top'] = $detail['is_fine'] == 1 ? 1 :'0';
		$detail['givelike'] = $detail['zan_num'];
		$detail['user_tel'] = $detail['mobile'];
		$detail['user_img'] = config_weixin_img($Users['face']);			
		$detail['user_name'] = config_user_name($Users['nickname']);
		$detail['type_name'] = $this->getListThread($detail['cate_id']);//分类
		$detail['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$detail['cate_id']))->getField('cate_name');//分类
		$detail['label'] = M('ThreadCateTag')->where(array('cate_id'=>$detail['cate_id']))->select();
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
				$Users = M('Users')->find($vv['user_id']);
				$dz[$kk]['user_img'] = config_weixin_img($Users['face']);		
			}
		}
		
		
		$pl = M('ThreadPostComments')->where(array('post_id'=>$post_id))->select();	
		
		if($pl){
			foreach($pl as $kkk => $vvv){
				$Users = M('Users')->find($vvv['user_id']);
				$pl[$kkk]['id'] = $vvv['comment_id'];	
				$pl[$kkk]['name'] = config_user_name($Users['nickname']);
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
	

	
  //查看是否收藏
  public function IsCollection(){
	  echo '1';
  }
  
  

   //置顶
    public function Top(){
		$config = D('Setting')->fetchAll();
		$res[] = array(
			'id' => 1,
			'type' => 1,
			'money' => $config['pinche']['top'],
		);
      	echo json_encode($res);
    }
	
	//查看二级分类下的标签
    public function Label(){
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
   
   public function Comments(){
	    $data['post_id'] = I('information_id','','trim'); 
		$data['user_id'] = I('user_id','','trim');
		$data['contents'] = I('details','','trim,htmlspecialchars');
		$data['create_time'] = time();
        $data['create_ip'] = get_client_ip();
		if($comment_id = M('ThreadPostComments')->add($data)){
			D('ThreadPost')->updateCount($post_id, 'reply_num');
            M('ThreadPost')->save(array('post_id' => $post_id, 'last_id' => $data['user_id'], 'last_time' =>$data['create_time']));
			D('ThreadPost')->noticeUserMsg($post_id,$cid);
			echo $comment_id;
	    }else{
         echo '2';
       }
   }
   
  
     //信息发帖
	public function Posting(){
			$data['thread_id'] = I('type2_id','','trim,htmlspecialchars');
			$data['cate_id'] = I('type_id','','trim,htmlspecialchars');
			if(!$res = M('ThreadCate')->find($data['cate_id'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'分类不存在'));
			}
			
			$data['city_id'] = I('city_id','','trim');
			if(empty($data['city_id'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'城市不能为空'));
			}
			$data['mobile'] = I('user_tel','','trim,htmlspecialchars');
			if(empty($data['mobile'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'电话不能为空'));
			}
			if(!isMobile($data['mobile']) && !isPhone($data['mobile'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'电话格式不正确'));
			}
			$data['address'] = I('address','','trim,htmlspecialchars');//地址
			$data['audit'] = 1;
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
            $data['user_id'] = I('user_id','','trim');//会员
			
            $details = I('details','','trim,htmlspecialchars');//内容
            if($words = D('Sensitive')->checkWords($details)){
				$this->ajaxReturn(array('code'=>'0','msg'=>'商家介绍含有敏感词：' . $words));
            }
			//标签开始
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
			
			
			$data['title'] = niuMsubstr($details,0,30,false);//标题
            if($post_id = M('ThreadPost')->add($data)){
				$img = I('img','','trim,htmlspecialchars');//图片
				$imgs = explode(',', $img);
				if($imgs){
					M('ThreadPost')->where(array('post_id'=>$post_id))->save(array('photo'=>$imgs['0']));
				}
				$photos = array_splice($imgs,1,9); 
				foreach($photos as $val) {
					M('ThreadPostPhoto')->where(array('post_id'=>$post_id))->save(array('photo'=>$val));
				}
				$this->ajaxReturn(array('code'=>'1','msg'=>'发布信息成功'));
            }
			$this->ajaxReturn(array('code'=>'0','msg'=>'发布信息失败'));

	}
	
	//个人中心我的信息编辑
	public function UpdPost(){
		
			$data['post_id'] = I('id','','trim,htmlspecialchars');
			$data['address'] = I('address','','trim,htmlspecialchars');//地址
			$data['mobile'] = I('user_tel','','trim,htmlspecialchars');
			
			if(empty($data['mobile'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'电话不能为空'));
			}
			if(!isMobile($data['mobile']) && !isPhone($data['mobile'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'电话格式不正确'));
			}
			$details = I('details','','trim,htmlspecialchars');//内容
            if($words = D('Sensitive')->checkWords($details)){
				$this->ajaxReturn(array('code'=>'0','msg'=>'商家介绍含有敏感词：' . $words));
            }
			$data['details'] = $details;//内容
			$data['title'] = niuMsubstr($details,0,30,false);//标题
            if(M('ThreadPost')->save($data)){
				$img = I('img','','trim,htmlspecialchars');//图片
				$imgs = explode(',', $img);
				if($imgs){
					M('ThreadPost')->where(array('post_id'=>$data['post_id']))->save(array('photo'=>$imgs['0']));
				}
				$photos = array_splice($imgs,1,9); 
				foreach($photos as $val) {
					M('ThreadPostPhoto')->where(array('post_id'=>$data['post_id']))->save(array('photo'=>$val));
				}
				$this->ajaxReturn(array('code'=>'1','msg'=>'修改信息成功'));
            }
			$this->ajaxReturn(array('code'=>'0','msg'=>'修改信息失败'));

	}
	
	
		//我的分类信息
	public function MyPost(){
		import('ORG.Util.Page');
		$user_id = I('user_id','','trim');
        $map = array('audit' => 1, 'closed' => 0,'user_id'=>$user_id);
		$count = M('ThreadPost')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('ThreadPost')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$Users = M('Users')->find($val['user_id']);
			$list[$k]['id'] = $val['post_id'];
		    $list[$k]['user'] = $Users;
			$list[$k]['top'] = $val['is_fine'] == 1 ? 1 :'0';
			$list[$k]['givelike'] = $val['zan_num'];
			$list[$k]['user_tel'] = $val['mobile'];
		    $list[$k]['user_img'] = config_weixin_img($Users['face']);			
		    $list[$k]['user_name'] = config_user_name($Users['nickname']);
			$list[$k]['type_name'] = $this->getListThread($val['thread_id']);//分类
			$list[$k]['type2_name'] = M('ThreadCate')->where(array('cate_id'=>$val['cate_id']))->value('cate_name');//分类
			$list[$k]['label'] = M('ThreadCateTag')->where(array('cate_id'=>$val['cate_id']))->select();
			$list[$k]['time'] = $val['create_time'];
			$list[$k]['sh_time'] = $val['create_time'];
			$list[$k]['img'] = $this->getListPics($val['post_id']);
			$list[$k]['img1'] = $this->getListPics($val['post_id']);
			$list[$k]['details'] = cleanhtml($val['details']);
		}
		foreach($list as $k => $val){
			$data2[]=array(
			  'tz'=>$list[$k],
			  'label'=>array(),
			 );
		}
        $json_str = json_encode($list);
        exit($json_str); 
		
	}
	
	
	public function DelPost(){
		$post_id = I('id','','trim');
		$res = M('ThreadPost')->where('post_id',$post_id)->delete();
		exit($res); 
	}
	
	
	//获取频道有问题
	public function getListThread($thread_id){
		$thread = M('Thread')->where(array('thread_id'=>$thread_id))->find();
		return $thread['thread_name'];
	}
	
	
	
	//获取列表图片开始
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
			return false;
		}
	}
	
	
	//会员提现发送模板消息
	public function txmessage(){
		$form_id = I('form_id','','trim');
		$openid = I('openid','','trim');
		$cash_id = I('cash_id','','trim');
		exit(1);
	}
	
	
	//会员提现
	public function TiXian(){
		
		$username = I('username','','trim,htmlspecialchars');
		$method = I('method','','trim,htmlspecialchars');//应该是状态
		$shop_id = I('store_id','','trim');
		
		$user_id = I('user_id','','trim');
		$type = I('type','','trim');
		$connect = M('Connect')->where(array('uid' =>$user_id,'type'=>'weixin'))->find();
		
		$money = I('tx_cost','','trim') * 100;//提现金额
		$data['re_user_name'] = I('name','','trim,htmlspecialchars');
		$data['user_id'] = $user_id;
		
		$sj_cost =  I('sj_cost','','trim,htmlspecialchars') * 100;//实际提现
	    $commission = $money - $sj_cost;
		
		
        $arr = array();
        $arr['user_id'] = $user_id;
		$arr['shop_id'] = $shop_id;
        $arr['money'] = $sj_cost;
		$arr['commission'] = $commission;
        $arr['type'] = 'user';
        $arr['addtime'] = NOW_TIME;
        $arr['account'] = $username;//微信账户
		$arr['re_user_name'] = $data['re_user_name'];
		$arr['code'] = 'weixin';
			
		if($commission){
			$intro = '您申请提现，扣款'.round($money/100,2).'元，其中手续费：'.round($commission/100,2).'元';
		}else{
			$intro = '您申请提现，扣款'.round($money/100,2).'元';
		}
			
		if($cash_id = M('UsersCash')->add($arr)){
			if(D('Users')->addMoney($user_id, -$money,$intro)){
				D('UserSex')->save($data);
				exit($cash_id);
			}else{
				exit(0);
			}
		}
		exit(0);
	}
	
	//我的提现
	public function MyTiXian(){
		import('ORG.Util.Page');
		$user_id = I('user_id','','trim');
        $map = array('user_id'=>$user_id);
		$count = M('UsersCash')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('UsersCash')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['cash_id'];
			$list[$k]['time'] = $val['addtime'];
			$list[$k]['tx_cost'] = round($val['money']/100,2);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	

	//余额明细
	public function YEmx(){
		import('ORG.Util.Page');
		$user_id = I('user_id','','trim');
        $map = array('user_id'=>$user_id);
		$count = M('UserMoneyLogs')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('UserMoneyLogs')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$list[$k]['id'] = $val['log_id'];
			$list[$k]['time'] = $val['addtime'];
			$list[$k]['tx_cost'] = round($val['money']/100,2);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	
	
	//收藏的商家
	public function MyStoreCollection(){
		import('ORG.Util.Page');
		$user_id = I('user_id','','trim');
        $map = array('user_id' => $user_id,'closed' => 0);
		$count = M('ShopFavorites')->where($map)->count();
        $Page = new Page($count,5);
        $show = $Page->show();
        $p = I('p');
        if($Page->totalPages < $p){
            die('');
        }
		$list = M('ShopFavorites')->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
			$Shop = M('Shop')->find($val['shop_id']);
			$list[$k]['id'] = $val['favorites_id'];
			$list[$k]['shop_name'] = $Shop['shop_name'];
			$list[$k]['addr'] = $Shop['addr'];
			$list[$k]['tel'] = $Shop['tel'] ? $Shop['tel'] : $Shop['mobile'];
			$list[$k]['star'] = $Shop['star'];
			$list[$k]['views'] = $Shop['view'];
			$list[$k]['coordinates'] = $Shop['lat'].','.$Shop['lng'];
			$list[$k]['details'] = M('ShopDetails')->find($Shop['shop_id']);
			$list[$k]['logo'] = config_weixin_img($Shop['photo']);
		}
        $json_str = json_encode($list);
        exit($json_str); 
	}
	  
	  
}