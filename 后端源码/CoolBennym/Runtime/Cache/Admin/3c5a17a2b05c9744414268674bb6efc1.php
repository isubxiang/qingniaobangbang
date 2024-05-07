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
        <li class="li1">功能</li>
        <li class="li2">短信管理</li>
        <li class="li2 li3">大于短信列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span> 每页显示50条，可批量删除<?php if(empty($keyword)): ?>，已请求<?php echo ($count); ?>次<?php endif; ?></p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('dayu/index','','大于模板管理');?>
                <?php echo BA('setting/sms','','短信配置');?>
            </div>
            <div class="right">
                <form  method="post"  action="<?php echo U('dayusms/index');?>"> 
                    <div class="seleHidden" id="seleHidden">
                        <div class="seleK">
                            <span>手机号码</span>
                            <input type="text"  class="tu-inpt-text" name="keyword" value="<?php echo ($keyword); ?>"  />
                            <input type="submit" value=" 搜索"  class="inpt-button-tudou" />
                        </div>
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="sms_id" /></td>
                        <td>短信记录ID</td>  
                        <td>使用签名</td>
                        <td>本地模板调用</td>
                        <td>接收号码</td>
                        <td>发送状态</td>
                        <td>返回内容</td>
                        <td>内容</td>
                        <td>创建时间</td>
                        <td>创建IP</td>
                        <td>操作</td>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_sms_id" type="checkbox" name="sms_id[]" value="<?php echo ($var["sms_id"]); ?>" /> </td>
                            <td><?php echo ($var["sms_id"]); ?></td>
                            <?php $sign = $var['sign']; $sign_array = array(); if(!empty($sign)){ $sign_array= explode('-',$sign); } ?>
                            <td><?php echo ($sign_array[0]); ?></td>
                            <td><?php echo ($var["code"]); ?></td>
                            <td><?php echo ($var["mobile"]); ?></td>
                            <td><?php if(($var["status"]) == "0"): ?>失败<?php else: ?>成功<?php endif; ?></td>
                            <td><?php echo ($var["info"]); ?></td>
                            <td><?php echo ($var["content"]); ?></td>
                           <td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                           <td><?php echo ($var["create_ip"]); echo ($var["create_ip_area"]); ?></td>
                        <td>
                            <?php echo BA('dayusms/delete',array("sms_id"=>$var["sms_id"]),'删除','act','tu-dou-btn');?>
                        </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                    <?php echo BA('dayusms/delete','','批量删除','list',' a2');?>
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>