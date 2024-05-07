<?php
class IntegralexchangeAction extends CommonAction{
	
	
	public function _initialize(){
        parent::_initialize();
		$this->assign('getTypes',$getTypes = D('Integralexchange')->getTypes());
    }
	
	
	
    public function index(){
        $Integralexchange = D('Integralexchange');
        import('ORG.Util.Page');
        $map = array();
		
        $getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
        if ($user_id = (int) $this->_param('user_id')) {
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
		
        $getSearchShopIds = $this->getSearchShopId($this->city_id);
		if($getSearchShopIds['shop_ids']){
			 $map['shop_id'] = array('in',$getSearchShopIds['shop_ids']);
		}elseif($getSearchShopIds['shop_id']){
			$map['shop_id'] = $getSearchShopIds['shop_id'];
			$shop = D('Shop')->find($map['shop_id']);
            $this->assign('shop_name', $shop['shop_name']);
            $this->assign('shop_id',$map['shop_id']);
		}
		
        if (isset($_GET['st']) || isset($_POST['st'])) {
            $st = (int) $this->_param('st');
            if ($st != 999) {
                $map['status'] = $st;
            }
            $this->assign('st', $st);
        } else {
            $this->assign('st', 999);
        }
        $count = $Integralexchange->where($map)->count();
        $Page = new Page($count, 15);
        $show = $Page->show();
        $list = $Integralexchange->where($map)->order(array('exchange_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = $shop_ids = $good_ids = $addr_ids = array();
        foreach ($list as $val) {
            $user_ids[$val['user_id']] = $val['user_id'];
            $shop_ids[$val['shop_id']] = $val['shop_id'];
            $good_ids[$val['goods_id']] = $val['goods_id'];
            $addr_ids[$val['addr_id']] = $val['addr_id'];
        }
        $this->assign('users', D('Users')->itemsByIds($user_ids));
        $this->assign('shops', D('Shop')->itemsByIds($shop_ids));
        $this->assign('goods', D('Integralgoods')->itemsByIds($good_ids));
        $this->assign('addrs', D('Useraddr')->itemsByIds($addr_ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
    public function audit($exchange_id = 0){
        if (is_numeric($exchange_id) && ($exchange_id = (int) $exchange_id)) {
            $obj = D('Integralexchange');
            $obj->save(array('exchange_id' => $exchange_id, 'audit' => 1));
            $this->tuSuccess('审核成功', U('integralexchange/index'));
        } else {
            $exchange_id = $this->_post('exchange_id', false);
            if (is_array($exchange_id)) {
                $obj = D('Integralexchange');
                foreach ($exchange_id as $id) {
                    $obj->save(array('exchange_id' => $id, 'audit' => 1));
                }
                $this->tuSuccess('审核成功', U('integralexchange/index'));
            }
            $this->tuError('请选择要审核的积分兑换');
        }
    }
}