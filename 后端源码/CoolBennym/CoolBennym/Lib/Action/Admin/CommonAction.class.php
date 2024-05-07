<?php
class CommonAction extends Action{
    protected $_admin = array();
	protected $city_id = 0;
	protected $school_id = 0;//学校ID
	protected $school_user_id = 0;//学校会员ID
    protected $_CONFIG = array();
	
	
	
    protected function _initialize(){
		$this->_CONFIG = D('Setting')->fetchAll();
        if($this->_CONFIG['site']['https'] == 1){
			define('__HOST__', 'https://' . $_SERVER['HTTP_HOST']);
		}else{
			define('__HOST__', 'http://' . $_SERVER['HTTP_HOST']);
		}
        $this->assign('CONFIG', $this->_CONFIG);
		
		
        $this->_admin = session('admin');
		$this->_admins = cookie('admin');
		
        if(strtolower(MODULE_NAME) != 'login' && strtolower(MODULE_NAME) != 'public'){
            if(empty($this->_admin)){
                header('Location: ' . U('login/index'));
                die;
            }
            //演示账号不能操作结束
            if($this->_admin['role_id'] != 1){
				
                $this->_admin['menu_list'] = D('RoleMaps')->getMenuIdsByRoleId($this->_admin['role_id']);
				
				
				//学校详情
				$school = M('running_school')->find($this->_admin['school_id']);
				//分站的
				$this->school_id= $this->_admin['school_id'];
				$this->school_user_id = $school['user_id'] ? $school['user_id'] : $this->_admin['user_id'];
				
				/*
				if($this->_admin['user_id'] != $school['user_id']){
					$this->error('学校绑定的会员ID跟管理员列表绑定的会员ID不一致', U('login/index'));
				}
				*/
				
				
                if(strtolower(MODULE_NAME) != 'index'){
                    $menu_action = strtolower(MODULE_NAME . '/' . ACTION_NAME);
                    $menu = D('Menu')->fetchAll();
                    $menu_id = 0;
                    foreach($menu as $k => $v){
                        if(strtolower($v['menu_action']) == strtolower($menu_action)){
                            $menu_id = (int) $k;
                            break;
                        }
                    }
                }
            }
			
            $admin_user_name = D('Admin')->find($this->_admin['admin_id']);
            if($admin_user_name['is_username_lock'] == 1){
                session('admin', null);
                $this->error('您的账户已被冻结', U('login/index'));
            }
        }
		
		
       
		
		
        $this->assign('admin', $this->_admin);
		$this->assign('ROLE',$ROLE = M('Role')->find($this->_admin['role_id']));
		$this->assign('SCHOOL',$SCHOOL = M('running_school')->find($this->_admin['school_id']));
        $this->assign('today', TODAY);
        $this->assign('nowtime', NOW_TIME);
		$this->assign('ctl', strtolower(MODULE_NAME));
        $this->assign('act', ACTION_NAME);
    }
	
	
	
	
   protected function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = ''){
        parent::display($this->parseTemplate($templateFile), $charset, $contentType, $content = '', $prefix = '');
   }
   
   
   private function parseTemplate($template = ''){
        $depr = C('TMPL_FILE_DEPR');
        $template = str_replace(':', $depr, $template);
        $theme = $this->getTemplateTheme();
        define('NOW_PATH', BASE_PATH . '/themes/' . $theme . 'Admin/');
        define('THEME_PATH', BASE_PATH . '/themes/default/Admin/');
        define('APP_TMPL_PATH', __ROOT__ . '/themes/default/Admin/');
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
            $themes = D('Template')->fetchAll();
            if(C('TMPL_DETECT_THEME')){
                $t = C('VAR_TEMPLATE');
                if(isset($_GET[$t])){
                    $theme = $_GET[$t];
                }elseif(cookie('think_template')){
                    $theme = cookie('think_template');
                }
                if(!isset($themes[$theme])){
                    $theme = C('DEFAULT_THEME');
                }
                cookie('think_template', $theme, 864000);
            }
        }
        return $theme ? $theme . '/' : '';
    }
	
	
	
    protected function tuSuccess($message, $jumpUrl = '', $time = 3000){
        $str = '<script>';
        $str .= 'parent.success("' . $message . '",' . $time . ',\'jumpUrl("' . $jumpUrl . '")\');';
        $str .= '</script>';
        die($str);
    }
	
	
    protected function tuError($message, $time = 3000, $yzm = false){
        $str = '<script>';
        if($yzm){
            $str .= 'parent.error("' . $message . '",' . $time . ',"yzmCode()");';
        }else{
            $str .= 'parent.error("' . $message . '",' . $time . ');';
        }
        $str .= '</script>';
        die($str);
    }
	
	
	protected function layerOpen($content, $width = '300', $height = '300'){
        $str = '<script>';
        $str .= 'parent.layeropen("' . $content . '",' . $width . ');';
        $str .= '</script>';
        die($str);
    }
	
	
	
	//学校搜索组合封装
	protected function getSchoolId($_school_id){
		
		$school_id = (int) $this->_param('school_id');
		
	
		
		if($school_id){
			if($_school_id){
				$map['school_id'] = $_school_id;
			}else{
				$map['school_id'] = $school_id;
			}
        }else{
			if($_school_id){
				$map['school_id'] =$_school_id;
			}else{
				$map['school_id'] =0;
			}
		}
	    return $map['school_id'];
	}
	
	
	//分站城市搜索组合封装
	protected function getSearchCityId($_city_id){
		$city_id = (int) $this->_param('city_id');
		if($city_id){
			if($_city_id){
				$map['city_id'] = $_city_id;
			}else{
				$map['city_id'] = $city_id;
			}
        }else{
			if($_city_id){
				$map['city_id'] =$_city_id;
			}else{
				$map['city_id'] =0;
			}
		}
	    return $map['city_id'];
	}
	
	
	//搜索时间
	protected function getSearchDate(){
		if(($bg_date = I('bg_date','', 'htmlspecialchars')) && ($end_date = I('end_date','', 'htmlspecialchars'))){
            $bg_time = strtotime($bg_date);
            $end_time = strtotime($end_date);
            $map = array(array('ELT', $end_time), array('EGT', $bg_time));
            $this->assign('bg_date', $bg_date);
            $this->assign('end_date', $end_date);
        }else{
            if($bg_date = I('bg_date','', 'htmlspecialchars')){
                $bg_time = strtotime($bg_date);
                $this->assign('bg_date', $bg_date);
                $map = array('EGT', $bg_time);
            }
            if($end_date = I('end_date','', 'htmlspecialchars')){
                $end_time = strtotime($end_date);
                $this->assign('end_date', $end_date);
                $map = array('ELT', $end_time);
            }
        }
		return $map;
	}
	
	
	protected function getSearchShopId($city_id){
		$shop_id = (int) $this->_param('shop_id');
		$list = D('Shop')->where(array('city_id'=>$city_id))->select();
		foreach($list as $val){
			$shop_ids[$val['shop_id']] = $val['shop_id'];
		}
		if($shop_id){
			if($city_id){
				$map['shop_ids'] = $shop_ids;
			}else{
				$map['shop_id'] = $shop_id;
			}
        }else{
			if($city_id){
				$map['shop_ids'] = $shop_ids;
			}
		}
	    return $map;
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
        return iptoarea($_ip);
    }
}