<?php
class CommonModel extends Model{
    protected $pk = '';
    protected $tableName = '';
    protected $token = '';
    protected $cacheTime = 86400;
    protected $orderby = array();
	
	
	protected function _initialize(){
		$ip = get_client_ip();
		
	
		
        $arr = M('adminIpAuth')->select();
		$$ip_config = array();
		foreach($arr as $k => $val){
            $ip_config[$k]['start'] = $val['start'];
            $ip_config[$k]['end'] = $val['end'];
        }
	
		/*
		$ip_config = array(
			array("start" => '27.13.26.25', "end" => '27.13.26.255'),
			array("start" => '111.37.0.0', "end" => '111.37.255.255')
		);
		*/
		
		$ip = get_client_ip();
		$result = IpAuth($ip, $ip_config);
		if($result == 1){
			M('AdminLog')->add(array('username' =>'禁止IP访问账户','password'=>'禁止IP访问','last_time' => NOW_TIME,'last_ip' =>$ip,'audit' => 0));
			TUDOUCMS;die;
		}
	}
	
    //针对全部查询出的数据的排序
    public function updateCount($id, $col, $num = 1){
        $id = (int) $id;
        return $this->execute(" update " . $this->getTableName() . " set {$col} = ({$col} + '{$num}') where " . $this->pk . " = '{$id}' ");
    }
	
	
    //多属性开始
    public function updCount($id, $col, $num = 1){
        return $this->execute("update " . C('DB_PREFIX') . "goods_attr set {$col} = ({$col} + '{$num}') where attr_id = '{$id}' ");
    }
    //多属性结束
    public function itemsByIds($ids = array()){
        if (empty($ids)) {
            return array();
        }
        $data = $this->where(array($this->pk => array('IN', $ids)))->select();
        $return = array();
        foreach ($data as $val) {
            $return[$val[$this->pk]] = $val;
        }
        return $return;
    }
    public function fetchAll($field = '*', $where = array()){
        $cache = cache(array('type' => 'File', 'expire' => $this->cacheTime));
        if (!($data = $cache->get($this->token))) {
            $result = $this->field($field);
            if (!empty($where)) {
                $result = $result->where($where);
            }
            $result = $result->order($this->orderby)->select();
            $data = array();
            foreach ($result as $row) {
                $data[$row[$this->pk]] = $this->_format($row);
            }
            $cache->set($this->token, $data);
        }
        return $data;
    }
    public function cleanCache(){
        $cache = cache(array('type' => 'File', 'expire' => $this->cacheTime));
        $cache->rm($this->token);
    }
    public function _format($data){
        return $data;
    }
    protected function tuJump($jumpUrl){
        $str = '<script>';
        $str .= 'parent.jumpUrl("' . $jumpUrl . '");';
        $str .= '</script>';
        die($str);
    }
    protected function tuMsg($message, $jumpUrl = '', $time = 3000){
        $str = '<script>';
        $str .= 'parent.boxmsg("' . $message . '","' . $jumpUrl . '","' . $time . '");';
        $str .= '</script>';
        exit($str);
    }
    protected function tuError($message, $time = 2000, $yzm = false, $parent = true){
        $parent = $parent ? "parent." : "";
        $str = "<script>";
        if ($yzm) {
            $str .= $parent . "error(\"" . $message . "\"," . $time . ",\"verify()\");";
        } else {
            $str .= $parent . "error(\"" . $message . "\"," . $time . ");";
        }
        $str .= "</script>";
        exit($str);
    }
}