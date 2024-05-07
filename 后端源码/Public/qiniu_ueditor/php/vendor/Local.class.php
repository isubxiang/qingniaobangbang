<?php

/**
 * 原始的本地上传处理方法
 * 
 * @author   widuu <admin@widuu.com>
 * @document https://github.com/widuu/qiniu_ueditor_1.4.3
 */

class Local extends Base{

	/**
	 * 上传方法[基于Ueditor自带的Uploader]
	 * 
	 * @author widuu <admin@widuu.com>
	 */

	public function upload($method){
		$upload_config = $this->setUploadConfig($method);
		
		//var_dump( $setWater);die;
		
		$fieldName = $upload_config['fieldName'];
		$base64    = $upload_config['base64'];
		unset($upload_config['fieldName']);
		unset($upload_config['base64']);
		$File = new LocalDriver($fieldName, $upload_config, $base64);
		
		
		
		$getFileInfo = $File->getFileInfo();
		
		if($upload_config['use_water']){ 
           $setWater = $this->addWaterMap($upload_config['rootPath'].$getFileInfo['url'],$upload_config['water_url']);
        }
		
		return $File->getFileInfo();
	}
	



	
	/**
	 * 追加水印
	 *
	 * @param string $source_img 原始图片url
	 * @param string $water_map 水印图片url
	 */
	public function addWaterMap($source_img, $water_map){
	  $img = imagecreatefromjpeg($source_img); //读取原图
	  $img_x = imagesx($img); //原图宽
	  $img_y = imagesy($img); //原图高
	  imagealphablending($img, true);//设置为混合填色模式
	  //$img_water_map = imagecreatefromjpeg($water_map); //读取水印图片
	  $img_water_map = imagecreatefrompng($water_map);
	  $water_x = imagesx($img_water_map); //水印宽
	  $water_y = imagesy($img_water_map); //水印高
	  $wimg_x = $img_x - $water_x - 10; //水印x坐标
	  $wimg_y = $img_y - $water_y - 10; //水印y坐标
	  imagecopy($img, $img_water_map, $wimg_x, $wimg_y, 0, 0, $water_x, $water_y); //分别为原图，水印，水印x坐标，水印y坐标，水印图片横轴开始点，水印图片纵轴开始点，水印横轴结束，水印纵轴结束
	  imagejpeg($img, $source_img, 95); //输出到目标文件
	  imagedestroy($img); //销毁内存数据流
	  imagedestroy($img_water_map); //销毁内存数据流
	  return true;
	}

	/**
	 * 删除文件方法
	 * 
	 * @author widuu <admin@widuu.com>
	 */

	public function remove(){
		$file = trim($_POST['key']);
		$config    = $this->config;
		$root_path = $config['root_path'];
		$file_path = $root_path.$file;
		if( file_exists($file_path) ){
			$result = @unlink($file_path);
			if( $result ){
				return array(
					'state' => 'SUCCESS'
				);
			}else{
				return array(
					'state' => 'ERROR',
					'error' => 'delete file error'
				);
			}
		}

		return array(
			'state' => 'ERROR',
			'error' => 'file not exists'
		);
	}

	/**
	 * 远程图片抓取 [采用原有ueditor方法]
	 * 
	 * @author widuu <admin@widuu.com>
	 */

	public function catchimage(){
		$ue_config = $this->getUeConfig();
		/* 上传配置 */
		$config = array(
		    "pathFormat" => $ue_config['catcherPathFormat'],
		    "maxSize" 	 => $ue_config['catcherMaxSize'],
		    "allowFiles" => $ue_config['catcherAllowFiles'],
		    "oriName" 	 => "remote.png"
		);

		$fieldName = $ue_config['catcherFieldName'];

		/* 抓取远程图片 */
		$list = array();
		if (isset($_POST[$fieldName])) {
		    $source = $_POST[$fieldName];
		} else {
		    $source = $_GET[$fieldName];
		}

		foreach ( $source as $img_url ) {
		    $file = new File($img_url, $config, "remote");
		    $info = $file->getFileInfo();
		    array_push($list, array(
		        "state"    => $info["state"],
		        "url"      => $info["url"],
		        "size"     => $info["size"],
		        "title"    => htmlspecialchars($info["title"]),
		        "original" => htmlspecialchars($info["original"]),
		        "source"   => htmlspecialchars($img_url)
		    ));
		}

		return array(
		    'state'=> count($list) ? 'SUCCESS':'ERROR',
		    'list'=> $list
		);
	}
	
	/**
	 * 列出文件列表 [采用原有ueditor方法]
	 * 
	 * @author widuu <admin@widuu.com>
	 */

	public function listFile($method){
		$ue_config = $this->getUeConfig();
		
		if( $method == 'listimage'){
			$config_prefix = 'image';
		}else{
			$config_prefix = 'file';
		}

		$config = array(
 			"allowFiles" => $ue_config[$config_prefix.'ManagerAllowFiles'],
        	"listSize"   => $ue_config[$config_prefix.'ManagerListSize'],
        	"path" 		 => $ue_config[$config_prefix.'ManagerListPath'],
		);

		$allow_files = substr(str_replace(".", "|", join("", $config['allowFiles'])), 1);
	
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $config['listSize'];
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;

		$path = $_SERVER['DOCUMENT_ROOT'] . (substr($config['path'], 0, 1) == "/" ? "":"/") . $config['path'];
		$files = array();
		$this->getFiles($path, $allow_files,$files);

		if (!count($files)) {
		    return array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    );
		}

		/* 获取指定范围的列表 */
		$len = count($files);

		$php_config = $this->config;

		if( $php_config['orderby'] == 'desc' ){
			for ($i = $start, $list = array(); $i < $len && $i < $end; $i++){
			   $list[] = $files[$i];
			}
		}else{
			for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
			    $list[] = $files[$i];
			}
		}

		/* 返回数据 */
		$result = array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		);

		return $result;
	}



	/**
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	 */

	private function getFiles($path, $allowFiles, &$files = array()){
	    if (!is_dir($path)) return null;
	    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
	    $handle = opendir($path);
	    while (false !== ($file = readdir($handle))) {
	        if ($file != '.' && $file != '..') {
	            $path2 = $path . $file;
	            if (is_dir($path2)) {
	                $this->getFiles($path2, $allowFiles, $files);
	            } else {
	                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
	                    $files[] = array(
	                        'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
	                        'mtime'=> filemtime($path2)
	                    );
	                }
	            }
	        }
	    }
	    return $files;
	}
	
	
	
}
