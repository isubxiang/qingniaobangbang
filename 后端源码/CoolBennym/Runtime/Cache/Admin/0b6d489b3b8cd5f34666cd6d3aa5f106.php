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
        <li class="li2">支付设置</li>
        <li class="li2 li3">支付方式</li>
    </ul>
</div>
<div class="main-tu-js">
    <p class="attention"><span>注意：</span>支付方式配置，目前先开通支付宝微信，那些不常用的支付方式删除了，没卵用！</p>
    <div class="tudou-js-nr">
        <div class="title" style="margin-bottom: 10px;">支付方式</div>
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td>ID</td>
                    <td>支付名称</td>
                    <td>支付别名</td>
                    <td>PC支付图标</td>
                    <td>WAP支付图标</td>
                    <td>说明</td>
                    <td>错误返回说明</td>
                    <td>操作</td>   
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td class="w50"><?php echo ($var["payment_id"]); ?></td>
                        <td width="100"><?php echo ($var["name"]); ?></td>
                        <td width="100"><?php echo ($var["code"]); ?></td>
                        <td width="60"><img style="width:50px;" src="/static/default/wap/image/pay/<?php echo ($var["mobile_logo"]); ?>"></td>
                        <td width="60"><?php if(($var["is_mobile_only"]) == "0"): ?><img style="width:50px;" src="/static/default/wap/image/pay/<?php echo ($var["logo"]); ?>"><?php endif; ?></td>
                        <td style="text-align: left;"><?php echo ($var["contents"]); ?></td>
                        <td style=" color:#F00"><?php echo ($var["error_intro"]); ?></td>
                        <td class="w80">
                            <?php if(($var["is_open"]) == "1"): echo BA('payment/uninstall',array("payment_id"=>$var["payment_id"]),'卸载','act','tu-dou-btn');?>
                             <?php echo BA('payment/install',array("payment_id"=>$var["payment_id"]),'编辑','','tu-dou-btn');?>
                    <?php else: ?>
                    <?php echo BA('payment/install',array("payment_id"=>$var["payment_id"]),'安装','','tu-dou-btn'); endif; ?>
                    </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
    </div>
</div>
  		</div>
	</body>
</html>