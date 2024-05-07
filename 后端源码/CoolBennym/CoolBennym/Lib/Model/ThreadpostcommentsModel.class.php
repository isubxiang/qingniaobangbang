<?php

class ThreadpostcommentsModel extends CommonModel{
    protected $pk   = 'comment_id';
    protected $tableName =  'thread_post_comments';
	
	
	
	 public function tjmonthCount($month = '',$user_id = ''){
        $sql = "";
        $month = $month ? str_replace('-', '', $month): '';
        $user_id = (int)$user_id;
        if($month && $user_id){

            $sql="select count(1) as num from (SELECT sum(money) as money,FROM_UNIXTIME(create_time,'%Y%m') as m,user_id  FROM ".$this->getTableName()." where FROM_UNIXTIME(create_time,'%Y%m') = '{$month}' and user_id='{$user_id}' group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id)tb  ";         
        }else{
            if($month){

                $sql=" select count(1) as num from (SELECT sum(money) as money,FROM_UNIXTIME(create_time,'%Y%m') as m,user_id  FROM ".$this->getTableName()." where FROM_UNIXTIME(create_time,'%Y%m') = '{$month}'  group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id)tb  ";         

            }elseif($user_id){
                $sql=" select count(1) as num from (SELECT sum(money) as money,FROM_UNIXTIME(create_time,'%Y%m')  as m,user_id  FROM ".$this->getTableName()." where user_id='{$user_id}'   group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id)tb  ";         
            }else{
                $sql=" select count(1) as num from (SELECT sum(money) as money,FROM_UNIXTIME(create_time,'%Y%m')  as m,user_id  FROM ".$this->getTableName()."    group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id)tb  ";         

            }
        }

        $data = $this->query($sql);
        return (int)$data[0]['num'];


    }

    

    public function tjmonth($month = '',$user_id = '',$start,$num){
        $sql = "";
        $month = $month ? str_replace('-', '', $month): '';
        $start = (int)$start;
        $num = (int)$num;
        $user_id = (int)$user_id;
        if($month && $user_id){
            $sql="SELECT count(1) as money,FROM_UNIXTIME(create_time,'%Y%m') as m,user_id  FROM ".$this->getTableName()." where FROM_UNIXTIME(create_time,'%Y%m') = '{$month}' and user_id='{$user_id}' group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id order by FROM_UNIXTIME(create_time,'%Y%m') desc  limit {$start},{$num} ";         
        }else{
            if($month){
                $sql="SELECT count(1) as money,FROM_UNIXTIME(create_time,'%Y%m') as m,user_id  FROM ".$this->getTableName()." where FROM_UNIXTIME(create_time,'%Y%m') = '{$month}'  group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id  order by FROM_UNIXTIME(create_time,'%Y%m') desc   limit {$start},{$num}  ";         
            }elseif($user_id){
                $sql="SELECT count(1) as money,FROM_UNIXTIME(create_time,'%Y%m')  as m,user_id  FROM ".$this->getTableName()." where user_id='{$user_id}'   group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id  order by FROM_UNIXTIME(create_time,'%Y%m') desc   limit {$start},{$num} ";         
            }else{
                $sql="SELECT count(1) as money,FROM_UNIXTIME(create_time,'%Y%m')  as m,user_id  FROM ".$this->getTableName()."    group  by  FROM_UNIXTIME(create_time,'%Y%m'),user_id  order by FROM_UNIXTIME(create_time,'%Y%m') desc  limit {$start},{$num}  ";         
            }
        }
        $data = $this->query($sql);
        return $data;
    }
    
    
     
}