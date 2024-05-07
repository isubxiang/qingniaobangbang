<?php
class SettingAction extends CommonAction{
	
	
	
    public function site(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'site', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('站点设置成功', U('setting/site'));
        }else{
            $this->assign('schools', $schools = M('running_school')->where(array('closed'=>0,'audit'=>0))->select());
			//p($schools);die;
            $this->assign('ranks', D('Userrank')->fetchAll());
            //增加分销
			
            $this->display();
        }
    }
	
	
    public function config(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'config', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('全局设置成功', U('setting/config'));
        }else{
			
            $this->display();
        }
    }
	
	
	
	
    public function attachs(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'attachs', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('附件设置成功', U('setting/attachs'));
        }else{
            $this->display();
        }
    }
	
	
	
	
	
	
   public function sms(){
		$config = D('Setting')->fetchAll();
		if(!empty($config['sms']['sms_bao_account'])){
			$http = tmplToStr('http://www.smsbao.com/query?u='.$config["sms"]["sms_bao_account"].'&p='.md5($config["sms"]["sms_bao_password"]), $local);
			
			import("@/Net.Curl");
			$this->curl = new Curl();
	
			//$res = file_get_contents($http);
			
			//如果是选择get模式
			if($config['sms']['curl'] == 'get'){
				$res = $this->curl->get($http);
				$res = json_decode($res, true);
			}else{
				$res = file_get_contents($http);
			}
			
			
			
			
			$res1 = explode(",", $res);
			if($res1[1] > 0){
				$number = $res1[1];
			}else{
				$number = '短信宝账户或者密码错了';
			}
		}else{
			$number = '短信宝账或户密码未设置';
		}
		$this->assign('number',$number);
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'sms', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('短信配置成功', U('setting/sms'));
        }else{
            $this->display();
        }
    }
	
	
	
	public function pay(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'pay', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('支付设置成功', U('setting/pay'));
        }else{
            $this->display();
        }
    }
	
	
	
    public function weixin(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'weixin', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('微信设置成功', U('setting/weixin'));
        }else{
            $this->display();
        }
    }
  
  
    public function weixinmenu(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $result = D('Weixin')->weixinmenu($data);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'weixinmenu', 'v' => $data));
            D('Setting')->cleanCache();
            if($result > 1){
				$this->tuError('菜单设置错误，错误码：'.$result);
			}else{
				$this->tuSuccess('菜单设置成功', U('setting/weixinmenu'));
			}
        }else{
            $this->display();
        }
    }
	

	
    public function integral(){
        if($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'integral', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('积分设置成功', U('setting/integral'));
        }else{
            $this->display();
        }
    }
	
	
   
    public function other(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'other', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('设置成功', U('setting/other'));
        } else {
            $this->display();
        }
    }
	

  
    public function register(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'register', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('注册成功', U('setting/register'));
        } else {
            $this->display();
        }
    }
   
    public function cash() {
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'cash', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('提现设置成功', U('setting/cash'));
        } else {
			$this->assign('ranks', D('Userrank')->fetchAll());
            $this->display();
        }
    }
   
   
    public function sms_shop(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'sms_shop', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('购买短信设置成功', U('setting/sms_shop'));
        } else {
            $this->display();
        }
    }
	public function running(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'running', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('跑腿设置成功', U('setting/running'));
        } else {
            $this->display();
        }
    }
	
    public function ele(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data['time'] = time();
            $data = serialize($data);
            D('Setting')->save(array('k' => 'ele', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('外卖更新设置成功', U('setting/ele'));
        } else {
            $this->display();
        }
    }
	
	
	
	public function shop(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data['time'] = time();
            $data = serialize($data);
            D('Setting')->save(array('k' => 'shop', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('商家全局配置成功', U('setting/shop'));
        } else {
            $this->display();
        }
    }
	
	
	
	
	
	public function wxapp(){
        if ($this->isPost()){
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'wxapp', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('小程序配置成功', U('setting/wxapp'));
        }else{
			$this->assign('list', $list = M('WeixinSetting')->order('id asc')->select());
            $this->display();
        }
    }
	
	
	
	
	public function delivery(){
        if ($this->isPost()) {
            $data = $this->_post('data', false);
            $data = serialize($data);
            D('Setting')->save(array('k' => 'delivery', 'v' => $data));
            D('Setting')->cleanCache();
            $this->tuSuccess('配送员配置成功', U('setting/delivery'));
        }else{
            $this->display();
        }
    }
	
	

	
	
}