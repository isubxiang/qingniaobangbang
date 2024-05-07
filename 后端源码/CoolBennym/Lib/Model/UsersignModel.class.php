<?php
class  UsersignModel extends CommonModel{
     protected $pk   = 'user_id';
     protected $tableName =  'user_sign';
    
     public function getSign($user_id,$integral = false){
         $user_id = (int)$user_id;
		 $last_day_time = strtotime(date("Y-m-d 00:00:00"))-86400;
		 $last_time = strtotime(date("Y-m-d 00:00:00"));
		 
		// p($last_day_time);
		//p($last_time);die;
		
		 $res = $this->where(array('user_id'=>$user_id))->order('sign_id desc')->find();
		 $is_first = ($res['sign_id'] > 1) ? 0 :1;
		 
         if(!$data = $this->where(array('user_id'=>$user_id,'last_time'=>$last_day_time))->order('sign_id desc')->find()){
             $data = array(
                 'user_id' => $user_id,
                 'day'  => 0,
                 'last_time' => $last_day_time,
                 'is_first' => $is_first
             );
             $this->add($data);
         }
		 
		 $datas = $this->where(array('user_id'=>$user_id))->order('sign_id desc')->find();
		 
         if($integral!==false){ //返回明日登录积分 及 今天是否登录的状态
             $day=$datas['day'] == 0 ? $datas['day'] + 2 : $datas['day']+1;
             if($day > 1){
                 $integral+=$day; //加上连续登陆的天数
             }
			 
             $datas['integral'] = $integral;
             $lastdate = date('Y-m-d',$datas['last_time']);
			 
             if($lastdate  == TODAY){ 
                 $datas['is_sign'] = 1;
             }else{
                 $datas['is_sign'] = 0;
             }
         }
		 // p($datas);die;
         return $datas;
     }
     
     public function sign($user_id,$integral,$firstintegral = 0){ 
         $user_id = (int)$user_id;
         $integral = (int) $integral;
         $data = $this->getSign($user_id);
         $lastdate = date('Y-m-d',$data['last_time']);
		 
		 $last_time = strtotime(date("Y-m-d 00:00:00"));
		
		 if($res = $this->where(array('user_id'=>$user_id,'last_time'=>$last_time ))->find()){
			return false; 
		 }
		  
		
		  
		  
         if($lastdate < TODAY){ 
             if(NOW_TIME - $data['last_time'] > 86400){//隔天了
                 $data['day']+=1;
             }else{
				 $data['day'] =  1;
                 
             }
			 
             if($data['day'] > 1){
                 $integral+=$data['day']; //加上连续登陆的天数
             }
             $is_first = false;
             if($data['is_first']){
                 $is_first = true;
                 $data['is_first'] = 0;
             }
             $data['last_time'] = strtotime(date("Y-m-d 00:00:00"));
			 
			 unset($data['sign_id']);
			 
             if($this->add($data)){
                 $return = $integral;
                if($is_first){
                   D('Users')->addIntegral($user_id,$firstintegral,'首次签到');     
                   $return += $firstintegral;
                }
                D('Users')->addIntegral($user_id,$integral,TODAY.'手机签到');             
                return $return;
             }
             return false;
         }
         return false;
     }
     
     
    
    
}