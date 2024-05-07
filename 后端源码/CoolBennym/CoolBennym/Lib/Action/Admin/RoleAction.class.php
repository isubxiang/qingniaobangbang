<?php
class RoleAction extends CommonAction{
	
    public function _initialize(){
        parent::_initialize();
		$this->assign('schools', $schools= M('running_school')->where(array('closed'=>0))->select());
    }
	
    public function index(){
        $Role = D('Role');
        import('ORG.Util.Page');
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        $map = array();
        if($keyword){
            $map['role_name'] = array('LIKE', '%' . $keyword . '%');
        }
		$this->assign('keyword', $keyword);
		
		if($type = (int) $this->_param('type')){
			$map['type'] = $type;
            $this->assign('type', $type);
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
        $count = $Role->where($map)->count();
        $Page = new Page($count, 15);
        $show = $Page->show();
        $list = $Role->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
    public function auth($role_id = 0){
        if(($role_id = (int) $role_id) && ($detail = D('role')->find($role_id))){
            if($this->isPost()){
                $menu_ids = $this->_post('menu_id');
                $obj = D('RoleMaps');
                $obj->delete(array('where' => " role_id = '{$role_id}' "));
                foreach($menu_ids as $val){
                    if(!empty($val)){
                        $data = array('role_id' => $role_id, 'menu_id' => (int) $val);
                        $obj->add($data);
                    }
                }
                $this->tuSuccess('授权成功', U('role/auth', array('role_id' => $role_id)));
            }else{
                $this->assign('menus', D('Menu')->fetchAll());
                $this->assign('menuIds', D('RoleMaps')->getMenuIdsByRoleId($role_id));
                $this->assign('role_id', $role_id);
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->error('请选择正确的角色');
        }
    }
	
	
    public function create(){
        if($this->isPost()){
             $data = $this->checkFields($this->_post('data', false), array('type','role_name','school_id','city_id','area_id','business_id'));
			if(empty($data['role_name'])){
				$this->tuError('请输入角色名称');
			}
			$data['role_name'] = htmlspecialchars($data['role_name'], ENT_QUOTES, 'UTF-8');
			$data['type'] = (int) $data['type'];
			if(empty($data['type'])){
				$this->tuError('类型不能为空');
			}
			$data['school_id'] = (int) $data['school_id'];
            $obj = D('Role');
            if($obj->add($data)){
                $obj->cleanCache();
                $this->tuSuccess('添加成功', U('role/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }
	
	
	
    public function edit($role_id = 0){
        if($role_id = (int) $role_id){
            $obj = D('Role');
            $role = $obj->fetchAll();
            if(!isset($role[$role_id])){
                $this->tuError('请选择要编辑的角色');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false),array('type','role_name','school_id','city_id','area_id','business_id'));
				if(empty($data['role_name'])){
					$this->tuError('请输入角色名称');
				}
				$data['role_name'] = htmlspecialchars($data['role_name'], ENT_QUOTES, 'UTF-8');
				$data['type'] = (int) $data['type'];
				if(empty($data['type'])){
					$this->tuError('类型不能为空');
				}
				$data['school_id'] = (int) $data['school_id'];
                $data['role_id'] = $role_id;
                if($obj->save($data)){
                    $obj->cleanCache();
                    $this->tuSuccess('操作成功', U('role/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $role[$role_id]);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的角色');
        }
    }
	
	
	
	
	
    public function delete($role_id = 0){
        if($role_id = (int) $role_id){
            $obj = D('Role');
            $obj->delete($role_id);
            $obj->cleanCache();
            $this->tuSuccess('删除成功', U('role/index'));
        }
        $this->tuError('请选择要删除的组');
    }
   
}