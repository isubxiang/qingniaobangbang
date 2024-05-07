<?php
class UserintegrallogsAction extends CommonAction{
	
	
	
    public function index(){
        $Userintegrallogs = D('Userintegrallogs');
        import('ORG.Util.Page');
        $map = array();
        $getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
        if ($user_id = (int) $this->_param('user_id')) {
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')) {
            $map['intro'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
		if(isset($_GET['type']) || isset($_POST['type'])){
            $type = (int) $this->_param('type');
            if($type != 999){
                $map['type'] = $type;
            }
            $this->assign('type', $type);
        }else{
            $this->assign('type', 999);
        }
		
		if($types = (int) $this->_param('types')){
            if($types == 1){
				$map['integral'] = array('gt',0);
			}elseif($types == 2){
				$map['integral'] = array('lt',0);
			}
            $this->assign('types', $types);
        }
		
		$order = $this->_param('order', 'htmlspecialchars');
        $orderby = '';
        switch ($order){
            case '2':
                $orderby = array('integral' => 'asc');
                break;
            case '1':
                $orderby = array('integral' => 'desc');
                break;
            default:
                $orderby = array('integral' => 'desc');
                break;
        }
        $this->assign('order', $order);
		
		
        $count = $Userintegrallogs->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Userintegrallogs->where($map)->order($orderby)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = array();
        foreach ($list as $k => $val) {
            $user_ids[$val['user_id']] = $val['user_id'];
        }
		session('integral_logs_map',$map);
        $this->assign('users', D('Users')->itemsByIds($user_ids));
        $this->assign('list', $list);
        $this->assign('page', $show);
		$this->assign('count',$count);
        $this->display();
    }
	
	
	//列表导出
    public function export(){
        $arr = D('Userintegrallogs')->where($_SESSION['integral_logs_map'])->order(array('log_id' => 'asc'))->select();
        $date = date("Y_m_d", time());
        $filetitle = "积分列表";
        $fileName = $filetitle . "_" . $date;
        $html = "﻿";
        $filter = array(
			'aa' => '日志编号', 
			'bb' => '年', 
			'cc' => '月', 
			'dd' => '日', 
			'ee' => '日志生成时间', 
			'ff' => '会员ID', 
			'gg' => '会员昵称', 
			'hh' => '会员手机', 
			'ii' => '会员邮箱', 
			'jj' => '积分数量', 
			'kk' => '积分类型', 
			'll' => '积分说明' 
		);
        foreach ($filter as $key => $title) {
            $html .= $title . "\t,";
        }
        $html .= "\n";
        foreach ($arr as $k => $v) {
            $Users = D('Users')->find($v['user_id']);
			
            $createTime = date('H:i:s', $v['create_time']);
            $createTimeYear = date('Y', $v['create_time']);
            $createTimeMonth = date('m', $v['create_time']);
            $createTimeDay = date('d', $v['create_time']);
            $filter = array(
				'aa' => '日志编号', 
				'bb' => '年', 
				'cc' => '月', 
				'dd' => '日', 
				'ee' => '日志生成时间', 
				'ff' => '会员ID', 
				'gg' => '会员昵称', 
				'hh' => '会员手机', 
				'ii' => '会员邮箱', 
				'jj' => '积分数量', 
				'kk' => '积分类型', 
				'll' => '积分说明' 
			);
            $arr[$k]['aa'] = $v['log_id'];
            $arr[$k]['bb'] = $createTimeYear;
            $arr[$k]['cc'] = $createTimeMonth;
            $arr[$k]['dd'] = $createTimeDay;
            $arr[$k]['ee'] = $createTime;
            $arr[$k]['ff'] = $v['user_id'];
            $arr[$k]['gg'] = $Users['nickname'];
            $arr[$k]['hh'] = $Users['mobile'];
            $arr[$k]['ii'] = $Users['email'];
            $arr[$k]['jj'] = $v['integral'];
            $arr[$k]['kk'] = '暂无';
            $arr[$k]['ll'] = $v['intro'];
            foreach ($filter as $key => $title) {
                $html .= $arr[$k][$key] . "\t,";
            }
            $html .= "\n";
        }
        /* 输出CSV文件 */
        ob_end_clean();
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename={$fileName}.csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $html;
        exit;
    }
	
	
}