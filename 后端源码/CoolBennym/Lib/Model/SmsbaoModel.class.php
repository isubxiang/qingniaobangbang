<?php
class SmsbaoModel extends CommonModel{
    protected $pk   = 'sms_id';
    protected $tableName =  'sms_bao';
	
	
	 protected $type = array(
        0 => '成功',
        30 => '密码错误',
        40 => '账户不存在',
		41 => '余额不足',
		42 => '账户已过期',		
        43 => 'IP地址限制',
		50 => '内容敏感词',
		51 => '手机号不正确',
    );


	
	
	
    public function getType(){
        return $this->type;
    }
	
	
	
	//扣除短信国外接口
	public function ToUpdate($sms_id,$shop_id,$res){
		$data = array();
		$data['sms_id'] = $sms_id;
		$data['shop_id'] = $shop_id;
		$data['status'] = $res['Result'] == "Success" ? 0 : 1;
		
		$data['msg'] = $res['Result'] . ''.$res['Reason'];
		M('sms_bao')->save($data);
		
		M('sms_shop')->where(array('type'=>'shop','status'=>'0','shop_id'=>$shop_id))->setDec('num');
		return true;
	}
	
	//塞通云信
	public function ToUpdateMysubmail($sms_id,$shop_id,$res){
		$data = array();
		$data['sms_id'] = $sms_id;
		$data['shop_id'] = $shop_id;
		$data['status'] = $res['status'] == "success" ? 0 : 1;
		$data['msg'] = $res['send_id'] ? $res['send_id'] : $res['msg'].'错误编码【'.$res['code'].'】';
		M('sms_bao')->save($data);
		M('sms_shop')->where(array('type'=>'shop','status'=>'0','shop_id'=>$shop_id))->setDec('num');
		return true;
	}
	

	//短信宝短信宝
	public function ToUpdateSmsBao($sms_id,$shop_id,$res){
		$data = array();
		$data['sms_id'] = $sms_id;
		$data['shop_id'] = $shop_id;
		$data['status'] = $res;
		$data['msg'] = '';
		
		M('sms_bao')->save($data);
		
		M('sms_shop')->where(array('type'=>'shop','status'=>'0','shop_id'=>$shop_id))->setDec('num');
		
		return true;
	}
	
	
	//获取付费主体
	public function get_paying_body($sms_id){
		$detail = $this->find($sms_id);
		
		if($detail['shop_id'] > 0){
			$Shop = D('Shop')->find($detail['shop_id']);
			if($Shop){
				return $Shop['shop_name'];
			}else{
				return '平台付费';
			}
		}
		return '平台付费';
	}
	
	 
}