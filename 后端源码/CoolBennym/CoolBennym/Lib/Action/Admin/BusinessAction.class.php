<?php
class BusinessAction extends CommonAction{
	
    private $create_fields = array('business_name', 'user_id','ratio','orderby');
    private $edit_fields = array('business_name','user_id', 'ratio','orderby');
    private $area_id = '';
	
	
    public function _initialize(){
        parent::_initialize();
        $this->area_id = (int) $_REQUEST['area_id'];
        if(!$this->area_id){
            $this->error('请选择对应的区域');
        }
        $this->assign('area_id', $this->area_id);
    }
	
	
    public function index(){
        $Business = D('Business');
        import('ORG.Util.Page');
        $map = array('area_id' => $this->area_id);
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if($keyword){
            $map['business_name'] = array('LIKE', '%' . $keyword . '%');
        }
        $count = $Business->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Business->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k => $val) {
			$user_ids[$val['user_id']] = $val['user_id'];
        }
        $this->assign('keyword', $keyword);
		$this->assign('users', D('Users')->itemsByIds($user_ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
    public function create(){
        if($this->isPost()){
            $data = $this->createCheck();
            $obj = D('Business');
            if($obj->add($data)) {
                $obj->cleanCache();
                $this->tuSuccess('添加成功', U('business/index', array('area_id' => $this->area_id)));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }
	
	
    private function createCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['business_name'] = htmlspecialchars($data['business_name']);
        if(empty($data['business_name'])){
            $this->tuError('商圈名称不能为空');
        }
		$data['user_id'] = (int) $data['user_id'];
		$data['ratio'] = (int) ($data['ratio']*100);
        $data['area_id'] = $this->area_id;
        if(empty($data['area_id'])){
            $this->tuError('所在区域不能为空');
        }
        $data['orderby'] = (int) $data['orderby'];
        return $data;
    }
	
	
    public function edit($business_id = 0){
        if($business_id = (int) $business_id){
            $obj = D('Business');
            if(!($detail = $obj->find($business_id))){
                $this->tuError('请选择要编辑的商圈管理');
            }
            if($this->isPost()){
                $data = $this->editCheck();
                $data['business_id'] = $business_id;
                if (false !== $obj->save($data)){
                    $obj->cleanCache();
                    $this->tuSuccess('操作成功', U('business/index', array('area_id' => $this->area_id)));
                }
                $this->tuError('操作失败');
            }else{
				$this->assign('user', D('Users')->where(array('user_id'=>$detail['user_id']))->find());
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的商圈管理');
        }
    }
	
	
	
	
    public function hots($business_id){
        if($business_id = (int) $business_id){
            $obj = D('Business');
            if(!($detail = $obj->find($business_id))){
                $this->tuError('请选择商圈');
            }
            $detail['is_hot'] = $detail['is_hot'] == 0 ? 1 : 0;
            $obj->save(array('business_id' => $business_id, 'is_hot' => $detail['is_hot']));
            $obj->cleanCache();
            $this->tuSuccess('操作成功', U('business/index', array('area_id' => $this->area_id)));
        }else{
            $this->tuError('请选择商圈');
        }
    }
	
	
    private function editCheck() {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['business_name'] = htmlspecialchars($data['business_name']);
        if (empty($data['business_name'])) {
            $this->tuError('商圈名称不能为空');
        }
		$data['user_id'] = (int) $data['user_id'];
		$data['ratio'] = (int) ($data['ratio']*100);
        $data['area_id'] = $this->area_id;
        if (empty($data['area_id'])) {
            $this->tuError('所在区域不能为空');
        }
        $data['orderby'] = (int) $data['orderby'];
        return $data;
    }
	
	
    public function delete(){
        if(is_numeric($_GET['business_id']) && ($business_id = (int) $_GET['business_id'])){
            $obj = D('Business');
            $obj->delete($business_id);
            $obj->cleanCache();
            $this->tuSuccess('删除成功', U('business/index', array('area_id' => $this->area_id)));
        }else{
            $business_id = $this->_post('business_id', false);
            if (is_array($business_id)) {
                $obj = D('Business');
                foreach ($business_id as $id) {
                    $obj->delete($id);
                }
                $obj->cleanCache();
                $this->tuSuccess('删除成功', U('business/index', array('area_id' => $this->area_id)));
            }
            $this->tuError('请选择要删除的商圈管理');
        }
    }
	
	
    public function child($area_id = 0){
        $datas = D('Business')->fetchAll();
        $str = '<option value="0">请选择</option>';
        foreach($datas as $val){
            if($val['area_id'] == $area_id){
                $str .= '<option value="' . $val['business_id'] . '">' . $val['business_name'] . '</option>';
            }
        }
        echo $str;
        die;
    }
}