<?php
class CouponAction extends CommonAction{
	
    private $create_fields = array('shop_id','title','school_id','photo','full_price','reduce_price','money','expire_date','num','limit_num','intro');
    private $edit_fields = array('shop_id','title','school_id','photo','full_price','reduce_price','money','expire_date','num','limit_num','intro');
	
    public function index(){
        import('ORG.Util.Page');
        $map = array('closed' => 0);
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
        $getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
        if($audit = (int) $this->_param('audit')){
            $map['audit'] = $audit === 1 ? 1 : 0;
            $this->assign('audit', $audit);
        }
		
        $count = M('coupon')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = M('coupon')->where($map)->order(array('coupon_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach($list as $k => $val){
            if($val['shop_id']){
                $shop_ids[$val['shop_id']] = $val['shop_id'];
            }
            $val['create_ip_area'] = $this->ipToArea($val['create_ip']);
			$val['school'] = M('running_school')->where(array('school_id'=>$val['school_id']))->find();
			$val['shop'] = M('shop')->where(array('shop_id'=>$val['shop_id']))->find();
            $list[$k] = $val;
        }
        if($shop_ids){
            $this->assign('shops', D('Shop')->itemsByIds($shop_ids));
        }
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
    public function create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false), $this->create_fields);
		
			$data['shop_id'] = (int) $data['shop_id'];
			if(empty($data['shop_id'])) {
				$this->tuError('商家不能为空');
			}
			$shop = M('shop')->find($data['shop_id']);
			if(empty($shop)) {
				$this->tuError('请选择正确的商家');
			}
			$data['cate_id'] = $shop['cate_id'];
			$data['city_id'] = $shop['city_id'];
			$data['area_id'] = $shop['area_id'];
			
			
			$data['school_id'] = (int) $data['school_id'];
			if(empty($data['school_id'])){
				$this->tuError('学校不能为空');
			}
			$school = M('running_school')->find($data['school_id']);
			if(empty($school)){
				$this->tuError('请选择正确的商家');
			}
			$data['title'] = htmlspecialchars($data['title']);
			if(empty($data['title'])){
				$this->tuError('标题不能为空');
			}
			$data['photo'] = htmlspecialchars($data['photo']);
			if(empty($data['photo'])){
				$this->tuError('请上传红包图片');
			}
			if(!isImage($data['photo'])){
				$this->tuError('红包图片格式不正确');
			}
			$data['expire_date'] = htmlspecialchars($data['expire_date']);
			if(empty($data['expire_date'])){
				$this->tuError('过期日期不能为空');
			}
			if(!isDate($data['expire_date'])){
				$this->tuError('过期日期格式不正确');
			}
			$data['intro'] = htmlspecialchars($data['intro']);
			if(empty($data['intro'])){
				$this->tuError('红包描述不能为空');
			}
			
			$data['full_price'] = (int) ($data['full_price'] * 100);
			if(empty($data['full_price'])){
				$this->tuError('满多少钱不能为空');
			}
			$data['reduce_price'] = (int) ($data['reduce_price'] * 100);
			if(empty($data['reduce_price'])) {
				$this->tuError('减多少钱不能为空');
			}
			if($data['reduce_price'] >= $data['full_price']){
				$this->tuError('减多少钱不能大于减多少钱');
			}
			$data['money'] = (int) ($data['money'] * 100);
			
			
			$data['num'] = (int) $data['num'];
			$data['limit_num'] = (int) $data['limit_num'];
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();

            if(M('coupon')->add($data)){
                $this->tuSuccess('添加成功', U('coupon/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }
	
	
    //发放红包
    public function deliver($coupon_id = 0){
        if($coupon_id = (int) $coupon_id){

            if(!($detail = M('coupon')->find($coupon_id))){
                $this->tuError('请选择要编辑的红包');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false),array('user_id','num','coupon_id'));
				
				$data['school_id'] = (int) $detail['school_id'];
				if(empty($data['school_id'])){
					$this->tuError('学校不能为空');
				}
				
				
				$school = M('running_school')->find($data['school_id']);
				if(empty($school)){
					$this->tuError('请选择正确的学校');
				}
				$data['user_id'] = (int) $data['user_id'];
				if(empty($data['user_id'])){
					$this->tuError('会员不能为空');
				}
				$users = M('users')->find($data['user_id']);
				if(empty($users)){
					$this->tuError('请选择正确的会员');
				}
				$data['num'] = (int)$data['num'];
				if(empty($data['num'])){
					$this->tuError('请选择正确的张');
				}
				
				$download = M('coupon_download')->where(array('coupon_id'=>$coupon_id,'user_id'=>$data['user_id'],'is_used'=>0))->find();
				if($download){
					$this->tuError('当前会员有重复的红包暂时无法重复发放');
				}
				
				if(false !== D('Coupondownload')->deliver($coupon_id,$data['school_id'],$data['user_id'],$data['num'])){
                    $this->tuSuccess('发放红包成功', U('coupondownload/index',array('coupon_id'=>$coupon_id)));
                }else{
					$this->tuError(D('Coupondownload')->getError());
				}
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要发放的红包');
        }
    }
	
	
	
	 //编辑红包
	 public function edit($coupon_id = 0){
        if($coupon_id = (int) $coupon_id){

            if(!($detail = M('coupon')->find($coupon_id))){
                $this->tuError('请选择要编辑的红包');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
				
				$data['shop_id'] = (int) $data['shop_id'];
				if(empty($data['shop_id'])) {
					$this->tuError('商家不能为空');
				}
				$shop = M('shop')->find($data['shop_id']);
				if(empty($shop)) {
					$this->tuError('请选择正确的商家');
				}
				$data['cate_id'] = $shop['cate_id'];
				$data['city_id'] = $shop['city_id'];
				$data['area_id'] = $shop['area_id'];
				
				$data['school_id'] = (int) $data['school_id'];
				if(empty($data['school_id'])){
					$this->tuError('学校不能为空');
				}
				$school = M('running_school')->find($data['school_id']);
				if(empty($school)){
					$this->tuError('请选择正确的商家');
				}
				$data['title'] = htmlspecialchars($data['title']);
				if(empty($data['title'])){
					$this->tuError('标题不能为空');
				}
				$data['photo'] = htmlspecialchars($data['photo']);
				if(empty($data['photo'])){
					$this->tuError('请上传红包图片');
				}
				if(!isImage($data['photo'])){
					$this->tuError('红包图片格式不正确');
				}
				$data['expire_date'] = htmlspecialchars($data['expire_date']);
				if(empty($data['expire_date'])){
					$this->tuError('过期日期不能为空');
				}
				if(!isDate($data['expire_date'])){
					$this->tuError('过期日期格式不正确');
				}
				$data['intro'] = htmlspecialchars($data['intro']);
				if(empty($data['intro'])){
					$this->tuError('红包描述不能为空');
				}
				$data['full_price'] = (int) ($data['full_price'] * 100);
				if(empty($data['full_price'])){
					$this->tuError('满多少钱不能为空');
				}
				$data['reduce_price'] = (int) ($data['reduce_price'] * 100);
				if(empty($data['reduce_price'])){
					$this->tuError('减多少钱不能为空');
				}
				if($data['reduce_price'] >= $data['full_price']){
					$this->tuError('减多少钱不能大于减多少钱');
				}
				$data['money'] = (int) ($data['money'] * 100);
				$data['num'] = (int) $data['num'];
				$data['limit_num'] = (int) $data['limit_num'];
			
                $data['coupon_id'] = $coupon_id;
                if(false !== M('coupon')->save($data)){
                    $this->tuSuccess('操作成功', U('coupon/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->assign('shop', D('Shop')->find($detail['shop_id']));
				$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的红包');
        }
    }
   
   
   
    public function delete($coupon_id = 0){
        if(is_numeric($coupon_id) && ($coupon_id = (int) $coupon_id)){
            M('coupon')->save(array('coupon_id' => $coupon_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('coupon/index'));
        }else{
            $coupon_id = $this->_post('coupon_id', false);
            if(is_array($coupon_id)){
                foreach($coupon_id as $id){
                    M('coupon')->save(array('coupon_id' =>$id,'closed'=>1));
                }
                $this->tuSuccess('删除成功', U('coupon/index'));
            }
            $this->tuError('请选择要删除的红包');
        }
    }
	
	
	
    public function audit($coupon_id = 0){
        if(is_numeric($coupon_id) && ($coupon_id = (int) $coupon_id)){
            M('coupon')->save(array('coupon_id' =>$coupon_id,'audit' => 1));
            $this->tuSuccess('审核成功',U('coupon/index'));
        }else{
            $coupon_id = $this->_post('coupon_id', false);
            if(is_array($coupon_id)){
                foreach($coupon_id as $id){
                    M('coupon')->save(array('coupon_id' =>$id,'audit' => 1));
                }
                $this->tuSuccess('审核成功', U('coupon/index'));
            }
            $this->tuError('请选择要审核的红包');
        }
    }
}