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
        <li class="li1">主题</li>
        <li class="li2">主题管理</li>
        <li class="li2 li3">编辑话题</li>
    </ul>
</div>
<form  target="x-frame" action="<?php echo U('threadpost/edit',array('post_id'=>$detail['post_id']));?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
                
                <tr>
                    <td class="tu-left-td">标题：</td>
                    <td class="tu-right-td">
                        <textarea name="data[title]" cols="80" rows="4"><?php echo ($detail["title"]); ?></textarea>
                    </td>
                </tr>
              	
               
                
               <tr>
                    <td class="tu-left-td">主题分类：</td>
                    <td class="tu-right-td">
                        <select id="cate_id" name="data[cate_id]" class="seleFl w210"  style="float:left;">
                            <option value="">=请选择分类=</option>
                            <?php if(is_array($cates)): $i = 0; $__LIST__ = $cates;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$var): $mod = ($i % 2 );++$i;?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <select name="data[thread_id]" id="thread_id" class="seleFl w210"  style="float:left;">
                            <option value="0">=请选择所属贴吧=</option>
                            <?php if(is_array($threads)): foreach($threads as $key=>$var2): ?><option value="<?php echo ($var2["thread_id"]); ?>"  <?php if(($var2["thread_id"]) == $detail["thread_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var2["thread_name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>   
                
                
                
                <script>
					$(document).ready(function(e){
						$("#cate_id").change(function (){
							var url = '<?php echo U("thread/child",array("cate_id"=>"0000"));?>';
							if($(this).val() > 0){
								var url2 = url.replace('0000', $(this).val());
								$.get(url2, function (data){
									$("#thread_id").html(data);
								}, 'html');
							}
						});
					});
				</script>
				
                
                <tr>
                    <td class="tu-left-td">用户：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="user_id" name="data[user_id]" value="<?php echo (($detail["user_id"])?($detail["user_id"]):''); ?>" class="tudou-manageInput" />
                            <input type="text" name="nickname" id="nickname"  value="<?php echo ($user["nickname"]); ?>"  class="tudou-manageInput" />
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="tu-dou-btn">选择用户</a>
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
            		<tr>
                    <td class="tu-left-td">贴吧主图：</td>
                 <td class="tu-right-td">
                    <div style="width:300px;height:150px; float:left;">
                        <input type="hidden" name="data[photo]" value="<?php echo ($detail["photo"]); ?>" id="data_photo" />
                        <div id="fileToUpload1">操作贴吧主图</div>
                    </div>
                    <div style="width:300px;height:150px;float:left;">
                        <img id="photo_img" width="120" height="80"  src="<?php echo config_img($detail['photo']);?>" />
                    </div>
                    <script>                                            
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
						pick: '#fileToUpload1',                             
						resize: true,  
						compress :{width:800,height:600,quality:100,allowMagnify: false,crop: true}                       
					});                                                 
					uploader.on('uploadSuccess', function(file,resporse){                             
						$("#data_photo").val(resporse.url);                             
						$("#photo_img").attr('src',resporse.url).show();    
						layer.msg('上传成功');                        
					});                                                
					uploader.on('uploadError', function(file){                             
						layer.msg('上传出错');                         
					});                     
                    </script>
                </td>
            </tr> 
            
            
                
                  <tr>
                  <td class="tu-left-td">贴吧图片：</td>
                        <td class="tu-right-td">
                            <div>
                                <div id="moreToUpload" >上传图片</div>
                            </div>							
							<script>                                
									var width_thread = '<?php echo thumbSize($CONFIG[attachs][thread][thumb],0);?>';                           
									var height_thread = '<?php echo thumbSize($CONFIG[attachs][thread][thumb],1);?>';
									var pic_pc_quality = '<?php echo ($CONFIG[attachs][pic_pc_quality][thumb]); ?>';  
									var uploader = WebUploader.create({                                    
									auto: true,                                    
									swf: '/static/default/webuploader/Uploader.swf',                                    
									server: '<?php echo U("app/upload/uploadify",array("model"=>"thread"));?>',                                    
									pick: '#moreToUpload',                                    
									fileNumberLimit:10,                                    
									resize: true, 
									crop: false,  
									
									compress : {width: width_thread,height: height_thread,quality: pic_pc_quality,allowMagnify: false,crop: true}    													
								});                                                               
								uploader.on('uploadSuccess',function(file,resporse){                                    
								var str = '<span style="width: 150px; height: 130px; float: left; margin-left: 5px; margin-top: 10px;"><img width="150" height="100" src="' + resporse.url + '"><input type="hidden" name="thumb[]" value="' + resporse.url + '" />    <a href="javascript:void(0);">取消</a>  </span>';                                   
								 $(".jq_uploads_img").append(str);                                
									 });                                                            
								 uploader.on('uploadError',function(file){                                    
									alert('上传出错');                                
								 });                                
								 $(document).on("click",".jq_uploads_img a",function(){                                    
									 $(this).parent().remove();                                
								 });                            
                             </script>
                            <div class="jq_uploads_img">
                                <?php if(is_array($thumb)): foreach($thumb as $key=>$item): ?><span style="width: 150px; height: 130px; float: left; margin-left: 5px; margin-top: 10px;"> 
                                        <img width="150" height="100" src="<?php echo config_img($item['photo']);?>">  
                                        <input type="hidden" name="thumb[]" value="<?php echo ($item["photo"]); ?>" />  
                                        <a href="javascript:void(0);">取消</a>  
                                    </span><?php endforeach; endif; ?>
                            </div>					
                        </td>
                    </tr>
             <tr>
      
      
           <tr>
              <td class="tu-left-td">详细内容：</td>
                <td class="tu-right-td">
                <script type="text/plain" id="data_details" name="data[details]" style="width:800px;height:360px;"><?php echo ($detail["details"]); ?></script>
                </td>
                </tr><link rel="stylesheet" href="__PUBLIC__/qiniu_ueditor/themes/default/css/ueditor.min.css" type="text/css">
                    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.config.js"></script>
                    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.all.min.js"></script>
                    <script type="text/javascript" src="__PUBLIC__/qiniu_ueditor/lang/zh-cn/zh-cn.js"></script>
                <script>
                    um = UE.getEditor('data_details',{
                        lang: 'zh-cn',
						toolbars:[['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'backcolor', 'fontsize', 'fontfamily', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'link', 'unlink', 'map', 'template', 'background','inserttable','print','attachment',  'emotion',  'snapscreen','insertimage', 'music', 'insertvideo']],  
                        });
                </script>
                
                
               <tr>
                  <td class="tu-left-td">排序：</td>
                    <td class="tu-right-td"><input type="text" name="data[orderby]" value="<?php echo (($detail["orderby"])?($detail["orderby"]):''); ?>" class="tudou-manageInput"/>
                  </td>
                </tr>
                <tr>
                    <td class="tu-left-td">是否精华：</td>
                    <td class="tu-right-td">
                        <input type="radio" name="data[is_fine]"  <?php if(($detail["is_fine"]) == "1"): ?>checked="checked"<?php endif; ?>  value="1"/> 是
                        <input type="radio" name="data[is_fine]" <?php if(($detail["is_fine"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/> 否

                    </td>
                </tr>
                
                

    </table>
</div>
<div class="sm-qr-tu"><input type="submit" value="确认编辑" class="sm-tudou-btn-input" /></div>
</div>
</form>
  		</div>
	</body>
</html>