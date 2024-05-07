<?php
class UsermoneylogsAction extends CommonAction{
	
	
	public function _initialize(){
        parent::_initialize();
		$this->assign('getMoneyTypes',$getMoneyTypes = D('Users')->getMoneyTypes());
    }
	
	
	
    public function index(){
        $Usermoneylogs = D('Usermoneylogs');
        import('ORG.Util.Page');
        $map = array();
        $getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
        if($user_id = (int) $this->_param('user_id')) {
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['intro'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
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
		
		if($types = (int) $this->_param('types')){
            if($types == 1){
				$map['money'] = array('gt',0);
			}elseif($types == 2){
				$map['money'] = array('lt',0);
			}
            $this->assign('types', $types);
        }
		
		$order = $this->_param('order', 'htmlspecialchars');
        $orderby = '';
        switch ($order){
            case '2':
                $orderby = array('money' => 'asc');
                break;
            case '1':
                $orderby = array('money' => 'desc');
                break;
            default:
                $orderby = array('money' => 'desc');
                break;
        }
        $this->assign('order', $order);
		
		
        $count = $Usermoneylogs->where($map)->count();
        $Page = new Page($count,25);
        $show = $Page->show();
        $list = $Usermoneylogs->where($map)->order(array('log_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = array();
        foreach($list as $k => $val) {
            $user_ids[$val['user_id']] = $val['user_id'];
			$list[$k]['school'] = M('RunningSchool')->where(array('school_id'=>$val['school_id']))->find();
        }
		
		
		
        $this->assign('users', D('Users')->itemsByIds($user_ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
}