<?php
class AdminAction extends CommonAction{
    private $create_fields = array('type','user_id','username','password','role_id','mobile','school_id','city_id','area_id','business_id');
    private $edit_fields = array('type','user_id','username','password','role_id','mobile','school_id','city_id','area_id','business_id');
	
	public function _initialize(){
        parent::_initialize();
		$this->assign('schools', $schools= M('running_school')->where(array('closed'=>0))->select());
    }
	
    public function index(){
		if($this->_admin['admin_id'] != 1){
			$this->error('想啥呢？咱们没那个权限ok?');die;
		}
        $obj = D('Admin');
        import('ORG.Util.Page');
        $keyword = trim($this->_param('keyword', 'htmlspecialchars'));
        $map = array('closed' => 0);
        if($keyword){
            $map['username'] = array('LIKE', '%' . $keyword . '%');
        }
		if($type = (int) $this->_param('type')){
			$map['type'] = $type;
            $this->assign('type', $type);
        }
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
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
	    if(isset($_GET['is_username_lock']) || isset($_POST['is_username_lock'])){
            $is_username_lock = (int) $this->_param('is_username_lock');
            if($is_username_lock != 999){
                $map['is_username_lock'] = $is_username_lock;
            }
            $this->assign('is_username_lock', $is_username_lock);
        }else{
            $this->assign('is_username_lock', 999);
        }
        $count = $obj->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $obj->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
            $val['create_ip_area'] = $this->ipToArea($val['create_ip']);
            $val['last_ip_area'] = $this->ipToArea($val['last_ip']);
			$val['city'] = D('City')->where(array('city_id'=>$val['city_id']))->find();
			$val['area'] = D('Area')->where(array('area_id'=>$val['area_id']))->find();
			$val['business'] = D('Business')->where(array('business_id'=>$val['business_id']))->find();
			$val['user'] = D('Users')->where(array('user_id'=>$val['user_id']))->find();
            $list[$k] = D('Admin')->_format($val);
			$list[$k]['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
        }
        $this->assign('citys', D('City')->fetchAll());
        $Page->parameter .= 'keyword=' . urlencode($keyword);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	//权限管理
	public function getRoles($admin_id = 0,$type = 0,$school_id = 0){
		
		if($type == 1){
			$datas = M('Role')->where(array('type'=>$type))->select();
		}else{
			$datas = M('Role')->where(array('type'=>$type,'school_id'=>$school_id))->select();
		}
		
		$Admin = M('Admin')->where(array('admin_id'=>$admin_id))->find();
		
		
        $str = '';
        foreach($datas as $var){
			if($Admin && $Admin['type'] == $var['type']){
				$str .= '<option value="' . $var['role_id'] . '" selected="selected"> ' . $var['role_name'] .'</option>' . '';
			}else{
				$str .= '<option value="' . $var['role_id'] . '" > ' . $var['role_name'] .'</option>' . '';
			}
        }
        echo $str;
        die;
    }
	
	
	public function log(){
		if($this->_admin['admin_id'] != 1){
			$this->error('想啥呢？咱们没那个权限ok?');die;
		}
        $obj = M('AdminLog');
        import('ORG.Util.Page');
        $keyword = trim($this->_param('keyword', 'htmlspecialchars'));
        $map = array('closed' => 0);
        if($keyword){
            $map['username'] = array('LIKE', '%' . $keyword . '%');
        }
		
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		
		
		if(isset($_GET['audit']) || isset($_POST['audit'])){
            $audit = (int) $this->_param('audit');
            if($audit != 999){
                $map['audit'] = $audit;
            }
            $this->assign('audit', $audit);
        }else{
            $this->assign('audit', 999);
        }
		
		if(isset($_GET['type']) || isset($_POST['type'])){
            $type = (int) $this->_param('type');
            if($type != 999){
                $map['type'] = $type;
            }
            $this->assign('type', $type);
        }else{
            $this->assign('type', 999);
        }
		
		
        $count = $obj->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $obj->where($map)->order('last_time desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
            $list[$k]['last_ip_area'] = $this->ipToArea($val['last_ip']);
			$list[$k]['login'] = serialize($val['login']);
        }
		$this->assign('count', (int)$count);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	

	
	
	
    public function create(){
		if($this->_admin['admin_id'] != 1) {
			$this->error('想啥呢？咱们没那个权限ok?');die;
		}
        if($this->isPost()){
            $data = $this->createCheck();
            $obj = D('Admin');
            if ($obj->add($data)){
                $this->tuSuccess('添加成功', U('admin/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->assign('roles', D('Role')->fetchAll());
            $this->display();
        }
    }

	
	
    private function createCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['username'] = htmlspecialchars($data['username']);
        if(empty($data['username'])){
            $this->tuError('用户名不能为空');
        }
        if(D('Admin')->getAdminByUsername($data['username'])){
            $this->tuError('用户名已经存在');
        }
        $data['password'] = md5($data['password']);
        if(empty($data['password'])){
            $this->tuError('密码不能为空');
        }
		
		$data['type'] = (int) $data['type'];
        if(empty($data['type'])){
            $this->tuError('类型不能为空');
        }
		$data['user_id'] = (int) $data['user_id'];
		$data['school_id'] = (int) $data['school_id'];
		
        $data['role_id'] = (int) $data['role_id'];
        if(empty($data['role_id'])){
            $this->tuError('角色不能为空');
        }
		if($data['role_id'] == 1){
            $this->tuError('您不能添加管理员角色，请选择其他角色');
        }
		
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if (empty($data['mobile'])) {
            $this->tuError('手机不能为空');
        }
        if (!isMobile($data['mobile'])) {
            $this->tuError('手机格式不正确');
        }
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] = get_client_ip();
        return $data;
    }
	
	
	
    public function edit($admin_id = 0){
        if($admin_id = (int) $admin_id){
            $obj = D('Admin');
            if(!($detail = $obj->find($admin_id))){
                $this->tuError('请选择要编辑的管理员');die;
            }
			
			if($this->_admin['admin_id'] != 1){
				$this->error('想啥呢？咱们没那个权限ok?');die;
			}
			
			
            if($this->isPost()){
                $data = $this->editCheck();
                $data['admin_id'] = $admin_id;
                if ($obj->save($data)){
                    $this->tuSuccess('操作成功', U('admin/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('roles', D('Role')->fetchAll());
				$this->assign('user', D('Users')->find($detail['user_id']));
                $this->assign('detail', $detail);
                $this->display();
            }
        } else{
            $this->tuError('请选择要编辑的管理员');
        }
    }
	
	
	
	
    private function editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        if($data['password'] === '******'){
            unset($data['password']);
        }else{
            $data['password'] = htmlspecialchars($data['password']);
            if(empty($data['password'])){
                $this->tuError('密码不能为空');
            }
            $data['password'] = md5($data['password']);
        }
		$data['type'] = (int) $data['type'];
        if(empty($data['type'])){
            $this->tuError('类型不能为空');
        }
		$data['user_id'] = (int) $data['user_id'];
		$data['school_id'] = (int) $data['school_id'];
        if($this->_admin['role_id'] != 1){
            unset($data['role_id']);
        }else{
            $data['role_id'] = (int) $data['role_id'];
            if (empty($data['role_id'])){
                $this->tuError('角色不能为空');
            }
        }
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if(empty($data['mobile'])){
            $this->tuError('手机不能为空');
        }
        if(!isMobile($data['mobile'])){
            $this->tuError('手机格式不正确');
        }
        return $data;
    }
	
	
    public function delete($admin_id = 0){
		
		if($this->_admin['admin_id'] != 1){
			$this->error('想啥呢？咱们没那个权限ok?');die;
		}
		
        if(is_numeric($admin_id) && ($admin_id = (int) $admin_id)){
            $obj = D('Admin');
            $obj->save(array('admin_id' => $admin_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('admin/index'));
        }else{
            $admin_id = $this->_post('admin_id', false);
            if(is_array($admin_id)){
                $obj = D('Admin');
                foreach($admin_id as $id){
                    $obj->save(array('admin_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('删除成功', U('admin/index'));
            }
            $this->tuError('请选择要删除的管理员');
        }
    }
	
	
    public function is_username_lock($admin_id){
        $obj = D('Admin');
        if(!($detail = $obj->find($admin_id))){
            $this->error('请选择要编辑的账户');
        }
		if($this->_admin['admin_id'] != 1){
			$this->error('想啥呢？咱们没那个权限ok?');die;
		}
		
        $data = array('is_username_lock' => 0, 'admin_id' => $admin_id);
        if($detail['is_username_lock'] == 0){
            $data['is_username_lock'] = 1;
        }
        $obj->save($data);
        $this->tuSuccess('操作成功', U('admin/index'));
    }
	
	
	public function ip(){
		
		if($this->_admin['admin_id'] != 1){
			$this->error('想啥呢？咱们没那个权限ok?');die;
		}
		
        $obj = M('adminIpAuth');
        import('ORG.Util.Page');
        $keyword = trim($this->_param('keyword', 'htmlspecialchars'));
        $map = array('closed' => 0);
        if($keyword){
            $map['start|end'] = array('LIKE', '%' . $keyword . '%');
        }
        $count = $obj->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $obj->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
            $list[$k]['start_ip_area'] = $this->ipToArea($val['start']);
            $list[$k]['end_ip_area'] = $this->ipToArea($val['end']);
        }
        $Page->parameter .= 'keyword=' . urlencode($keyword);
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
		
    public function createIp(){
		if($this->_admin['admin_id'] != 1){
			$this->tuError('您没有编辑权限');die;
		}
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false),array('start', 'end'));
			$data['start'] = htmlspecialchars($data['start']);
			if(empty($data['start'])) {
				$this->tuError('开始IP不能为空');
			}
			$data['end'] = htmlspecialchars($data['end']);
			if(empty($data['end'])){
				$this->tuError('结束IP不能为空');
			}
            $obj = M('adminIpAuth');
            if($obj->add($data)){
                $this->tuSuccess('添加成功', U('admin/ip'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }
	
	public function editIp($id = 0){
        if($id = (int) $id){
            $obj = M('adminIpAuth');
            if(!($detail = $obj->find($id))){
                $this->tuError('ID不存在');die;
            }
			if($this->_admin['admin_id'] != 1){
				$this->tuError('您没有编辑权限');die;
			}
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false),array('start','end'));
				$data['start'] = htmlspecialchars($data['start']);
				if(empty($data['start'])){
					$this->tuError('开始IP不能为空');
				}
				$data['end'] = htmlspecialchars($data['end']);
				if(empty($data['end'])){
					$this->tuError('结束IP不能为空');
				}
                $data['id'] = $id;
                if($obj->save($data)){
                    $this->tuSuccess('操作成功', U('admin/ip'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的ID');
        }
    }
	
	
	
}

