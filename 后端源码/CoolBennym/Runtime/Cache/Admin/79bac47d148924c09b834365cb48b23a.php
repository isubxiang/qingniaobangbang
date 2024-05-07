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
        <li class="li2">广告位</li>
        <li class="li2 li3">新增</li>
    </ul>
</div>

<p class="attention"><span>注意：</span>添加前，以及模板调用方法看</p>


<div class="main-tudou-sc-add">
	<form  target="x-frame" action="<?php echo U('adsite/create');?>" method="post">
		<div class="tu-table-box">
       
            <table class="table table-hover">
                <tr>
                    <td class="tu-left-td">广告位名称：</td>
                    <td  class="tu-right-td"><input type="text" name="data[site_name]" value="<?php echo (($detail["site_name"])?($detail["site_name"]):''); ?>" class="tudou-manageInput w300" />
                    </td>
                </tr>
				
                <tr>
                    <td class="tu-left-td">模版选择：</td>
                    <td  class="tu-right-td">
						<select name="data[theme]">
							<?php if(is_array($template)): foreach($template as $key=>$item): ?><option value="<?php echo ($item[theme]); ?>"><?php echo ($item[name]); ?></option><?php endforeach; endif; ?>
						</select>
                    </td>
                </tr>
				
                <tr>
                    <td class="tu-left-td">广告位类型：</td>
                    <td  class="tu-right-td">
                        <select name="data[site_type]" class="tu-manage-select w300">
                            <?php if(is_array($types)): foreach($types as $key=>$var): ?><option value="<?php echo ($key); ?>"><?php echo ($var); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">广告位位置：</td>
                    <td  class="tu-right-td">
                        <select name="data[site_place]" class="tu-manage-select w300">
                            <?php if(is_array($place)): foreach($place as $key=>$var): ?><option value="<?php echo ($key); ?>"><?php echo ($var); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">广告位售价：</td>
                    <td class="tu-right-td"><input type="text" name="data[site_price]" value="<?php echo (($detail["site_price"])?($detail["site_price"]):''); ?>" class="tudou-manageInput w80" />
                    <code>这里是一天的售价，必须为整数，不支持小数点</code>
                    </td>
                </tr> 
                
            </table>
		</div>
		<div class="sm-qr-tu"><input type="submit" value="确认添加" class="sm-tudou-btn-input" /></div>
	</form>
</div>

</div>
</div>



  
  		</div>
	</body>
</html>