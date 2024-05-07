<?php
class AreaMapModel extends CommonModel{
    protected $pk   = 'id';
    protected $tableName =  'area_map';
    protected $token = 'area_map';
    protected $orderby = array('orderby'=>'asc');
   
    // 一个表示区域的三维数组
    public $config = null;

    // 包含每个区域的四边形
    public $rectangles = null;

    // 每个区域（多边形）的所有边
    public $lines = null;

    // 要判断的点的x, y坐标
    public $_x = null;
    public $_y = null;
    public $arealist = null;
	public $area_id = null;
	
    public function _initialize(){
		//p($this->area_id);die;
		
        $this->arealist = M('area_map')->where(array('area_id'=>$area_id))->field('id,name,position')->order('id asc')->select();
        //进行验证的区域
        $list = array();
        for ($i = 0; $i < count($this->arealist); $i++){
            $option     = unserialize($this->arealist[$i]['position']);
            $newOptioin = array();
            for ($x = 0; $x < count($option); $x++) {
                $newOptioin[$x]['x'] = $option[$x]['lng'];
                $newOptioin[$x]['y'] = $option[$x]['lat'];
            }
            $list[$i] = $newOptioin;
        }
        $this->config = $list;
        $this->initRectangles();
        $this->initLines();
    }

    /*
        获取包含每个配送区域的四边形
    */
    public function initRectangles(){
        foreach($this->config as $k => $v){
            $this->rectangles[$k]['minX'] = $this->getMinXInEachConfig($k);
            $this->rectangles[$k]['minY'] = $this->getMinYInEachConfig($k);
            $this->rectangles[$k]['maxX'] = $this->getMaxXInEachConfig($k);
            $this->rectangles[$k]['maxY'] = $this->getMaxYInEachConfig($k);
        }
    }

    /*
        初始化每个区域（多边形）的边（线段：直线的一部分【限制x或者y坐标范围】）
        n 个顶点构成的多边形，有 n-1 条边
    */
    public function initLines(){
        foreach ($this->config as $k => $v) {
            $pointNum = count($v);		// 区域的顶点个数
            $lineNum = $pointNum - 1; 	// 区域的边条数
            for($i=0; $i<$lineNum; $i++){
                // y=kx+b : k
                if($this->config[$k][$i]['x'] - $this->config[$k][$i+1]['x'] == 0) $this->lines[$k][$i]['k'] = 0;
                else $this->lines[$k][$i]['k'] =
                    ($this->config[$k][$i]['y'] - $this->config[$k][$i+1]['y'])/($this->config[$k][$i]['x'] - $this->config[$k][$i+1]['x']);
                // y=kx+b : b
                $this->lines[$k][$i]['b'] = $this->config[$k][$i+1]['y'] - $this->lines[$k][$i]['k'] * $this->config[$k][$i+1]['x'];
                $this->lines[$k][$i]['lx'] = min($this->config[$k][$i]['x'], $this->config[$k][$i+1]['x']);
                $this->lines[$k][$i]['rx'] = max($this->config[$k][$i]['x'], $this->config[$k][$i+1]['x']);
            }
            $pointNum-=1;
            if($this->config[$k][$pointNum]['x'] - $this->config[$k][0]['x'] == 0) $this->lines[$k][$pointNum]['k'] = 0;
            else $this->lines[$k][$pointNum]['k'] =
                ($this->config[$k][$pointNum]['y'] - $this->config[$k][0]['y'])/($this->config[$k][$pointNum]['x'] - $this->config[$k][0]['x']);
            // y=kx+b : b
            $this->lines[$k][$pointNum]['b'] = $this->config[$k][0]['y'] - $this->lines[$k][$pointNum]['k'] * $this->config[$k][0]['x'];
            $this->lines[$k][$pointNum]['lx'] = min($this->config[$k][$pointNum]['x'], $this->config[$k][0]['x']);
            $this->lines[$k][$pointNum]['rx'] = max($this->config[$k][$pointNum]['x'], $this->config[$k][0]['x']);
        }
    }

    /*
        获取一组坐标中，x坐标最小值
    */
    public function getMinXInEachConfig($index){
        $minX = 200;
        foreach ($this->config[$index] as $k => $v) {
            if($v['x'] < $minX){
                $minX = $v['x'];
            }
        }
        return $minX;
    }

    /*
        获取一组坐标中，y坐标最小值
    */
    public function getMinYInEachConfig($index){
        $minY = 200;
        foreach ($this->config[$index] as $k => $v) {
            if($v['y'] < $minY){
                $minY = $v['y'];
            }
        }
        return $minY;
    }

    /*
        获取一组坐标中，x坐标最大值
    */
    public function getMaxXInEachConfig($index){
        $maxX = 0;
        foreach ($this->config[$index] as $k => $v) {
            if($v['x'] > $maxX){
                $maxX = $v['x'];
            }
        }
        return $maxX;
    }

    /*
        获取一组坐标中，y坐标最大值
    */
    public function getMaxYInEachConfig($index){
        $maxY = 0;
        foreach ($this->config[$index] as $k => $v) {
            if($v['y'] > $maxY){
                $maxY = $v['y'];
            }
        }
        return $maxY;
    }

    /*
        获取 y=y0 与特定区域的所有边的交点，并去除和顶点重复的，再将交点分为左和右两部分
    */
    public function getCrossPointInCertainConfig($index){
        $crossPoint = null;
        foreach ($this->lines[$index] as $k => $v) {
            if($v['k'] == 0) return true;
            $x0 = ($this->_y - $v['b']) / $v['k'];	// 交点x坐标
            if($x0 == $this->_x) return true;		// 点在边上
            if($x0 > $v['lx'] && $x0 < $v['rx']){
                if($x0 < $this->_x) $crossPoint['left'][] = $x0;
                if($x0 > $this->_x) $crossPoint['right'][] = $x0;
            }
        }
        return $crossPoint;
    }

    /*
        检测一个点，是否在区域内
        返回结果：
            return === false : 点不在区域内
            return 0, 1, 2, 3 ... 点所在的区域编号（配置文件中的区域编号。）
    */
    public function checkPoint($x,$y){
        $this->_x = $x;
        $this->_y = $y;
        $contain = null;
        foreach ($this->rectangles as $k => $v) {
            if($x > $v['maxX'] || $x < $v['minX'] || $y > $v['maxY'] || $y < $v['minY']){
                continue;
            }else{
                $contain = $k;
                break;
            }
        }
        if($contain === null) return false;
        $crossPoint = $this->getCrossPointInCertainConfig($contain);
        if($crossPoint === true) return $this->arealist[$contain]['id'];
        if(count($crossPoint['left'])%2 == 1 && count($crossPoint['right'])%2 == 1) return $this->arealist[$contain]['id'];
        return false;
    }
	
	public function inArea($lng,$lat,$area_id){
		$this->area_id = $area_id;
		//p($this->area_id);die;
        (int)$int = D('AreaMap')->checkPoint($lng,$lat);
        if($int >= 0) {
            return $int;
        }else{
            return false;
        }
    }
 
}