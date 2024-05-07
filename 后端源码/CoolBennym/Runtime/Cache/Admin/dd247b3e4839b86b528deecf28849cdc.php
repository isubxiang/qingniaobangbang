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
        <li class="li1">系统</li>
        <li class="li2">管理员管理</li>
        <li class="li2 li3">管理员登录日志</li>
    </ul>
</div>
<div class="main-tu-js">
    <p class="attention"><span>注意：</span>这里是管理员登录，当前搜索结果<?php echo ($count); ?>条</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr">
            <div class="right">
                <form method="post" action="<?php echo U('admin/log');?>">
                 <div class="seleHidden" id="seleHidden">
                 
                 
                 
                        <div class="seleK">
                        <label>
                            <span>开始时间</span>
                            <input type="text" name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>"  onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text w150" />
                        </label>
                        <label>
                            <span>结束时间</span>
                            <input type="text" name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text w150" />
                        </label>
                        
                        	<label>
                                <span>类型：</span>
                                <select class="select w120" name="type">
                                    <option <?php if(($type) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                                    <option <?php if(($type) == "1"): ?>selected="selected"<?php endif; ?>  value="1">会员</option>
                                    <option <?php if(($type) == "2"): ?>selected="selected"<?php endif; ?>  value="2">管理员</option>
                                    <option <?php if(($type) == "3"): ?>selected="selected"<?php endif; ?>  value="3">ajax请求</option>
                                </select>
                            </label>
                            
                            
                			<label>
                                <span>登录状态：</span>
                                <select class="select w120" name="audit">
                                    <option <?php if(($audit) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                                    <option <?php if(($audit) == "0"): ?>selected="selected"<?php endif; ?>  value="0">失败</option>
                                    <option <?php if(($audit) == "1"): ?>selected="selected"<?php endif; ?>  value="1">成功</option>
                                </select>
                            </label>
                            </div>
        				</div> 
                    <input type="text"  class="tu-inpt-text" name="keyword" value="<?php echo ($keyword); ?>"/>
                    <input type="submit" value="  搜索"  class="inpt-button-tudou" />
                </form>
            </div>
        </div>
        <form target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50">ID</td>
                        <td>类型</td>
                        <td>登录用户名</td>
                        <td>登录密码</td>
                        <td>登录时间</td>
                        <td>登录IP</td>
                        <td>登录状态</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><?php echo ($var["id"]); ?></td>
                            <td>
                                <?php if(($var["type"]) == "1"): ?>会员<?php endif; ?>
                                <?php if(($var["type"]) == "2"): ?>管理员<?php endif; ?>
                                <?php if(($var["type"]) == "3"): ?>ajax请求<?php endif; ?>
                            </td>
                            <td><?php echo ($var["username"]); ?></td>
                            <td><?php echo ($var["password"]); ?></td>
                            <td><?php echo (date("Y-m-d H:i:s",$var["last_time"])); ?></td>
                            <td><?php echo ($var["last_ip"]); ?>(<?php echo ($var["last_ip_area"]); ?>)</td>
                            <td><?php if(($var["audit"]) == "0"): ?>失败<?php else: ?>成功<?php endif; ?></td>
                        </tr><?php endforeach; endif; ?>

                </table>
                <?php echo ($page); ?>
            </div>
        </form>

    </div>
</div>
  		</div>
	</body>
</html>