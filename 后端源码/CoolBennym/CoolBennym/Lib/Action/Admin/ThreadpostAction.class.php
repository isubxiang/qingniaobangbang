<?php


class ThreadpostAction extends CommonAction{
	
	private $create_fields = array('school_id','title','photo', 'user_id', 'cate_id','thread_id', 'orderby', 'details','is_fine');
    private $edit_fields = array('school_id','title','photo',  'user_id','cate_id','thread_id', 'details', 'orderby', 'is_fine');
	private $comments_create_fields = array('comment_id','post_id','user_id','contents');
    private $comments_edit_fields = array('comment_id','post_id','user_id','contents');


    public function _initialize(){
        parent::_initialize();
		$cates = M('thread_cate')->limit(0,30)->select();
		foreach($cates as $k=>$v){
			$list[$k]['thread'] = D('thread')->where(array('cate_id'=>$v['cate_id'],'closed'=>0))->limit(0,30)->select();
        }
        $this->assign('cates',$cates);
		
    }
    
    
    public function index(){
        $Threadpost = D('Threadpost');
        import('ORG.Util.Page'); 
        $map = array('closed' => 0);
		
		$getSearchDate = $this->getSearchDate();//时间搜索
		if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
		
		
        if($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
		
		if($cate_id = (int) $this->_param('cate_id')){
            $this->assign('cate_id', $cate_id);
        }
		
		if($thread_id = (int) $this->_param('thread_id')){
            $this->assign('thread_id', $thread_id);
        }
		
		
		if($p = (int) $this->_param('p')){
            $this->assign('p', $p);
        }
		
		if($user_id = (int) $this->_param('user_id')){
            $users = D('Users')->find($user_id);
            $this->assign('nickname', $users['nickname']);
            $this->assign('user_id', $user_id);
            $map['user_id'] = $user_id;
        }
		
		$getSchoolId = $this->getSchoolId($this->school_id);
		if($getSchoolId){
			$map['school_id'] = $getSchoolId;
			$school = M('running_school')->where(array('school_id'=>$getSchoolId))->find();
            $this->assign('Name',$school['Name']);
            $this->assign('school_id',$getSchoolId);
		}
		
		if(isset($_GET['audit']) || isset($_POST['audit'])){
            $audit = (int) $this->_param('audit');
            if($audit != 999){
                $map['audit'] = $audit;
            }
            $this->assign('audit', $audit);
        }else{
            $this->assign('audit',999);
        }
		
		
        $count = $Threadpost->where($map)->count(); 
        $Page = new Page($count, 25);
        $show = $Page->show(); 
        $list = $Threadpost->where($map)->order(array('post_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $user_ids = array();
        foreach($list as $k=>$val){
            $user_ids[$val['user_id']] = $val['user_id'];
			$val['create_ip_area'] = $this->ipToArea($val['create_ip']);
			$val['city'] = D('City')->find($val['city_id']);
			$val['area'] = D('Area')->find($val['area_id']);
			$val['business'] = D('Business')->find($val['business_id']);
			$val['business'] = D('Business')->find($val['business_id']);
			$val['thread'] = D('thread')->find($val['thread_id']);
			$val['cate'] = D('thread_cate')->find($val['thread']['cate_id']);
            $list[$k] = $val;
        }
        $this->assign('users',D('Users')->itemsByIds($user_ids));
        $this->assign('list', $list); 
        $this->assign('page', $show); 
		$this->assign('count', $count); 
        $this->display(); 
    }



    public function create($thread_id){
        if($thread_id = (int) $thread_id){
            $obj = D('Threadpost');
            if(!$detail = D('Thread')->find($thread_id)){
                $this->tuError('主题不正确');
            }
            if($this->isPost()){
                $data = $this->createCheck();
                $thumb = $this->_param('thumb', false);
                foreach($thumb as $k => $val){
                    if(empty($val)){
                        unset($thumb[$k]);
                    }
                    if(!isImage($val)){
                        unset($thumb[$k]);
                    }
                }
                $data['thread_id'] = $thread_id;
                $data['cate_id'] = $detail['cate_id'];
                if($post_id = $obj->add($data)){
                    D('Thread')->updateCount($thread_id,'posts');
                    foreach($thumb as $k=>$val){
                        D('Threadpostphoto')->add(array('post_id'=>$post_id,'photo'=>$val));
                    }
                    $this->tuSuccess('操作成功', U('threadpost/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择发帖所属的主题');
        }
    }
    
	
	
    private function createCheck(){
		$data = $this->checkFields($this->_post('data', false), $this->create_fields);
        $data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])){
            $this->tuError('话题标题不能为空');
        }
		$data['user_id'] = (int) $data['user_id'];
        if(empty($data['user_id'])){
            $this->tuError('用户不能为空');
        }
        $data['details'] = $data['details'];
        if(empty($data['details'])){
            $this->tuError('话题简介不能为空');
        }
        if($words = D('Sensitive')->checkWords($data['details'])){
            $this->tuError('话题简介含有敏感词：' . $words);
        }
        $data['audit'] = 1;
		$data['orderby'] = (int) $data['orderby'];
        $data['is_fine'] = (int) $data['is_fine'];
        $data['create_time'] = NOW_TIME;
        $data['create_ip'] = get_client_ip();
        return $data;
    }
    
    
    public function edit($post_id = 0,$p = 0){

        if($post_id = (int) $post_id){
            $obj = D('Threadpost');
            if(!$detail = $obj->find($post_id)){
                $this->tuError('请选择要编辑的话题');
            }
            $Thread = D('Thread')->find($detail['thread_id']);
            if($this->isPost()){
                $data = $this->editCheck();
                $thumb = $this->_param('thumb', false);
                foreach($thumb as $k => $val){
                    if(empty($val)){
                        unset($thumb[$k]);
                    }
                    if(!isImage($val)){
                        unset($thumb[$k]);
                    }
                }
                $data['post_id'] = $post_id;

                if(false !== $obj->save($data)){
                    D('Threadpostphoto')->where(array('post_id'=>$post_id))->delete();
                    foreach($thumb as $k=>$val){
                        D('Threadpostphoto')->add(array('post_id'=>$post_id,'photo'=>$val));
                    }
                    $this->tuSuccess('操作成功', U('threadpost/index',array('p'=>$p)));
                }
                $this->tuError('操作失败');
            }else{
                $thumb = D('Threadpostphoto')->where(array('post_id'=>$post_id))->select();
                $this->assign('thumb', $thumb);
                $this->assign('detail', $detail);
				
				$threads = M('thread')->where(array('cate_id'=>$Thread['cate_id']))->select();
                $this->assign('threads', $threads);
				$this->assign('school', $school= M('running_school')->where(array('school_id'=>$detail['school_id']))->find());
				//p($school);die;
				
				$this->assign('user', D('Users')->find($detail['user_id']));
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的话题');
        }
    }
	

	
	
    private function editCheck(){
        $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
        $data['title'] = htmlspecialchars($data['title']);
        if(empty($data['title'])){
            $this->tuError('话题标题不能为空');
        }
		$data['user_id'] = (int) $data['user_id'];
        if(empty($data['user_id'])){
            $this->tuError('用户不能为空');
        }
        $data['details'] = $data['details'];
        if(empty($data['details'])) {
            $this->tuError('话题简介不能为空');
        }
        if($words = D('Sensitive')->checkWords($data['details'])){
            $this->tuError('话题简介含有敏感词：' . $words);
        } 
        $data['audit'] = 1;
		$data['orderby'] = (int) $data['orderby'];
        $data['is_fine'] = (int) $data['is_fine'];
        return $data;
    }
    
	
	
    public function audit($post_id = 0,$p = 0){
        $obj = D('Threadpost');
        if(is_numeric($post_id) && ($post_id = (int) $post_id)){
            $obj->save(array('post_id' => $post_id, 'audit' => 1));
            $this->tuSuccess('审核成功', U('threadpost/index'));
        }else{
            $post_id = $this->_post('post_id', false);
            if(is_array($post_id)){
                foreach($post_id as $id){
                    $obj->save(array('post_id' => $id, 'audit' => 1));
                }
                $this->tuSuccess('审核成功', UU('threadpost/index',array('p'=>$p)));
            }
            $this->tuError('请选择要审核的话题');
        }
    }
    
	
    
    public function delete($post_id = 0,$p = 0){
        $obj = D('Threadpost');
        if(is_numeric($post_id) && ($post_id = (int) $post_id)){
            $obj->save(array('post_id' => $post_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('threadpost/index'));
        }else{
            $post_id = $this->_post('post_id', false);
            if(is_array($post_id)){
                foreach($post_id as $id){
                    $obj->save(array('post_id'=>$id,'closed' =>1));
                }
                $this->tuSuccess('删除成功', U('threadpost/index',array('p'=>$p)));
            }
            $this->tuError('请选择要删除的话题');
        }
    }
	
	
	
	//回复列表
	public  function comments($post_id = 0){
       
       import('ORG.Util.Page');
	   
       $map = array();
	   
	   $post_id =(int) $post_id;
	   if($post_id){
		  $map['post_id'] = $post_id; 
	   }
	   $detail = D('Threadpost')->find($post_id);
	   
	   
	   $getSearchDate = $this->getSearchDate();//时间搜索
	   if(is_array($getSearchDate)){
			$map['create_time'] = $getSearchDate;
		}
	   
	   
	   
       if($comment_id= (int) $this->_param('comment_id')){
           $map['comment_id'] = $comment_id;
           $this->assign('comment_id', $comment_id);
       }
       if($user_id= (int) $this->_param('user_id')){
           $map['user_id'] = $user_id;
           $this->assign('user_id', $user_id);
       }
       if($audit = (int)$this->_param('audit')){
           $map['audit'] = ($audit === 1 ? 1:0);
           $this->assign('audit',$audit);
       }
	   
	   
	   
       $count = M('thread_post_comments')->where($map)->count();
       $Page = new Page($count,15);
       $show = $Page->show();
       $list = M('thread_post_comments')->where($map)->order(array('comment_id'=>'desc'))->limit($Page->firstRow.','.$Page->listRows)->select();
       $post_ids = $user_ids = array();
       foreach($list  as  $k=>$val){
           $post_ids[$val['post_id']] = $val['post_id'];
           $user_ids[$val['user_id']] = $val['user_id'];
           $val['create_ip_area'] = $this->ipToArea($val['create_ip']);
		   $val['thread_name'] = D('Thread')->comments_get_thread_name($val['post_id']);
           $list[$k] = $val;
       }
       if(!empty($post_ids)){
           $this->assign('threadpost',D('Threadpost')->itemsByIds($post_ids));
       }
       if(!empty($user_ids)){
           $this->assign('users',D('Users')->itemsByIds($user_ids));
       }
       
	   
       $this->assign('list',$list);
	   $this->assign('post_id',$post_id); 
       $this->assign('page',$show);
       $this->display(); 
    }
	
	
	//添加贴吧回复
    public function comments_create($post_id = 0){
		
		$post_id =(int) $post_id;
		if(!$detail = M('thread_post')->find($post_id)){
           $this->tuError('请选择要编辑的回复帖子');
        }
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false), $this->comments_create_fields);
			$data['post_id'] = (int)$data['post_id'];
			if(empty($data['post_id'])){
				$this->tuError('帖子ID不能为空');
			}
			$data['user_id'] = (int)$data['user_id'];
			if(empty($data['user_id'])){
				$this->tuError('用户不能为空');
			}
			$data['contents'] = SecurityEditorHtml($data['contents']);
			if(empty($data['contents'])){
				$this->tuError('内容不能为空');
			}
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
			$data['audit'] = 1;
			if($rest = M('thread_post_comments')->add($data)){
				$this->tuSuccess('添加成功！',U('threadpost/comments',array('post_id'=>$post_id)));
			}
        $this->tuError('操作失败');
        }else{
			$this->assign('detail',$detail); 
			$this->assign('post_id',$post_id); 
            $this->display();
        }
    }



	
	//编辑贴吧回复
    public function comments_edit($comment_id = 0,$post_id = 0){
		
        if($comment_id =(int) $comment_id){
        
            if(!$detail = M('thread_post_comments')->find($comment_id)){
                $this->tuError('请选择要编辑的回复帖子');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false), $this->comments_edit_fields);
				$data['post_id'] = (int)$data['post_id'];
				if(empty($data['post_id'])){
				   $this->tuError('帖子ID不能为空');
				}        
				$data['user_id'] = (int)$data['user_id'];
				if(empty($data['user_id'])){
				   $this->tuError('用户不能为空');
				}        
				$data['contents'] = SecurityEditorHtml($data['contents']);
				if(empty($data['contents'])){
				   $this->tuError('内容不能为空');
				}
				$data['audit'] = 1;
                $data['comment_id'] = $comment_id;
				
                if(false!==M('thread_post_comments')->save($data)){
                    $this->tuSuccess('操作成功！',U('threadpost/comments',array('post_id'=>$post_id)));
                }
                $this->tuError('操作失败');
                
            }else{
                $this->assign('detail',$detail);      
                $this->assign('user',D('Users')->find($detail['user_id']));
				$this->assign('post_id',$post_id); 
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的回复帖子');
        }
    }
	
	

	
	 //审核贴吧
    public function comments_audit($comment_id = 0,$post_id = 0){
         if(is_numeric($comment_id) && $comment_id = (int)$comment_id){
             M('thread_post_comments')->where(array('comment_id'=>$comment_id))->save(array('audit'=>1));
             $this->tuSuccess('操作成功',U('threadpost/comments',array('post_id'=>$post_id)));
         }else{
            $comment_id = $this->_post('comment_id',false);
            if(is_array($comment_id)){     
              
                foreach($comment_id as $id){
                    M('thread_post_comments')->where(array('comment_id'=>$id))->save(array('audit'=>1));
                }                
                $this->tuSuccess('批量操作成功',U('threadpost/comments',array('post_id'=>$post_id)));
            }
            $this->tuError('请选择要批量操作的回复帖子');
         }
    }
	
	
	
    //删除贴吧回复
    public function comments_delete($comment_id = 0,$post_id = 0){
         if(is_numeric($comment_id) && $comment_id = (int)$comment_id){
             M('thread_post_comments')->delete($comment_id);
             $this->tuSuccess('删除成功',U('threadpost/comments',array('post_id'=>$post_id)));
         }else{
            $comment_id = $this->_post('comment_id',false);
            if(is_array($comment_id)){     
                foreach($comment_id as $id){
                    M('thread_post_comments')->delete($id);
                }                
                $this->tuSuccess('批量删除成功',U('threadpost/comments',array('post_id'=>$post_id)));
            }
            $this->tuError('请选择要批量删除的回复帖子');
         }
    }

}
