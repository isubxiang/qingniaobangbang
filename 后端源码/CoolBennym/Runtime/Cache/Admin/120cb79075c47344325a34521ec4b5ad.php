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
.main-cate .tudou-js-nr .tu-table-box { border-bottom: 1px solid #dbdbdb;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">主题</li>
        <li class="li2">主题分类</li>
        <li class="li2 li3">分类列表</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>暂时只支持一级分类，贴吧暂时没必要二级分类，需要开发更多论坛插件联系QQ：120-585-022</p>
    <form  target="x-frame" method="post">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="border-top: 1px solid #dbdbdb;">
            <div class="left">
                <?php echo BA('threadcate/create','','添加分类','load','',600,480);?>
            </div>
            <div class="right">
                 <?php echo BA('threadcate/update','','更新','list','tu-dou-btn');?>
            </div>
        </div>

        <div class="tu-table-box">
            
                <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF; text-align:center;">
                    <tr bgcolor="#fefefe" height="48px">
                    	<td>分类ID</td>
                        <td>分类名称</td>
                        <td>贴吧数量</td>
                        <td>贴吧图片</td>
                        <td>贴吧价格</td>
                        <td>排序</td>
                        <td>操作</td>
                    </tr>

                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr height="48px">
                        	<td><?php echo ($var["cate_id"]); ?></td>
                            <td><?php echo ($var["cate_name"]); ?></td>
                            <td style="text-align:center"><?php echo ($var["count"]); ?></td>
                            <td><img class="w80" src="<?php echo config_img($var['photo']);?>"/></td>
                            <td>¥<?php echo round($var['money']/100,2);?>元</td>
                            <td style="padding-left:70px;"><input name="orderby[<?php echo ($var["cate_id"]); ?>]" value="<?php echo ($var["orderby"]); ?>" type="text" class="remberinput w80" /></td>
                            <td style="text-align:center;"> 
                                <?php echo BA('threadcate/edit',array("cate_id"=>$var["cate_id"]),'编辑','load','tu-dou-btn',600,480);?>
                                <?php echo BA('threadcate/delete',array("cate_id"=>$var["cate_id"]),'删除','act','tu-dou-btn');?>
                                <?php echo BA('thread/index',array("cate_id"=>$var["cate_id"]),'主题列表[子分类]','','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>     
                </table>
                <div class="tu-select-nr">
                    <div class="left">
                        <?php echo BA('threadcate/update','','更新分类排序','list','tu-dou-btn');?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
  		</div>
	</body>
</html>