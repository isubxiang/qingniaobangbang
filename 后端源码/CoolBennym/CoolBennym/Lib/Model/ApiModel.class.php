<?php
class ApiModel extends CommonModel{


	 //生成小程序海报
	 public function qrcodeWxapp($id,$page,$width,$parameter='postId',$scene = ''){
		$config = D('Setting')->fetchAll();
		
		$patch = BASE_PATH.'/attachs/poster/'.$parameter.'_'.$id.'.png';			
		$patch2 = '/attachs/poster/'.$parameter.'_'.$id.'.png';
		$fiel = $patch;
		$appid = $config['wxapp']['appid'];
		$srcret = $config['wxapp']['appsecret'];
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$srcret;
		$data=$this->getCurlWxapp($url);
		$data = json_decode($data,true);
		$postdata['scene']=$scene;
		$postdata['width']=$width;
		$postdata['page']=$page;
		$postdata['auto_color']=false;
		$postdata['line_color']=['r'=>'0','g'=>'0','b'=>'0'];
		$postdata['is_hyaline']=false;
		$post_data = json_encode($postdata);
		$url="https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=".$data['access_token'];
		$result =$this->postCurlWxapp($url,$post_data);
		file_put_contents($fiel,$result);
		
		$res = $config['site']['host'].$patch2;
		
		return $res;
	}
	
	
	//生成小程序海报get请求
	public function getCurlWxapp($url){
		$info=curl_init();
		curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($info,CURLOPT_HEADER,0);
		curl_setopt($info,CURLOPT_NOBODY,0);
		curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($info,CURLOPT_URL,$url);
		$output= curl_exec($info);
		curl_close($info);
		return $output;
	}
	
	//生成小程序海报post请求
	public function postCurlWxapp($url,$data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if(curl_errno($ch)){
			return false;
		}else{
			return $tmpInfo;
		}
	}

  	public function bindcode($fuid){
		$token = 'fuid_' . $fuid;
		$url = U('Wap/passport/register', array('fuid' => $fuid));
		$file = tuQrCode($token,$url,8,'user');
		return $file;
	}

   
	
	 //统计获取统计
     public function getDbHighcharts($bg_time,$end_time,$school_id,$id,$db){
      if($db == 'users'){
		$tableName = 'tu_users';  
		$pk_id = 'user_id';
		$FROM_UNIXTIME = 'reg_time';
	  }elseif($db == 'running'){
		 $tableName = 'tu_running';   
		 $pk_id = 'running_id';
		 $FROM_UNIXTIME = 'create_time';
	  }
	    
	  
	  
        if($school_id && $id){
            $data = $this->query(" SELECT count(".$pk_id .") as num,FROM_UNIXTIME(".$FROM_UNIXTIME.",'%m%d') as day from  ".$tableName." where  ".$FROM_UNIXTIME." >= '{$bg_time}' AND ".$FROM_UNIXTIME." <= '{$end_time}' and school_id='{$school_id}' and ".$pk_id."='{$id}'  group by  FROM_UNIXTIME(".$FROM_UNIXTIME.",'%m%d')");
        }elseif($id){
            $data = $this->query(" SELECT count(".$pk_id .") as num,FROM_UNIXTIME(".$FROM_UNIXTIME.",'%m%d') as day from  ".$tableName." where  ".$FROM_UNIXTIME." >= '{$bg_time}' AND ".$FROM_UNIXTIME." <= '{$end_time}' and school_id='{$school_id}'  group by  FROM_UNIXTIME(".$FROM_UNIXTIME.",'%m%d')");
		}else{
            $data = $this->query(" SELECT count(".$pk_id .") as num,FROM_UNIXTIME(".$FROM_UNIXTIME.",'%m%d') as day from  ".$tableName." where  ".$FROM_UNIXTIME." >= '{$bg_time}' AND ".$FROM_UNIXTIME." <= '{$end_time}'  group by  FROM_UNIXTIME(".$FROM_UNIXTIME.",'%m%d')");
        }
		
        $showdata = array();
        $days = array();
        for($i = $bg_time; $i<=$end_time; $i += 86400){
            $days[date('md',$i)] = '\''.date('m月d日',$i).'\''; 
        }
		
        $num = array();
        foreach($days  as $k=>$v){
            $num[$k] = 0;
            foreach($data as $val){
                if($val['day'] == $k){
                    $num[$k] = $val['num'];
                }
            }
        }
		
       $showdata['day'] = join(',',$days);
       $showdata['num'] = join(',',$num);

       return $showdata;
    }      
	
	
		
}