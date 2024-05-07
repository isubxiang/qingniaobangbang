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
        <li class="li1">设置</li>
        <li class="li2">上传设置</li>
		<li class="li2 li3">配置列表</li>
    </ul>
</div>
<style>
.main-tu-js .tudou-js-nr, .main-tudou-sc-add .tudou-js-nr { border: 0px solid #dbdbdb;}
</style>
<div class="main-tu-js">
    <p class="attention"><span>注意：</span>只能开启一种上传方式，不能都设置为启用状态！不会配置联系：1--2--0--5---8--5--022</p>
    <div class="tudou-js-nr">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50">ID</td>
                        <td>名称</td>
                        <td>accessKey</td>
                        <td>secrectKey</td>
                        <td>域名</td>
                        <td>空间名</td>
                        <td>超时时间</td>
                        <td>是否开启(0关闭1开启)</td>                
                        <td class="w120">操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): $config = (json_decode($var['para'], true)); ?>
                        <tr>
                            <td><?php echo ($var["id"]); ?></td>
                            <td><?php echo ($var["type"]); ?></td>
                            <td><?php echo ($config["accessKey"]); ?></td>
                            <td><?php echo ($config["secrectKey"]); ?></td>
                            <td><?php echo ($config["domain"]); ?></td> 
                            <td><?php echo ($config["bucket"]); ?></td> 
                            <td><?php echo ($config["timeout"]); ?></td>  
                            <td><?php echo ($var["status"]); ?></td>            
                            <td>
                                <?php echo BA('upset/edit',array("id"=>$var["id"]),'编辑','','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
            </div>
    </div>
</div>
  		</div>
	</body>
</html>