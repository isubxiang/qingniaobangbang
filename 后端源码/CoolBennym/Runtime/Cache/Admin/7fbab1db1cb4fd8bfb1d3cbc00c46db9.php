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
        <li class="li2">基本设置</li>
        <li class="li2 li3">积分设置</li>
    </ul>
</div>
<style>
.profit {text-align: center;color: #333;font-weight: bold; background: #F5F5FB;}
.tu-left-td{width:200px;}
</style>
<p class="attention"><span>注意：消费抵扣积分比例不要经常修改，出现的错误概不负责</span></p>
<form  target="x-frame" action="<?php echo U('setting/integral');?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
               	
                <tr>
                	<td class="tu-right-td profit" colspan="2"> ↓↓↓最重要的设置，积分在购物的时候抵扣比例，只能写0</td>
                </tr>
                
                
                
                <tr><td class="tu-right-td profit" colspan = "2"> ↓↓↓下面是各种操作奖励积分，二开请写下面！不能填写小数，只能填写整数</td></tr>
                
                
                <tr>
                    <td class="tu-left-td">绑定手机：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[mobile]" value="<?php echo ($CONFIG["integral"]["mobile"]); ?>" class="tudou-sc-add-text-name w150" />
                        <code>用户绑定手机获得的积分</code>
                    </td>
                </tr>  
                
                <tr>
                    <td class="tu-left-td">会员注册：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[register]" value="<?php echo ($CONFIG["integral"]["register"]); ?>" class="tudou-sc-add-text-name w150" />
                        <code>会员注册成功后默认奖励的积分，建议设置1-100分</code>
                    </td>
                </tr> 
                
                
            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认保存" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>