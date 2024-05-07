<?php
class ShopAction extends CommonAction{
	
	
    private $photo_create_fields = array('title', 'photo', 'orderby');
	
    public function about(){
        if($this->isPost()){
			
            $data = $this->checkFields($this->_post('data', false), array('logo','photo','addr','contact','tel','mobile','lat','lng'));
            $data['logo'] = htmlspecialchars($data['logo']);
			$data['photo'] = htmlspecialchars($data['photo']);
			
			
            $data['contact'] = htmlspecialchars($data['contact']);
            $data['tel'] = htmlspecialchars($data['tel']);
			$data['mobile'] = htmlspecialchars($data['mobile']);
            if(empty($data['mobile'])){
                $this->tuError('手机不能为空');
            }
            if(!isMobile($data['mobile'])) {
                $this->tuError('手机格式不正确');
            }
            $data['shop_id'] = $this->shop_id;
			
			
			
            $details = $this->_post('details', 'SecurityEditorHtml');
            if($words = D('Sensitive')->checkWords($details)){
                $this->tuMsg('商家介绍含有敏感词：' . $words);
            }
            $ex = array(
				'details' => $details, 
			);
            if(false !== D('Shop')->save($data)){
                D('Shopdetails')->upDetails($this->shop_id, $ex);
                $this->tuMsg('操作成功', U('shop/about'));
            }
            $this->tuMsg('操作失败');
        }else{
            $this->assign('ex', D('Shopdetails')->find($this->shop_id));
			
			$this->assign('detail',$detail = $this->shop);
			$lat = $detail['lat'];
            $lng = $detail['lng'];
            if(empty($lat) || empty($lng)){
				$lat = addslashes(cookie('lat')) ? addslashes(cookie('lat')) : $this->_CONFIG['site']['lat'];
            	$lng = addslashes(cookie('lng')) ? addslashes(cookie('lng')) : $this->_CONFIG['site']['lng'];
            }
			
            $this->assign('lat', $lat);
            $this->assign('lng', $lng);
            $this->display();
        }
    }
	
	
  

}