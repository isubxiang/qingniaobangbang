<?php
class IndexAction extends CommonAction{
	
	
	//订单状态
	 private function getOrderStatus(){
        return array(
			'1' => '待付款', 
			'2' => '待处理', 
			'4' => '制作中', 
			'8' => '待配送', 
			'16' => '已接单', 
			'32' => '配送中',
			'64' => '待评价',
			'128' => '已完成',
			'256' => '付款超时', 
			'512' => '用户取消', 
			'1024' => '商家取消',
			'2048' => '过期取消',
			'4096' => '后台取消',
			'8192' => '退款失败',
		);
    }
	
	//构架函数
	public function _initialize(){
        parent::_initialize();
		$this->config  = D('Setting')->fetchAll();
        $this->assign('getOrderStatus', $this->getOrderStatus());
		$this->getOrderStatus = $this->getOrderStatus();
    }
	
	
	
    public function index(){
        $menu = D('Menu')->where(array('is_show'=>1))->fetchAll();
        if($this->_admin['role_id'] != 1){
            if($this->_admin['menu_list']){
                foreach($menu as $k => $val){
                    if(!empty($val['menu_action']) && !in_array($k, $this->_admin['menu_list'])){
                        unset($menu[$k]);
                    }
                }
                foreach($menu as $k1 => $v1){
                    if($v1['parent_id'] == 0){
                        foreach($menu as $k2 => $v2){
                            if($v2['parent_id'] == $v1['menu_id']){
                                $unset = true;
                                foreach($menu as $k3 => $v3){
                                    if($v3['parent_id'] == $v2['menu_id']){
                                        $unset = false;
                                    }
                                }
                                if($unset){
                                    unset($menu[$k2]);
                                }
                            }
                        }
                    }
                }
				
                foreach($menu as $k1 => $v1){
                    if($v1['parent_id'] == 0){
                        $unset = true;
                        foreach($menu as $k2 => $v2){
                            if($v2['parent_id'] == $v1['menu_id']){
                                $unset = false;
                            }
                        }
                        if($unset){
                            unset($menu[$k1]);
                        }
                    }
                }
            }else{
                $menu = array();
            }
        }
        $this->assign('menuList', $menu);
        $this->display();
    }
	
	
	//条码生成器
	public function getBarcodeGen(){
		$barcode = I('barcode','','htmlspecialchars');
		if(!$barcode){
			$this->ajaxReturn(array('code' => '0', 'msg' => '请输入条码'));
		}
		if(strlen($barcode) != 13){
			$this->ajaxReturn(array('code' => '0', 'msg' => '条码位数错误'));
		}
		if(false == is_numeric($barcode)){
			$this->ajaxReturn(array('code' => '0', 'msg' => '条码必须为数字'));
		}
		$file = D('Api')->getBarcodeGen($barcode);
		if($file){
			$this->ajaxReturn(array('code' => '1', 'msg' => '生成成功','img'=>config_weixin_img($file)));
		}else{
			$this->ajaxReturn(array('code' => '0', 'msg' => '生成失败'));
		}
	}


	public function action_delete($log_id = 0){
		if(is_numeric($log_id) && ($log_id = (int) $log_id)){
			M('AdminActionLogs')->delete($log_id);
			$this->tuSuccess('删除成功', U('index/main'));
		}else{
			$log_id = $this->_post('log_id', false);
			if(is_array($log_id)){
				foreach($log_id as $id){
					M('AdminActionLogs')->delete($id);
				}
				$this->tuSuccess('批量删除成功', U('index/main'));
			}
			$this->tuError('非法操作');
		}
	}
	
	
	public function action_delete_all(){
		M('AdminActionLogs')->where(array('log_id'=>array('gt',0)))->delete();
		$this->tuSuccess('删除全部操作日志成功', U('index/main'));
	}
	
	
    public function main(){
		
		
		$obj = D('Menu');
        import('ORG.Util.Page');
        $map = array('is_show'=>'1','parent_id'=>array('gt',0));
        $keyword = $this->_param('keyword', 'htmlspecialchars');
        if($keyword){
            $map['menu_name|menu_action'] = array('LIKE', '%' . $keyword . '%');
        }
		if($keyword){
			$count = $obj->where($map)->count();
			$Page = new Page($count,4);
			$show = $Page->show();
			$lists = $obj->where($map)->select();
			foreach($lists as $k => $val){
                if(empty($val['menu_action'])){
                    unset($lists[$k]);
				}
        	}
			$count = count($lists);
			$Page = new Page($count,4);
			$show = $Page->show();
			$lists = array_slice($lists, $Page->firstRow, $Page->listRows);
			
			$this->assign('keyword', $keyword);
			$this->assign('lists', $lists);
		}
        $this->assign('page', $show);
		
		
		$condition = array();
		$count2 = M('AdminActionLogs')->count();
		$Page2 = new Page($count2,4);
		$show2 = $Page2->show();
		$action = M('AdminActionLogs')->where($condition)->limit($Page2->firstRow . ',' . $Page2->listRows)->select();
		foreach($action as $k =>$v){    
          $Admin = M('Admin')->where(array('admin_id'=>$v['admin_id']))->order('log_id desc')->find();
          $action[$k]['admin'] = $Admin;
        }
		$this->assign('action', $action);
		$this->assign('page2', $show2);	
	
		
		
		
		$this->assign('warning',$warning = D('Admin')->find($this->_admin['admin_id']));
        $bg_time = strtotime(TODAY);
       
	   
    
		if($this->school_id){
			$counts['users'] = (int) M('users')->where(array('school_id'=>$this->school_id))->count();
			$counts['totay_user'] = (int) M('users')->where(array('school_id'=>$this->school_id,'reg_time' => array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->count();
			$counts['moneylogs'] = (int)M('user_money_logs')->where(array('school_id'=>$this->school_id,'create_time'=>array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->count();
			$counts['money_and'] = (int)M('users')->where(array('school_id'=>$this->school_id))->sum('money');
			$counts['money_integral'] = (int)M('users')->where(array('school_id'=>$this->school_id))->sum('integral');
			$counts['money_cash'] = (int)M('users_cash')->where(array('school_id'=>$this->school_id))->sum('money');
			$counts['money_cash_day'] = (int)M('users_cash')->where(array('school_id'=>$this->school_id,'addtime' => array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->sum('money');
			$counts['money_cash_ok'] = (int)M('users_cash')->where(array('school_id'=>$this->school_id,'status'=>1))->sum('money');
			$counts['money_cash_audit'] = (int)M('users_cash')->where(array('school_id'=>$this->school_id,'status'=>0))->count();
			
			$counts['delivery_audit_1'] = (int)M('running_delivery')->where(array('school_id'=>$this->school_id,'audit'=>1,'closed'=>0))->count();//配送员数量1
			$counts['delivery_audit_2'] = (int)M('running_delivery')->where(array('school_id'=>$this->school_id,'audit'=>2,'closed'=>0))->count();//配送员数量2
			$counts['running_Type_2_all'] = (int)M('running')->where(array('school_id'=>$this->school_id,'closed'=>0,'Type'=>2))->count();//总配送订单
			$counts['running_Type_1_all'] = (int)M('running')->where(array('school_id'=>$this->school_id,'closed'=>0,'Type'=>1))->count();//总外卖订单
			
			
			$money['money'] = (int)M('payment_logs')->where(array('school_id'=>$this->school_id,'type' =>'money','is_paid'=>1))->sum('need_pay');
			$money['running'] = (int)M('payment_logs')->where(array('school_id'=>$this->school_id,'type' =>'running','is_paid'=>1))->sum('need_pay');
			
			
			//资金统计开始
			$money['ok'] = (int)M('payment_logs')->where(array('school_id'=>$this->school_id,'is_paid'=>1))->sum('need_pay');//总付款金额
			$money['tui'] = (int)M('payment_logs')->where(array('school_id'=>$this->school_id,'is_paid'=>1,'refund_id'=>array('NEQ',NULL)))->sum('need_pay');//原路退款金总额
			
			$money['day_ok'] = (int)M('payment_logs')->where(array('school_id'=>$this->school_id,'create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time)),'is_paid'=>1))->sum('need_pay');//今日总付款金额
			//今日原路退款金总额
			$money['day_tui'] = (int)M('payment_logs')->where(array('school_id'=>$this->school_id,'create_time'=>array(array('ELT',NOW_TIME),array('EGT',$bg_time)),'is_paid'=>1,'refund_id'=>array('NEQ',NULL)))->sum('need_pay');
			
			
			$counts['profit1'] = (int)M('running_money')->where(array('school_id'=>$this->school_id))->sum('commission');//跑腿利润
			$counts['profit2'] = (int)M('shop_money')->where(array('school_id'=>$this->school_id))->sum('commission');//商家利润
			$counts['profit'] = $counts['profit1'] + $counts['profit2'];//今日结算总佣金 
			
			$counts['day_profit1'] = (int)M('running_money')->where(array('school_id'=>$this->school_id,'create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time))))->sum('commission');//今日跑腿利润
			$counts['day_profit2'] = (int)M('shop_money')->where(array('school_id'=>$this->school_id,'create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time))))->sum('commission');//今日商家利润
			$counts['day_profit'] = $counts['day_profit1'] + $counts['day_profit2'];//今日结算总佣金 
			//资金统计结束
			
			//今日分站佣金
			$counts['day_city_money'] = (int)M('user_money_logs')->where(array('school_id'=>$this->school_id,'create_time'=>array(array('ELT', NOW_TIME),array('EGT',$bg_time)),'type'=>5))->sum('money');
			//分站总佣金
			$counts['city_money'] = (int)M('user_money_logs')->where(array('school_id'=>$this->school_id,'type'=>5))->sum('money');
			
			$this->assign('money',$money);
		}else{
			$counts['users'] = (int) M('users')->count();
			$counts['totay_user'] = (int) M('users')->where(array('reg_time' => array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->count();
			$counts['user_moblie'] = (int) M('users')->where(array('mobile'=>array('EXP','IS NULL')))->count();
			$counts['user_email'] = (int) M('users')->where(array('email'=>array('EXP','IS NULL')))->count();
			$counts['user_weixin'] = (int) M('connect')->where(array('type'=>weixin))->count();
			$counts['user_weibo'] = (int) M('connect')->where(array('type'=>weibo))->count();
			$counts['user_qq'] = (int) M('connect')->where(array('type'=>qq))->count();
			$counts['user_weixin_day'] = (int) M('connect')->where(array('reg_time' => array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->count();
			$counts['moneylogs'] = (int)M('user_money_logs')->where(array('create_time'=>array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->count();
			$counts['money_and'] = (int)M('users')->sum('money');
			$counts['money_integral'] = (int)M('users')->sum('integral');
			$counts['money_cash'] = (int)M('users_cash')->sum('money');
			$counts['money_cash_day'] = (int)M('users_cash')->where(array('addtime' => array(array('ELT', NOW_TIME), array('EGT', $bg_time))))->sum('money');
			$counts['money_cash_ok'] = (int)M('users_cash')->where(array('status'=>1))->sum('money');
			$counts['money_cash_audit'] = (int)M('users_cash')->where(array('status'=>0))->count();
			
			$counts['delivery_audit_1'] = (int)M('running_delivery')->where(array('audit'=>1,'closed'=>0))->count();//配送员数量1
			$counts['delivery_audit_2'] = (int)M('running_delivery')->where(array('audit'=>2,'closed'=>0))->count();//配送员数量2
			
			$counts['running_Type_2_all'] = (int)M('running')->where(array('closed'=>0,'Type'=>2))->count();//总配送订单
			$counts['running_Type_1_all'] = (int)M('running')->where(array('closed'=>0,'Type'=>1))->count();//总外卖订单
			
			$money['money'] = (int)M('payment_logs')->where(array('type' =>'money','is_paid'=>1))->sum('need_pay');
			$money['running'] = (int)M('payment_logs')->where(array('type' =>'running','is_paid'=>1))->sum('need_pay');
			
			
			//资金统计开始
			$money['ok'] = (int)M('payment_logs')->where(array('is_paid'=>1))->sum('need_pay');//总付款金额
			$money['tui'] = (int)M('payment_logs')->where(array('is_paid'=>1,'refund_id'=>array('NEQ',NULL)))->sum('need_pay');//原路退款金总额
			
			$money['day_ok'] = (int)M('payment_logs')->where(array('create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time)),'is_paid'=>1))->sum('need_pay');//今日总付款金额
			$money['day_tui'] = (int)M('payment_logs')->where(array('create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time)),'is_paid'=>1,'refund_id'=>array('NEQ',NULL)))->sum('need_pay');//今日原路退款金总额
			
			
			$counts['profit1'] = (int)M('running_money')->sum('commission');//跑腿利润
			$counts['profit2'] = (int)M('shop_money')->sum('commission');//商家利润
			$counts['profit'] = $counts['profit1'] + $counts['profit2'];//今日结算总佣金 
			
			$counts['day_profit1'] = (int)M('running_money')->where(array('create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time))))->sum('commission');//今日跑腿利润
			$counts['day_profit2'] = (int)M('shop_money')->where(array('create_time'=>array(array('ELT',NOW_TIME), array('EGT',$bg_time))))->sum('commission');//今日商家利润
			$counts['day_profit'] = $counts['day_profit1'] + $counts['day_profit2'];//今日结算总佣金 
			//资金统计结束
			$this->assign('money',$money);
		}
		
		
		
		
		
		
		//统计数量
		$getOrderStatus = array();
		foreach($this->getOrderStatus as $k2 =>$v2){   
		    $getOrderStatus[$k2]['id'] = $k2; 
		    $getOrderStatus[$k2]['name'] = $v2; 
			if($this->school_id){
				$getOrderStatus[$k2]['count'] = (int)M('running')->where(array('school_id'=>$this->school_id,'OrderStatus'=>$k2,'closed'=>0))->count();
			}else{
				$getOrderStatus[$k2]['count'] = (int)M('running')->where(array('OrderStatus'=>$k2,'closed'=>0))->count();
			}
		  	
		}
		$this->assign('getOrderStatus',$getOrderStatus);
		
	
        //增加IP通知
        $ad['last_ip'] = $this->ipToArea($admin['last_ip']);
        $this->assign('ad', $ad);
        $v = (require BASE_PATH . '/version.php');
        $this->assign('v', $v);
        $this->assign('counts', $counts);
		
		
		
		$bg_time = time() - 86400 * 30;
		$bg_date = date('Y-m-d',$bg_time);
        $end_date = date('Y-m-d',time());
		$this->assign('bg_date', $bg_date);
        $this->assign('end_date', $end_date);
		
		$data = D('Api')->getDbHighcharts($bg_time,time(),$this->school_id,$id = '0',$db = 'users');
        $this->assign('data',$data);

		$data1 = D('Api')->getDbHighcharts($bg_time,time(),$this->school_id,$id = '0',$db = 'running');
        $this->assign('data1',$data1);
        $this->display();
    }
	
	
	//申请权限
	public function apply(){
		$admin_id = I('admin_id','','trim,htmlspecialchars');
		$action = I('action','','trim,htmlspecialchars');
		$name = I('name','','trim,htmlspecialchars');
	
		$menu = M('Menu')->where(array('menu_action'=>$action))->find();
		
		$this->layerOpen('申请授权功能还没开放<br>如果您是管理员请手动添加<br>模块名称【'.$name.'】<br>模块url【'.$action.'】<br>添加方法【http://www.hatudou.com/11234.html】','400','300',5000,$class = 'layui-layer-demo',$anim = 2);
		exit;
		
		//p($menu);die;
	 	if(M('Menu')->add($data)){
			M('Menu')->cleanCache();
			$this->tuSuccess('申请成功', U('index/index'));
		}
		$this->tuError('申请失败');
    }
	
    public function check(){
        //后期获得通知使用！
        die('1');
    }
	
	
	
}