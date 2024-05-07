<?php
class PushModel extends CommonModel{
    protected $pk   = 'push_id';
    protected $tableName =  'push';
	
	
	protected $type = array(
        1 => '短信',
        2 => '微信',
    );
	
	protected $category = array(
        1 => '会员',
    );
	
	
    public function getType(){
        return $this->type;
    }
	public function getCategory(){
        return $this->category;
    }
	
	
	//获取那些会员是应该发送的
	public function getUserIds($category,$rank_id,$grade_id){
			if($category == 1){
				$condition['closed'] = '0';
				if($rank_id){
					$condition['rank_id'] = $rank_id;
				}
				$Users = M('Users')->where($condition)->select();
				foreach($Users as $val) {
					$user_ids[$val['user_id']] = $val['user_id'];
				}
			}
			
			if($category == 3){
				return false;
			}
			return  $user_ids;
	}
	
	
	//返回会员数组
 	public function getList($push_id){
		
		if(!$detail = $this->find($push_id)){
            return false;
        }
		
		$user_ids = $this->getUserIds($detail['category'],$detail['rank_id'],$detail['grade_id']);
		$condition['user_id'] = array('in',$user_ids);
		
		//给会员发送短信
		if($detail['type'] == 1){
			$list = M('Users')->where($condition)->select();
			foreach($list as $k => $val){
				if(!$val['mobile']){
					unset($list[$k]);
				}
			}
			return $list;
		}elseif($detail['type'] == 2){
			$list = M('Users')->where($condition)->select();
			
			foreach($list as $k => $val){
				$connect = M('connect')->where(array('type' =>'weixin','uid'=>$val['user_id']))->find();
				if(!$connect['openid']){
					unset($list[$k]);
				}
			}
			return $list;
		}else{
			$list = M('Users')->where($condition)->select();
			return $list;
		}
		return false;
    }
}