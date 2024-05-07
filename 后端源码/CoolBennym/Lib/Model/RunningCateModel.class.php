<?php
class RunningCateModel extends CommonModel{
    protected $pk = 'cate_id';
    protected $tableName = 'running_cate';
    protected $token = 'running_cate';
    protected $orderby = array('is_hot' => 'desc', 'orderby' => 'asc');
	
    protected $channel = array('ershou' => 1, 'car' => 2, 'qiuzhi' => 3, 'love' => 4, 'house' => 5, 'peixun' => 6, 'jobs' => 7, 'service' => 8, 'jianzhi' => 9, 'chongwu' => 10);
	
    protected $channelMeans = array(
		1 => '二手', 
		2 => '车辆', 
		3 => '求职', 
		4 => '商城', 
		5 => '房屋', 
		6 => '培训', 
		7 => '招聘', 
		8 => '服务', 
		9 => '兼职', 
		10 => '宠物'
	);
	
	
    public function getChannel(){
        return $this->channel;
    }
	
	
    //全新改版
    public function getChannelMeans(){
		$channelMeans = (explode(',',C('cfg_channelname')));
		foreach($channelMeans as $key=>$val){
			$newarr[$key+1]=$val;
		}
        return $newarr;
    }
}