<?php
class CoupondownloadAction extends CommonAction{
	
	
    public function index(){
        $Coupondownload = D('Coupondownload');
        import('ORG.Util.Page');
        $map = array('closed' =>0);
		
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['mobile'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
        $getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        if($is_used = (int) $this->_param('is_used')){
            $map['is_used'] = $is_used === 1 ? 1 : 0;
            $this->assign('is_used', $is_used);
        }
		
		
		if(isset($_GET['status']) || isset($_POST['status'])){
            $status =(int) $this->_param('status');
            if($status != 999){
                $map['status'] = $status;
            }
            $this->assign('status', $status);
        }else{
            $this->assign('status', 999);
        }
		
		
		
        if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
        }
		
		$coupon_id = (int) $this->_param('coupon_id');
        if($coupon_id){
            $map['coupon_id'] = $coupon_id;
        }
		
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		
		
        $count = $Coupondownload->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Coupondownload->where($map)->order(array('download_id' =>'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $shop_ids = $coupons = array();
		
        foreach($list as $k => $val){
            if($val['shop_id']){
                $shop_ids[$val['shop_id']] = $val['shop_id'];
            }
            if($val['coupon_id']){
                $coupons[$val['coupon_id']] = $val['coupon_id'];
            }
            $val['create_ip_area'] = $this->ipToArea($val['create_ip']);
            $val['used_ip_area'] = $this->ipToArea($val['used_ip']);
			$val['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
			$val['shop'] = M('shop')->where(array('shop_id'=>$val['shop_id']))->find();
            $list[$k] = $val;
        }
        if($shop_ids){
            $this->assign('shops', D('Shop')->itemsByIds($shop_ids));
        }
        if($coupons){
            $this->assign('coupons', D('Coupon')->itemsByIds($coupons));
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
	public function delete($download_id = 0){
        if(is_numeric($download_id) && ($download_id = (int) $download_id)){
            M('coupon_download')->save(array('download_id' => $download_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('coupondownload/index'));
        }else{
            $download_id = $this->_post('download_id', false);
            if(is_array($download_id)){
                foreach($download_id as $id){
                    M('coupon_download')->save(array('download_id' =>$id,'closed'=>1));
                }
                $this->tuSuccess('删除成功', U('coupondownload/index'));
            }
            $this->tuError('请选择要删除的红包');
        }
    }
	
	public function hexiao($download_id = 0){
        $download_id = (int)$download_id;
		if(empty($download_id)){
			$this->tuError('ID不存在');
		}
		$detail = M('coupon_download')->where(array('download_id'=>$download_id))->find();
		if($detail['is_used'] != 0){
			$this->tuError('优惠券已核销');
		}
		$result = M('coupon_download')->save(array('download_id'=>$detail['download_id'],'is_used' =>1,'status' =>2,'used_time'=>time(),'used_ip'=>get_client_ip()));
		if($result){
			$this->tuSuccess('核销成功', U('coupondownload/index'));
		}else{
			$this->tuError('核销失败');
		}
    }
	
}