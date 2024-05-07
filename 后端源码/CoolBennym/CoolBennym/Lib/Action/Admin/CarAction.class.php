<?php
class CarAction extends CommonAction{
	
   	//设置
	public function set(){
        $this->title = '系统设置';
        if(IS_POST){
            $rollingPlanIntroduce = I('rollingPlanIntroduce','','trim');
            if(empty($rollingPlanIntroduce)){
				$this->tuError('不能留空');
            }
            $file = CONF_PATH.'/config.site.php';
            $arr = array_keys($_POST);
            $siteConfig = array();
            for($i=0;$i<count($arr);$i++){
                $siteConfig['cfg_'.$arr[$i]] = htmlspecialchars($_POST[$arr[$i]]);
            }
            if(!writeArr($siteConfig,$file)){
				$this->tuError('保存失败');
            }
			$this->tuSuccess('保存成功', U('car/set'));
            exit;
        }
        $this->display();
    }
	
	
    public function city(){
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['name|shortPinyin'] = array('LIKE', '%'.$keyword.'%');
        }    
        $this->assign('keyword',$keyword);
		if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
        }
		if(isset($_GET['status']) || isset($_POST['status'])){
            $status = $this->_param('is_open', 'htmlspecialchars');
            if ($status == 1) {
                $map['status'] = 1;
            }else{
				$map['status'] = 0;
			}
            $this->assign('status', $status);
        }else{
            $this->assign('status', 999);
        }
        $count = M('CarCity')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('CarCity')->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['user'] = M('Users')->find($val['user_id']);
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }
	
	
	 
	
	


    public function city_create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false),array('name','user_id','areaCode','point','shortPinyin','firstLetter','lng','lat','orderby'));
			$data['name'] = htmlspecialchars($data['name']);
			if(empty($data['name'])){
				$this->tuError('城市名称不能为空');
			} 
			$data['user_id'] = (int) $data['user_id'];
			$data['areaCode'] = htmlspecialchars($data['areaCode']);
			$data['shortPinyin'] = htmlspecialchars($data['shortPinyin']);
			$data['firstLetter'] = htmlspecialchars($data['firstLetter']);
			$data['lng'] = htmlspecialchars($data['lng']);
			$data['lat'] = htmlspecialchars($data['lat']);
			$data['lng'] = htmlspecialchars($data['lng']);
			$data['point'] = $data['lng'].'|'.$data['lat'];
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
            if(M('CarCity')->add($data)){
                $this->tuSuccess('添加成功', U('car/city'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }


    public function city_edit($id = 0){
        if($id = (int) $id){
            if(!$detail = M('CarCity')->find($id)){
                $this->tuError('请选择要编辑的城市站点');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false),array('name','user_id','areaCode','point','shortPinyin','firstLetter','lng','lat','orderby'));
				$data['name'] = htmlspecialchars($data['name']);
				if(empty($data['name'])){
					$this->tuError('城市名称不能为空');
				} 
				$data['user_id'] = (int) $data['user_id'];
				$data['areaCode'] = htmlspecialchars($data['areaCode']);
				$data['shortPinyin'] = htmlspecialchars($data['shortPinyin']);
				$data['firstLetter'] = htmlspecialchars($data['firstLetter']);
				$data['lng'] = htmlspecialchars($data['lng']);
				$data['lat'] = htmlspecialchars($data['lat']);
				$data['lng'] = htmlspecialchars($data['lng']);
				$data['point'] = $data['lng'].'|'.$data['lat'];
                $data['id'] = $id;
                if(false !== M('CarCity')->save($data)){
                    $this->tuSuccess('操作成功', U('car/city'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
				$this->assign('user', D('Users')->where(array('user_id'=>$detail['user_id']))->find());
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的城市站点');
        }
    }
	
	

    public function city_delete($id = 0){
        if($id = (int) $id){
			if(M('CarCity')->where(array('id'=>$id))->delete()){
				$this->tuSuccess('删除成功', U('car/city'));
			}else{
				$this->tuError('删除城市更新数据库失败');
			}
        }else{
            $this->tuError('请选择要删除的城市站点');
        }
    }
	
	
	public function points($id = 0,$type = 0){
        import('ORG.Util.Page');
		if(!$detail = M('CarCity')->find($id)){
            $this->error('请选择要编辑的城市站点');
        }
	 	if(!$type){
            $this->error('类型错误');
        }
		$this->assign('type',$type);
		
		//p($type);die;
       
        $positionList = M('CarCitySelectPoints')->where(array('id'=>$id,'type' =>$type))->select();
        for($i = 0; $i < count($positionList); $i++){
            $positionList[$i]['position']    = unserialize($positionList[$i]['position']);
            $positionList[$i]['create_time'] = date('Y-m-d H:i:s', $positionList[$i]['create_time']);
        }
        $this->assign('positionList', $positionList);
		$this->assign('detail',$detail);
		
        $this->display();
    }
	
	
	
	 //添加区域
    public function pushPosition($id = 0,$type = 0){
        if(!$this->isPost()){
            $result = array('status'  => false,'message' => '非法操作');
        }else{
            $data['position'] = $this->toFloat($_POST['position'],6);
            $data['name']  = I('name');
			$data['id']  = $id;
			$data['type']  =$type;
            $data['create_time'] = time();
		
            if($data && is_array($data)){
                $res = M('CarCitySelectPoints')->add($data);
                if($res){
                    $result = array('status'  => true,'data'    => $_POST['position'],'message' => '添加成功'
                    );
                }else{
                    $result = array('status'  => false,'message' => '添加失败');
                }
            }else{
                $result = array('status'  => false,'message' => '数据不合理');
            }
        }
        echo json_encode($result, true);
    }


    //过滤坐标值为6位小数,序列化坐标数组
    private function toFloat ($data, $num){
        $data = json_decode($data, true);
        if($data && is_array($data)){
            foreach($data as $k => $v){
                (array)$v;
                $v['lng'] = sprintf("%." . $num . "f", $v['lng']);
                $v['lat'] = sprintf("%." . $num . "f", $v['lat']);
            }
        }
        return serialize($data);
    }
	

    //获取区域列表
    public function poList(){
        $positionList =M('CarCitySelectPoints')->field('points_id,position,color')->select();
        for($i = 0; $i < count($positionList); $i++){
            $positionList[$i]['position'] = unserialize($positionList[$i]['position']);
        }
        echo json_encode($positionList, true);
    }

    //删除区域
    public function del($points_id){
		if(!$detail = M('CarCitySelectPoints')->find($points_id)){
            $this->tuError('内容不存在');
        }
		if(M('CarCitySelectPoints')->where(array('points_id'=>$points_id))->delete()){
			$this->tuSuccess('删除成功', U('car/points',array('id'=>$detail['id'],'type'=>$detail['type'])));
		}else{
			$this->tuError('删除失败');
		}
    }

    //更新区域颜色
    public function upColor(){
        if(!$this->isPost()){
            $result = array('status'  => false,'message' => '非法操作');
        }else{
            $color = $_POST['color'];
            $id = $_POST['id'];
			$points_id = $_POST['points_id'];
			$type  = $_POST['type'];

            if($color && $id){
                $res = M('CarCitySelectPoints')->where(array('points_id'=>$points_id))->save(array('color'=>$color,'id'=>$id,'type'=>$type));
                if($res){
                    $result = array('status'  => true,'message' => '更新成功');
                }else{
                    $result = array('status'  => false, 'message' => '数据更新失败');
                }
            }else{
                $result = array('status'  => false,'message' => '参数不正确');
            }
        }
        echo json_encode($result,true);
    }


	
	

	//线路列表
	 public function intercity(){
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['areaEndName|areaStartName'] = array('LIKE', '%'.$keyword.'%');
        }    
        $this->assign('keyword',$keyword);
        $count = M('CarIntercity')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('CarIntercity')->where($map)->order(array('id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['user'] = M('Users')->find($val['user_id']);
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }


    public function intercity_create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false),array('areaStartId','areaEndId'));
			$data['areaStartId'] = (int) $data['areaStartId'];
			$data['areaEndId'] = (int) $data['areaEndId'];
			if(empty($data['areaStartId'])){
				$this->tuError('起点城市ID不能为空');
			} 
			if(!$areaStart = M('CarCity')->find($data['areaStartId'])){
				$this->tuError('起点城市不存在');
			}
			if(empty($data['areaEndId'])){
				$this->tuError('终点城市ID不能为空');
			}
			if(!$areaEnd = M('CarCity')->find($data['areaEndId'])){
				$this->tuError('终点城市不存在');
			}
			if($data['areaStartId'] == $data['areaEndId']){
				$this->tuError('起点城市跟终点城市不能一致');
			}
			$data['areaStartCode'] = $areaStart['areaCode'];
			$data['areaStartName'] = $areaStart['name'];
			$data['areaEndCode'] = $areaEnd['areaCode'];
			$data['areaEndName'] = $areaEnd['name'];
				
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
            if(M('CarIntercity')->add($data)){
                $this->tuSuccess('添加成功', U('car/intercity'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }


    public function intercity_edit($id = 0){
        if($id = (int) $id){
            if(!$detail = M('CarIntercity')->find($id)){
                $this->tuError('请选择要编辑的城市站点');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false),array('areaStartId','areaEndId'));
				$data['areaStartId'] = (int) $data['areaStartId'];
				$data['areaEndId'] = (int) $data['areaEndId'];
				if(empty($data['areaStartId'])){
					$this->tuError('起点城市ID不能为空');
				} 
				if(!$areaStart = M('CarCity')->find($data['areaStartId'])){
                	$this->tuError('起点城市不存在');
            	}
				if(empty($data['areaEndId'])){
					$this->tuError('终点城市ID不能为空');
				}
				if(!$areaEnd = M('CarCity')->find($data['areaEndId'])){
                	$this->tuError('终点城市不存在');
            	}
				if($data['areaStartId'] == $data['areaEndId']){
					$this->tuError('起点城市跟终点城市不能一致');
				}
				$data['areaStartCode'] = $areaStart['areaCode'];
				$data['areaStartName'] = $areaStart['name'];
				$data['areaEndCode'] = $areaEnd['areaCode'];
				$data['areaEndName'] = $areaEnd['name'];
				
                $data['id'] = $id;
                if(false !== M('CarIntercity')->save($data)){
                    $this->tuSuccess('操作成功', U('car/intercity'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的城市站点');
        }
    }
	
	

    public function intercity_delete($id = 0){
        if($id = (int) $id){
			$this->tuError('不要随意删除');
			if(M('CarIntercity')->where(array('id'=>$id))->delete()){
				$this->tuSuccess('删除成功', U('car/intercity'));
			}else{
				$this->tuError('删除城市更新数据库失败');
			}
        }else{
            $this->tuError('请选择要删除的城市站点');
        }
    }
	
	
	public function getWeeks($time = '', $format='m-d',$num='7'){
	  $time = $time != '' ? $time : time();
	  $date = array();
	  for ($i=1; $i<=$num; $i++){
		$date[$i] = date($format ,strtotime( '+' . $i-1 .' days', $time));
	  }
	  return $date;
	}
	
	//线路列表
	 public function panlist($id){
		 
		if(!$detail = M('CarIntercity')->find($id)){
            $this->tuError('线路错误');
        }
		$this->assign('id',$id);
		$this->assign('detail', $detail);
		
		
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['contactNumber|contactWay'] = array('LIKE', '%'.$keyword.'%');
        }  
		
		$date = $this->_param('date','htmlspecialchars');
        if($date){
            $map['date'] = $date;
        }    
        $this->assign('date',$date);
		
		  
        $this->assign('keyword',$keyword);
        $count = M('CarPanList')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('CarPanList')->where($map)->order(array('list_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show); 
		
		$this->assign('getWeeks', $getWeeks=$this->getWeeks(time(),'Y-m-d',15));
        $this->display();
    }
	
	
	 public function publish($id = 0,$list_id = 0){
        if($id = (int) $id){
            if(!$detail = M('CarIntercity')->find($id)){
                $this->tuError('请选择要编辑的线路站点');
            }
			
		    $list = M('CarPanList')->find($list_id);
            $this->assign('list',$list);
			
			$date = $this->_param('date','htmlspecialchars');
			$date = $list['date'] ? $list['date'] : $date;
			$this->assign('date',$date);
		
		
            if($this->isPost()){
				
				
                $data = $this->checkFields($this->_post('data', false),array('list_id','aheadSaleDay','imagePath','leftNum','onStopTicketsLeftNum','price','stockCode','startTime','endTime','type','typeText','vehicleSeats','contactNumber','contactWay','date','isPrompt','remark','rollingPlanIntroduce'));
				
				if(!$Start = M('CarCity')->find($detail['areaStartId'])){
                	$this->tuError('起点城市不存在');
            	}
				
				if(!$End = M('CarCity')->find($detail['areaEndId'])){
                	$this->tuError('终点城市不存在');
            	}
				
				$data['list_id'] = (int) $data['list_id'];//车次ID
				$data['id'] = $id;
				
				
				$data['startAreaName'] = $Start['name'];
				$data['endAreaName'] = $End['name'];
				$data['aheadSaleDay'] = htmlspecialchars($data['aheadSaleDay']);//提前销售日
				$data['imagePath'] = htmlspecialchars($data['imagePath']);//图片
				
				$data['leftNum'] = (int) $data['leftNum'];//总位置
				$data['onStopTicketsLeftNum'] = (int) $data['onStopTicketsLeftNum'];//已预订位置
				$data['price'] = (int) $data['price']*100;//单价
				$data['productLineName'] = $data['startAreaName'].'->'.$data['endAreaName'];
				$data['stockCode'] = htmlspecialchars($data['stockCode']);
				
				$data['startTime'] = htmlspecialchars($data['startTime']);
				$data['endTime'] = htmlspecialchars($data['endTime']);
				$data['date'] = htmlspecialchars($data['date']);
		
				$data['type'] = (int) $data['type'];
				$data['typeText'] = htmlspecialchars($data['typeText']);//接送方式
				$data['vehicleSeats'] = (int) $data['vehicleSeats'];//剩余席位
				
				$data['contactNumber'] = htmlspecialchars($data['contactNumber']);//联系方式
				$data['contactWay'] = htmlspecialchars($data['contactWay']);//联系人
				$data['isPrompt'] = htmlspecialchars($data['isPrompt']);//是否提示
				$data['remark'] = htmlspecialchars($data['remark']);//车票预售期说明
				$data['rollingPlanIntroduce'] = htmlspecialchars($data['rollingPlanIntroduce']);//平面图连接
				
                
				if($data['list_id']){
					$data['update_time'] = NOW_TIME;
					$data['update_ip'] = get_client_ip();
					$res = M('CarPanList')->save($data);
					$intro = '修改成功';
				}else{
					$data['create_time'] = NOW_TIME;
					$data['create_ip'] = get_client_ip();
					$res = M('CarPanList')->add($data);
					$intro = '添加成功';
				}
				
                if($res){
                    $this->tuSuccess($intro, U('car/panlist',array('id'=>$id,'date'=>$data['date'])));
                }else{
					$this->tuError('操作失败');
				}
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的城市站点');
        }
    }
	
	
	
	
	public function order(){
        import('ORG.Util.Page');
        $map = array();
        $keyword = $this->_param('keyword','htmlspecialchars');
        if($keyword){
            $map['contact|contactPhone'] = array('LIKE', '%'.$keyword.'%');
        }    
        $this->assign('keyword',$keyword);
		if($user_id = (int) $this->_param('user_id')){
            $map['user_id'] = $user_id;
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
        }
        $count = M('CarOrder')->where($map)->count(); 
        $Page = new Page($count, 25); 
        $show = $Page->show(); 
        $list = M('CarOrder')->where($map)->order(array('order_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach($list as $k => $val){
			$list[$k]['startPoint'] = unserialize($val['startPoint']);
			$list[$k]['endPoint'] = unserialize($val['endPoint']);
			$list[$k]['appInfo'] = unserialize($val['appInfo']);
			$list[$k]['user'] = M('Users')->find($val['user_id']);
        }
        $this->assign('list', $list);
        $this->assign('page', $show); 
        $this->display();
    }

	
}
