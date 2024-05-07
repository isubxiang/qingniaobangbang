<?php



class ThreadcateAction extends CommonAction{


    public function index(){
        $list = D('Threadcate')->order(array('orderby' =>'asc'))->select();
		foreach($list as $key => $v){
            if($v['cate_id']){
                $count = D('Thread')->where(array('cate_id' => $v['cate_id']))->count();
            }
            $list[$key]['count'] = $count;
        }
        $this->assign('list', $list); 
        $this->display(); 
    }


    public function create(){
        if($this->isPost()){
             $data = $this->checkFields($this->_post('data', false), array('school_id','cate_name','photo','money','orderby'));
			$data['cate_name'] = htmlspecialchars($data['cate_name']);
			if (empty($data['cate_name'])){
				$this->tuError('分类名称不能为空');
			}
			$data['photo'] = htmlspecialchars($data['photo']);
				$data['money'] = (int) ($data['money']*100);
			$data['orderby'] = (int) $data['orderby'];
				$obj = D('Threadcate');
            if ($obj->add($data)) {
                $obj->cleanCache();
                $this->tuSuccess('添加成功', U('threadcate/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }

    public function edit($cate_id = 0){
        if($cate_id = (int) $cate_id){
            $obj = D('Threadcate');
            if(!$detail = $obj->find($cate_id)){
                $this->tuError('请选择要编辑的主题分类');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false),array('school_id','cate_name','photo','money','orderby'));
				$data['cate_name'] = htmlspecialchars($data['cate_name']);
				if (empty($data['cate_name'])){
					$this->tuError('分类品牌不能为空');
				}
				$data['photo'] = htmlspecialchars($data['photo']);
					$data['money'] = (int) ($data['money']*100);
				$data['orderby'] = (int) $data['orderby'];
                $data['cate_id'] = $cate_id;
                if (false !== $obj->save($data)){
                    $obj->cleanCache();
                    $this->tuSuccess('操作成功', U('threadcate/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的主题分类');
        }
    }

  

    public function delete($cate_id = 0){
        if(is_numeric($cate_id) && ($cate_id = (int) $cate_id)){
            $obj = D('Threadcate');
            $obj->delete($cate_id);
            $obj->cleanCache();
            $this->tuSuccess('删除成功', U('threadcate/index'));
           
        } 
    }
    
    public function update(){
        $orderby = $this->_post('orderby', false);
        $obj = D('Threadcate');
        foreach($orderby as $key => $val){
            $data = array(
                'cate_id' => (int) $key,
                'orderby' => (int) $val
            );
            $obj->save($data);
        }
        $obj->cleanCache();
        $this->tuSuccess('更新成功', U('threadcate/index'));
    }


}
