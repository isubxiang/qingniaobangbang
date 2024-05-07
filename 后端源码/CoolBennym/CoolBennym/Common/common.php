<?php

$getConfigKey = getConfigKey('site');
define('__HOST__',$getConfigKey['host']);

function getConfigKey($key = 'site'){
    $result = D('Setting')->where(array('k'=>$key))->find();
	$data = unserialize($result['v']);
    return $data;
}


function setUid($uid){
    import("ORG/Crypt/Base64");
    $uid = 'USER_'.$uid.'_'.NOW_TIME;
    $uid = Base64::encrypt($uid, C('AUTH_KEY'));
    cookie('HATUDOU_TOKEN',$uid,86400 * 365); //存2小时
    return true;
}
function clearUid(){
    cookie('HATUDOU_TOKEN',null); 
    return true;
}
//不清楚是什么东西
function getUid(){
    import("ORG/Crypt/Base64");
    $token = cookie('HATUDOU_TOKEN');
    $token = Base64::decrypt($token, C('AUTH_KEY'));
    $token = explode('_', $token);
    if($token[0]!= 'USER') return 0;
    return (int)$token[1];
}

function export_csv($filename,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');   
    echo $data;die;   
} 


function searchWordFrom() { //主要方便存入COOKIE（跟踪一个月）
    
    if(!empty($_GET['tuiyitui'])){//全局的推广连接可以 主要是投放广告等监控使用
        $keyword = htmlspecialchars($_GET['tuiyitui']);
        $from = 'tui';//推广
        cookie('tui_from',$keyword,30*86400);//存放在COOKIE 一个月
    }
    
    $referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
    if(strstr( $referer, 'baidu.com')){ //百度
        preg_match( "|baidu.+wo?r?d=([^\&]*)|is", $referer, $tmp );
        $keyword = htmlspecialchars(urldecode( $tmp[1] ));
        $from = 'baidu';
    }elseif(strstr( $referer, 'google.com') or strstr( $referer, 'google.cn')){ //谷歌
        preg_match( "|google.+q=([^\&]*)|is", $referer, $tmp );
        $keyword = htmlspecialchars(urldecode( $tmp[1] ));
        $from = 'google';
    }elseif(strstr( $referer, 'soso.com')){ //搜搜
        preg_match( "|soso.com.+w=([^\&]*)|is", $referer, $tmp );
        $keyword = htmlspecialchars(urldecode( $tmp[1] ));
        $from = 'soso';
    }elseif(strstr( $referer, 'so.com')){ //360搜索
        preg_match( "|so.+q=([^\&]*)|is", $referer, $tmp );
        $keyword = htmlspecialchars(urldecode( $tmp[1] ));
        $from = '360';  
    }elseif(strstr( $referer, 'sogou.com')){ //搜狗
        preg_match( "|sogou.com.+query=([^\&]*)|is", $referer, $tmp );
        $keyword = htmlspecialchars(urldecode( $tmp[1] ));
        $from = 'sogou';    
    }else{
        return false;
    }
    cookie('search_word_from',json_encode(array('keyword'=>$keyword,'from'=>$from)),30*86400);//存放在COOKIE 一个月
    return true;
}


require_cache(APP_PATH.'Lib/phpqrcode/phpqrcode.php'); //引入二维码生成图片
//tuQrCode
function tuQrCode($token,$url,$size = 8,$patchs=''){ //生成网址的二维码 返回图片地址
	$name = date('Y/m/d/', NOW_TIME);
    $md5 = md5($token);
	
	//如果有定义路径
	if($patchs){
		$patch =BASE_PATH.'/attachs/'.$patchs.'/'.$name;
	}else{
		$patch =BASE_PATH.'/attachs/'. 'weixin/'.$name;
	}
	
    if(!file_exists($patch)){
        mkdir($patch,0755,true);
    }
	
	if($patchs){
		//如果有定义
		$file = '/attachs/'.$patchs.'/'.$name.$md5.'.png';
    	$fileName  = BASE_PATH.''.$file;
	}else{
		$file = '/attachs/weixin/'.$name.$md5.'.png';
    	$fileName  =BASE_PATH.''.$file;
	}
	

    if(!file_exists($fileName)){
        $level = 'L';
        if(strstr($url,__HOST__)){
            $data = $url;
        }else{
            $data =__HOST__. $url;
        }
        QRcode::png($data, $fileName, $level, $size,2,true);
    }
    return $file;
}

//生成支付二维码
function buildCode($token,$url2){
		$config = D('Setting')->fetchAll();
		$name = date('Y/m/d/',time());
		$md5 = md5($token);
		$patch =BASE_PATH.'/attachs/'.'weixin/'.$name;
		if(!file_exists($patch)){
			mkdir($patch,0755,true);
		}
		$file = '/attachs/weixin/'.$name.$md5.'.png';
		$fileName  =BASE_PATH.''.$file;
		if(!file_exists($fileName)){
			$level = 'L';
			QRcode::png($url2,$fileName,$level,$size = 8,2,true);
		}
		return $file; 
	}


function ToQrCode($token,$url,$size,$patchs=''){ 
	$name = date('Y/m/d/', NOW_TIME);
    $md5 = md5($token);
	
	if($patchs){
		$patch =BASE_PATH.'/attachs/'.$patchs.'/'.$name;
	}else{
		$patch =BASE_PATH.'/attachs/'. 'weixin/'.$name;
	}
	

    if(!file_exists($patch)){
        mkdir($patch,0755,true);
    }
	
   if($patchs){
		//如果有定义
		$file = '/attachs/'.$patchs.'/'.$name.$md5.'.png';
    	$fileName  = BASE_PATH.''.$file;
	}else{
		$file = '/attachs/weixin/'.$name.$md5.'.png';
    	$fileName  =BASE_PATH.''.$file;
	}
	
	
    if(!file_exists($fileName)){
        $level = 'L';
        if(strstr($url,__HOST__)){
            $data = $url;
        }else{
            $data =__HOST__. $url;
        }
        QRcode::png($data, $fileName, $level, $size,2,true);
    }
    return $file;
}
function is_mobile() { 
    $user_agent = $_SERVER['HTTP_USER_AGENT']; 
    $mobile_agents = array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi", 
    "android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio", 
    "au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu", 
    "cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ", 
    "fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi", 
    "htc","huawei","hutchison","inno","ipad","ipaq","iphone","ipod","jbrowser","kddi", 
    "kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo", 
    "mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-", 
    "moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia", 
    "nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-", 
    "playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo", 
    "samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank", 
    "sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit", 
    "tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin", 
    "vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce", 
    "wireless","xda","xde","zte"); 
    $is_mobile = false; 
    foreach ($mobile_agents as $device) { 
        if (stristr($user_agent, $device)) { 
            $is_mobile = true; 
            break; 
        } 
    } 
    return $is_mobile; 
} 

function is_weixin() {
    return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
}

function is_miniprogram(){
	$miniprogram = strpos($_SERVER['HTTP_USER_AGENT'], 'miniprogram');
	if(!$miniprogram){
		$miniprogram = strpos($_SERVER['HTTP_USER_AGENT'], 'miniProgram');
	}
    return $miniprogram;
}



function is_QQBrowser() {
    return strpos($_SERVER['HTTP_USER_AGENT'], 'QQBrowser');
}
function isWx() {
    return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
}

function isMail($email) {
	$pattern = "/^[a-zA-Z][a-zA-z0-9-]*[@]([a-zA-Z0-9]*[.]){1,3}[a-zA-Z]*/";
	if(preg_match($pattern,$email)!= 1){
		return false;
	}else{
		return true;
	}
}
//验证邮箱函数
function is_valid_email($email, $test_mx = false){
    if(eregi("^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
        if($test_mx){
            list($username, $domain) = split("@", $email);
            return getmxrr($domain, $mxrecords);
        }
        else
            return true;
    else
        return false;
}
function isIos(){
	$is_iphone = (strpos($agent, 'iphone')) ? true : false; 
	$is_ipad = (strpos($agent, 'ipad')) ? true : false;  
	if($is_iphone==true || $is_ipad == true){
		return true;
	}else{
		return false;
	}
}
   


//专门给含有HTML的字段
function niuMsubstr($str,$start,$length,$suffix){
    
   $str = preg_replace( "@<(.*?)>@is", "", $str);
   return   msubstr($str, $start, $length, 'utf-8', $suffix);
}



//专门给含有HTML的字段
function tu_msubstr($str,$start,$length,$suffix){
    
   $str = preg_replace( "@<(.*?)>@is", "", $str);
   return   msubstr($str, $start, $length, 'utf-8', $suffix);
}


function isSecondDomain($domain){
    return (boolean) preg_match('/^[a-z0-9]{4,10}$/i', $domain);
}

function getDomain($url) {
    $host = strtolower($url);
    if (strpos($host, '/') !== false) {
        $parse = @parse_url($host);
        $host = $parse ['host'];
    }
    $topleveldomaindb = array('com', 'edu', 'gov', 'int', 'mil', 'net', 'org', 'biz', 'info', 'pro', 'name', 'museum', 'coop', 'aero', 'xxx', 'idv', 'mobi', 'cc', 'me');
    $str = '';
    foreach ($topleveldomaindb as $v) {
        $str .= ($str ? '|' : '') . $v;
    }

    $matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))$";
    if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
        $domain = $matchs ['0'];
    } else {
        $domain = $host;
    }
    return $domain;
}



//时间格式化2
function formatTime($time) {

    $t = NOW_TIME - $time;
    $mon = (int) ($t / (86400 * 30));
	if ($mon >= 12) {
        return '一年前';
    }
	if ($mon >= 6) {
        return '半年前';
    }
	if ($mon >= 3) {
        return '三个月前';
    }
	if ($mon >= 2) {
        return '二个月前';
    }
    if ($mon >= 1) {
        return '一个月前';
    }
    $day = (int) ($t / 86400);
    if ($day >= 1) {
        return $day . '天前';
    }
    $h = (int) ($t / 3600);
    if ($h >= 1) {
        return $h . '小时前';
    }
    $min = (int) ($t / 60);
    if ($min >= 1) {
        return $min . '分前';
    }
    return '刚刚';
}

//时间格式化2
function pincheTime($time) {
	  $today  =  strtotime(date('Y-m-d')); //今天零点
      $here  =  (int)(($time - $today)/86400) ; 
	  if($here==1){
		  return '明天';  
	  }
	  if($here==2) {
		  return '后天';  
	  }
	  if($here>=3 && $here<7){
		  return $here.'天后';  
	  }
	  if($here>=7 && $here<30){
		  return '一周后';  
	  }
	  if($here>=30 && $here<365){
		  return '一个月后';  
	  }
	  if($here>=365){
		  $r = (int)($here/365).'年后'; 
		  return   $r;
	  }
	 return '今天';
}

//时间格式化2
function ele_wait_Time($time) {
    $mon = (int) ($time / (86400 * 30));
	if ($mon >= 12) {
        return '一年前';
    }
	if ($mon >= 6) {
        return '半年前';
    }
	if ($mon >= 3) {
        return '三个月前';
    }
	if ($mon >= 2) {
        return '二个月前';
    }
    if ($mon >= 1) {
        return '一个月前';
    }
    $day = (int) ($time / 86400);
    if ($day >= 1) {
        return $day . '天';
    }
    $h = (int) ($time / 3600);
    if ($h >= 2) {
        return $h . '小时';
    }
    $min = (int) ($time / 60);
    if ($min >= 1) {
        return $min . '分钟';
    }
    return '0分钟';
}
/*
 * 经度纬度 转换成距离
 * $lat1 $lng1 是 数据的经度纬度
 * $lat2,$lng2 是获取定位的经度纬度
 */

function rad($d) {
    return $d * 3.1415926535898 / 180.0;
}

function getDistanceNone($lat1, $lng1, $lat2, $lng2) {
    $EARTH_RADIUS = 6378.137;
    $radLat1 = rad($lat1);
    //echo $radLat1;  
    $radLat2 = rad($lat2);
    $a = $radLat1 - $radLat2;
    $b = rad($lng1) - rad($lng2);
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * $EARTH_RADIUS;
    $s = round($s * 10000);
    return $s;
}

//外卖专用检测地址
function getAddrDistance($lat1, $lng1, $lat2, $lng2) {
    $s = getDistanceNone($lat1, $lng1, $lat2, $lng2);
    $s = $s / 10000;
    return round($s * 1000);
}



function getDistance($lat1, $lng1, $lat2, $lng2) {
    $s = getDistanceNone($lat1, $lng1, $lat2, $lng2);
    $s = $s / 10000;
    if ($s < 1) {
        $s = round($s * 1000);
        $s.='m';
    } else {
        $s = round($s, 2);
        $s.='km';
    }
    return $s;
}


function getDistance2($lat1, $lng1, $lat2, $lng2) {
    $s = getDistanceNone($lat1, $lng1, $lat2, $lng2);
    $s = $s / 10000;
    if ($s < 1) {
        $s = round($s * 1000);
        $s.='m';
    } else {
        $s = round($s, 2);
        $s.='km';
    }
    return $s;
}


function getDistanceCN($lat1, $lng1, $lat2, $lng2) {
    $s = getDistanceNone($lat1, $lng1, $lat2, $lng2);
    $s = $s / 10000;
    if ($s < 1) {
        $s = round($s * 1000);
        $s.='米';
    } else {
        $s = round($s, 2);
        $s.='千米';
    }
    return $s;
}


//空白区域插件
function block($id) {
    if (!defined('THEME_NAME'))
        return '';
    $token = 'tu_' . THEME_NAME . '_' . $id;
    $cache = cache(array('type' => 'File', 'expire' => 600)); //10分钟缓存
    if (!$content = $cache->get($token)) {
        $settings = D('Templatesetting')->getWidgetsByThemeBlock(THEME_NAME, $id);
        if (empty($settings))
            return '';
        $content = '';
        foreach ($settings as $data) {
            $cfg = $data['setting'];
            $cfg['setting_id'] = $data['setting_id'];
            $content.= W($data['widget'], $cfg, true);
        }
        $cache->set($token, $content);
    }
    return $content;
}

function tmplToStr($str, $datas) {
    preg_match_all('/{(.*?)}/', $str, $arr);
    foreach ($arr[1] as $k => $val) {
        $v = isset($datas[$val]) ? $datas[$val] : '';
        $str = str_replace($arr[0][$k], $v, $str);
    }
    return $str;
}

function arrayToObject($e) {
    if (gettype($e) != 'array')
        return;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object')
            $e[$k] = (object) arrayToObject($v);
    }
    return (object) $e;
}

function objectToArray($e) {
    $e = (array) $e;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'resource')
            return;
        if (gettype($v) == 'object' || gettype($v) == 'array')
            $e[$k] = (array) objectToArray($v);
    }
    return $e;
}

function delFileByDir($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
           
            $fullpath = $dir . "/" . $file;
            if(is_dir($fullpath)) {
                delFileByDir($fullpath);
            }else{
                unlink($fullpath);
            }
        }
    }
    closedir($dh);
}

function getDirName($dir) {
    $dh = opendir($dir);
    $return = array();
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (is_dir($fullpath)) {
                $return[$file] = $file;
            }
        }
    }
    closedir($dh);
    return $return;
}



function LinkTo($ctl, $vars = array(),$var2=array()) {
    $vars = array_merge($vars,$var2);
    foreach ($vars as $k => $v) {
        if (empty($v))
            unset($vars[$k]);
    }
    return U($ctl, $vars);
}
//获取IP返回地址的函数
function IpToArea($_ip) {
    static $IpLocation;
    if(empty($IpLocation)){
         import('ORG.Net.IpLocation'); // 
         $IpLocation = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
    }
    $arr = $IpLocation->getlocation($_ip);
 
    return $arr['country'] . $arr['area'];
}

/**
 * 分站后台模版带权限的URL校验
 * @param string $url URL表达式，格式：'[分组/模块/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string $title  标题
 * @param string $mini  是否异步加载
 * @param string $class A标签样式
 * @param string|array  $vars 传入的参数，支持数组和字符串
 * @return string
 */
function TUFZBA($url = '', $vars = '', $title = '', $mini = "", $class = "", $width = '', $height = '') {
    $url = U($url, $vars);

    //权限判断 暂时忽略，后面补充
    $m = $c = $h = $w = '';
    if (!empty($mini)) {
        $m = ' mini="' . $mini . '"  ';
    }
    if (!empty($class)) {
        $c = ' class="' . $class . ' " ';
    }
    if (!empty($width)) {
        $w = ' w="' . $width . ' " ';
    }
    if (!empty($width)) {
        $h = ' h="' . $height . ' " ';
    }
    return '<a href="' . $url . '" ' . $m . $c . $w . $h . ' >' . $title . '</a>';
}


/**
 * 后台模版带权限的URL校验
 * @param string $url URL表达式，格式：'[分组/模块/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string $title  标题
 * @param string $mini  是否异步加载
 * @param string $class A标签样式
 * @param string|array  $vars 传入的参数，支持数组和字符串
 * @return string
 */
 
 
function BA($url = '', $vars = '', $title = '', $mini = "", $class = "", $width = '', $height = ''){
	$config = D('Setting')->fetchAll();
	static $admin;
    if(empty($admin)){
		$admin = session('admin');
		//$admins = cookie('admin');//cookie
		$admin['menu_list'] = D('RoleMaps')->getMenuIdsByRoleId($admin['role_id']);
    }
	if($admin['role_id'] != 1){
        $menu = D('Menu')->fetchAll();
        $menu_id = 0;
        foreach($menu as $k => $v){
            if(strtolower($v['menu_action']) == strtolower($url)){
                $menu_id = (int) $k;
            }
        }
        $url = U($url, $vars);
    }else{
        $url = U($url, $vars);
    }

    //权限判断 暂时忽略，后面补充
    $m = $c = $h = $w = '';
    if(!empty($mini)){
        $m = ' mini="' . $mini . '"  ';
    }
    if(!empty($class)){
        $c = ' class="' . $class . ' " ';
    }
    if(!empty($width)){
        $w = ' w="' . $width . ' " ';
    }
    if(!empty($width)){
        $h = ' h="' . $height . ' " ';
    }

    return '<a data-menu-action="'.$menu_action.'"  data-menu-name="'.$menu_name.'" href="' . $url . '" ' . $m . $c . $w . $h . ' >' . $title . '</a>';
}





/**
 * 过滤不安全的HTML代码
 */
function SecurityEditorHtml($str) {
    $farr = array(
        "/\s+/", //过滤多余的空白 
        "/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",
        "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU"
    );
    $tarr = array(
        " ",
        "＜\\1\\2\\3＞",
        "\\1\\2",
    );
    $str = preg_replace($farr, $tarr, $str);
    return $str;
}

/**
 * 判断一个字符串是否是一个Email地址
 *
 * @param string $string
 * @return boolean
 */
function isEmail($string) {
    return (boolean) preg_match('/^[a-z0-9.\-_]{2,64}@[a-z0-9]{2,32}(\.[a-z0-9]{2,5})+$/i', $string);
}

/**
 * 检查是否为一个合法的时间格式
 *
 * @access  public
 * @param   string  $time
 * @return  void
 */
function isTime($time) {
    $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

    return preg_match($pattern, $time);
}

/**
 * 判断一个字符串是否是一个合法时间
 *
 * @param string $string
 * @return boolean
 */
function isDate($string) {
    if (preg_match('/^\d{4}-[0-9][0-9]-[0-9][0-9]$/', $string)) {
        $date_info = explode('-', $string);
        return checkdate(ltrim($date_info[1], '0'), ltrim($date_info[2], '0'), $date_info[0]);
    }
    if (preg_match('/^\d{8}$/', $string)) {
        return checkdate(ltrim(substr($string, 4, 2), '0'), ltrim(substr($string, 6, 2), '0'), substr($string, 0, 4));
    }
    return false;
}

/**
 * 判断输入的字符串是否是一个合法的电话号码（仅限中国大陆）
 *
 * @param string $string
 * @return boolean
 */
function isPhone($string) {
    if (preg_match('/^[0,4]\d{2,3}-\d{7,8}$/', $string))
        return true;
    return false;
}

/**
 * 判断输入的字符串是否是一个合法的手机号(仅限中国大陆)
 *
 * @param string $string
 * @return boolean
 */


function isMobile($string) {
    if(!is_numeric($string)){
        return false;
    }
	if(!ctype_digit($string)){
		return false;
	}
	if(11 != strlen($string)){
		return false;
	}
	if($string[0] != 1){
		return false;
	}
	if(preg_match('/^1[3456789]{1}\d{9}$/', $string)){
		return true;
	}
	return false;
}



/**
 * 判断输入的字符串是否是一个合法的QQ
 *
 * @param string $string
 * @return boolean
 */
function isQQ($string) {
    if (ctype_digit($string)) {
        $len = strlen($string);
        if ($len < 5 || $len > 13)
            return false;
        return true;
    }
    return isEmail($string);
}

/**
 *
 * @param string $fileName
 * @return boolean
 
function isImage($fileName) {
    $ext = explode('.', $fileName);
    $ext_seg_num = count($ext);
    if ($ext_seg_num <= 1)
        return false;

    $ext = strtolower($ext[$ext_seg_num - 1]);
    return in_array($ext, array('jpeg', 'jpg', 'png', 'gif'));
}
*/
function isImage($fileName) {
    $ext = explode('.', $fileName);
    $ext_seg_num = count($ext);
    if ($ext_seg_num <= 1)
        return false;

    $ext = strtolower($ext[$ext_seg_num - 1]);
    $nort = in_array($ext, array('jpeg', 'jpg', 'png', 'gif'));
    $hext = explode('?', $ext);
    $httt = in_array($hext[0], array('jpeg', 'jpg', 'png', 'gif'));
    if($nort || $httt){
        return true;
    }else{
        return false;
    }
}
/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len = 6, $type = '', $addChars = '') {
    $str = '';
    switch ($type) {
        case 0:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1:
            $chars = str_repeat('0123456789', 3);
            break;
        case 2:
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3:
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 4:
            $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    if ($len > 10) {//位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    if ($type != 4) {
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
    } else {
        // 中文随机字
        for ($i = 0; $i < $len; $i++) {
            $str.= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
        }
    }
    return $str;
}

/**
 * 获取登录验证码 默认为4位数字
 * @param string $fmode 文件名
 * @return string
 */
function build_verify($length = 4, $mode = 1) {
    return rand_string($length, $mode);
}

/**
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec = 2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size, $dec) . " " . $a[$pos];
}

/**
 * 检查字符串是否是UTF8编码
 * @param string $string 字符串
 * @return Boolean
 */
function is_utf8($string) {
    return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    )*$%xs', $string);
}

/**
 * 代码加亮
 * @param String  $str 要高亮显示的字符串 或者 文件名
 * @param Boolean $show 是否输出
 * @return String
 */
function highlight_code($str, $show = false) {
    if (file_exists($str)) {
        $str = file_get_contents($str);
    }
    $str = stripslashes(trim($str));
    $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);
    $str = str_replace(array('&lt;?php', '?&gt;', '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);
    $str = '<?php //tempstart' . "\n" . $str . '//tempend ?>'; // <?
    $str = highlight_string($str, TRUE);
    if (abs(phpversion()) < 5) {
        $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
        $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
    }
    $str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
    $str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
    $str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);
    $str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
    $line = explode("<br />", rtrim(ltrim($str, '<code>'), '</code>'));
    $result = '<div class="code"><ol>';
    foreach ($line as $key => $val) {
        $result .= '<li>' . $val . '</li>';
    }
    $result .= '</ol></div>';
    $result = str_replace("\n", "", $result);
    if ($show !== false) {
        echo($result);
    } else {
        return $result;
    }
}

//输出安全的html
function h($text, $tags = null) {
    $text = trim($text);
    $text = preg_replace('/<!--?.*-->/', '', $text);
    $text = preg_replace('/<\?|\?' . '>/', '', $text);
    $text = preg_replace('/<script?.*\/script>/', '', $text);
    $text = str_replace('[', '&#091;', $text);
    $text = str_replace(']', '&#093;', $text);
    $text = str_replace('|', '&#124;', $text);
    $text = preg_replace('/\r?\n/', '', $text);
    $text = preg_replace('/<br(\s\/)?' . '>/i', '[br]', $text);
    $text = preg_replace('/<p(\s\/)?' . '>/i', '[br]', $text);
    $text = preg_replace('/(\[br\]\s*){10,}/i', '[br]', $text);
    while (preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1], $text);
    }
    while (preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . $mat[3], $text);
    }
    if (empty($tags)) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
    }
    $text = preg_replace('/<(' . $tags . ')( [^><\[\]]*)>/i', '[\1\2]', $text);
    $text = preg_replace('/<\/(' . $tags . ')>/Ui', '[/\1]', $text);
    $text = preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i', '', $text);
    while (preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace('>', ']', str_replace('<', '[', $mat[0])), $text);
    }
    while (preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat)) {
        $text = str_replace($mat[0], $mat[1] . '|' . $mat[3] . '|' . $mat[4], $text);
    }
    while (preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat)) {
        $text = str_replace($mat[0], str_replace($mat[1], '', $mat[0]), $text);
    }
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    $text = str_replace('"', '&quot;', $text);
    $text = str_replace('[', '<', $text);
    $text = str_replace(']', '>', $text);
    $text = str_replace('|', '"', $text);
    $text = str_replace('  ', ' ', $text);
    return $text;
}

function ubb($Text) {
    $Text = trim($Text);
    $Text = preg_replace("/\\t/is", "  ", $Text);
    $Text = preg_replace("/\[h1\](.+?)\[\/h1\]/is", "<h1>\\1</h1>", $Text);
    $Text = preg_replace("/\[h2\](.+?)\[\/h2\]/is", "<h2>\\1</h2>", $Text);
    $Text = preg_replace("/\[h3\](.+?)\[\/h3\]/is", "<h3>\\1</h3>", $Text);
    $Text = preg_replace("/\[h4\](.+?)\[\/h4\]/is", "<h4>\\1</h4>", $Text);
    $Text = preg_replace("/\[h5\](.+?)\[\/h5\]/is", "<h5>\\1</h5>", $Text);
    $Text = preg_replace("/\[h6\](.+?)\[\/h6\]/is", "<h6>\\1</h6>", $Text);
    $Text = preg_replace("/\[separator\]/is", "", $Text);
    $Text = preg_replace("/\[center\](.+?)\[\/center\]/is", "<center>\\1</center>", $Text);
    $Text = preg_replace("/\[url=http:\/\/([^\[]*)\](.+?)\[\/url\]/is", "<a href=\"http://\\1\" target=_blank>\\2</a>", $Text);
    $Text = preg_replace("/\[url=([^\[]*)\](.+?)\[\/url\]/is", "<a href=\"http://\\1\" target=_blank>\\2</a>", $Text);
    $Text = preg_replace("/\[url\]http:\/\/([^\[]*)\[\/url\]/is", "<a href=\"http://\\1\" target=_blank>\\1</a>", $Text);
    $Text = preg_replace("/\[url\]([^\[]*)\[\/url\]/is", "<a href=\"\\1\" target=_blank>\\1</a>", $Text);
    $Text = preg_replace("/\[img\](.+?)\[\/img\]/is", "<img src=\\1>", $Text);
    $Text = preg_replace("/\[color=(.+?)\](.+?)\[\/color\]/is", "<font color=\\1>\\2</font>", $Text);
    $Text = preg_replace("/\[size=(.+?)\](.+?)\[\/size\]/is", "<font size=\\1>\\2</font>", $Text);
    $Text = preg_replace("/\[sup\](.+?)\[\/sup\]/is", "<sup>\\1</sup>", $Text);
    $Text = preg_replace("/\[sub\](.+?)\[\/sub\]/is", "<sub>\\1</sub>", $Text);
    $Text = preg_replace("/\[pre\](.+?)\[\/pre\]/is", "<pre>\\1</pre>", $Text);
    $Text = preg_replace("/\[email\](.+?)\[\/email\]/is", "<a href='mailto:\\1'>\\1</a>", $Text);
    $Text = preg_replace("/\[colorTxt\](.+?)\[\/colorTxt\]/eis", "color_txt('\\1')", $Text);
    $Text = preg_replace("/\[emot\](.+?)\[\/emot\]/eis", "emot('\\1')", $Text);
    $Text = preg_replace("/\[i\](.+?)\[\/i\]/is", "<i>\\1</i>", $Text);
    $Text = preg_replace("/\[u\](.+?)\[\/u\]/is", "<u>\\1</u>", $Text);
    $Text = preg_replace("/\[b\](.+?)\[\/b\]/is", "<b>\\1</b>", $Text);
    $Text = preg_replace("/\[quote\](.+?)\[\/quote\]/is", " <div class='quote'><h5>引用:</h5><blockquote>\\1</blockquote></div>", $Text);
    $Text = preg_replace("/\[code\](.+?)\[\/code\]/eis", "highlight_code('\\1')", $Text);
    $Text = preg_replace("/\[php\](.+?)\[\/php\]/eis", "highlight_code('\\1')", $Text);
    $Text = preg_replace("/\[sig\](.+?)\[\/sig\]/is", "<div class='sign'>\\1</div>", $Text);
    $Text = preg_replace("/\\n/is", "<br/>", $Text);
    return $Text;
}

function cleanhtml($str, $length = 0, $suffix = true) {
    $str = preg_replace("@<(.*?)>@is", "", $str);
    if ($length > 0) {
        $str = msubstr($str, 0, $length, 'utf-8', $suffix);
    }
    return $str;
}

// 随机生成一组字符串
function build_count_rand($number, $length = 4, $mode = 1) {
    if ($mode == 1 && $length < strlen($number)) {
        return false;
    }
    $rand = array();
    for ($i = 0; $i < $number; $i++) {
        $rand[] = rand_string($length, $mode);
    }
    $unqiue = array_unique($rand);
    if (count($unqiue) == count($rand)) {
        return $rand;
    }
    $count = count($rand) - count($unqiue);
    for ($i = 0; $i < $count * 3; $i++) {
        $rand[] = rand_string($length, $mode);
    }
    $rand = array_slice(array_unique($rand), 0, $number);
    return $rand;
}

function remove_xss($val) {
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val); // with a ;
        $val = preg_replace('/(&#0{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val); // with a ;
    }
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    $found = true; 
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(&#0{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                $found = false;
            }
        }
    }
    return $val;
}

/**
 * 把返回的数据集转换成Tree
 * @access public
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
    $tree = array();
    if (is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = & $list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = & $list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = & $refer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc') {
    if (is_array($list)) {
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}

/**
 * 在数据列表中搜索
 * @access public
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list, $condition) {
    if (is_string($condition))
        parse_str($condition, $condition);
    $resultSet = array();
    foreach ($list as $key => $data) {
        $find = false;
        foreach ($condition as $field => $value) {
            if (isset($data[$field])) {
                if (0 === strpos($value, '/')) {
                    $find = preg_match($value, $data[$field]);
                } elseif ($data[$field] == $value) {
                    $find = true;
                }
            }
        }
        if ($find)
            $resultSet[] = &$list[$key];
    }
    return $resultSet;
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from = 'gbk', $to = 'utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}


/* 提取所有图片 */
function getImgs($content,$order='all'){
	$pattern='/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
	preg_match_all($pattern,$content,$match);
	if(isset($match[1])&&!empty($match[1])){
		if($order==='all'){
			return $match[1];
		}
		if(is_numeric($order)&&isset($match[1][$order])){
			return $match[1][$order];
		}
	}
	return '';
}


/*对象转换为数组*/
function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}

//重复数组
function a_array_unique($array){
   $out = array();
   foreach ($array as $key=>$value){
       if (!in_array($value, $out)){
           $out[$key] = $value;
       }
   }
   return $out;
}

//坐标范围
function returnSquarePoint($lng, $lat,$distance){
    $dlng =  2 * asin(sin($distance / (2 * 6378.2)) / cos(deg2rad($lat)));
    $dlng = rad2deg($dlng);
    $dlat = $distance/6378.2;
    $dlat = rad2deg($dlat);
    return array(
		'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
		'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
		'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
		'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
	);
}

//偏移换算
function placeToBaidu($lng,$lat){
	$p = 3.14159265358979324 * 6378.2 / 360.0;
	$x = $lng;
	$y = $lat;
	$z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $p); 
	$theta = atan2($y, $x) + 0.000003 * cos($x * $p); 
	$bd_lng = $z * cos($theta) + 0.0065;
	$bd_lat = $z * sin($theta) + 0.006;
	return array('lng' => $bd_lng ,'lat' => $bd_lat);
}
//carrot添加全局归递找父级
function get_all_parent ($array, $cate_id) {
	$arr = array();
	foreach ($array as $v) {
		if ($v['cate_id'] == $cate_id) {
			$arr[] = $v;
			$arr = array_merge($arr, get_all_parent($array, $v['parent_id']));
		}
	}
	return $arr;
}

/*数据库备份*/
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}



function configWedUrl(){
	$config = D('Setting')->fetchAll();
	return $config['site']['host'];
}
function config_img($img){
	if(strstr($img,"images4.5maiche.cn")){
		$img = str_replace("images4.5maiche.cn","images4.yanjiu007.com",$img);
	}elseif(strstr($img,"http")){
	 	$img = $img;
	}elseif(empty($img)){
		$img = __ROOT__.'/attachs/default.jpg';
	}else{
		if(strstr($img,"attachs")){
			$img = __ROOT__.''.$img;
		}else{
			$img = __ROOT__.'/attachs/'.$img;
		}
	}
	return  $img;
} 

function config_weixin_img($img){
	$config = D('Setting')->fetchAll();
	if(strstr($img,"images4.5maiche.cn")){
		$img = str_replace("images4.5maiche.cn","images4.yanjiu007.com",$img);
	}elseif(strstr($img,"http")){
	 	$img = $img;
	}elseif(empty($img)){
		$img = $config['site']['host'] .'/attachs/default.jpg';
	}else{
		if(strstr($img,"attachs")){
			$img =$config['site']['host'] . ''.$img;
		}else{
			$img = $config['site']['host'] . '/attachs/'.$img;
		}
	}
	return  $img;
} 



//返回完整的URL，1代表PC，2代表手机端
function config_navigation_url($url,$type){
	if(strstr($url,"http")){
	 	$url = $url;
	}elseif(strstr($url,"https")){
		$url = $url;
	}else{
		if($type == 1){
			$url = __HOST__ . $url;
		}else{
			if(strstr($url,"wap")){
				$url = __HOST__ . '/'.$url;
			}else{
				$url = __HOST__ . '/wap/'.$url;
			}
		}
	}
	return  $url;
} 

function config_user_name($user_name){
	if(strstr($user_name,'@')){
	 	$user_name = substr_replace($user_name,'****',3,4);
	}elseif(preg_match("/1[3458]{1}\d{9}$/",$user_name)){
		$user_name = substr_replace($user_name,'******',3,6);
	}else{
		$user_name = $user_name;
	}
	return  $user_name;
} 
//分割缩略图设置尺寸
function thumbSize($thumb = '200X200',$key = 0){
    if(is_array($thumb)){
        $thumb = $thumb['thumb'];
    }
    $array = explode('X',$thumb);
    return $array[$key];
}


/***
$val 返回$array 中的一个区间值
**/
function compareArr($array,$val){
    $val = intval($val);
    if(is_array($array)){
        foreach($array as $k=>$v){
            if(isset($array[$k+1])){
                if($val >= $v and $val < $array[$k+1]){
                    return $k;
                }
            }else{
                if($val >= $v){
                    return $k;
                }
            }
        }
        return a;
    }else{
        return a;
    }
}

/**
 * 刷新商品库存, 如果商品有设置规格库存, 则商品总库存 等于 所有规格库存相加
 * @param type $goods_id  商品id
 */
function refresh_stock($goods_id){
    $count = M("TpSpecGoodsPrice")->where("goods_id = $goods_id")->count();
    if($count == 0) return false; // 没有使用规格方式 没必要更改总库存

    $store_count = M("TpSpecGoodsPrice")->where("goods_id = $goods_id")->sum('store_count');
    M("Goods")->where("goods_id = $goods_id")->save(array('num'=>$store_count)); // 更新商品的总库存
}


/**
*根据订单id来刷新相应的多属性的规格
*/
function refresh_spec_stock($goods_id,$key,$num){
   $spt=D('TpSpecGoodsPrice')->where("`key`='{$key}' and `goods_id`='{$goods_id}'")->find();	              
   if(empty($spt)) return false; 
   // 没有使用规格方式 
    //库存操作库存
    $data['store_count'] = $spt['store_count'] + $num;
    D('TpSpecGoodsPrice')->where("`key`='{$key}' and `goods_id`='{$goods_id}'")->save($data);;	
}


/**
* 判断规格的库存是否还充足
*/
function is_spec_stock($goods_id,$key,$num){
   $spt=D('TpSpecGoodsPrice')->where("`key`='{$key}' and `goods_id`='{$goods_id}'")->find();	              
   if(empty($spt)) return true; // 没有使用规格 也就不需要判断了
   
   if($spt['store_count']>=$num) return true;
   else return false;

}

/**
*获取单个规格的库存
*/
function get_one_spec_stock($goods_id,$key){
   $spt=D('TpSpecGoodsPrice')->where("`key`='{$key}' and `goods_id`='{$goods_id}'")->find();	              
   if(empty($spt)) return 0; // 没有使用规格 也就不需要判断了
   
   return $spt['store_count'];

}

/**
 * 两个数组的笛卡尔积
*
 * @param unknown_type $arr1
 * @param unknown_type $arr2
*/
function combineArray($arr1,$arr2) {         
    $result = array();
    foreach ($arr1 as $item1) 
    {
        foreach ($arr2 as $item2) 
        {
            $temp = $item1;
            $temp[] = $item2;
            $result[] = $temp;
        }
    }
    return $result;
}
/**
 * @param $arr
 * @param $key_name
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名 
 */
function convert_arr_key($arr, $key_name)
{
    $arr2 = array();
    foreach($arr as $key => $val){
        $arr2[$val[$key_name]] = $val;        
    }
    return $arr2;
}

/**
 * 所有数组的笛卡尔积
*
 * @param unknown_type $data
*/
function combineDika() {
    $data = func_get_args();
    $data = current($data);
    $cnt = count($data);
    $result = array();

        $arr1 = array_shift($data);
    foreach($arr1 as $key=>$item) 
    {
        $result[] = array($item);
    }       

    foreach($data as $key=>$item) 
    {                                
        $result = combineArray($result,$item);
    }
    return $result;
}

/**
 * @param $arr
 * @param $key_name
  * @param $key_name2
 * @return array
 * 将数据库中查出的列表以指定的 id 作为数组的键名 数组指定列为元素 的一个数组
 */
function get_id_val($arr, $key_name,$key_name2){
    $arr2 = array();
    foreach($arr as $key => $val){
        $arr2[$val[$key_name]] = $val[$key_name2];
    }
    return $arr2;
}


function array_comparison($v1, $v2) { //比较数组
    if ($v1 === $v2) {
        return 0;
    }
    if ($v1 > $v2) {
        return 1;
    } else {
        return -1;
    }
}
//苹果手机下面不能保存cookie
function unescape($str){
			$ret = '';
			$len = strlen($str);
			for ($i = 0; $i < $len; $i ++){
				if ($str[$i] == '%' && $str[$i + 1] == 'u'){
					$val = hexdec(substr($str, $i + 2, 4));
					if ($val < 0x7f)
						$ret .= chr($val);
					else 
						if ($val < 0x800)
							$ret .= chr(0xc0 | ($val >> 6)) .
							 chr(0x80 | ($val & 0x3f));
						else
							$ret .= chr(0xe0 | ($val >> 12)) .
							 chr(0x80 | (($val >> 6) & 0x3f)) .
							 chr(0x80 | ($val & 0x3f));
					$i += 5;
				} else 
					if ($str[$i] == '%'){
						$ret .= urldecode(substr($str, $i, 3));
						$i += 2;
					} else
						$ret .= $str[$i];
			}
	return $ret;
}
function getcwdOL(){
   $total = $_SERVER[PHP_SELF];
   $file = explode("/", $total);
   $file = $file[sizeof($file) - 1];
  return substr($total, 0, strlen($total) - strlen($file) - 1);
}

function getSiteUrl(){
  $host = $_SERVER[SERVER_NAME];
  $port = ($_SERVER[SERVER_PORT] == "80") ? "" : ":$_SERVER[SERVER_PORT]";
  return "http://" . $host . $port . getcwdOL();
}
//毫秒时间戳
function msectime() {
  list($tmp1, $tmp2) = explode(' ', microtime());
  return (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
}
//积分抵扣
function deduction($integral){
	$config = D('Setting')->fetchAll();
	if($config['integral']['buy'] == 0){
	 	return  round($integral/100,2);
	}elseif($config['integral']['buy'] == 10){
		return  round($integral/10,2);
	}elseif($config['integral']['buy'] == 100){
		return  round($integral/1,2);
	}else{
		return  '配置错误';
	}
	return  '配置错误';
} 

//获取外卖购物车产品数量函数
function getEleCartNum($product_id,$shop_id){
	$lists = (array) json_decode(cookie('ele'));
	foreach ($lists as $shop_id => $items) {
       foreach ($items as $k2 => $item) {
		   $item = (array) $item;
            if($item['product_id'] == $product_id){
				return $item['num'];
			}
           
       }
   }
	return 0;
}


//获取菜市场购物车产品数量函数
function getMarketCartNum($product_id,$shop_id){
	$lists = (array) json_decode(cookie('market'));
	foreach ($lists as $shop_id => $items) {
       foreach ($items as $k2 => $item) {
		   $item = (array) $item;
            if($item['product_id'] == $product_id){
				return $item['num'];
			}
           
       }
   }
	return 0;
}

//获取便利店购物车产品数量函数
function getStoreCartNum($product_id,$shop_id){
	$lists = (array) json_decode(cookie('store'));
	foreach ($lists as $shop_id => $items) {
       foreach ($items as $k2 => $item) {
		   $item = (array) $item;
            if($item['product_id'] == $product_id){
				return $item['num'];
			}
           
       }
   }
	return 0;
}


//数组去掉重复，按照键值	
function second_array_unique_bykey($arr, $key){  
    $tmp_arr = array();  
    foreach($arr as $k => $v){  
        if(in_array($v[$key], $tmp_arr)){  
            unset($arr[$k]); 
        }  
        else{  
            $tmp_arr[$k] = $v[$key]; 
        }  
   }  
   return $arr;  
}  


//数组去掉重复，按照键值	
function getChannelName($channel_id){  
    $list = D('Lifecate')->getChannelMeans();
	foreach ($list as $k => $val){
		if($k == $channel_id){
			return $val;
		}
	}
   return '选择子分类';
}
  
//根据类型计算积分	
function getUseIntegral($integral,$type,$id){  
  	$config = D('Setting')->fetchAll();
	if($config['integral']['buy'] == 0){
	 	return  round($integral/100,2);
	}elseif($config['integral']['buy'] == 10){
		return  round($integral/10,2);
	}elseif($config['integral']['buy'] == 100){
		return  round($integral/1,2);
	}else{
		return  '积分配置错误';
	}
	return  '积分配置错误';
}  


//根据频道获取有多少信息
function getLifeChannelCount($channel_id,$city_id){
	$Lifecate = D('Lifecate')->where(array('channel_id'=>$channel_id))->select();
	$cate_ids = array();
	foreach($Lifecate as $k =>$val){
		$cate_ids[$val['cate_id']] = $val['cate_id'];
	}
	$count = D('Life')->where(array('cate_id'=>array('IN',$cate_ids),'closed'=>'0','audit'=>'1','city_id'=>$city_id))->count();
	return $count;
}

//签到代码
function getSign($row,$user_id){
    $t = $row + 1;
	
    if($t > date('d')){
        $td = "<td style='background-color:lemonchiffon' valign='top'>
<div align='right' valign='top'><span style='position:relative;right:20px;'>" . $t . "</span>
</div><div align='left'> </div><div align='left'> </div></td>";
    }else{
        if(strlen($t) == 1){
            $day = "0" . $t;
        }else{
            $day = $t;
        }
        $t2 = strtotime(date("Y-m-" . $day . ""));
		
		
        $info = D('Usersign')->field('user_id')->where("last_time = " . $t2 . " AND status = 0 AND user_id = " . $user_id . "")->find();
	
	
        if($info){
            $td = "<td style='background-color:navajowhite;navajowhite ;'>
<div align='right' valign='top'><span style='position:relative;right:20px;'>" . $t . "</span>
</div><div align='left'>
<img width='35px' height='35px' src='/static/default/wap/image/index/sign.gif' style='position:relative;left:10px;'> 已签到
</div></td>";
        }else{
            if($t == date('d')) {
                $td = "<td  class='today' onclick='signDay($(this))'>
<div align='right' valign='top'><span style='position:relative;right:20px;'>" . $t . "</span></div>
<div align='center'><a style='cursor:pointer;color:#ffffff;' >签到</a></div></td>";
            }else{
                $td = "<td style='background-color:#DCDCDC;'>
<div align='right' valign='top'><span style='position:relative;right:20px;'>" . $t . "</span>
</div><div align='left'style='height:47px'>
</div></td>";
            }
        }
    }
    return $td;
}

//判断环境是不是win，是返回真
function isWondows(){
	//如果是win
	if(PATH_SEPARATOR == ';'){
		//检测iis
		if(isset($_SERVER['HTTP_X_REWRITE_URL'])){
			return true;
		//检测iis5.0
		}else if(isset($_SERVER['ORIG_PATH_INFO'])){
			return true;	
		}else{
			return false;
		}
	}else{
		return false;
	}
}


//获取当前域名
function curPageURL(){
  $pageURL = 'http';
  if($_SERVER["HTTPS"] == "on"){
    $pageURL .= "s";
  }
  $pageURL .= "://";
  if($_SERVER["SERVER_PORT"] != "80"){
    $pageURL .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  } else{
    $pageURL .= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
  }
  return $pageURL;
}

function IpAuth($ip, $config){
    $ipArr = explode(".", $ip);
    for ( $i=0; $i<count($config); $i++ ){
        $ips = explode(".", $config[$i]['start']);
        $ipe = explode(".", $config[$i]['end']);
        for( $j=0; $j<4; $j++ ){
            if( $ips[$j]==$ipArr[$j] || $ipArr[$j]==$ipe[$j] ){
                if($j == 3){
                    return true;
                }else{
                    continue;
                }
            }else if( $ips[$j]<$ipArr[$j] && $ipArr[$j]<$ipe[$j] ){
                return true;
            }else{
                continue 2;
            }
        }
    }
    return false;
}



//百度测算距离和时间
function get_dist_info($sLat,$sLng,$eLat,$eLng){
    $url = "http://api.map.baidu.com/direction/v1/routematrix?output=json&origins=".$sLat.",".$sLng."&destinations=".$eLat.",".$eLng."&ak=7b92b3afff29988b6d4dbf9a00698ed8";
    $map_info = file_get_contents($url);
    $map_info = json_decode($map_info,1);
    if($map_info["status"]){
        $juli = getdistance($sLat, $sLng, $eLat, $eLng);
        $time = $juli*3;
        return array("juli"=>$juli,"time"=>$time);
    }else{
        $juli=$map_info["result"]["elements"][0]['distance']['text'];
        $time=$map_info["result"]["elements"][0]['duration']['text'];
        return array("juli"=>$juli, "juli_value"=>$map_info["result"]["elements"][0]['distance']['value'], "time"=>$time, "time_value"=>$map_info["result"]["elements"][0]['duration']['value']);
    }
}



//高德坐标系距离计算
function amapDistance($lot,$lat,$lng2,$lat2,$id = 0){
	$config = getConfigKey('config');
	$info['status'] = '';
    $url = 'https://restapi.amap.com/v3/distance?origins='.$lot.','.$lat.'&destination='.$lng2.','.$lat2.'&output=JSON&key=40a870f6c33baedb157e9caab481e942&type=1';
    $info = file_get_contents($url);
    $info = json_decode($info,1);

    if($info['status'] == 1){
		$juli = $info['results'][0]['distance'];
		$time = $info['results'][0]['duration'];
		$s = $juli/1000;
		if($s < 1){
			$s = round($s*1000);
			$s.='m';
		}else{
			$s = round($s,2);
			$s.='km';
		}
        return array('juli'=>$juli,'juliKm'=>$s,'time'=>$time,'times'=>timeChangeDay($time),'info'=> $info['results'][0]['info']);
    }else{
        return array('juli'=>'50000','juliKm'=>'','time'=>'','times'=>'');
    }
}

//高德坐标系距离计算2
function amapDistance2($lot,$lat,$lng2,$lat2,$id = 0){

	$info['status'] = '';
    $url = 'https://restapi.amap.com/v3/distance?origins='.$lot.','.$lat.'&destination='.$lng2.','.$lat2.'&output=JSON&key=40a870f6c33baedb157e9caab481e942&type=1';
    $info = file_get_contents($url);
    $info = json_decode($info,1);

    if($info['status'] == 1){
		$juli = $info['results'][0]['distance'];
		$time = $info['results'][0]['duration'];
		$s = $juli/1000;
		if($s < 1){
			$s = round($s*1000);
		}else{
			$s = round($s,2);
		}
        return array('juli'=>$juli,'juliKm'=>$s);
    }else{
        return array('juli'=>'50000','juliKm'=>'');
    }
}



//时间转换
function timeChangeDay($time){
	$d = floor($time/(3600*24));
	$h = floor(($time%(3600*24))/3600);
	$m = floor((($time%(3600*24))%3600)/60);
	if($d>'0'){
		return $d.'天'.$h.'小时'.$m.'分';
	}else{
		if($h!='0'){
			return $h.'小时'.$m.'分';
		}else{
			return $m.'分';
		}
	}
}






//从新计算配送价位
function update_logistics_price($dist,$city_id=0){
    $delivery_set = M("delivery_set")->where(array("city_id"=>$city_id))->find();
    if(empty($delivery_set)){
        $delivery_set = M("delivery_set")->where(array("city_id"=>0))->find();
    }
    $s_price = $delivery_set["s_price"];//配送费起步价
    $s_dist = $delivery_set["s_dist"];//起步配送距离
    $one_dist = $delivery_set["one_dist"];//每公里价位
    if(strpos($dist, "公里") !== false){
        $dist = ceil(str_replace("公里", "", $dist));

        if($dist>2){
            $amount = ($dist-$s_dist)*$one_dist+$s_price;
            //p($amount);
        }else{
            $amount = $s_price;
        }
    }else{
        $dist = str_replace("米", "", $dist);
        $dist = ceil($dist/1000);
        if($dist>2){
            $amount = ($dist-$s_dist)*$one_dist+$s_price;
        }else{
            $amount = $s_price;
        }
    }

    return round($amount*100,2);
}



//百度地图转换为高德地图
function getBaiduChangeMap($lat,$lng){
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    $x = $lng - 0.0065;
    $y = $lat - 0.006;
    $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
    $lng = $z * cos($theta);
    $lat = $z * sin($theta);
	return $lat.','.$lng;
}
	
//高德地图转换为百度地图
function getMapChangeBaidu($lat,$lng){
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    $x = $lng;
    $y = $lat;
    $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
    $lng = $z * cos($theta) + 0.0065;
    $lat = $z * sin($theta) + 0.006;
	return $lat.','.$lng;
}

//百度地图转换为高德地图
function getBaiduChangeMap2($lat,$lng){
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    $x = $lng - 0.0065;
    $y = $lat - 0.006;
    $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
    $lng = $z * cos($theta);
    $lat = $z * sin($theta);
	return array('lat'=>$lat,'lng'=>$lng);
}
	
//高德地图转换为百度地图
function getMapChangeBaidu2($lat,$lng){
	$x_pi = 3.14159265358979324 * 3000.0 / 180.0;
    $x = $lng;
    $y = $lat;
    $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
    $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
    $lng = $z * cos($theta) + 0.0065;
    $lat = $z * sin($theta) + 0.006;
	
	return array('lat'=>$lat,'lng'=>$lng);
}


//数组排序
function array_sort($array, $keys, $type='asc' ){
        if(!isset($array) || !is_array($array) || empty($array)) return '';
        if(!isset($keys) || trim($keys) == '' ) return '';
        if(!isset($type) || $type == '' || !in_array( strtolower($type), array( 'asc', 'desc' ) ) ) return '';
        
        $keysvalue  = array();
        foreach($array as $key => $val) {
            $val[$keys]   = str_replace( '-', '', $val[$keys]);
            $val[$keys]   = str_replace( ' ', '', $val[$keys]);
            $val[$keys]   = str_replace( ':', '', $val[$keys]);
            $keysvalue[]    = $val[$keys];
        }
        
        asort($keysvalue); //key值排序
        reset($keysvalue); //指针重新指向数组第一个
        foreach($keysvalue as $key => $vals) 
            $keysort[] = $key;
        
        $keysvalue  = array();
        $count = count($keysort);
        if(strtolower($type) != 'asc' ){
            for($i = $count - 1; $i >= 0; $i-- ) 
                $keysvalue[] = $array[$keysort[$i]];
        }else{
            for($i = 0; $i < $count; $i++)
                $keysvalue[] = $array[ $keysort[$i]];
        }
        return $keysvalue;
    }


/**
 * [writeArr 写入配置文件方法]
 * @param  [type] $arr      [要写入的数据]
 * @param  [type] $filename [文件路径]
 * @return [type]           [description]
 */
 
 
function writeArr($arr, $filename) {
    return file_put_contents($filename, "<?php\r\nreturn " . var_export($arr, true) . ";");
}

//判断目录是否有文件
function dir_exist_file($path){
    if(!is_dir($path)){
        return false;
    }else{
        $files = scandir($path);
        // 删除  "." 和 ".."
        unset($files[0]);
        unset($files[1]);
        // 判断是否为空
        if(!empty($files[2])){
            return true;
        }else{
            return false;
        }
    }
}

//currency货币转换
function currency($ctl,$act){
	$getConfigKey =  getConfigKey($key = 'site');
	
	if($ctl == 'ele' || $ctl == 'eleorder' || $ctl == 'eledianping'){
		$getConfigKey['currency'] ? $getConfigKey['currency'] : '¥';
	}
	return $getConfigKey['currency'] ? $getConfigKey['currency'] : '¥';
}
//currency货币转换
function currencyText($ctl,$act){
	$getConfigKey =  getConfigKey($key = 'site');
	return $getConfigKey['currencyText'] ? $getConfigKey['currencyText'] : '元';
}



//格式化打印函数
function p($array) {
	dump ( $array, 1, '<pre style=font-size:14px;color:#00ae19;>', 0 );
}