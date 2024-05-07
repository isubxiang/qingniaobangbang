<?php
class ShopyuyueAction extends CommonAction
{
    private $create_fields = array('user_id', 'shop_id', 'name', 'mobile', 'yuyue_date', 'yuyue_time', 'number', 'create_time', 'create_ip');
    private $edit_fields = array('user_id', 'shop_id', 'name', 'mobile', 'yuyue_date', 'yuyue_time', 'number');
    public function index()
    {
        $Shopyuyue = D('Shopyuyue');
        import('ORG.Util.Page');
        $map = array();
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['name|mobile'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
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
		
		
        if ($user_id = (int) $this->_param('user_id')) {
            $map['user_id'] = $user_id;
            $user = D('Users')->find($user_id);
            $this->assign('nickname', $user['nickname']);
            $this->assign('user_id', $user_id);
        }
        $count = $Shopyuyue->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Shopyuyue->where($map)->order(array('yuyue_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = $shop_ids = array();
        foreach ($list as $k => $val) {
            $val['create_ip_area'] = $this->ipToArea($val['create_ip']);
            $list[$k] = $val;
            $user_ids[$val['user_id']] = $val['user_id'];
            $shop_ids[$val['shop_id']] = $val['shop_id'];
        }
        if (!empty($user_ids)) {
            $this->assign('users', D('Users')->itemsByIds($user_ids));
        }
        if (!empty($shop_ids)) {
            $this->assign('shops', D('Shop')->itemsByIds($shop_ids));
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    public function create()
    {
        if ($this->isPost()) {
            $data = $this->createCheck();
            $obj = D('Shopyuyue');
            if ($obj->add($data)) {
                $this->tuSuccess('添加成功', U('shopyuyue/index'));
            }
            $this->tuError('操作失败');
        } else {
            $this->display();
        }
    }
    private function createCheck()
    {
        $data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['user_id'] = (int) $data['user_id'];
        $data['shop_id'] = (int) $data['shop_id'];
        if (empty($data['shop_id'])) {
            $this->tuError('商家不能为空');
        }
        $data['name'] = htmlspecialchars($data['name']);
        if (empty($data['name'])) {
            $this->tuError('称呼不能为空');
        }
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if (empty($data['mobile'])) {
            $this->tuError('手机不能为空');
        }
        if (!isMobile($data['mobile'])) {
            $this->tuError('手机格式不正确');
        }
        $data['yuyue_date'] = htmlspecialchars($data['yuyue_date']);
        $data['yuyue_time'] = htmlspecialchars($data['yuyue_time']);
        if (empty($data['yuyue_date']) || empty($data['yuyue_time'])) {
            $this->tuError('预定日期不能为空');
        }
        if (!isDate($data['yuyue_date'])) {
            $this->tuError('预定日期格式错误');
        }
        $data['number'] = (int) $data['number'];
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] = get_client_ip();
        $data['code'] = D('Shopyuyue')->getCode();
        return $data;
    }
    public function edit($yuyue_id = 0)
    {
        if ($yuyue_id = (int) $yuyue_id) {
            $obj = D('Shopyuyue');
            if (!($detail = $obj->find($yuyue_id))) {
                $this->tuError('请选择要编辑的商家预约');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['yuyue_id'] = $yuyue_id;
                if (false !== $obj->save($data)) {
                    $this->tuSuccess('操作成功', U('shopyuyue/index'));
                }
                $this->tuError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->assign('user', D('Users')->find($detail['user_id']));
                $this->assign('shop', D('Shop')->find($detail['shop_id']));
                $this->display();
            }
        } else {
            $this->tuError('请选择要编辑的商家预约');
        }
    }
    private function editCheck()
    {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['user_id'] = (int) $data['user_id'];
        $data['shop_id'] = (int) $data['shop_id'];
        if (empty($data['shop_id'])) {
            $this->tuError('商家不能为空');
        }
        $data['name'] = htmlspecialchars($data['name']);
        if (empty($data['name'])) {
            $this->tuError('称呼不能为空');
        }
        $data['mobile'] = htmlspecialchars($data['mobile']);
        if (empty($data['mobile'])) {
            $this->tuError('手机不能为空');
        }
        if (!isMobile($data['mobile'])) {
            $this->tuError('手机格式不正确');
        }
        $data['yuyue_date'] = htmlspecialchars($data['yuyue_date']);
        $data['yuyue_time'] = htmlspecialchars($data['yuyue_time']);
        if (empty($data['yuyue_date']) || empty($data['yuyue_time'])) {
            $this->tuError('预定日期不能为空');
        }
        if (!isDate($data['yuyue_date'])) {
            $this->tuError('预定日期格式错误');
        }
        $data['number'] = (int) $data['number'];
        return $data;
    }
    public function delete($yuyue_id = 0)
    {
        if (is_numeric($yuyue_id) && ($yuyue_id = (int) $yuyue_id)) {
            $obj = D('Shopyuyue');
            $obj->delete($yuyue_id);
            $this->tuSuccess('删除成功', U('shopyuyue/index'));
        } else {
            $yuyue_id = $this->_post('yuyue_id', false);
            if (is_array($yuyue_id)) {
                $obj = D('Shopyuyue');
                foreach ($yuyue_id as $id) {
                    $obj->delete($id);
                }
                $this->tuSuccess('删除成功', U('shopyuyue/index'));
            }
            $this->tuError('请选择要删除的商家预约');
        }
    }
}