<?php
class LiveModel extends CommonModel{
    protected $pk = 'live_id';
    protected $tableName = 'live';
	
    protected $channelMeans = array(1 => '部门', 2 => '乡镇', 3 => '企业', 4 => '产品', 5 => '名店人', 6 => '名人');
	
	
    public function getChannel(){
        return $this->channel;
    }
	
	
	//全新改版getChannelMeans
    public function getChannelMeans(){
		
		$getConfigKey = getConfigKey('live');
		
		
		if($getConfigKey['live_channel_type'] == 1){
			$channelMeans = (explode(',',$getConfigKey['live_channel_means']));
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