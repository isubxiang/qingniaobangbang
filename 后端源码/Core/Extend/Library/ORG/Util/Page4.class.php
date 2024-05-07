<?php
class Page4{
    public $rollPage = 5;
    public $parameter;
    public $url = '';
    public $listRows = 20;
    public $firstRow;
    public $totalPages;
    protected $totalRows;
    protected $nowPage;
    protected $coolPages;
    protected $config = array('header' => '条记录', 'prev' => '上一页', 'next' => '下一页', 'first' => '第一页', 'last' => '最后一页', 'theme' => ' <span>%totalRow% %header% %nowPage%/%totalPage% 页</span> %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    protected $varPage;
   
   
   
    public function __construct($totalRows, $listRows = '', $parameter = '', $url = ''){
		
	
		
		
        $this->totalRows = $totalRows;
        $this->parameter = $parameter;
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'pageIndex';
        if (!empty($listRows)) {
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows / $this->listRows);
        //总页数
        $this->coolPages = ceil($this->totalPages / $this->rollPage);
		
        $this->nowPage = !empty($_GET[$this->varPage]) ? intval($_GET[$this->varPage]) : 0;
		
		//p($this->nowPage);
		
        if($this->nowPage == 0){
            $this->nowPage = 1;
        }elseif(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }
		
		//p($this->listRows);
		//p($this->nowPage);die;
		
		
        $this->firstRow = $this->listRows * ($this->nowPage - 1);
		
		//p($this->listRows);die;
		
        if(!empty($url)){
            $this->url = $url;
        }
    }
	
    public function setConfig($name, $value){
        if(isset($this->config[$name])){
            $this->config[$name] = $value;
        }
    }
	
	
    /**
     * 分页显示输出
     * @access public
     */
	 
    public function show(){
		//p(11);die;
        if(0 == $this->totalRows){
            return '';
        }
        $p = $this->varPage;
        $nowCoolPage = ceil($this->nowPage / $this->rollPage);
        // 分析分页参数
        if($this->url){
            $depr = C('URL_PATHINFO_DEPR');
            $url = rtrim(U('/' . $this->url, '', false), $depr) . $depr . '__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)){
                parse_str($this->parameter, $parameter);
            }elseif(is_array($this->parameter)){
                $parameter = $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var = !empty($_POST) ? $_POST : $_GET;
                if(empty($var)){
                    $parameter = array();
                }else{
                    $parameter = $var;
                }
            }
            $parameter[$p] = '__PAGE__';
            $url = U('', $parameter);
        }
		
		
        //上下翻页字符串
        $upRow = $this->nowPage - 1;
        $downRow = $this->nowPage + 1;
        if ($upRow > 0) {
            $upPage = "<a href='" . str_replace('__PAGE__', $upRow, $url) . "'>" . $this->config['prev'] . "</a>";
        } else {
            $upPage = '';
        }
        if ($downRow <= $this->totalPages) {
            $downPage = "<a href='" . str_replace('__PAGE__', $downRow, $url) . "'>" . $this->config['next'] . "</a>";
        } else {
            $downPage = '';
        }
        // << < > >>
        if ($nowCoolPage == 1) {
            $theFirst = '';
            $prePage = '';
        } else {
            $preRow = $this->nowPage - $this->rollPage;
            $prePage = "<a href='" . str_replace('__PAGE__', $preRow, $url) . "' >上" . $this->rollPage . "页</a>";
            $theFirst = "<a href='" . str_replace('__PAGE__', 1, $url) . "' >" . $this->config['first'] . "</a>";
        }
        if ($nowCoolPage == $this->coolPages) {
            $nextPage = '';
            $theEnd = '';
        } else {
            $nextRow = $this->nowPage + $this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='" . str_replace('__PAGE__', $nextRow, $url) . "' >下" . $this->rollPage . "页</a>";
            $theEnd = "<a href='" . str_replace('__PAGE__', $theEndRow, $url) . "' >" . $this->config['last'] . "</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for ($i = 1; $i <= $this->rollPage; $i++) {
            $page = ($nowCoolPage - 1) * $this->rollPage + $i;
            if ($page != $this->nowPage) {
                if ($page <= $this->totalPages) {
                    $linkPage .= "<a href='" . str_replace('__PAGE__', $page, $url) . "'>" . $page . "</a>";
                } else {
                    break;
                }
            } else {
                if ($this->totalPages != 1) {
                    $linkPage .= "<span class='current'>" . $page . "</span>";
                }
            }
        }
        $pageStr = str_replace(array('%header%', '%nowPage%', '%totalRow%', '%totalPage%', '%upPage%', '%downPage%', '%first%', '%prePage%', '%linkPage%', '%nextPage%', '%end%'), array($this->config['header'], $this->nowPage, $this->totalRows, $this->totalPages, $upPage, $downPage, $theFirst, $prePage, $linkPage, $nextPage, $theEnd), $this->config['theme']);
        return '<div class="paging">' . $pageStr . '</div>';
    }
}