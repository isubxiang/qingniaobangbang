<?php
class EleAction extends CommonAction{
	
	
    private $create_fields = array('shop_id','school_id', 'pic1','pic2',  'cate', 'distribution', 'is_open', 'busihour','is_pay', 'is_daofu','is_coupon', 'is_new', 'full_money', 'new_money','is_full','order_price_full_1','order_price_reduce_1','order_price_full_2','order_price_reduce_2', 'logistics','logistics_full', 'since_money', 'sold_num', 'month_num', 'intro', 'audit', 'orderby', 'rate');
    private $edit_fields = array('shop_name','school_id','pic1','pic2',  'city_id', 'area_id', 'business_id','is_open','busihour', 'cate', 'distribution', 'is_pay', 'is_daofu','is_coupon',  'is_new', 'full_money', 'new_money','is_full','order_price_full_1','order_price_reduce_1','order_price_full_2','order_price_reduce_2', 'logistics','logistics_full','since_money', 'sold_num', 'month_num', 'intro', 'orderby','lng','lat','audit', 'rate');

    public function _initialize(){
        parent::_initialize();
        $getEleCate = D('Ele')->getEleCate();
        $this->assign('getEleCate', $getEleCate);
    }

    public function index(){
        $Ele = D('Ele');
        import('ORG.Util.Page'); 
        $map = array();
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['shop_name'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }

        $getSearchCityId = $this->getSearchCityId($this->city_id);
		if($getSearchCityId){
			$map['city_id'] = $getSearchCityId;
			$this->assign('city_id',$getSearchCityId);
		}
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		
        if($area_id = (int) $this->_param('area_id')){
            $map['area_id'] = $area_id;
            $this->assign('area_id', $area_id);
        }
        if($cate_id = (int) $this->_param('cate_id')){
            $map['cate_id'] = array('IN', D('Shopcate')->getChildren($cate_id));
            $this->assign('cate_id', $cate_id);
        }
		if(isset($_GET['is_open']) || isset($_POST['is_open'])){
            $is_open = (int) $this->_param('is_open');
            if($is_open != 999){
                $map['is_open'] = $is_open;
            }
            $this->assign('is_open', $is_open);
        }else{
            $this->assign('is_open', 999);
        }
		if(isset($_GET['is_new']) || isset($_POST['is_new'])){
            $is_new = (int) $this->_param('is_new');
            if ($is_new != 999) {
                $map['is_new'] = $is_new;
            }
            $this->assign('is_new', $is_new);
        }else{
            $this->assign('is_new', 999);
        }
        $count = $Ele->where($map)->count();  
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = $Ele->where($map)->order(array('shop_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['shop'] = D('shop')->find($val['shop_id']);
			$list[$k]['city'] = D('City')->find($val['city_id']);
			$list[$k]['area'] = D('Area')->find($val['area_id']);
			$list[$k]['business'] = D('Business')->find($val['business_id']);
        }
        $this->assign('list', $list); 
        $this->assign('page', $show); 
        $this->display(); 
    }


    public function create(){
        if($this->isPost()){
            $data = $this->createCheck();
            $obj = D('Ele');
            $cate = $this->_post('cate', false);
            $cate = implode(',', $cate);
            $data['cate'] = $cate;
            if ($obj->add($data)) {
                $this->tuSuccess('添加成功', U('ele/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }



    private function createCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['shop_id'] = (int) $data['shop_id'];
        if(empty($data['shop_id'])){
            $this->tuError('ID不能为空');
        }
        if(!$shop = D('Shop')->find($data['shop_id'])){
            $this->tuError('商家不存在');
        }
        $data['shop_name'] = $shop['shop_name'];
        $data['lng'] = $shop['lng'];
        $data['lat'] = $shop['lat'];
        $data['city_id'] = $shop['city_id'];
        $data['area_id'] = $shop['area_id'];
        $data['business_id'] = $shop['business_id'];
		
		$data['pic1'] = htmlspecialchars($data['pic1']);
		$data['pic2'] = htmlspecialchars($data['pic2']);
		
		
        $data['is_open'] = (int) $data['is_open'];
        $data['is_pay'] = (int) $data['is_pay'];
        $data['is_daofu'] = (int) $data['is_daofu'];
		$data['is_coupon'] = (int) $data['is_coupon'];
        $data['is_new'] = (int) $data['is_new'];
        $data['full_money'] = (int) ($data['full_money'] * 100);
        $data['new_money'] = (int) ($data['new_money'] * 100);
	
		$data['is_full'] = (int) $data['is_full'];
		$data['order_price_full_1'] = (int) ($data['order_price_full_1'] * 100);
		$data['order_price_reduce_1'] = (int) ($data['order_price_reduce_1'] * 100);
		$data['order_price_full_2'] = (int) ($data['order_price_full_2'] * 100);
		$data['order_price_reduce_2'] = (int) ($data['order_price_reduce_2'] * 100);
	
		
        $data['logistics'] = (int) ($data['logistics'] * 100);
		if(empty($data['logistics'])) {
            $this->tuError('配送费必须填写');
        }
		
		
		
		$data['logistics_full'] = (int) ($data['logistics_full'] * 100);
        $data['since_money'] = (int) ($data['since_money'] * 100);
        $data['sold_num'] = (int) $data['sold_num'];
        $data['month_num'] = (int) $data['month_num'];
        $data['rate'] = (int) $data['rate'];
		if(empty($data['rate'])) {
            $this->tuError('销售提成不能为空');
        }
		if($data['rate'] < 0) {
            $this->tuError('销售提成设置错误');
        }
        $data['audit'] = (int) $data['audit'];
        $data['distribution'] = (int) $data['distribution'];
        $data['intro'] = htmlspecialchars($data['intro']);
        if (empty($data['intro'])) {
            $this->tuError('说明不能为空');
        }
        $data['orderby'] = (int) $data['orderby'];
        return $data;
    }



    public function edit($shop_id = 0){
        if($shop_id = (int) $shop_id){
            $obj = D('Ele');
            if(!$detail = $obj->find($shop_id)){
                $this->tuError('请选择要编辑的餐饮商家');
            }
            if($this->isPost()){
                $data = $this->editCheck();
                $data['shop_id'] = $shop_id;
				
                $cate = $this->_post('cate', false);
                $cate = implode(',', $cate);
                $data['cate'] = $cate;
				
				
                if(false !== $obj->save($data)){
                    $this->tuSuccess('操作成功', U('ele/index'));
                }
                $this->tuError('操作失败');
            }else{
                $cate = explode(',', $detail['cate']);
                $this->assign('cate', $cate);
                $this->assign('detail', $detail);
				$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的餐饮商家');
        }
    }




    private function editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
		$data['shop_name'] = htmlspecialchars($data['shop_name']);
        if(empty($data['shop_name'])){
            $this->tuError('外卖名称不能为空');
        }
		
		$data['city_id'] = (int) $data['city_id'];
        $data['area_id'] = (int) $data['area_id'];
        $data['business_id'] = (int) $data['business_id'];
		
		$data['pic1'] = htmlspecialchars($data['pic1']);
		$data['pic2'] = htmlspecialchars($data['pic2']);
		
        $data['is_open'] = (int) $data['is_open'];
        $data['is_pay'] = (int) $data['is_pay'];
        $data['is_daofu'] = (int) $data['is_daofu'];
		$data['is_coupon'] = (int) $data['is_coupon'];
        $data['is_new'] = (int) $data['is_new'];
        $data['full_money'] = (int) ($data['full_money'] * 100);
        $data['new_money'] = (int) ($data['new_money'] * 100);
		$data['is_full'] = (int) $data['is_full'];
		$data['order_price_full_1'] = (int) ($data['order_price_full_1'] * 100);
		$data['order_price_reduce_1'] = (int) ($data['order_price_reduce_1'] * 100);
		$data['order_price_full_2'] = (int) ($data['order_price_full_2'] * 100);
		$data['order_price_reduce_2'] = (int) ($data['order_price_reduce_2'] * 100);
		
		
        $data['logistics'] = (int) ($data['logistics'] * 100);
		if(empty($data['logistics'])) {
            $this->tuError('配送费必须填写');
        }
		
		
		$data['logistics_full'] = (int) ($data['logistics_full'] * 100);
        $data['since_money'] = (int) ($data['since_money'] * 100);
        $data['sold_num'] = (int) $data['sold_num'];
        $data['month_num'] = (int) $data['month_num'];
        $data['distribution'] = (int) $data['distribution'];
        $data['audit'] = (int) $data['audit'];
        $data['intro'] = htmlspecialchars($data['intro']);
        $data['rate'] = (int) $data['rate'];
		if(empty($data['rate'])) {
            $this->tuError('销售提成不能为空');
        }
		if($data['rate'] < 0) {
            $this->tuError('销售提成设置错误');
        }
        if (empty($data['intro'])) {
            $this->tuError('说明不能为空');
        }
        $data['orderby'] = (int) $data['orderby'];
		$data['lng'] = htmlspecialchars($data['lng']);
        $data['lat'] = htmlspecialchars($data['lat']);
        return $data;
    }



    public function delete($shop_id = 0){
        if(is_numeric($shop_id) && ($shop_id = (int) $shop_id)){
            $obj = D('Ele');
            $obj->delete($shop_id);
            $this->tuSuccess('删除成功', U('ele/index'));
        }else{
            $shop_id = $this->_post('shop_id', false);
            if(is_array($shop_id)){
                $obj = D('Ele');
                foreach($shop_id as $id){
                    $obj->delete($id);
                }
                $this->tuSuccess('批量删除成功', U('ele/index'));
            }
            $this->tuError('请选择要删除的餐饮商家');
        }
    }



	//商家审核
	public function audit($shop_id = 0){
        if(is_numeric($shop_id) && ($shop_id = (int) $shop_id)){
            $obj = D('Ele');
            $obj->save(array('shop_id' => $shop_id, 'audit' => 1));
            $this->tuSuccess('审核成功', U('ele/index'));
        }else{
            $shop_id = $this->_post('shop_id', false);
            if(is_array($shop_id)){
                $obj = D('Ele');
                foreach($shop_id as $id){
                    $obj->save(array('shop_id' => $id, 'audit' => 1));
                }
                $this->tuSuccess('批量审核成功', U('ele/index'));
            }
            $this->tuError('请选择要审核的信息');
        }
    }
	
	
    public function opened($shop_id = 0, $type = 'open'){
        if(is_numeric($shop_id) && ($shop_id = (int) $shop_id)){
            $obj = D('Ele');
            $is_open = 0;
            if($type == 'open'){
                $is_open = 1;
            }
            $obj->where(array('shop_id' =>$shop_id))->save(array('is_open' => $is_open));
            $this->tuSuccess('操作成功', U('ele/index'));
        }
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
        $this->success($info,U('ele/index',array('p'=>$p)));
    }
	
	
	

    public function select(){
        import('ORG.Util.Page'); 
        $map = array('audit'=>1);
        if($keyword = $this->_param('keyword','htmlspecialchars')){
            $map['shop_name|intro'] = array('LIKE','%'.$keyword.'%');
            $this->assign('keyword',$keyword);
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
		
		
        $count = M('Ele')->where($map)->count(); 
        $Page = new Page($count, 10); 
        $pager = $Page->show(); 
        $list = M('Ele')->where($map)->order(array('shop_id'=>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['city'] = D('City')->where(array('city_id'=>$val['city_id']))->find();
			$list[$k]['area'] = D('Area')->where(array('area_id'=>$val['area_id']))->find();
			$list[$k]['business'] = D('Business')->where(array('business_id'=>$val['business_id']))->find();
        }
        $this->assign('list', $list); 
        $this->assign('page', $pager); 
        $this->display(); 
    }
	
	
	
    
}
