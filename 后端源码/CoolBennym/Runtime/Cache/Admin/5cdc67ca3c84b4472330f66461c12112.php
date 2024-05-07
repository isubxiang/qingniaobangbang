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
        <li class="li1">支付设置</li>
        <li class="li2">微信小程序支付</li>
        <li class="li2 li3">微信小程序支付安装</li>
    </ul>
</div>
<p class="attention"><span>注意：</span>这里不是小程序支付，别搞错了哦，这里配置后只适合小程序端支付</p>
<form target="x-frame" action="<?php echo U('payment/install',array('payment_id'=>$detail['payment_id']));?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                <tr>
                    <td class="tu-left-td">支付方式:</td>
                    <td class="tu-right-td">
                        <img src="__PUBLIC__/images/<?php echo ($detail["logo"]); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">支付方式介绍:</td>
                    <td class="tu-right-td" style="font-size: 14px;">
                        <?php echo ($detail["contents"]); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">AppId：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[appid]" value="<?php echo ($detail["setting"]["appid"]); ?>" class="tudou-sc-add-text-name w200" />
                        <code>微信小程序后台的AppId</code>
                    </td>
                </tr>  
                <tr>
                    <td class="tu-left-td">AppSecret：</td>
                    <td class="tu-right-td">
                          <input type="text" name="data[appsecret]" value="<?php echo ($detail["setting"]["appsecret"]); ?>"  class="tudou-sc-add-text-name w360" />
                          <code>微信小程序后台的AppSecret,跟您微信配置里面的AppSecret必须一致</code>
                    </td>
                </tr>  
                 <tr>
                    <td class="tu-left-td">商户ID：</td>
                    <td class="tu-right-td">
                          <input type="text" name="data[mchid]" value="<?php echo ($detail["setting"]["mchid"]); ?>"  class="tudou-sc-add-text-name w200" />
                          <code>微信小程序后台左侧微信支付，支付配置里面有商户号，填写这里，不要有空格</code>
                    </td>
                </tr>
				<tr>
                    <td class="tu-left-td">商户密钥：</td>
                    <td class="tu-right-td">
                          <input type="text" name="data[appkey]" value="<?php echo ($detail["setting"]["appkey"]); ?>"  class="tudou-sc-add-text-name w360" />
                          <code>微微信小程序后台自己设置的，记得不要有空格，否则支付失败！</code>
                    </td>
                </tr>
               
                 <tr>
                    <td class="tu-left-td">安全码：</td>
                     <td class="tu-right-td">
                          <input type="text" name="data[safety]" value="******"  class="tudou-sc-add-text-name w360" />
                          <code>输入你的网站安全码，输入错误会导致修改失败哦</code>
                    </td>
                </tr>
            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确定安装" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>