<?php
class UseraddrAction extends CommonAction{
    private $create_fields = array('user_id', 'area_id', 'business_id', 'name', 'mobile', 'addr','lng', 'lat');
    private $edit_fields = array('user_id', 'area_id', 'business_id', 'name', 'mobile', 'addr','lng', 'lat');
	
    public function index(){
        import('ORG.Util.Page');
        $map = array('closed'=>0,'city_id'=>array('gt',0));
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['name|mobile|addr'] = array('LIKE', '%' . $keyword . '%');
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
        if($user_id = (int) $this->_param('user_id')){
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
        if($business_id = (int) $this->_param('business_id')){
            $map['business_id'] = $business_id;
            $this->assign('business_id', $business_id);
        }
        $count = M('UserAddr')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('UserAddr')->where($map)->order(array('addr_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $ids = array();
        foreach($list as $k => $val){
			$list[$k]['city'] = D('City')->where(array('city_id'=>$val['city_id']))->find();
			$list[$k]['area'] = D('Area')->where(array('area_id'=>$val['area_id']))->find();
			$list[$k]['business'] = D('Business')->where(array('business_id'=>$val['business_id']))->find();
            if($val['user_id']){
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
            $data = $this->createCheck();
            $obj = D('Useraddr');
            if($obj->add($data)){
                $this->tuSuccess('添加成功', U('useraddr/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }
	
	
    private function createCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['user_id'] = (int) $data['user_id'];
        if(empty($data['user_id'])){
            $this->tuError('用户不能为空');
        }
        $data['area_id'] = (int) $data['area_id'];
        if(empty($data['area_id'])){
            $this->tuError('地区不能为空');
        }
        $data['business_id'] = (int) $data['business_id'];
        if(empty($data['business_id'])){
            $this->tuError('商圈不能为空');
        }
        $data['name'] = htmlspecialchars($data['name']);
        if(empty($data['name'])){
            $this->tuError('收货人不能为空');
        }
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if(empty($data['mobile'])){
            $this->tuError('手机号码不能为空');
        }
        if(!isMobile($data['mobile'])) {
            $this->tuError('手机号码格式不正确');
        }
        $data['addr'] = htmlspecialchars($data['addr']);
        if(empty($data['addr'])){
            $this->tuError('具体地址不能为空');
        }
		$data['lng'] = htmlspecialchars($data['lng']);
        $data['lat'] = htmlspecialchars($data['lat']);
        return $data;
    }
	
	
    public function edit($addr_id = 0){
        if($addr_id = (int) $addr_id){
            $obj = D('Useraddr');
            if(!($detail = $obj->find($addr_id))){
                $this->tuError('请选择要编辑的商家地址');
            }
            if($this->isPost()){
                $data = $this->editCheck();
                $data['addr_id'] = $addr_id;
                if(false !== $obj->save($data)){
                    $this->tuSuccess('操作成功', U('useraddr/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->assign('user', D('Users')->find($detail['user_id']));
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的商家地址');
        }
    }
	
	
    private function editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['user_id'] = (int) $data['user_id'];
        if(empty($data['user_id'])){
            $this->tuError('用户不能为空');
        }
        $data['area_id'] = (int) $data['area_id'];
        if(empty($data['area_id'])){
            $this->tuError('地区不能为空');
        }
        $data['business_id'] = (int) $data['business_id'];
        if(empty($data['business_id'])){
            $this->tuError('商圈不能为空');
        }
        $data['name'] = htmlspecialchars($data['name']);
        if(empty($data['name'])){
            $this->tuError('收货人不能为空');
        }
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if(empty($data['mobile'])){
            $this->tuError('手机号码不能为空');
        }
        if(!isMobile($data['mobile'])){
            $this->tuError('手机号码格式不正确');
        }
        $data['addr'] = htmlspecialchars($data['addr']);
        if(empty($data['addr'])){
            $this->tuError('具体地址不能为空');
        }
		$data['lng'] = htmlspecialchars($data['lng']);
        $data['lat'] = htmlspecialchars($data['lat']);
        return $data;
    }
	
	
    public function delete($addr_id = 0){
        if(is_numeric($addr_id) && ($addr_id = (int) $addr_id)){
            $obj = D('Useraddr');
			$obj->save(array('addr_id' => $addr_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('useraddr/index'));
        }else{
            $addr_id = $this->_post('addr_id', false);
            if(is_array($addr_id)){
                $obj = D('Useraddr');
                foreach($addr_id as $id){
                    $obj->save(array('addr_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('删除成功', U('useraddr/index'));
            }
            $this->tuError('请选择要删除的收货地址');
        }
    }
	
	
}