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
.profit {text-align: center;color: #333;font-weight: bold; background: #F5F5FB;}
.tu-left-td{width:220px;}
.sogn{ width:120px; margin:5px 0;height: 30px;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">基本设置</li>
        <li class="li2 li3">支付设置</li>
    </ul>
</div>
<p class="attention"><span>这里写一些支付基本设置</span>注意：以后关于支付设置全部写这里</p>
<form  target="x-frame" action="<?php echo U('setting/pay');?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            
            
            	
                
              <tr>
                    <td class="tu-left-td">用户申请提现默认选择支付：</td>
                    <td class="tu-right-td">
                        <select name="data[cash]"  class="tudou-sc-add-text-name sogn">
                        	<option value="" <?php if($CONFIG['pay']['cash'] == ''): ?>selected='selected'<?php endif; ?>>请选择方式</option>
                            <option value="weixin" <?php if($CONFIG['pay']['cash'] == weixin): ?>selected='selected'<?php endif; ?>>微信</option>
                            <option value="bank" <?php if($CONFIG['pay']['cash'] == bank): ?>selected='selected'<?php endif; ?>>银行卡</option>
                            <option value="alipay" <?php if($CONFIG['pay']['cash'] == alipay): ?>selected='selected'<?php endif; ?>>支付宝</option>
                        </select>
                        <code>用户在会员中心收你钱提现的时候默认提现付款方式</code></td>
                </tr>
                
                <tr>
                  <td class="tu-right-td profit" colspan = "2"> 支付密码设置 </a></td>
                </tr>
                    
				
                
            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认保存" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>