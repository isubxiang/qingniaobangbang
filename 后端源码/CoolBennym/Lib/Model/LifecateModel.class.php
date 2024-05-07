<?php
class LifecateModel extends CommonModel{
	
    protected $pk = 'cate_id';
    protected $tableName = 'life_cate';
    protected $token = 'life_cate';
    protected $orderby = array('is_hot' => 'desc', 'orderby' => 'asc');
	
    protected $channel = array('ershou' => 1, 'car' => 2, 'qiuzhi' => 3, 'love' => 4, 'house' => 5, 'peixun' => 6, 'jobs' => 7, 'service' => 8, 'jianzhi' => 9, 'chongwu' => 10);
	
    protected $channelMeans = array(1 => '二手', 2 => '车辆', 3 => '求职', 4 => '商务', 5 => '房屋', 6 => '培训', 7 => '招聘', 8 => '服务', 9 => '兼职', 10 => '宠物');
	
	
    public function getChannel(){
        return $this->channel;
    }
	
	
	
	//全新改版getChannelMeans
    public function getChannelMeans(){
		
		$getConfigKey = getConfigKey('life');
		
		
		if($getConfigKey['life_channel_type'] == 1){
			$channelMeans = (explode(',',$getConfigKey['life_channel_means']));
			foreach($channelMeans as $key=>$val){
				$newarr[$key+1]=$val;
			}
			//p($newarr);die;
			return $newarr;
		}else{
			return $this->channelMeans;
		}
    }
	
}