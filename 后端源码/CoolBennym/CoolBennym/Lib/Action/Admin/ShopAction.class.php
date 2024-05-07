<?php		
class ShopAction extends CommonAction{
    private $create_fields = array('user_id','school_id','cate_id', 'grade_id', 'city_id', 'area_id', 'business_id', 'shop_name', 'logo', 'mobile', 'photo', 'addr', 'tel', 'extension', 'contact', 'tags', 'near',  'business_time','express_price',  'delivery_time', 'is_shop_earnest','earnest','orderby', 'lng', 'lat', 'price', 'recognition','panorama_url','is_restore_prestige');
    private $edit_fields = array('user_id','school_id', 'cate_id','grade_id','city_id', 'area_id', 'business_id', 'shop_name', 'mobile', 'logo', 'photo', 'addr', 'tel', 'extension', 'contact', 'tags', 'near', 'business_time', 'delivery_time', 'is_shop_earnest','earnest', 'orderby', 'lng', 'lat', 'price', 'is_ding', 'recognition','is_tuan_pay','is_hotel_pay','panorama_url', 'apiKey', 'mKey', 'partner', 'machine_code', 'service', 'service_audit', 'is_ele_print', 'is_tuan_print', 'is_goods_print', 'is_booking_print','is_appoint_print','service_audit','express_price','commission','bg_date', 'end_date','is_restore_prestige');
	
	public function _initialize(){
        parent::_initialize();
		$this->assign('cates', D('Shopcate')->fetchAll());
		$this->end_dates = D('Shop')->getEndDate();
        $this->assign('end_dates',$this->end_dates);
		$this->assign('types',$types = D('Shop')->getTypes());
        $this->assign('grades',$grades = D('Shopgrade')->where(array('closed'=>0))->select());//哈土豆二开增加商家等级
    }
	
	
	
	
	
	//批量试生产商家二维码
	public function buildqrcode($admin_id){
		$list = M('Shop')->where(array('audit'=>'1','closed'=>'0'))->select();
		$i= 0;
		foreach($list as $k => $val) {
            
			if($val['qrcode'] == ''){
				$i++;
				D('Shop')->buildShopQrcode($val['shop_id'],15);
			}
        }
		
		if($i){
			$explain = '生成'.$i.'个商家二维码';
		}else{
			$explain = '没有可生成的二维码或者操作失败';
		}
		
		$arr['admin_id'] = $admin_id;
		$arr['type'] = 2;
		$arr['intro'] = $explain;
		$arr['create_time'] = NOW_TIME;
		$arr['create_ip'] = get_client_ip();
		M('AdminActionLogs')->add($arr);  
        $this->tuSuccess($explain, U('index/main'));
    }
	
	//删除试生产商家二维
	public function delqrcode($admin_id){
		$list = M('Shop')->where(array('audit'=>'1','closed'=>'0'))->select();
		$i= 0;
		foreach($list as $k => $val){
			if($val['qrcode']){
				$i++;
				M('Shop')->where(array('shop_id'=>$val['shop_id']))->save(array('qrcode'=>''));
			}
        }
		
		if($i){
			$explain = '成功删除'.$i.'个商家二维码';
		}else{
			$explain = '没有可删除的二维码或者操作失败';
		}
		
		$arr['admin_id'] = $admin_id;
		$arr['type'] = 2;
		$arr['intro'] = $explain;
		$arr['create_time'] = NOW_TIME;
		$arr['create_ip'] = get_client_ip();
		M('AdminActionLogs')->add($arr);  
        $this->tuSuccess('成功删除'.$i.'个商家二维码', U('index/main'));
    }
	
	

	
    public function index(){
        $Shop = D('Shop');
        import('ORG.Util.Page');
		$p = (int) $this->_param('p');
        $map = array('closed' => 0);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['shop_name|tel'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		
		$getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
        if($area_id = (int) $this->_param('area_id')){
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
        if($cate_id = (int) $this->_param('cate_id')){
            $map['cate_id'] = array('IN', D('Shopcate')->getChildren($cate_id));
            $this->assign('cate_id', $cate_id);
        }
		
		if($endDate = (int) $this->_param('endDate')){
			if ($endDate != 999) {
				if($endDate == 1){
					$min = date("Y-m-d",strtotime("-1 day"));
					$max = date("Y-m-d",strtotime("+30 day"));
				}elseif($endDate == 2){
					$min = date("Y-m-d",strtotime("+31 day"));
					$max = date("Y-m-d",strtotime("+60 day"));
				}elseif($endDate == 3){
					$min = date("Y-m-d",strtotime("+61 day"));
					$max = date("Y-m-d",strtotime("+90 day"));
				}elseif($endDate == 4){
					$min = date("Y-m-d",strtotime("+91 day"));
					$max = date("Y-m-d",strtotime("+3600 day"));
				}
				$map['end_date'] = array('between', $min.','.$max);
				$this->assign('endDate', $endDate);
			}
		}else{
			$this->assign('endDate', 999);
		}
		
		
        $count = $Shop->where($map)->count();
        $Page = new Page($count,10);
        $show = $Page->show();
        $list = $Shop->order(array('shop_id' => 'desc'))->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $grade_ids =  $ids = array();
        foreach($list as $k => $val){
			$list[$k]['city'] = D('City')->where(array('city_id'=>$val['city_id']))->find();
			$list[$k]['area'] = D('Area')->where(array('area_id'=>$val['area_id']))->find();
			$list[$k]['business'] = D('Business')->where(array('business_id'=>$val['business_id']))->find();
			$list[$k]['sms'] = (int)M('SmsShop')->where(array('type' => shop,'shop_id' =>$val['shop_id'],'status'=>0))->sum('num');
            if($val['user_id']){
                $ids[$val['user_id']] = $val['user_id'];
            }
			$grade_ids[$val['grade_id']] = $val['grade_id'];
        }
        $this->assign('users', D('Users')->itemsByIds($ids));
		$this->assign('grade', D('Shopgrade')->itemsByIds($grade_ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
		$this->assign('p', $p);
        $this->display();
    }
	
	

	
	
	
    public function apply(){
        $Shop = D('Shop');
        import('ORG.Util.Page');
        $map = array('closed' => 0, 'audit' => 0);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['shop_name|tel'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
        $getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
        if($area_id = (int) $this->_param('area_id')){
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
        if($cate_id = (int) $this->_param('cate_id')){
            $map['cate_id'] = array('IN', D('Shopcate')->getChildren($cate_id));
            $this->assign('cate_id', $cate_id);
        }
        $count = $Shop->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Shop->order(array('shop_id' => 'asc'))->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $ids = array();
        foreach($list as $k => $val){
            if($val['user_id']) {
                $ids[$val['user_id']] = $val['user_id'];
            }
        }
        $this->assign('users', D('Users')->itemsByIds($ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
    public function create(){
        if($this->isPost()){
            $data2 = $data = $this->createCheck();
            $obj = D('Shop');
            $details = $this->_post('details', 'SecurityEditorHtml');
            if ($words = D('Sensitive')->checkWords($details)) {
                $this->tuError('商家介绍含有敏感词：' . $words);
            }
            $bank = $this->_post('bank', 'htmlspecialchars');
            unset($data['near'], $data['price'], $data['business_time'], $data['delivery_time']);
            if($shop_id = $obj->add($data)){
                D('Shopdetails')->upDetails($shop_id, $ex);
                $this->tuSuccess('添加成功', U('shop/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->assign('cates', D('Shopcate')->fetchAll());
            $this->display();
        }
    }
	
	
    public function select(){
        $Shop = D('Shop');
        import('ORG.Util.Page');
        $map = array('closed' =>0,'audit' =>1);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['shop_name|tel'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
        $getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
        if($area_id = (int) $this->_param('area_id')){
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
        if($cate_id = (int) $this->_param('cate_id')){
            $map['cate_id'] = array('IN', D('Shopcate')->getChildren($cate_id));
            $this->assign('cate_id', $cate_id);
        }
		
        $count = $Shop->where($map)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $list = $Shop->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $ids = array();
        foreach($list as $k => $val){
            if($val['user_id']){
                $ids[$val['user_id']] = $val['user_id'];
            }
			$list[$k]['city'] = D('City')->find($val['city_id']);
			$list[$k]['area'] = D('Area')->find($val['area_id']);
			$list[$k]['business'] = D('Business')->find($val['business_id']);
        }
		
        $this->assign('users', D('Users')->itemsByIds($ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
    private function createCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['user_id'] = (int) $data['user_id'];
        if(empty($data['user_id'])){
            $this->tuError('管理者不能为空');
        }
        $data['cate_id'] = (int) $data['cate_id'];
        if(empty($data['cate_id'])){
            $this->tuError('分类不能为空');
        }
		$data['grade_id'] = (int) $data['grade_id'];
      
        $data['city_id'] = (int) $data['city_id'];
        $data['area_id'] = (int) $data['area_id'];
        $data['business_id'] = (int) $data['business_id'];
        $data['shop_name'] = htmlspecialchars($data['shop_name']);
        if(empty($data['shop_name'])){
            $this->tuError('商铺名称不能为空');
        }
        $data['logo'] = htmlspecialchars($data['logo']);
        if(empty($data['logo'])){
            $this->tuError('请上传商铺LOGO');
        }
        if(!isImage($data['logo'])){
            $this->tuError('商铺LOGO格式不正确');
        }
        $data['photo'] = htmlspecialchars($data['photo']);
        if(empty($data['photo'])){
            $this->tuError('请上传店铺缩略图');
        }
        if(!isImage($data['photo'])){
            $this->tuError('店铺缩略图格式不正确');
        }
        $data['addr'] = htmlspecialchars($data['addr']);
        if(empty($data['addr'])){
            $this->tuError('店铺地址不能为空');
        }
        $data['tel'] = htmlspecialchars($data['tel']);
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if(empty($data['tel']) && empty($data['mobile'])){
            $this->tuError('店铺电话不能为空');
        }
		$data['express_price'] = (int) ($data['express_price']*100);
		
		
        $data['extension'] = htmlspecialchars($data['extension']);
        $data['contact'] = htmlspecialchars($data['contact']);
        $data['tags'] = str_replace(',', '，', htmlspecialchars($data['tags']));
        $data['near'] = htmlspecialchars($data['near']);
        $data['business_time'] = htmlspecialchars($data['business_time']);
        $data['orderby'] = (int) $data['orderby'];
		$data['panorama_url'] = htmlspecialchars($data['panorama_url']);
        $data['price'] = (int) $data['price'];
        $data['recognition'] = (int) $data['recognition'];
        $data['lng'] = htmlspecialchars($data['lng']);
        $data['lat'] = htmlspecialchars($data['lat']);
		$data['is_restore_prestige'] = (int) $data['is_restore_prestige'];
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] = get_client_ip();
        return $data;
    }
	
	
    public function edit($shop_id = 0){
        if($shop_id = (int) $shop_id){
            $obj = D('Shop');
            if(!($detail = $obj->find($shop_id))){
                $this->tuError('请选择要编辑的商家');
            }
            if($this->isPost()){
                $data = $this->editCheck($shop_id);
                $data['shop_id'] = $shop_id;
                $details = $this->_post('details', 'SecurityEditorHtml');
                if ($words = D('Sensitive')->checkWords($details)) {
                    $this->tuError('商家介绍含有敏感词：' . $words);
                }
                $bank = $this->_post('bank', 'htmlspecialchars');
                $shopdetails = D('Shopdetails')->find($shop_id);
                $ex = array('details' => $details, 'bank' => $bank, 'near' => $data['near'], 'price' => $data['price'], 'business_time' => $data['business_time']);
                
                unset($data['near'], $data['price'], $data['business_time']);
                if(false !== $obj->save($data)) {
                    D('Shopdetails')->upDetails($shop_id, $ex);
                    $this->tuSuccess('操作成功', U('shop/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('cates', D('Shopcate')->fetchAll());
                $this->assign('ex', D('Shopdetails')->find($shop_id));
                $this->assign('user', D('Users')->find($detail['user_id']));
				$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的商家');
        }
    }
	
	
    private function editCheck($shop_id){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['user_id'] = (int) $data['user_id'];
        if(empty($data['user_id'])) {
            $this->tuError('管理者不能为空');
        }
		
        $shop = D('Shop')->find(array('where' => array('user_id' => $data['user_id'])));
        if(!empty($shop) && $shop['shop_id'] != $shop_id) {
            $this->tuError('该管理者已经拥有商铺了');
        }
        $data['cate_id'] = (int) $data['cate_id'];
        if (empty($data['cate_id'])) {
            $this->tuError('分类不能为空');
        }
		$data['grade_id'] = (int) $data['grade_id'];
       
        $data['city_id'] = (int) $data['city_id'];
        $data['area_id'] = (int) $data['area_id'];
       
        $data['business_id'] = (int) $data['business_id'];
       
        $data['shop_name'] = htmlspecialchars($data['shop_name']);
        if (empty($data['shop_name'])) {
            $this->tuError('商铺名称不能为空');
        }
        $data['logo'] = htmlspecialchars($data['logo']);
        if (empty($data['logo'])) {
            $this->tuError('请上传商铺LOGO');
        }
        if (!isImage($data['logo'])) {
            $this->tuError('商铺LOGO格式不正确');
        }
        $data['photo'] = htmlspecialchars($data['photo']);
        if (empty($data['photo'])) {
            $this->tuError('请上传店铺缩略图');
        }
        if (!isImage($data['photo'])) {
            $this->tuError('店铺缩略图格式不正确');
        }
        $data['addr'] = htmlspecialchars($data['addr']);
        if (empty($data['addr'])) {
            $this->tuError('店铺地址不能为空');
        }
        $data['tel'] = htmlspecialchars($data['tel']);
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if (empty($data['tel']) && empty($data['mobile'])) {
            $this->tuError('店铺电话不能为空');
        }
        $data['contact'] = htmlspecialchars($data['contact']);
        $data['tags'] = htmlspecialchars($data['tags']);
        $data['near'] = htmlspecialchars($data['near']);
        $data['business_time'] = htmlspecialchars($data['business_time']);
		$data['express_price'] = (int) ($data['express_price']*100);
		
		$data['commission'] = (int) ($data['commission']*100);
		
        $data['orderby'] = (int) $data['orderby'];
		$data['panorama_url'] = htmlspecialchars($data['panorama_url']);
        $data['lng'] = htmlspecialchars($data['lng']);
        $data['lat'] = htmlspecialchars($data['lat']);
		
		$data['is_tuan_pay'] = (int) $data['is_tuan_pay'];
		$data['is_hotel_pay'] = (int) $data['is_hotel_pay'];
		
		
        $data['price'] = (int) $data['price'];
        $data['apiKey'] = htmlspecialchars($data['apiKey']);
        $data['mKey'] = htmlspecialchars($data['mKey']);
        $data['partner'] = htmlspecialchars($data['partner']);
        $data['machine_code'] = htmlspecialchars($data['machine_code']);
        $data['service'] = $data['service'];
        $data['service_audit'] = (int) $data['service_audit'];
        $data['is_ele_print'] = (int) $data['is_ele_print'];
        $data['is_tuan_print'] = (int) $data['is_tuan_print'];
        $data['is_goods_print'] = (int) $data['is_goods_print'];
        $data['is_booking_print'] = (int) $data['is_booking_print'];
		$data['is_appoint_print'] = (int) $data['is_appoint_print'];
		$data['is_restore_prestige'] = (int) $data['is_restore_prestige'];
		
		$data['bg_date'] = htmlspecialchars($data['bg_date']);
        if(!empty($data['bg_date'])) {
           if(!isDate($data['bg_date'])){
				$this->tuError('开始时间格式不正确');
			} 
        }
		
		$data['end_date'] = htmlspecialchars($data['end_date']);
        if(!empty($data['end_date'])){
            if(!isDate($data['end_date'])){
				$this->tuError('结束时间格式不正确');
			}
        }
        
        return $data;
    }
	
	
    public function delete($shop_id = 0){
        if(is_numeric($shop_id) && ($shop_id = (int) $shop_id)){
            $obj = D('Shop');
            $obj->save(array('shop_id' => $shop_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('shop/index'));
        }else{
            $shop_id = $this->_post('shop_id', false);
            if(is_array($shop_id)){
                $obj = D('Shop');
                foreach($shop_id as $id){
                    $obj->save(array('shop_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('删除成功', U('shop/index'));
            }
            $this->tuError('请选择要删除的商家');
        }
    }
	
	
    public function audit($shop_id = 0){
        $shop_id = (int) $shop_id;
		if($shop_id){
			if(M('shop')->save(array('shop_id'=>$shop_id,'audit' => 1))){
				$this->tuSuccess('审核成功', U('shop/index'));
			}else{
				$this->tuError('审核失败');
			}
        }else{
			$this->tuError('商家不存在');
		}
	}
	
	
    public function login($shop_id){
        $obj = D('Shop');
        if (!($detail = $obj->find($shop_id))){
            $this->error('请选择要编辑的商家');
        }
		if($this->_admin['admin_id'] != 1){
			$this->error('您没有权限');
		}
        if (empty($detail['user_id'])) {
            $this->error('该用户没有绑定管理者');
        }
		
        setUid($detail['user_id']);
        header('Location:' . U('Seller/index/index'));
        die;
    }
   
   
   
	//新版开启外卖配送
    public function is_ele_pei($shop_id,$p = 0){
       
        if(!($detail = M('shop')->find($shop_id))){
            $this->error('请选择要编辑的商家');
        }
		
        if($detail['is_ele_pei'] == 1){
			  M('shop')->save(array('shop_id'=>$shop_id,'is_ele_pei' =>0));
			  $info = '开启平台配送成功';
        }else{
            if($detail['is_ele_pei'] == 0){
                M('shop')->save(array('shop_id'=>$shop_id,'is_ele_pei' =>1));
				$info = '开启商家配送成功';
            }
        }
        $this->success($info,U('shop/index',array('p'=>$p)));
    }
	

	
    public function recovery(){
        $Shop = D('Shop');
        import('ORG.Util.Page');
        $map = array('closed' => 1);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['shop_name|tel'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
        $getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
        if($area_id = (int) $this->_param('area_id')){
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
        if ($cate_id = (int) $this->_param('cate_id')){
            $map['cate_id'] = array('IN', D('Shopcate')->getChildren($cate_id));
            $this->assign('cate_id', $cate_id);
        }
        $count = $Shop->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Shop->order(array('shop_id' => 'desc'))->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $ids = array();
        foreach($list as $k => $val){
            if($val['user_id']){
                $ids[$val['user_id']] = $val['user_id'];
            }
        }
        $this->assign('users', D('Users')->itemsByIds($ids));
        $this->assign('citys', D('City')->fetchAll());
        $this->assign('areas', D('Area')->fetchAll());
        $this->assign('cates', D('Shopcate')->fetchAll());
        $this->assign('business', D('Business')->fetchAll());
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
  
	
    public function delete2($shop_id = 0){
        $shop_id = (int) $shop_id;
        if(!empty($shop_id)){
            $goods = D('Goods')->where(array('shop_id' => $shop_id))->select();
            foreach ($goods as $k => $value){
                D('Goods')->save(array('goods_id' => $value['goods_id'], 'closed' => 1));
            }
            $coupon = D('Coupon')->where(array('shop_id' => $shop_id))->select();
            foreach($coupon as $k => $value){
                D('Tuan')->save(array('coupon_id' => $value['coupon_id'], 'closed' => 1));
            }
            $tuan = D('Tuan')->where(array('shop_id' => $shop_id))->select();
            foreach($goods as $k => $value){
                D('Tuan')->save(array('tuan_id' => $value['tuan_id'], 'closed' => 1));
            }
            D('Ele')->save(array('shop_id' => $value['shop_id'], 'audit' => 0));
            D('Shop')->delete($shop_id);
            $this->tuSuccess('彻底删除成功', U('shop/recovery'));
        }else{
            $this->tuError('操作失败');
        }
    }
	
	
	
	
	
	
	
}