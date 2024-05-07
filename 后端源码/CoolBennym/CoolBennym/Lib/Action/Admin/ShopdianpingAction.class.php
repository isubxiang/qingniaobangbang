<?php
class ShopdianpingAction extends CommonAction
{
    private $create_fields = array('user_id', 'reply', 'shop_id', 'score', 'cost', 'contents', 'show_date');
    private $edit_fields = array('user_id', 'reply', 'shop_id', 'score', 'cost', 'contents', 'show_date');
    public function index()
    {
        $Shopdianping = D('Shopdianping');
        import('ORG.Util.Page');
        $map = array('closed' => 0);
		
		
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
        $count = $Shopdianping->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Shopdianping->where($map)->order(array('dianping_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
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
            $obj = D('Shopdianping');
            if ($dianping_id = $obj->add($data)) {
                $photos = $this->_post('photos', false);
                $local = array();
                foreach ($photos as $val) {
                    if (isImage($val)) {
                        $local[] = $val;
                    }
                }
                if (!empty($local)) {
                    D('Shopdianpingpics')->upload($dianping_id, $data['shop_id'], $local);
                }
                $this->tuSuccess('添加成功', U('shopdianping/index'));
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
        if (empty($data['user_id'])) {
            $this->tuError('用户不能为空');
        }
        $data['shop_id'] = (int) $data['shop_id'];
        if (empty($data['shop_id'])) {
            $this->tuError('商家不能为空');
        }
        $data['score'] = (int) $data['score'];
        if (empty($data['score'])) {
            $this->tuError('评分不能为空');
        }
        $data['cost'] = (int) $data['cost'];
        $data['contents'] = htmlspecialchars($data['contents']);
        if (empty($data['contents'])) {
            $this->tuError('评价内容不能为空');
        }
        $data['show_date'] = htmlspecialchars($data['show_date']);
        if (empty($data['show_date'])) {
            $this->tuError('生效日期不能为空');
        }
        if (!isDate($data['show_date'])) {
            $this->tuError('生效日期格式不正确');
        }
        $data['reply'] = htmlspecialchars($data['reply']);
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] = get_client_ip();
        return $data;
    }
    public function edit($dianping_id = 0)
    {
        if ($dianping_id = (int) $dianping_id) {
            $obj = D('Shopdianping');
            if (!($detail = $obj->find($dianping_id))) {
                $this->tuError('请选择要编辑的商铺点评');
            }
            if ($this->isPost()) {
                $data = $this->editCheck();
                $data['dianping_id'] = $dianping_id;
                if (false !== $obj->save($data)) {
                    $photos = $this->_post('photos', false);
                    $local = array();
                    foreach ($photos as $val) {
                        if (isImage($val)) {
                            $local[] = $val;
                        }
                    }
                    if (!empty($local)) {
                        D('Shopdianpingpics')->upload($dianping_id, $data['shop_id'], $local);
                    }
                    $this->tuSuccess('操作成功', U('shopdianping/index'));
                }
                $this->tuError('操作失败');
            } else {
                $this->assign('detail', $detail);
                $this->assign('user', D('Users')->find($detail['user_id']));
                $this->assign('shop', D('Shop')->find($detail['shop_id']));
                $this->assign('photos', D('Shopdianpingpics')->getPics($dianping_id));
                $this->display();
            }
        } else {
            $this->tuError('请选择要编辑的商铺点评');
        }
    }
    private function editCheck()
    {
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['user_id'] = (int) $data['user_id'];
        if (empty($data['user_id'])) {
            $this->tuError('用户不能为空');
        }
        $data['shop_id'] = (int) $data['shop_id'];
        if (empty($data['shop_id'])) {
            $this->tuError('商家不能为空');
        }
        $data['score'] = (int) $data['score'];
        if (empty($data['score'])) {
            $this->tuError('评分不能为空');
        }
        $data['cost'] = (int) $data['cost'];
        $data['contents'] = htmlspecialchars($data['contents']);
        if (empty($data['contents'])) {
            $this->tuError('评价内容不能为空');
        }
        $data['show_date'] = htmlspecialchars($data['show_date']);
        if (empty($data['show_date'])) {
            $this->tuError('生效日期不能为空');
        }
        if (!isDate($data['show_date'])) {
            $this->tuError('生效日期格式不正确');
        }
        $data['reply'] = htmlspecialchars($data['reply']);
        $photos = $this->_post('photos', false);
        $local = array();
        foreach ($photos as $val) {
            if (isImage($val)) {
                $local[] = $val;
            }
        }
        $data['photos'] = json_encode($local);
        return $data;
    }
    public function delete($dianping_id = 0)
    {
        if (is_numeric($dianping_id) && ($dianping_id = (int) $dianping_id)) {
            $obj = D('Shopdianping');
            $obj->save(array('dianping_id' => $dianping_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('shopdianping/index'));
        } else {
            $dianping_id = $this->_post('dianping_id', false);
            if (is_array($dianping_id)) {
                $obj = D('Shopdianping');
                foreach ($dianping_id as $id) {
                    $obj->save(array('dianping_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('删除成功', U('shopdianping/index'));
            }
            $this->tuError('请选择要删除的商铺点评');
        }
    }
}