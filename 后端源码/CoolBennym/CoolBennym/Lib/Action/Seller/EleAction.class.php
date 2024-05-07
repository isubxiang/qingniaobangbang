 <?php
class EleAction extends CommonAction{
	
	
    private $create_fields = array('product_name','desc','cate_id','is_options', 'photo','cost_price','price','tableware_price','is_new','is_hot','is_tuijian','num','limit_num','create_time','create_ip');
    private $edit_fields = array('product_name','desc','cate_id','is_options', 'photo','cost_price','price','tableware_price','is_new','is_hot','is_tuijian','num','limit_num');
	
	
	//订单状态
	private function getOrderStatus(){
        return array(
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
	
	
    public function _initialize(){
        parent::_initialize();
        $this->ele = M('Ele')->find($this->shop_id);
	
	
        if(empty($this->ele) && ACTION_NAME != 'apply'){
            $this->error('您还没有入住外卖频道,即将为您跳转', U('ele/apply'));
        }
        if(!empty($this->ele) && ACTION_NAME != 'apply' && $this->ele['audit'] == 0){
            $this->error('您的外卖还在审核中哦', U('ele/apply'));
        }
        if(!empty($this->ele) && ACTION_NAME != 'apply' && $this->ele['audit'] == 2){
            $this->error('您的审核未通过哦', U('ele/apply'));
        }
        $this->assign('ele', $this->ele);
        $getEleCate = D('Ele')->getEleCate();
        $this->assign('getEleCate', $getEleCate);
        $this->elecates = D('Elecate')->where(array('shop_id' => $this->shop_id, 'closed' => 0))->select();
		$this->assign('orderTypes', $orderTypes = D('Eleorder')->getEleOrderType());
        $this->assign('elecates', $this->elecates);
		
		$this->config = D('Setting')->fetchAll();
		
		$this->assign('getOrderStatus', $this->getOrderStatus());
		$this->getOrderStatus = $this->getOrderStatus();
		
    }
	
	
	
	 public function apply(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false),array(
				'shop_id','pic1','pic2','distribution','is_open','is_pay','is_fan','fan_money','is_new','full_money','new_money','logistics','since_money','sold_num','month_num','intro','orderby'
			));
			$data['shop_id'] = $this->shop_id;
			if(empty($data['shop_id'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'ID不能为空'));
			}
			if(!($shop = M('Shop')->find($data['shop_id']))){
				$this->ajaxReturn(array('code'=>'0','msg'=>'商家不存在'));
			}
			$data['shop_name'] = $shop['shop_name'];
			$data['lng'] = $shop['lng'];
			$data['lat'] = $shop['lat'];
			$data['area_id'] = $shop['area_id'];
			$data['city_id'] = $shop['city_id'];
			$data['business_id'] = $shop['business_id'];
			
			$data['pic1'] = htmlspecialchars($data['pic1']);
			$data['pic2'] = htmlspecialchars($data['pic2']);
			
			$data['is_open'] = (int) $data['is_open'];
			$data['is_pay'] = (int) $data['is_pay'];
			$data['is_fan'] = (int) $data['is_fan'];
			$data['fan_money'] = (int) ($data['fan_money'] * 100);
			$data['is_new'] = (int) $data['is_new'];
			$data['full_money'] = (int) ($data['full_money'] * 100);
			$data['new_money'] = (int) ($data['new_money'] * 100);
			$data['logistics'] = (int) ($data['logistics'] * 100);
			$data['since_money'] = (int) ($data['since_money'] * 100);
			if($data['since_money'] <= 0){
				$this->ajaxReturn(array('code'=>'0','msg'=>'起送价设置错误'));
			}
			$data['distribution'] = (int) $data['distribution'];
			$data['intro'] = htmlspecialchars($data['intro']);
			if(empty($data['intro'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'说明不能为空'));
			}

            $cate = $this->_post('cate', false);
            $cate = implode(',', $cate);
            $data['cate'] = $cate;
			
			
            if(M('Ele')->add($data)){
				$this->ajaxReturn(array('code'=>'1','msg'=>'申请成功，请等待网站管理员审核','url' =>U('index/index')));
            }
			$this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
        }else{
            $lat = addslashes(cookie('lat'));
            $lng = addslashes(cookie('lng'));
            if(empty($lat) || empty($lng)){
                $lat = $this->_CONFIG['site']['lat'];
                $lng = $this->_CONFIG['site']['lng'];
            }
            if($business_id = (int) $this->_param('business_id')){
                $map['business_id'] = $business_id;
                $this->assign('business_id', $business_id);
            }
            $this->assign('lat', $lat);
            $this->assign('lng', $lng);
			$this->assign('ele', $this->ele);
			$this->assign('detail', $this->ele);
            $this->display();
        }
    }
	
	
	
	//基本设置
    public function gears(){
		
        if($this->isPost()){
			
			$is_open = (int) $_POST['is_open'];
			$is_taking = (int) $_POST['is_taking'];
			
			$logistics_full = (int) ($_POST['logistics_full']*100);
			
			
			$logistics = (int) ($_POST['logistics']*100);
			if(empty($logistics)) {
				$this->ajaxReturn(array('code'=>'0','msg'=>'配送费必须填写'));
			}
			$busihour = $_POST['busihour'];
		
			$since_money = (int) ($_POST['since_money']*100);
			
			
			$tags = $_POST['tags'];
			M('Ele')->save(array(
				'shop_id' => $this->shop_id, 
				'is_open' => $is_open,
				'busihour' => $busihour,  
				'logistics' => $logistics, 
				'since_money' => $since_money, 
				'tags' => $tags
			));
		
			$data = $this->checkFields($this->_post('data', false), array('is_ele_print','is_ele_print_type','apiKey','mKey','partner','machine_code'));
			
			$data['shop_id'] = $this->shop_id;
			$data['is_ele_print'] = (int) $_POST['is_ele_print'];
			$data['is_taking'] = (int) $_POST['is_taking'];//自动接单
			$data['is_ele_print_type'] = (int) $_POST['is_ele_print_type'];
            $data['apiKey'] = htmlspecialchars($data['apiKey']);
            $data['mKey'] = htmlspecialchars($data['mKey']);
            $data['partner'] = htmlspecialchars($data['partner']);
            $data['machine_code'] = htmlspecialchars($data['machine_code']);
			
			//更新商家打印配置
			M('shop')->save($data);
			
			$this->ajaxReturn(array('code'=>'1','msg'=>'外卖设置成功','url'=>U('ele/gears')));
        }
        $this->assign('ele', $this->ele);
        $this->display();
	}
	
	
	
	
	


    public function elecate(){
        import('ORG.Util.Page');
        $map = array('closed' => '0');
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['cate_name'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if($shop_id = $this->shop_id){
            $map['shop_id'] = $shop_id;
            $shop = M('Shop')->find($shop_id);
            $this->assign('shop_name', $shop['shop_name']);
            $this->assign('shop_id', $shop_id);
        }
        $count = M('EleCate')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('EleCate')->where($map)->order(array('cate_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $shop_ids = array();
        foreach($list as $k => $val){
            if($val['shop_id']){
                $shop_ids[$val['shop_id']] = $val['shop_id'];
            }
        }
        if($shop_ids){
            $this->assign('shops', D('Shop')->itemsByIds($shop_ids));
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	
    public function catecreate(){
		$shop_id = $this->shop_id;
		$cate_name = I('cate_name', '', 'trim,htmlspecialchars');
		if(empty($cate_name)){
			$this->ajaxReturn(array('status' => 'error', 'message' => '分类名称不能为空'));
		}
		$data = array('shop_id' => $shop_id, 'cate_name' => $cate_name, 'num' => 0, 'closed' => 0);
		if(M('EleCate')->add($data)){
			$this->ajaxReturn(array('status' => 'success', 'message' => '添加成功'));
		}
		$this->ajaxReturn(array('status' => 'error', 'message' => '添加失败'));
    }
	
	
	public function cateDelete(){
		$cate_id = I('cate_id', '', 'trim,htmlspecialchars');
		if(empty($cate_id)){
			$this->ajaxReturn(array('status' => 'error', 'message' => 'ID不存在'));
		}
		if($res = M('EleCate')->where(array('cate_id'=>$cate_id))->delete()){
			$this->ajaxReturn(array('status' => 'success', 'message' => '操作成功'));
		}
		$this->ajaxReturn(array('status' => 'error', 'message' => '操作失败'));
    }
	
	
    public function cateedit(){
		$cate_id = I('v', '', 'intval,trim');
		if($cate_id){
			if(!($detail = M('EleCate')->find($cate_id))){
				$this->ajaxReturn(array('status' => 'error', 'message' => '请选择要编辑的菜单分类'));
			}
			if($detail['shop_id'] != $this->shop_id){
				$this->ajaxReturn(array('status' => 'error', 'message' => '请不要操作其他商家的菜单分类'));
			}
			$cate_name = I('cate_name', '', 'trim,htmlspecialchars');
			if (empty($cate_name)){
				$this->ajaxReturn(array('status' => 'error', 'message' => '分类名称不能为空'));
			}
			$data = array('cate_name' => $cate_name);
			if(false !== M('EleCate')->where('cate_id =' . $cate_id)->setField($data)){
				$this->ajaxReturn(array('status' => 'success', 'message' => '操作成功'));
			}
			$this->ajaxReturn(array('status' => 'error', 'message' => '操作失败'));
		}else{
			$this->ajaxReturn(array('status' => 'error', 'message' => '请选择要编辑的菜单分类'));
		}
    }
	
	
	
    public function index(){
        import('ORG.Util.Page');
        $map = array('closed' => 0);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['product_name'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if($shop_id = $this->shop_id){
            $map['shop_id'] = $shop_id;
            $this->assign('shop_id', $shop_id);
        }
        $count = M('EleProduct')->where($map)->count();
        $Page = new Page($count,30);
        $show = $Page->show();
        $list = M('EleProduct')->where($map)->order(array('product_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $cate_ids = array();
        foreach($list as $k => $val){
            if($val['cate_id']){
                $cate_ids[$val['cate_id']] = $val['cate_id'];
            }
        }
        if($cate_ids){
            $this->assign('cates', D('Elecate')->itemsByIds($cate_ids));
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	
	
    public function shelves(){
        import('ORG.Util.Page');
        $map = array('closed' =>1);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['product_name'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
        if($shop_id = $this->shop_id){
            $map['shop_id'] = $shop_id;
            $this->assign('shop_id', $shop_id);
        }
        $count = M('EleProduct')->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list = M('EleProduct')->where($map)->order(array('product_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $cate_ids = array();
        foreach($list as $k => $val){
            if($val['cate_id']){
                $cate_ids[$val['cate_id']] = $val['cate_id'];
            }
        }
        if($cate_ids){
            $this->assign('cates', D('Elecate')->itemsByIds($cate_ids));
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	

	
    //下架菜单
    public function delete($product_id = 0){
        $product_id = (int) $product_id;
        if(empty($product_id)){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '访问错误'));
        }
        if(!($detail = M('EleProduct')->where(array('shop_id' => $this->shop_id, 'product_id' => $product_id))->find())){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '请选择要下架的菜单管理'));
        }
        D('Elecate')->updateNum($detail['cate_id']);
        M('EleProduct')->save(array('product_id' => $product_id, 'closed' => 1));
        $this->ajaxReturn(array('status' => 'success', 'msg' => '下架成功', U('ele/shelves')));
    }
	
	
	
    //上架菜单
    public function updates($product_id = 0){
        $product_id = (int) $product_id;
        if(empty($product_id)){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '访问错误'));
        }
        if(!($detail = M('EleProduct')->where(array('shop_id' => $this->shop_id, 'product_id' => $product_id))->find())){
            $this->ajaxReturn(array('status' => 'error', 'msg' => '请选择要上架的菜单管理'));
        }
        D('Elecate')->updateNum($detail['cate_id']);
        M('EleProduct')->save(array('product_id' => $product_id, 'closed' => 0));
        $this->ajaxReturn(array('status' => 'success', 'msg' => '上架成功', U('ele/index')));
    }
	
	
   
	
	
	
    public function create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false), $this->create_fields);
			$data['product_name'] = htmlspecialchars($data['product_name']);
			if(empty($data['product_name'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'菜名不能为空'));
			}
			$data['desc'] = htmlspecialchars($data['desc']);
			
			$data['shop_id'] = $this->shop_id;
			$data['cate_id'] = (int) $data['cate_id'];
			if(empty($data['cate_id'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'分类不能为空'));
			}
			$data['photo'] = htmlspecialchars($data['photo']);
			if(empty($data['photo'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'请上传缩略图'));
			}
			if(!isImage($data['photo'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'缩略图格式不正确'));
			}
			$data['cost_price'] = (int) ($data['cost_price'] * 100);
			$data['price'] = (int) ($data['price'] * 100);
			if(empty($data['price'])){
				$this->ajaxReturn(array('code'=>'0','msg'=>'价格不能为空'));
			}
			//添加
			$data['tableware_price'] = (int) ($data['tableware_price'] * 100);
		
		
		
			$data['is_new'] = (int) $data['is_new'];
			$data['is_hot'] = (int) $data['is_hot'];
			$data['is_tuijian'] = (int) $data['is_tuijian'];
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
			$data['audit'] = 1;
		
            if(M('EleProduct')->add($data)){
                D('Elecate')->updateNum($data['cate_id']);
                $this->ajaxReturn(array('code'=>'1','msg'=>'操作成功','url' =>U('ele/index')));
            }
            $this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
        }else{
            $this->display();
        }
    }
	
	

	
    public function edit($product_id = 0){
        if($product_id = (int) $product_id){
            if(!($detail = M('EleProduct')->find($product_id))){
                $this->error('请选择要编辑的菜单管理');
            }
            if($detail['shop_id'] != $this->shop_id){
                $this->error('请不要操作其他商家的菜单管理');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
				$data['product_name'] = htmlspecialchars($data['product_name']);
				if(empty($data['product_name'])){
					$this->ajaxReturn(array('code'=>'0','msg'=>'菜名不能为空'));
				}
				$data['desc'] = htmlspecialchars($data['desc']);
				$data['cate_id'] = (int) $data['cate_id'];
				if(empty($data['cate_id'])){
					$this->ajaxReturn(array('code'=>'0','msg'=>'分类不能为空'));
				}
				$data['photo'] = htmlspecialchars($data['photo']);
				if(empty($data['photo'])){
					$this->ajaxReturn(array('code'=>'0','msg'=>'请上传缩略图'));
				}
				if(!isImage($data['photo'])){
					$this->ajaxReturn(array('code'=>'0','msg'=>'缩略图格式不正确'));
				}
				$data['cost_price'] = (int) ($data['cost_price'] * 100);
				$data['price'] = (int) ($data['price'] * 100);
				if(empty($data['price'])){
					$this->ajaxReturn(array('code'=>'0','msg'=>'价格不能为空'));
				}
				
				
				$data['is_new'] = (int) $data['is_new'];
				$data['is_hot'] = (int) $data['is_hot'];
				$data['is_tuijian'] = (int) $data['is_tuijian'];
		
                $data['product_id'] = $product_id;
                if(false !== M('EleProduct')->save($data)){
                    D('Elecate')->updateNum($data['cate_id']);
					
                    $this->ajaxReturn(array('code'=>'1','msg'=>'操作成功','url' =>U('ele/index')));
                }
				$this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
            }else{
                $this->assign('detail', $detail);
				
                $this->display();
            }
        }else{
			$this->ajaxReturn(array('code'=>'0','msg'=>'请选择要编辑的菜单管理败'));
        }
    }
	

   //上架菜单
   public function Printing($order_id = 0){
        $order_id = (int) $order_id;
        if(!($detail = M('EleOrder')->find($order_id))){
			$this->ajaxReturn(array('code' =>'0','msg' => '订单不存在'));
        }
        if($detail['shop_id'] != $this->shop_id){
			$this->ajaxReturn(array('code' =>'0','msg' => '请不要非法操作'));
        }
		
        D('Eleorder')->combination_ele_print($order_id,$detail['addr_id']);
		
		if($res = M('EleOrder')->save(array('order_id' => $order_id,'is_print' =>1))){
			$this->ajaxReturn(array('code'=>'1','msg'=>'操作打印成功','url' => U('ele/order')));
		}else{
			$this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
		}
    }
  
  	
	//订单
	public function order(){
		
		//统计数量
		$getOrderStatus = array();
		foreach($this->getOrderStatus as $k2 =>$v2){   
		    $getOrderStatus[$k2]['id'] = $k2; 
		    $getOrderStatus[$k2]['name'] = $v2; 
			$getOrderStatus[$k2]['count'] = (int)M('running')->where(array('ShopId'=>$this->shop_id,'OrderStatus'=>$k2,'closed'=>0))->count();
		}
		$this->assign('getOrderStatus',$getOrderStatus);
		
        $status = I('status', '', 'intval,trim');
        $this->assign('status', $status);
		
		$keyword = $this->_param('keyword', 'htmlspecialchars');
        $this->assign('keyword', $keyword);
		
        $this->assign('nextpage', LinkTo('ele/loaddata',array('status'=>$status,'keyword'=>$keyword,'t' => NOW_TIME, 'p' => '0000')));
        $this->display();
    }

    //加载订单
    public function loaddata(){
        import('ORG.Util.Page');
        $map = array('ShopId' => $this->shop_id);
		
		if(isset($_GET['status']) || isset($_POST['status'])){
            $status = (int) $this->_param('status');
            if($status != 999) {
                $map['OrderStatus'] = $status;
            }
            $this->assign('status', $status);
        }else{
            $this->assign('status',999);
        }
		
		
        $count = M('Running')->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
		
		$var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = $_GET[$var];
        if($Page->totalPages < $p){
            die('0');
        }
        $list = M('Running')->where($map)->order(array('running_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
        foreach($list as $key => $val){
            $list[$key]['user'] = D('Users')->where(array('user_id'=>$val['user_id']))->find();
			$product = M('RunningProduct')->where(array('running_id'=>$val['running_id']))->select();
			foreach($product as $k2 => $v2){
				$product[$k2]['products'] = M('EleProduct')->where(array('product_id'=>$v2['product_id']))->find();
			}
			$list[$key]['product'] = $product;
			$list[$key]['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
			$list[$key]['d'] = M('RunningDelivery')->where(array('delivery_id'=>$val['delivery_id']))->find();
        }
		
        $this->assign('list', $list);
        $this->assign('page', $show);
		$this->display(); 
    }
	
	//订单详情
	public function detail($running_id = 0){
        $map = array('running_id'=>$running_id);  
	 
        $var = M('Running')->where($map)->find();
		$var['d'] = M('Delivery')->where(array('user_id'=>$val['cid']))->find();
		$var['city'] = M('City')->where(array('city_id'=>$val['city_id']))->find();
		$var['user'] = M('Users')->where(array('user_id'=>$val['user_id']))->find();
		$var['money'] = M('RunningMoney')->where(array('type'=>'running','order_id'=>$val['running_id']))->find();
		$var['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
		
		
		//菜品列表
		$product = M('RunningProduct')->where(array('running_id'=>$val['running_id']))->select();
		foreach($product as $k2 => $v2){
			$product[$k2]['products'] = M('EleProduct')->where(array('product_id'=>$v2['product_id']))->find();
		}
		$var['product'] = $product;
			
			
		
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
	
	
	//导航实时刷新定位
	public function daohang(){
		 
		$running_id = I('running_id', '', 'intval,trim');
        $user_id = $this->uid;
		
		$running = M('running')->where(array('running_id'=>$running_id))->find();
        $delivery = M('running_delivery')->where(array('delivery_id'=>$running['delivery_id']))->field('lng,lat')->find();
		
		$endAddress = unserialize($running['endAddress']);
		$AddressId = $endAddress['AddressId'];
        $UserAddr = M('UserAddr')->where(array('addr_id'=>$AddressId))->field('lat,lng')->find();
		
		$shop = M('shop')->where(array('shop_id'=>$running['ShopId']))->find();
		
		
        $shop_coor = array('lng'=>$shop['lng'], 'lat'=>$shop['lat']);
        $user_coor = array('lng'=>$UserAddr['lng'], 'lat'=>$UserAddr['lat']);
        $delivery_coor = array('lng'=>$delivery['lng'], 'lat'=>$delivery['lat']);
		
        if($this->isAjax()){
            $this->ajaxReturn(array('shop_coor'=>$shop_coor,'user_coor'=>$user_coor,'delivery_coor'=>$delivery_coor));
        }
		
		
        $this->assign('shop_coor',$shop_coor);
        $this->assign('user_coor',$user_coor);
        $this->assign('delivery_coor',$delivery_coor);
        $this->assign('running_id',$running_id);
		$this->assign('ok_date',$ok_date = D('Running')->sendTime($running_id));
        $this->display();
    }
	
	
	
	
    //取消订单
    public function cancel($running_id,$status = 0){
		
        if(!($detail = M('Running')->find($running_id))){
            $this->ajaxReturn(array('code'=>'0','msg'=>'没有该订单'));
        }
        if($detail['ShopId'] != $this->shop_id){
            $this->ajaxReturn(array('code'=>'0','msg'=>'您无权管理该商家'));
        }
	
		if($detail['OrderStatus'] > 2){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单状态不支持取消订单'));
		}
		
		$res =M('Running')->where(array('running_id'=>$detail['running_id']))->save(array('OrderStatus'=>1024,'status'=>1024,'cancel_time'=>time()));
		if($res){
			$RunningProduct = M('RunningProduct')->where(array('running_id'=>$running_id))->save(array('OrderStatus'=>1024,'cancel_time'=>time()));
			
			$v = M('Running')->find($running_id);
			
			//原路退回资金
			$info = '商家取消餐饮订单ID【'.$v['running_id'].'】';
			
			$mix = $this->config['running']['running_weixin_original_refund_mix'] ? $this->config['running']['running_weixin_original_refund_mix'] : 10;
			$mix2 = $mix*100;
			if($this->config['running']['running_weixin_original_refund'] == 1 && $v['MoneyPayment'] < $mix2  && $v['MoneyTip'] == 0){
				$runningOrderRefundUser = D('Running')->runningOrderRefundUser($v['running_id'],$v['user_id'],$v['MoneyPayment'],'running',$info);
				//如果退款失败
				if($runningOrderRefundUser == false){
					M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>8192,'OrderRefundInfo'=>D('Running')->getError()));
					$this->ajaxReturn(array('code'=>'0','msg'=>'退款有一点问题'.D('Running')->getError()));
				}					
			}else{
				D('Users')->addMoney($v['user_id'],$v['MoneyPayment'],$info,2,$v['school_id']);//退款给余额
			}
			
			
			
			D('Weixintmpl')->runningWxappNotice($v['running_id'],$OrderStatus = 1024,$user_id= '',$type = 1,$openid='',$form_id='');//模板消息订单ID，订单状态，下单人，类型
				
			$this->ajaxReturn(array('code'=>'1','msg'=>'您已成功取消订单','url' =>U('ele/order',array('status'=>$status))));
				
		}
		$this->ajaxReturn(array('code'=>'0','msg'=>'取消失败'));
    }
	
	

		
	//确认接单
    public function taking($running_id,$status = 0){
		
        if(!($detail = M('Running')->find($running_id))){
			$this->ajaxReturn(array('code'=>'0','msg'=>'没有该订单'));
        }
        if($detail['ShopId'] != $this->shop_id){
			$this->ajaxReturn(array('code'=>'0','msg'=>'您无权管理该商家'));
        }
		
		//确认发货逻辑封装
		$taking = D('Running')->taking($running_id,$isPrint = 0,$isPrintInfo = '');
		
		if($detail['orderType'] == 2 || $detail['is_ele_pei'] == 1){
			$this->ajaxReturn(array('code'=>'1','msg'=>'您已点击确认接单用户确认完成后该笔订单即可完成','url' =>U('ele/order',array('status'=>32))));
		}else{
			$this->ajaxReturn(array('code'=>'1','msg'=>'您已点击确认接单等待配送员接单中','url'=>U('ele/order',array('status'=>$status))));
		}
    }
	
	
	//商家完成订单
	public function complete($running_id = 0,$status = 0){
		$running_id = (int) $running_id;
		$v = M('Running')->find($running_id);
		if(!$v){
			$this->ajaxReturn(array('code'=>'0','msg'=>'订单不存在'));
		}
		if($v['ShopId'] != $this->shop_id){
			$this->ajaxReturn(array('code'=>'0','msg'=>'您无权管理该商家'));
        }
		if($v['OrderStatus'] == 0){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单未付款'));
		}
		if($v['OrderStatus'] < 16){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单状态已经不支持确认收货'));
		}
		
		$runingSettlement = D('Running')->runingSettlement($v['running_id'],$v['delivery_id'],$labels = '',$content = '');//结算封装函数
		if($runingSettlement == false){
			$this->ajaxReturn(array('code'=>'0','msg'=>D('Running')->getError()));
		}
		$this->ajaxReturn(array('code'=>'1','msg'=>'操作成功【资金原路返回】','url'=>U('ele/order',array('status'=>$status))));
    }
	
	
	//商家强制退款
	public function refund($running_id = 0,$status = 0){
		$running_id = (int) $running_id;
		
		$v = M('Running')->find($running_id);
		if(!$v){
			$this->ajaxReturn(array('code'=>'0','msg'=>'订单不存在'));
		}
		if($v['ShopId'] != $this->shop_id){
			$this->ajaxReturn(array('code'=>'0','msg'=>'您无权管理该商家'));
        }
		if($v['OrderStatus'] == 0){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单未付款'));
		}
		if($v['is_ele_pei'] == 0){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单是平台配送模式，不支持强制退款'));
		}
		if($v['OrderStatus'] > 32){
			$this->ajaxReturn(array('code'=>'0','msg'=>'当前订单状态已经不支持强制退款'));
		}
		//新增退款安全
		$logs = M('PaymentLogs')->where(array('type'=>'running','order_id'=>$running_id,'is_paid'=>1))->find();
		if(!$logs){
			$this->ajaxReturn(array('code'=>'0','msg'=>'支付订单不存在'));
		}
		if(!empty($logs) && !empty($logs['refund_id'])){
			$this->ajaxReturn(array('code'=>'0','msg'=>'请不要重复申请退款'));
		}
		
		//强制改变订单状态
		$res= M('Running')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>'4096'));
		
		if($res == false){
			$this->ajaxReturn(array('code'=>'0','msg'=>'更新订单状态失败'));
		}
		$RunningProduct = M('RunningProduct')->where(array('running_id'=>$v['running_id']))->save(array('OrderStatus'=>4096));
		
		
		$commonRefundUser = D('Running')->commonRefundUser($v['running_id'],$saveOrderStatus = '8192',$refundInfo = '商家强制退款',2,$type = 1);//超时无人接单退款功能封装
		
		if($commonRefundUser){
			$this->ajaxReturn(array('code'=>'1','msg'=>'操作成功【资金退回会员账户】','url'=>U('ele/order',array('status'=>$status))));
		}else{
			$this->ajaxReturn(array('code'=>'0','msg'=>'操作失败'));
		}
    }
	
	
	
	
	//打印订单小票打印机
	public function combinationElePrint($running_id = 0,$status = 0){
		$running_id = (int) $running_id;
		$v = M('Running')->find($running_id);
		if(!$v){
			$this->ajaxReturn(array('code'=>'0','msg'=>'订单不存在'));
		}
		if($v['ShopId'] != $this->shop_id){
			$this->ajaxReturn(array('code'=>'0','msg'=>'您无权管理该商家'));
        }
	
		
		$runningOrderRefundUser = D('Running')->combinationElePrint($running_id);
		
		$this->ajaxReturn(array('code'=>'1','msg'=>D('Running')->getError(),'url'=>U('ele/order',array('status'=>$status))));
	}
	
}