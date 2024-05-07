<?php

class ArticleAction extends CommonAction{
    private $create_fields = array( 'title', 'source', 'keywords', 'profiles', 'desc', 'photo', 'details');
    private $edit_fields = array( 'title', 'source', 'keywords', 'profiles', 'desc', 'photo', 'details');
   
    public function index(){
        $Article = M('Article');
        import('ORG.Util.Page');
        $map = array('closed' => 0);
        if ($keyword = $this->_param('keyword', 'htmlspecialchars')){
            $map['title'] = array('LIKE', '%' . $keyword . '%');
            $this->assign('keyword', $keyword);
        }
    
        $count = M('Article')->where($map)->count();
        $Page = new Page($count, 25);
        $show = $Page->show();
        $list = $Article->where($map)->order(array('article_id' => 'desc'))->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
	
	
   
    public function create(){
        if($this->isPost()){
            $data = $this->checkFields($this->_post('data', false), $this->create_fields);
			$data['shop_id'] = (int) $data['shop_id'];
			$data['title'] = htmlspecialchars($data['title']);
			if (empty($data['title'])) {
				$this->tuError('标题不能为空');
			}
			$data['source'] = htmlspecialchars($data['source']);
			$data['keywords'] = htmlspecialchars($data['keywords']);
			$data['desc'] = htmlspecialchars($data['desc']);
			$data['details'] = $data['details'];
			$data['create_time'] = NOW_TIME;
			$data['create_ip'] = get_client_ip();
            if(M('Article')->add($data)){
                $this->tuSuccess('添加成功', U('article/index'));
            }
            $this->tuError('操作失败');
        }else{
            $this->display();
        }
    }
	
	
	
	
    public function edit($article_id = 0){
        if($article_id = (int) $article_id){
            if(!($detail = M('article')->find($article_id))){
                $this->tuError('请选择要编辑的文章');
            }
            if($this->isPost()){
                $data = $this->checkFields($this->_post('data', false), $this->edit_fields);
				
				$data['article_id'] = $article_id;
				$data['title'] = htmlspecialchars($data['title']);
				if (empty($data['title'])) {
					$this->tuError('标题不能为空');
				}
				$data['details'] = $data['details'];
                if(false !== M('article')->save($data)){
                    $this->tuSuccess('操作成功', U('article/index'));
                }
                $this->tuError('操作失败');
            }else{
                $this->assign('detail', $detail);
                $this->display();
            }
        }else{
            $this->tuError('请选择要编辑的文章');
        }
    }
	
	
 
	
	
    public function delete($article_id = 0){
        if(is_numeric($article_id) && ($article_id = (int) $article_id)){
            M('Article')->save(array('article_id' => $article_id, 'closed' => 1));
            $this->tuSuccess('删除成功', U('article/index'));
        }else{
            $article_id = $this->_post('article_id', false);
            if(is_array($article_id)){
                foreach($article_id as $id){
                    M('Article')->save(array('article_id' => $id, 'closed' => 1));
                }
                $this->tuSuccess('批量删除成功', U('article/index'));
            }
            $this->tuError('请选择要删除的文章');
        }
    }
	
	
}