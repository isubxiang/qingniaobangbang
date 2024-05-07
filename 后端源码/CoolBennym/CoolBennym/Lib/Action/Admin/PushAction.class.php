<?php
class PushAction extends CommonAction {
	public function _initialize() {
        parent::_initialize();
		$this->assign('types',D('Push')->getType());
		$this->assign('categorys',D('Push')->getCategory());
        $this->assign('ranks',D('Userrank')->fetchAll());
    }
	
    private $create_fields = array('type','category','rank_id','grade_id','title','intro','url','create_time');
    private $edit_fields = array('type','category','rank_id','grade_id','title','intro','url','create_time');
	
	//推送首页
    public function index() {
        $obj = D('Push');
        import('ORG.Util.Page'); 
        $map = array();
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['title|intro|url'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
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
		
		if(isset($_GET['category']) || isset($_POST['category'])){
            $category = (int) $this->_param('category');
            if($category != 999){
                $map['category'] = $category;
            }
            $this->assign('category', $category);
        }else{
            $this->assign('category', 999);
        }
		
        $count = $obj->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = $obj->where($map)->order(array('push_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display(); 
    }

    public function create(){
        if ($this->isPost()){
            $data = $this->createCheck();
            $obj = D('Push');
            if($obj->add($data)){
                $this->tuSuccess('添加成功', U('Push/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }

    private function createCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
		$data['category'] = (int) $data['category'];
		$data['type'] = (int) $data['type'];
        $data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])) {
            $this->tuError('标题不能为空');
        }
		$data['intro'] = htmlspecialchars($data['intro']);
		$data['url'] = htmlspecialchars($data['url']);
		$data['rank_id'] = (int) $data['rank_id'];
		$data['grade_id'] = (int) $data['grade_id'];
        $data['create_time'] = NOW_TIME;
        return $data;
    }

    public function delete($push_id = 0){
        if(is_numeric($push_id) && ($push_id = (int) $push_id)){
            $obj = D('Push');
            $obj->delete($push_id);
            $this->tuSuccess('删除成功', U('Push/index'));
        }else{
            $push_id = $this->_post('push_id',false);
            if (is_array($push_id)){
                $obj = D('Push');
                foreach ($push_id as $id){
                    $obj->delete($id);
                }
                $this->tuSuccess('删除成功', U('Push/index'));
            }
            $this->tuError('请选择要删除的手机消息');
        }
    }
	
	//推送短信
	public function sms($push_id){
		$push_id= (int) $this->_param('push_id');
		$obj = D('Push');
		if($push_id){
			if(!$detail = $obj->find($push_id)) {
            	$this->error('推送的信息不存在');
        	}
			
			$list = $obj->getList($push_id);
			foreach($list as $k => $val){
				D('Sms')->sms_admin_push($detail,$val['mobile']);//批量推送短信
			}
			$obj->where(array('push_id'=>$push_id))->save(array('is_push'=>1,'push_time'=>NOW_TIME,));//更新数据库
			$this->tuSuccess('短信推送成功',U('push/index'));
		}else{
			$this->tuError('请选择您要推送的ID');
		}
    }
	
	//推送微信
	public function weixin($push_id){
		$push_id= (int) $this->_param('push_id');
		$obj = D('Push');
		if($push_id){
			if(!$detail = $obj->find($push_id)){
            	$this->error('推送的信息不存在');
        	}
			$list = $obj->getList($push_id);
			foreach($list as $k => $val){
				D('Weixintmpl')->weixin_admin_push($detail,$val['user_id']);//批量推送短信
			}
			$obj->where(array('push_id'=>$push_id))->save(array('is_push'=>1,'push_time'=>NOW_TIME,));//更新数据库
			$this->tuSuccess('微信推送成功',U('push/index'));
		}else{
			$this->tuError('请选择您要推送的ID');
		}
    }
	

}
