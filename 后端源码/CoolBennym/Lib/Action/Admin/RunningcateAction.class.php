<?php
class RunningcateAction extends CommonAction{
	
    private $create_fields = array(
		'channel_id','cate_name','photo','Detail','ErrandType','DefaultRemark','Tag','Remark','Url','Src','price','school_id','orderby','onMoneyTap','onExpressFeeLink','onExpressFeeLinkName','onExpressFeeLinkId','onFile','is_show','is_system','ErrandTimeRangeDays','ErrandTimeRangeBegin','ErrandTimeRangeEnd','rate'
	);
    private $edit_fields = array(
		'channel_id','cate_name','photo','Detail','ErrandType','DefaultRemark','Tag','Remark','Url','Src','price','school_id','orderby','onMoneyTap','onExpressFeeLink','onExpressFeeLinkName','onExpressFeeLinkId','onFile','is_show','is_system','ErrandTimeRangeDays','ErrandTimeRangeBegin','ErrandTimeRangeEnd','rate'
	);
	
	public function _initialize(){
        parent::_initialize();
        $this->lifechannel = D('RunningCate')->getChannelMeans();
        $this->assign('channel_ids', $this->lifechannel);
		$this->assign('schools',$schools = M('running_school')->order(array('school_id' => 'desc'))->select());
    }
	
	
	
    public function index(){
        import('ORG.Util.Page');
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        $map = array();
        if($keyword){
            $map['cate_name'] = array('LIKE', '%' . $keyword . '%');
        }
        $this->assign('keyword', $keyword);
		
		if($channel_id = (int) $this->_param('channel_id')){
			if($channel_id != 999){
				 $map['channel_id'] = $channel_id;
            	$this->assign('channel_id', $channel_id);
			}
           
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
        $count = M('RunningCate')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = (int) $_GET[$var];
        $this->assign('p', $p);
        $list = M('RunningCate')->where($map)->order(array('orderby' => 'asc','cate_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('channelmeans', D('RunningCate')->getChannelMeans());
        $this->display();
    }
	
    public function hots($cate_id){
        $var = C('VAR_PAGE') ? C('VAR_PAGE') : 'p';
        $p = (int) $_GET[$var];
        if($cate_id = (int) $cate_id){
            if(!($detail = M('RunningCate')->find($cate_id))){
                $this->tuError('请选择分类');
            }
            $detail['is_hot'] = $detail['is_hot'] == 0 ? 1 : 0;
            M('RunningCate')->save(array('cate_id' => $cate_id, 'is_hot' => $detail['is_hot']));
            D('RunningCate')->cleanCache();
            $this->tuSuccess('操作成功', U('runningcate/index', array('p' => $p)));
        }else{
            $this->tuError('请选择分类');
        }
    }
	
	
    public function create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false), $this->create_fields);
			$data['channel_id'] = (int) $data['channel_id'];
			if(empty($data['channel_id'])) {
				$this->tuError('所属频道不能为空');
			}
			$data['cate_name'] = htmlspecialchars($data['cate_name']);
			if (empty($data['cate_name'])) {
				$this->tuError('分类名称不能为空');
			}
			$data['price'] = (int) ($data['price'] * 100);
			$data['rate'] = (int) $data['rate'];
			$data['orderby'] = (int) $data['orderby'];
            if(M('RunningCate')->add($data)){
                D('RunningCate')->cleanCache();
                $this->tuSuccess('添加成功', U('runningcate/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->assign('channelmeans', D('RunningCate')->getChannelMeans());
            $this->display();
        }
    }
	
	
   
	
    public function edit($cate_id = 0){
        if($cate_id = (int) $cate_id){
            if(!($detail = M('RunningCate')->find($cate_id))){
                $this->tuError('请选择要编辑的分类管理');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
				$data['channel_id'] = (int) $data['channel_id'];
				if(empty($data['channel_id'])){
					$this->tuError('所属频道不能为空');
				}
				$data['cate_name'] = htmlspecialchars($data['cate_name']);
				if(empty($data['cate_name'])) {
					$this->tuError('分类名称不能为空');
				}
				$data['rate'] = (int) $data['rate'];
				$data['price'] = (int) ($data['price'] * 100);
                $data['cate_id'] = $cate_id;
                if(false !== M('RunningCate')->save($data)){
                    D('RunningCate')->cleanCache();
                    $this->tuSuccess('操作成功', U('runningcate/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
				$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
                $this->assign('channelmeans', D('RunningCate')->getChannelMeans());
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的分类管理');
        }
    }
	
	 public function update(){
        $orderby = $this->_post('orderby', false);
        $obj = D('RunningCate');
        foreach($orderby as $key => $val){
            $data = array(
                'cate_id' => (int) $key,
                'orderby' => (int) $val
            );
            $obj->save($data);
        }
        $obj->cleanCache();
        $this->tuSuccess('更新成功', U('runningcate/index'));
    }
	
	
	//新版开启外卖配送
    public function is_show($cate_id,$p = 0){
        $obj = D('RunningCate');
        if(!($detail = $obj->find($cate_id))){
            $this->error('请选择要编辑的分类');
        }
        if($detail['is_show'] == 1){
			
            $obj->save(array('cate_id' => $cate_id, 'is_show' =>0));
        }else{
            if($detail['is_show'] == 0){
                $obj->save(array('cate_id' => $cate_id, 'is_show' =>1));
            }
        }
        $this->tuSuccess('操作成功', U('runningcate/index',array('p'=>$p)));
    }
	
	
	
    public function delete($cate_id = 0){
        if(is_numeric($cate_id) && ($cate_id = (int) $cate_id)){
            M('RunningCate')->where(array('cate_id'=>$cate_id))->delete();
            D('RunningCate')->cleanCache();
            $this->tuSuccess('删除成功', U('runningcate/index'));
        }else{
            $cate_id = $this->_post('cate_id', false);
            if(is_array($cate_id)){
                foreach ($cate_id as $id){
                    M('RunningCate')->delete($id);
                }
                D('RunningCate')->cleanCache();
                $this->tuSuccess('批量删除成功', U('runningcate/index'));
            }
            $this->tuError('请选择要删除的分类管理');
        }
    }
	
	
    public function delattr($attr_id){
        if(empty($attr_id)) {
            $this->tuError('操作失败');
        }
        if(!($detail = M('RunningCateAttr')->find($attr_id))){
            $this->tuError('操作失败');
        }
        M('RunningCateAttr')->delete($attr_id);
        $this->tuSuccess('删除成功', U('runningcate/setting', array('cate_id' => $detail['cate_id'])));
    }
	
	
	
    public function ajax($cate_id, $life_id = 0){
        if(!($cate_id = (int) $cate_id)){
            $this->error('请选择正确的分类');
        }
        if(!($detail = M('RunningCate')->find($cate_id))){
            $this->error('请选择正确的分类');
        }
        $this->assign('cate', $detail);
        $this->assign('attrs', M('RunningCateAttr')->order(array('orderby' => 'asc'))->where(array('cate_id' => $cate_id))->select());
		$this->assign('tags', M('RunningCateTag')->order(array('orderby' => 'asc'))->where(array('cate_id' => $cate_id))->select());
        if($life_id){
            $this->assign('detail', M('Running')->find($life_id));
            $this->assign('maps', D('RunningCateAttr')->getAttrs($life_id));
			$this->assign('tag', D('RunningCateTag')->getTags($life_id));
        }
        $this->display();
    }
	
	
	
	public function setting($cate_id){
        if(!($cate_id = (int) $cate_id)){
            $this->error('请选择正确的分类');
        }
        if(!($detail = M('RunningCate')->find($cate_id))){
            $this->error('请选择正确的分类');
        }
        if($this->isPost()){
            $data = $this->_post('data', false);
            foreach($data as $key => $val){
                foreach($val as $k => $v){
                    if(!empty($v['attr_name'])){
                        D('RunningCateAttr')->add(array('cate_id' => $cate_id, 'type' => htmlspecialchars($key), 'attr_name' => htmlspecialchars($v['attr_name']), 'orderby' => (int) $v['orderby']));
                    }
                }
            }
            $old = $this->_post('old', false);
            foreach($old as $key => $val){
                D('RunningCateAttr')->save(array('attr_id' => (int) $key, 'attr_name' => htmlspecialchars($val['attr_name']), 'orderby' => (int) $val['orderby']));
            }
            $this->tuSuccess('操作成功', U('runningcate/setting', array('cate_id' => $cate_id)));
        }else{
            $this->assign('detail', $detail);
            $this->assign('attrs', D('RunningCateAttr')->order(array('orderby' => 'asc'))->where(array('cate_id' => $cate_id))->select());
            $this->display();
        }
    }
	
	
	public function tag_delete($tag_id){
        if(empty($tag_id)){
            $this->tuError('操作失败');
        }
        if(!($detail = M('RunningCateTag')->find($tag_id))){
            $this->tuError('操作失败');
        }
        M('RunningCateTag')->delete($tag_id);
        $this->tuSuccess('删除成功', U('runningcate/tag', array('cate_id' => $detail['cate_id'])));
    }
	
	
    public function tag($cate_id){
        if(!($cate_id = (int) $cate_id)){
            $this->error('请选择正确的分类');
        }
        if(!($detail = M('RunningCate')->find($cate_id))){
            $this->error('请选择正确的分类');
        }
        if($this->isPost()){
            $data = $this->_post('data', false);
            foreach ($data as $key => $val){
                foreach ($val as $k => $v){
                    if (!empty($v['tag_name'])){
                        M('RunningCateTag')->add(array('cate_id' => $cate_id, 'type' => htmlspecialchars($key), 'tag_name' => htmlspecialchars($v['tag_name']), 'orderby' => (int) $v['orderby']));
                    }
                }
            }
            $old = $this->_post('old', false);
            foreach($old as $key => $val){
                M('RunningCateTag')->save(array('tag_id' => (int) $key, 'tag_name' => htmlspecialchars($val['tag_name']), 'orderby' => (int) $val['orderby']));
            }
            $this->tuSuccess('操作成功', U('runningcate/tag', array('cate_id' => $cate_id)));
        }else{
            $this->assign('detail', $detail);
            $this->assign('tags', M('RunningCateTag')->order(array('orderby' => 'asc'))->where(array('cate_id' => $cate_id))->select());
            $this->display();
        }
    }
}