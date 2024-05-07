<?php
class GroupAction extends CommonAction{
	
	//订单状态
	 private function getOrderStatus(){
        return array(
			'1' => '待付款', 
			'2' => '待处理', 
			'4' => '制作中', 
		);
    }
	
	 public function _initialize(){
        parent::_initialize();
    }
	

	

	
	//拼团分类
    public function type(){
        import('ORG.Util.Page');
		$map = array();
        if($keyword = $this->_param("keyword","htmlspecialchars")){
            $map['name'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        $count = M('group_type')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('group_type')->where($map)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	

   //删除分类
   public function typeDelete($id = 0){
		$id = (int) $id;
		$detail = M('group_type')->where(array('id'=>$id))->find();
        if(!$detail ){
            $this->tuError('商品不存在');
        }
		if(M('group_type')->where(array('id'=>$id))->delete()){
			$this->tuSuccess('操作成功', U('group/type'));
		}else{
            $this->tuError('操作失败');            
        }
    }
	
	
	

	//操作分类
	public function typePublish($id = 0){
       
		if($this->isPost()){
			$data = $this->checkFields($this->_post('data',false),array('id','school_id','name','img','num'));
			
			$data['id'] = (int) $data['id']?$data['id']:$id;
			
			if($data['id']){
				$res =M('group_type')->save($data);
				$intro = '修改成功';
			}else{
				$res = M('group_type')->add($data);
				$intro = '添加成功';
			}
			if($res){
				$this->tuSuccess($intro,U('group/type',array('cid'=>$data['id'])));
			}else{
				$this->tuError('操作失败');
			}
		}else{
			$this->assign('id',$id);
			$this->assign('detail',$detail =M('group_type')->find($id));
			$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
			$this->display();
		}
    }
	
	
	//商品
    public function goods(){
        import('ORG.Util.Page');
		$map = array();
        if($keyword = $this->_param("keyword","htmlspecialchars")){
            $map['name'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        $count = M('group_goods')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('group_goods')->where($map)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['type'] = M('group_type')->where(array('id'=>$val['type_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	

   //删除商品
   public function goodsDelete($id = 0){
		$id = (int) $id;
		$detail = M('group_goods')->where(array('id'=>$id))->find();
        if(!$detail ){
            $this->tuError('商品不存在');
        }
		if(M('group_goods')->where(array('id'=>$id))->delete()){
			$this->tuSuccess('删除成功', U('group/goods'));
		}else{
            $this->tuError('删除失败');            
        }
    }
	
	
	
   //审核商品
   public function goodsAudit($id = 0,$state = 0){
		$id = (int) $id;
		$state = (int) $state;
		$detail = M('group_goods')->where(array('id'=>$id))->find();
        if(!$detail ){
            $this->tuError('商品不存在');
        }
		if(M('group_goods')->where(array('id'=>$id))->save(array('state'=>$state))){
			$this->tuSuccess('审核成功', U('group/goods'));
		}else{
            $this->tuError('审核失败');            
        }
    }
	
	

	//操作商品
	public function goodsPublish($id = 0){
       
		if($this->isPost()){
			$data = $this->checkFields($this->_post('data',false),array('id','school_id','store_id','city_id','shop_id','type_id','name','logo','img','inventory','pt_price','y_price','dd_price','ycd_num','ysc_num','people','start_time','end_time','xf_time','is_shelves','details','details_img','num','introduction'));
			
			$data['id'] = (int) $data['id']?$data['id']:$id;
			
			
			$data['shop_id'] = (int) $data['shop_id'];
			if(empty($data['shop_id'])){
				$this->tuError('商家不能为空');
			}
			$shop = M('shop')->find($data['shop_id']);
			if(empty($shop)){
				$this->tuError('请选择正确的商家');
			}
			$data['store_id'] = (int) $data['shop_id'];
			$data['city_id'] = $shop['city_id'];
			
			
			$data['pt_price'] = $data['pt_price'];
			if($data['pt_price'] <= 0){
				$this->tuError('售价非法');
			}
			$data['logo'] = $data['logo'];
			if(empty($data['logo'])){
				$this->tuError('请上传logo');
			}
			$data['name'] = $data['name'];
			if(empty($data['name'])){
				$this->tuError('请填写标题');
			}
			$data['start_time'] = strtotime($data['start_time']);
			$data['end_time'] = strtotime($data['end_time']);
			$data['xf_time'] = strtotime($data['xf_time']);
		
			
			$photos = $this->_post('photos', false);
			if(is_array($photos)){
				foreach($photos as $k => $val){
					if(empty($val)){
						unset($photos[$k]);
					}
					if(!isImage($val)){
						unset($photos[$k]);
					}
				}
			}
			
			$data['details_img'] = serialize($photos);
			$data['time'] = time();//操作时间
			
			if($data['id']){
				$res =M('group_goods')->save($data);
				$intro = '修改成功';
			}else{
				$res = M('group_goods')->add($data);
				$intro = '添加成功';
			}
			if($res){
				$this->tuSuccess($intro,U('group/goods',array('cid'=>$data['id'])));
			}else{
				$this->tuError('操作失败');
			}
		}else{
			$this->assign('id',$id);
			$detail =M('group_goods')->find($id);
            $this->assign('thumb',$thumb = unserialize($detail['details_img']));
			$this->assign('types',$types =M('group_type')->select());
			$this->assign('shop', M('shop')->where(array('shop_id'=>$detail['shop_id']))->find());
		
			$detail['start_time'] = date("Y-m-d H:m:s",$detail['start_time']);
			$detail['end_time'] = date("Y-m-d H:m:s",$detail['end_time']);
			$detail['xf_time'] = date("Y-m-d H:m:s",$detail['xf_time']);
			
			$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
			$this->assign('detail',$detail);
			$this->display();
		}
    }

	
	
	//团购
    public function index(){
        import('ORG.Util.Page');
		$map = array();
        if($keyword = $this->_param("keyword","htmlspecialchars")){
            $map['goods_name'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		if($type = $this->_param('type')){
            if($otype != 'all'){
                $map['type'] = $type;
            }
            $this->assign('type',$type);
        }else{
            $this->assign('type','all');
        }
		
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		
        $count = M('group')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('group')->where($map)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	//订单
    public function order(){
        import('ORG.Util.Page');
		$map = array();
        if($keyword = $this->_param("keyword","htmlspecialchars")){
            $map['goods_name'] = array("LIKE","%".$keyword."%");
            $this->assign( "keyword", $keyword);
        }
		
		if($type = $this->_param('type')){
            if($otype != 'all'){
                $map['type'] = $type;
            }
            $this->assign('type',$type);
        }else{
            $this->assign('type','all');
        }
		
		if($state = $this->_param('state')){
            if($otype != '999'){
                $map['state'] = $state;
            }
            $this->assign('state',$state);
        }else{
            $this->assign('state','999');
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        $count = M('group_order')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('group_order')->where($map)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['user'] = M('users')->where(array('user_id'=>$val['user_id']))->find();
			$list[$k]['goods'] = M('group_goods')->where(array('id'=>$val['goods_id']))->find();
			$list[$k]['shop'] = M('shop')->where(array('id'=>$val['shop_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	//订单
    public function orderInfo($id){
        $this->assign('id',$id);
		$detail =M('group_order')->find($id);
		$detail['user'] = M('users')->where(array('user_id'=>$val['user_id']))->find();
		$detail['goods'] = M('group_goods')->where(array('id'=>$val['goods_id']))->find();
		$detail['shop'] = M('shop')->where(array('id'=>$val['shop_id']))->find();
		$this->assign('detail',$detail);
        $this->display();
    }
	
	
}


