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
        <li class="li2">广告管理</li>
        <li class="li2 li3">广告位设置</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <div class="tudou-js-nr">
        <form  target="x-frame" method="post">
            <div class="tu-table-box" style="margin-top: 20px; margin-bottom: 20px;">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
              
                    <?php if(is_array($place)): $pl = 0; $__LIST__ = $place;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($pl % 2 );++$pl;?><tr>
                            <td colspan="4" style="text-align:left;padding-left:20px;font-size:16px;font-weight:bold; background:#F5F5F5"><?php echo ($item); ?></td>
                        </tr>
                        <tr>
                        <?php $i=0; ?>
                        <?php if(is_array($adsite)): foreach($adsite as $key=>$var): if($var['site_place'] == $pl): ?><td>
                                        <b>（<?php echo ($var["site_id"]); ?>）</b>&nbsp; <?php echo ($var["site_name"]); ?>
                                        <?php echo BA('adsite/edit',array("site_id"=>$var["site_id"]),'编辑','','tu-dou-btn');?>
                                        <?php echo BA('adsite/delete',array("site_id"=>$var["site_id"]),'删除','act','tu-dou-btn');?>
                                        <?php echo BA('ad/index',array("site_id"=>$var["site_id"]),'管理广告','','tu-dou-btn');?>
                                    </td>
                                    <?php $i++; if($i%2==0) echo '</tr><tr>'; endif; endforeach; endif; ?>
                           </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                   
                </table>
              
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>