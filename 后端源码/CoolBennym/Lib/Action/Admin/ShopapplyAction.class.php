<?php
class ShopapplyAction extends CommonAction{
	
	
    private $create_fields = array('cate_id', 'name', 'shop_name', 'contact', 'create_time', 'create_ip');
    public function index(){
        $Shopapply = D('Shopapply');
        import('ORG.Util.Page');
        $map = array();
        if($name = $this->_param('name', 'htmlspecialchars')){
            $map['name'] = array('LIKE', '%' . $name . '%');
            $this->assign('name', $name);
        }
        if($shop_name = $this->_param('shop_name', 'htmlspecialchars')){
            $map['shop_name'] = array('LIKE', '%' . $shop_name . '%');
            $this->assign('shop_name', $shop_name);
        }
        if ($contact = $this->_param('contact', 'htmlspecialchars')){
            $map['contact'] = array('LIKE', '%' . $contact . '%');
            $this->assign('contact', $contact);
        }
        $count = $Shopapply->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Shopapply->order(array('apply_id' => 'desc'))->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $key => $val){
            $val['create_ip_area'] = $this->ipToArea($val['create_ip']);
            $val = $Shopapply->_format($val);
            $list[$key] = $val;
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
    public function create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false), $this->create_fields);
			$data['cate_id'] = (int) $data['cate_id'];
			if(empty($data['cate_id'])) {
				$this->tuError('分类不能为空');
			}
			$data['name'] = htmlspecialchars($data['name']);
			if(empty($data['name'])){
				$this->tuError('店主名称不能为空');
			}
			$data['shop_name'] = htmlspecialchars($data['shop_name']);
			if(empty($data['shop_name'])) {
				$this->tuError('店铺名称不能为空');
			}
			$data['contact'] = htmlspecialchars($data['contact']);
			if (empty($data['contact'])) {
				$this->tuError('联系方式不能为空');
			}
			if (!isPhone($data['contact']) && !isMobile($data['contact'])){
				$this->tuError('联系方式格式不正确');
			}
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
			
			$obj = D('Shopapply');
            if($obj->add($data)){
                $this->tuSuccess('添加成功', U('shopapply/index'));
            }
            $this->tuError('操作失败');
        } else {
            $this->assign('cates', D('Shopcate')->fetchAll());
            $this->display();
        }
    }
   
   
    public function audit($apply_id = 0){
        if(is_numeric($apply_id) && ($apply_id = (int) $apply_id)){
            $obj = D('Shopapply');
            $obj->save(array('apply_id' => $apply_id, 'audit' => 1));
            $this->tuSuccess('确认成功', U('shopapply/index'));
        }else{
            $apply_id = $this->_post('apply_id', false);
            if(is_array($apply_id)){
                $obj = D('Shopapply');
                foreach ($apply_id as $id){
                    $obj->save(array('apply_id' => $id, 'audit' => 1));
                }
                $this->tuSuccess('确认成功', U('shopapply/index'));
            }
            $this->tuError('请选择要确认的商家申请');
        }
    }
}