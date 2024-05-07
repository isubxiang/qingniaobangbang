<?php

class ThreadModel extends CommonModel{
    protected $pk   = 'thread_id';
    protected $tableName =  'thread';

    //置顶过期
	public function updateTopDate($post_id = 0){
		
		$map = array('top_date'=>array('elt',TODAY));
		$list = M('thread')->where($map)->order('create_time desc')->select();
		foreach($list as $k => $val){
			M('thread')->where(array('post_id'=>$val['post_id']))->save(array('is_top'=>'0'));
		}
		return true;
	}


	//获取名字
	public function comments_get_thread_name($post_id){
        $detail = $this->where(array('post_id'=>$post_id,'closed'=>0))->find();
        if(!empty($detail)){
		   return $detail['thread_name'];
		}else{
		   return '该部落不存在';   
	    }
    }
    
}