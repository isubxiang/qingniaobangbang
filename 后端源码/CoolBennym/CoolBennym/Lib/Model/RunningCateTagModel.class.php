<?php
class RunningCateTagModel extends CommonModel{
	
    protected $pk = 'tag_id';
    protected $tableName = 'running_cate_tag';
    protected $token = 'running_cate_tag';
    protected $orderby = array('orderby' => 'asc', 'tag_id' => 'asc');
	
	
    public function getTags($cate_id){
        $items = $this->where(array('cate_id' => (int) $cate_id))->select();
        $return = array();
        foreach ($items as $val) {
            $return[$val['type']][$val['tag_id']] = $val;
        }
        return $return;
    }
}