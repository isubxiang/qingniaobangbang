<?php
class CommonAction extends Action{
    protected $uid = 0;
    protected $_CONFIG = array();
	
    protected function _initialize(){
        
        $this->_CONFIG = D('Setting')->fetchAll();
		$config = $this->_CONFIG;
       
        define('TU_DOMAIN', $this->_CONFIG['site']['hostdo']);
		define('IS_MOBILE', is_mobile());
		define('TU_HOST_PREFIX', $this->_CONFIG['site']['host_prefix']);
		
		
	    if($this->_CONFIG['site']['https'] == 1){
			define('__HOST__', 'https://' . $_SERVER['HTTP_HOST']);
		}else{
			define('__HOST__', 'http://' . $_SERVER['HTTP_HOST']);
		}

		
		$http = ($this->_CONFIG['site']['https'] == 1) ? 'https' : 'http';
		
		
        define('IN_MOBILE', true);
        $ctl = strtolower(MODULE_NAME);
        $act = strtolower(ACTION_NAME);
        $is_weixin = is_weixin();
        $is_weixin = $is_weixin == false ? false : true;
        define('IS_WEIXIN', $is_weixin);
     
        $this->uid = getUid();
		
        if(!empty($this->uid)){
            $member = $MEMBER = $this->member = D('Users')->find($this->uid);//客户端缓存会员数据
            cookie('member', $member, 86000);//cookie保存时间，建议后台设置，暂时这样修改
        }
       
        define('__HOST__', $this->_CONFIG['site']['host']);
        $this->assign('CONFIG', $this->_CONFIG);
        $this->assign('MEMBER', $this->member);
		$this->assign('connect',$connect = D('Connect')->where(array('uid'=>$this->uid,'type'=>'weixin'))->find());//客户端缓存会员数据
		
 
        $this->assign('today', TODAY);//兼容模版的其他写法
        $this->assign('nowtime', NOW_TIME);
        $this->assign('ctl', strtolower(MODULE_NAME));//主要方便调用
		
        $this->assign('act', ACTION_NAME);
        $this->assign('is_weixin', IS_WEIXIN);

		
    }
  
	
	
   
	
    private function tmplToStr($str, $datas){
        preg_match_all('/{(.*?)}/', $str, $arr);
        foreach ($arr[1] as $k => $val) {
            $v = isset($datas[$val]) ? $datas[$val] : '';
            $str = str_replace($arr[0][$k], $v, $str);
        }
        return $str;
    }
	
    public function show($templateFile = ''){
        parent::display($templateFile);
    }
	
	
    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = ''){
        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
    }
	
    private function parseTemplate($template = ''){
        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        $theme = $this->getTemplateTheme();
        define('NOW_PATH', BASE_PATH . '/themes/' . $theme . 'Wap/');
        define('THEME_PATH', BASE_PATH . '/themes/default/Wap/');
        define('APP_TMPL_PATH', __ROOT__ . '/themes/default/Wap/');
        if('' == $template){
            $template = strtolower(MODULE_NAME) . $depr . strtolower(ACTION_NAME);
        }elseif (false === strpos($template, '/')) {
            $template = strtolower(MODULE_NAME) . $depr . strtolower($template);
        }
        $file = NOW_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
        if(file_exists($file)){
            return $file;
        }
        return THEME_PATH . $template . C('TMPL_TEMPLATE_SUFFIX');
    }
	
	
	
    private function getTemplateTheme(){
        define('THEME_NAME', 'default');
        if($this->theme){
            $theme = $this->theme;
        }else{
            $theme = D('Template')->getDefaultTheme();
            if(C('TMPL_DETECT_THEME')){
                $t = C('VAR_TEMPLATE');
                if(isset($_GET[$t])){
                    $theme = $_GET[$t];
                }elseif (cookie('think_template')){
                    $theme = cookie('think_template');
                }
                if(!in_array($theme, explode(',', C('THEME_LIST')))){
                    $theme = C('DEFAULT_THEME');
                }
                cookie('think_template', $theme, 864000);
            }
        }
        return $theme ? $theme . '/' : '';
    }
	
    protected function ajaxLogin(){
        $str = '<script>';
        $str .= 'parent.ajaxLogin();';
        $str .= '</script>';
        die($str);
    }
	
    protected function checkFields($data = array(), $fields = array()){
        foreach($data as $k => $val){
            if(!in_array($k, $fields)){
                unset($data[$k]);
            }
        }
        return $data;
    }
	
	
	
    protected function ipToArea($_ip){
        return IpToArea($_ip);
    }
	
	
}