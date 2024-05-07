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
        <li class="li2">拼团商品</li>
        <li class="li2 li3">操作商品</li>
    </ul>
</div>

<div class="main-tu-js">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin:10px 20px;">
            <div class="left">
                <?php echo BA('group/goodsPublish',array('id'=>$id),'刷新本页','','',600,360);?>
            </div>
        </div>
 </div>       
        
<form target="x-frame" action="<?php echo U('group/goodsPublish',array('id'=>$id));?>" method="post">
    <div class="main-tudou-sc-add" style="margin-top:10px;">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            	<input type="hidden" name="data[id]" value="<?php echo ($id); ?>" class="tudou-manageInput"/>
                
                
                <tr>
                    <td class="tu-left-td">选择商家：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="shop_id" name="data[shop_id]" value="<?php echo (($detail["shop_id"])?($detail["shop_id"]):''); ?>"/>
                            <input type="text" id="shop_name" name="shop_name" value="<?php echo ($shop["shop_name"]); ?>" class="tudou-manageInput" />
                        </div>
                        <a mini="select"  w="1000" h="600" href="<?php echo U('shop/select');?>" class="tu-dou-btn">选择商家</a>
                        <code>必须选择商家</code>
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
                    <td class="tu-left-td">图片：</td>
                     <td class="tu-right-td">
                        <div style="width: 300px;height:150px;float:left;">
                            <input type="hidden" name="data[logo]" value="<?php echo ($detail["logo"]); ?>" id="data_logo"/>
                            <div id="fileToUpload" >上传缩略图</div>
                        </div>
                        <div style="width:300px;height:150px; float:left;">
                            <img id="logo_img" width="200" height="100" src="<?php echo config_img($detail['logo']);?>"/>
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
                            $("#data_logo").val(resporse.url);                             
                            $("#logo_img").attr('src',resporse.url).show();                         
                        });                                                
                        uploader.on('uploadError', function(file){                             
                            alert('上传出错');                         
                        });                     
                        </script>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[name]" value="<?php echo (($detail["name"])?($detail["name"]):''); ?>" class="tudou-manageInput w360"/>
					<code>商品名称</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">排序：</td>
                    <td class="tu-right-td"><input type="text" name="data[num]" value="<?php echo (($detail["num"])?($detail["num"]):''); ?>" class="tudou-manageInput w80"/>
					<code>商品排序</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">商品分类：</td>
                    <td class="tu-right-td">
                       <select id="grade_id" name="data[type_id]" class="seleFl w210">
                       <option value="0">请选择...</option>
                        <?php if(is_array($types)): foreach($types as $key=>$var): ?><option value="<?php echo ($var["id"]); ?>"  <?php if(($var["id"]) == $detail["type_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <code>必须选择分类</code>
                    </td>
                </tr>   
                <tr>
                    <td class="tu-left-td">拼团售价：</td>
                    <td class="tu-right-td"><input type="text" name="data[pt_price]" value="<?php echo (($detail["pt_price"])?($detail["pt_price"]):''); ?>" class="tudou-manageInput w80"/>
					<code>拼团售价</code>
                    </td>
                </tr>
				<tr>
                    <td class="tu-left-td">拼团原价：</td>
                    <td class="tu-right-td"><input type="text" name="data[y_price]" value="<?php echo (($detail["y_price"])?($detail["y_price"]):''); ?>" class="tudou-manageInput w80"/>
					<code>拼团原价</code>
                    </td>
                </tr>
          
                
                <tr>
                    <td class="tu-left-td">单独价格：</td>
                    <td class="tu-right-td"><input type="text" name="data[dd_price]" value="<?php echo (($detail["dd_price"])?($detail["dd_price"]):''); ?>" class="tudou-manageInput w80"/>
					<code>单独价格</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">商品库存：</td>
                    <td class="tu-right-td"><input type="text" name="data[inventory]" value="<?php echo (($detail["inventory"])?($detail["inventory"]):''); ?>" class="tudou-manageInput w80"/>
					<code>商品库存</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">请输入商品销量：</td>
                    <td class="tu-right-td"><input type="text" name="data[ysc_num]" value="<?php echo (($detail["ysc_num"])?($detail["ysc_num"]):''); ?>" class="tudou-manageInput w80"/>
					<code>请输入商品销量</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">已成团数量：</td>
                    <td class="tu-right-td"><input type="text" name="data[ycd_num]" value="<?php echo (($detail["ycd_num"])?($detail["ycd_num"]):''); ?>" class="tudou-manageInput w80"/>
					<code>已成团数量</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">开团人数：</td>
                    <td class="tu-right-td"><input type="text" name="data[people]" value="<?php echo (($detail["people"])?($detail["people"]):''); ?>" class="tudou-manageInput w80"/>
					<code>开团人数</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">商品简介：</td>
                    <td class="tu-right-td"><input type="text" name="data[introduction]" value="<?php echo (($detail["introduction"])?($detail["introduction"]):''); ?>" class="tudou-manageInput w360"/>
					<code>商品简介</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">活动日期：</td>
                    <td class="tu-right-td">
                    <code>开始日期</code>
                    <input type="text" name="data[start_time]" value="<?php echo (($detail['start_time'])?($detail['start_time']):""); ?>" onfocus="WdatePicker({ dateFmt: 'yyyy-MM-dd HH:mm:ss' })"  class="inputData"/>
                    <code>结束日期</code>
                    <input type="text" name="data[end_time]" value="<?php echo (($detail['end_time'])?($detail['end_time']):""); ?>" onfocus="WdatePicker({ dateFmt: 'yyyy-MM-dd HH:mm:ss' })"  class="inputData"/>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">消费截止时间：</td>
                    <td class="tu-right-td">
                    <input type="text" name="data[xf_time]" value="<?php echo (($detail['xf_time'])?($detail['xf_time']):""); ?>" onfocus="WdatePicker({ dateFmt: 'yyyy-MM-dd HH:mm:ss' })"  class="inputData"/>
                    <code>消费截止时间</code>
                    </td>
                </tr>
                
                <link rel="stylesheet" href="__PUBLIC__/qiniu_ueditor/themes/default/css/ueditor.min.css" type="text/css">
				<script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.config.js"></script>
                <script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.all.min.js"></script>
                <script type="text/javascript" src="__PUBLIC__/qiniu_ueditor/lang/zh-cn/zh-cn.js"></script>
                
                <tr>
                    <td class="tu-left-td">商品详情：</td>
                    <td class="tu-right-td"><script type="text/plain" id="data_details" name="data[details]" style="width:800px;height:360px;"><?php echo ($detail["details"]); ?></script>
                    <code>商品详情</code>
                    </td>
                </tr>
        	 <script>
                    um = UE.getEditor('data_details',{
                        lang: 'zh-cn',
						toolbars:[['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontborder', 'backcolor', 'fontsize', 'fontfamily', 'justifyleft', 'justifyright', 'justifycenter', 'justifyjustify', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', 'link', 'unlink', 'map', 'template', 'background','inserttable','print','attachment',  'emotion',  'snapscreen','insertimage', 'music', 'insertvideo']],  
                        });
                </script>
            
            
            	 <tr>
                  <td class="tu-left-td">更多图片：</td>
                    <td class="tu-right-td">
                    <div>
                        <div id="moreToUpload" >上传图片</div>
                    </div>							
                    <script>                                
                        var width2 = '<?php echo thumbSize($CONFIG[attachs][lifepic][thumb],0);?>';  //获取宽度                              
                        var height2 = '<?php echo thumbSize($CONFIG[attachs][lifepic][thumb],1);?>'; //获取高度 
                        var uploader = WebUploader.create({                                    
                            auto: true,                                    
                            swf: '/static/default/webuploader/Uploader.swf',                                    
                            server: '<?php echo U("app/upload/uploadify",array("model"=>"lifepic"));?>',                                    
                            pick: '#moreToUpload',                                    
                            fileNumberLimit:10,                                    
                            resize: true, 
                            crop: false,  
                            compress : {width:width2,height:height2,quality:100,allowMagnify: false,crop: true},														
                        });                                                               
                        uploader.on('uploadSuccess',function(file,resporse){                                    
                        var str = '<span style="width:220px;height:130px;float:left;margin-left:5px;margin-top:5px;"><img width="200" height="100" src="' + resporse.url + '"><input type="hidden" name="photos[]" value="' + resporse.url + '"/><a href="javascript:void(0);">取消</a></span>';                                   
                         $(".jq_uploads_img").append(str);                                
                        });                                                            
                         uploader.on( 'uploadError', function(file){                                    
                            alert('上传出错');                                
                         });                                
                         $(document).on("click",".jq_uploads_img a",function(){                                    
                             $(this).parent().remove();                                
                         });                            
                     </script>
                    <div class="jq_uploads_img">
                        <?php if(is_array($thumb)): foreach($thumb as $key=>$item): ?><span style="width:220px;height:130px;float:left;margin-left:5px;margin-top:5px;"> 
                                <img width="200" height="100" src="<?php echo config_img($item);?>">  
                                <input type="hidden" name="photos[]" value="<?php echo ($item); ?>" />  
                                <a href="javascript:void(0);">取消</a>  
                            </span><?php endforeach; endif; ?>
                    </div>					
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