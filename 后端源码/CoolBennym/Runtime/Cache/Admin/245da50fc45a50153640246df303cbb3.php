<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title><?php echo ($CONFIG["site"]["sitename"]); ?>管理后台</title>
        <meta name="description" content="<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台"/>
        <meta name="keywords" content="<?php echo ($CONFIG["site"]["sitename"]); ?>管理后台"/>
        <link href="__TMPL__statics/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="__PUBLIC__/js/jquery-ui.css" rel="stylesheet" type="text/css"/>
        
        
        <script> 
			var TU_PUBLIC = '__PUBLIC__'; 
			var TU_ROOT = '__ROOT__'; 
        </script>
        <script src="__PUBLIC__/js/jquery.js"></script>
        <script src="__PUBLIC__/js/jquery-ui.min.js"></script>
        <script src="__PUBLIC__/js/my97/WdatePicker.js"></script>
        <script src="/Public/js/layer/layer.js"></script>
        <script src="__PUBLIC__/js/admin.js"></script>
        <script src="__PUBLIC__/js/echarts-all-3.js"></script>
        
        <link rel="stylesheet" type="text/css" href="/static/default/webuploader/webuploader.css">
		<script src="/static/default/webuploader/webuploader.min.js"></script>
    </head>
    
    
    </head>
<style type="text/css">
#ie9-warning{ background:#F00; height:38px; line-height:38px; padding:10px;
position:absolute;top:0;left:0;font-size:12px;color:#fff;width:97%;text-align:left; z-index:9999999;}
#ie6-warning a {text-decoration:none; color:#fff !important;}
</style>

<!--[if lte IE 9]>
<div id="ie9-warning">您正在使用 Internet Explorer 9以下的版本，请用谷歌浏览器访问后台、部分浏览器可以开启极速模式访问！不懂点击这里！" target="_blank">查看为什么？</a>
</div>
<script type="text/javascript">
function position_fixed(el, eltop, elleft){  
       // check if this is IE6  
       if(!window.XMLHttpRequest)  
              window.onscroll = function(){  
                     el.style.top = (document.documentElement.scrollTop + eltop)+"px";  
                     el.style.left = (document.documentElement.scrollLeft + elleft)+"px";  
       }  
       else el.style.position = "fixed";  
}
       position_fixed(document.getElementById("ie9-warning"),0, 0);
</script>
<![endif]-->


    <body>
         <iframe id="x-frame" name="x-frame" style="display:none;"></iframe>
   <div class="main">
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">拼团</li>
        <li class="li2">拼团分类</li>
        <li class="li2 li3">操作分类</li>
    </ul>
</div>

<div class="main-tu-js">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin:10px 20px;">
            <div class="left">
                <?php echo BA('group/typePublish',array('id'=>$id),'刷新本页','','',600,360);?>
            </div>
        </div>
 </div>       
        
<form target="x-frame" action="<?php echo U('group/typePublish',array('id'=>$id));?>" method="post">
    <div class="main-tudou-sc-add" style="margin-top:10px;">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            	<input type="hidden" name="data[id]" value="<?php echo ($id); ?>" class="tudou-manageInput"/>
                
               
				<tr>
                <td class="tu-left-td">图片：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height:150px;float:left;">
                        <input type="hidden" name="data[img]" value="<?php echo ($detail["img"]); ?>" id="data_img"/>
                        <div id="fileToUpload" >上传缩略图</div>
                    </div>
                    <div style="width:300px;height:150px; float:left;">
                        <img id="img_img" width="200" height="100" src="<?php echo config_img($detail['img']);?>"/>
                    </div>
                    <script>                                            
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
					});                                                 
					uploader.on('uploadSuccess', function(file,resporse){                             
						$("#data_img").val(resporse.url);                             
						$("#img_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on('uploadError', function(file){                             
						alert('上传出错');                         
					});                     
                    </script>
                </td>
            </tr>
                <tr>
                    <td class="tu-left-td">名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[name]" value="<?php echo (($detail["name"])?($detail["name"]):''); ?>" class="tudou-manageInput" />
					<code>分类名称</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">选择学校：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="school_id" name="data[school_id]" value="<?php echo (($detail["school_id"])?($detail["school_id"]):''); ?>"/>
                            <input class="tudou-sc-add-text-name w210 sj" type="text" name="Name" id="Name"  value="<?php echo ($school["Name"]); ?>"/>
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="seleSj">选择学校</a>
                        <code>中途不要选择学校，请注意编辑</code>
                    </td>
                </tr> 
                
                
                <tr>
                    <td class="tu-left-td">排序：</td>
                    <td class="tu-right-td"><input type="text" name="data[num]" value="<?php echo (($detail["num"])?($detail["num"]):''); ?>" class="tudou-manageInput" />
					<code>分类排序</code>
                    </td>
                </tr>

            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认操作" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>