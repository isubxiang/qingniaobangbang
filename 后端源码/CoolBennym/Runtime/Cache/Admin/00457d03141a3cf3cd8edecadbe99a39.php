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
<style>
.tu-left-td{width:200px;}
.profit{text-align:center; color:#000; font-weight:bold; background:#ECECEC;}
</style>


<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">商家功能</li>
        <li class="li2 li3">功能设置</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>商家的基本设置写在这里</p>
</div>  

     
<div class="main-tudou-sc-add">
    <div class="tu-table-box">
        <form  target="x-frame" action="<?php echo U('setting/shop');?>" method="post">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
          
                
                <tr><td class="tu-right-td profit" colspan = "2"> 商家入驻设置  </td></tr>
                <tr>
                    <td  class="tu-left-td">商家入驻扣除费用：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[shop_apply_prrice]" value="<?php echo ($CONFIG["shop"]["shop_apply_prrice"]); ?>" class="tudou-sc-add-text-name  w80" />
                        <code>单位元，不支持负数，建议填写整数，不填写或者填写为0不生效，具体是在商家入驻成功后扣除相关费用，如果会员余额不足，无法入驻，建议留空或者填写为0。</code>
                    </td>
                </tr>
               
            </table>
            <div class="sm-qr-tu"><input type="submit" value="确认设置" class="sm-tudou-btn-input" /></div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>