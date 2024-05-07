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
        <li class="li1">当前位置</li>
        <li class="li2">文章</li>
        <li class="li2 li3">编辑</li>
    </ul>
</div>
        <form target="x-frame" action="<?php echo U('article/edit',array('article_id'=>$detail['article_id']));?>" method="post">
           <div class="main-tudou-sc-add">
        <div class="tu-table-box"> 
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            
               
                <tr>
                    <td class="tu-left-td">标题：</td>
                    <td class="tu-right-td"><input type="text" name="data[title]" value="<?php echo (($detail["title"])?($detail["title"]):''); ?>" class="tudou-manageInput" />
					<code>输入文章标题</code>
                    </td>
                </tr>
            
           		<tr>
                    <td class="tu-left-td">详细内容：</td>
                    <td class="tu-right-td">
                        <script type="text/plain" id="data_details" name="data[details]" style="width:800px;height:360px;"><?php echo ($detail["details"]); ?></script>
                    </td>
                </tr>
                
                <link rel="stylesheet" href="__PUBLIC__/qiniu_ueditor/themes/default/css/ueditor.min.css" type="text/css">
				<script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.config.js"></script>
                <script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.all.min.js"></script>
                <script type="text/javascript" src="__PUBLIC__/qiniu_ueditor/lang/zh-cn/zh-cn.js"></script>
                <script>
                    um = UE.getEditor('data_details',{
                        lang: 'zh-cn',
						toolbars:[['fullscreen','source','link','undo','redo','bold','italic','underline','fontborder','backcolor','fontsize']],                         });
                </script>
          
        
       
    </table>
            <div class="sm-qr-tu"><input type="submit" value="确认编辑" class="sm-tudou-btn-input" /></div>
</form>
</div>
</div>
  		</div>
	</body>
</html>