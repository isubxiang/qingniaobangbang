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
        <li class="li2">后台菜单设置</li>
        <li class="li2 li3">菜单列表</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>这里主要管理后台的菜单，如果关闭一级菜单显示，那么二级菜单已不显示，以此类推，操作完毕后记得清理缓存，通常后台的基本权限是和这类菜单关联的</p>
    <div class="tudou-js-nr">
        <form id="cate_action" action="<?php echo U('menu/update');?>" target="x-frame" method="post">
            <div class="tu-select-nr" style="border-top: 1px solid #e1e6eb;">
                <div class="left">
                    <?php echo BA('menu/create','','添加菜单','load','',600,280);?>
                </div>
                <div class="right">
                     <input type="submit" class="sBtn" value="更新"  />
                </div>
            </div>
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF; text-align:center;">
                    <tr bgcolor="#F5F6FA" height="35px;" style="color:#333; font-size:16px; line-height:35px;">
                        <td>分类</td>
                        <td>排序</td>
                        <td>状态</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($datas)): foreach($datas as $key=>$var): if(($var["parent_id"] == 0)): ?><tr bgcolor="#f1f1f1" height="48px" style="font-size:14px; color:#545454; text-align:left; line-height:48px;<?php if(($var["is_show"]) == "0"): ?>background-color:#f5f5f5;<?php endif; ?>">
                                <td style="padding-left:20px;"><?php echo ($var["menu_name"]); ?></td>
                                <td style="padding-left:70px;"><input name="orderby[<?php echo ($var["menu_id"]); ?>]" value="<?php echo ($var["orderby"]); ?>" type="text" class="remberinput w80" /></td>
                                <td style="padding-left:20px;"><?php if(($var["is_show"]) == "0"): ?>影藏<?php else: ?>显示<?php endif; ?></td>
                                <td style="text-align:center;">
                                    <?php echo BA('menu/create',array("parent_id"=>$var['menu_id']),'添加','load','tu-dou-btn',600,280);?>
                                    <?php echo BA('menu/edit',array("menu_id"=>$var['menu_id']),'编辑','load','tu-dou-btn',600,280);?>
                                    <?php if(($var["is_show"]) == "0"): echo BA('menu/is_show',array("menu_id"=>$var["menu_id"]),'开启显示','act','tu-dou-btn gray');?>
                                    <?php else: ?>
                                      <?php echo BA('menu/is_show',array("menu_id"=>$var["menu_id"]),'关闭显示','act','tu-dou-btn dot'); endif; ?>
                                    
                                    
                                    <?php echo BA('menu/delete',array("menu_id"=>$var['menu_id']),'删除','act','tu-dou-btn');?>
                                </td>
                            </tr>
                            <?php if(is_array($datas)): foreach($datas as $key=>$var2): if(($var2["parent_id"]) == $var["menu_id"]): ?><tr height="48px" style="font-size:14px; color:#545454; text-align:center; line-height:48px;<?php if(($var2["is_show"]) == "0"): ?>background-color:#f5f5f5;<?php endif; ?>">
                                    <td><?php echo ($var2["menu_name"]); ?></td>
                                    <td><input name="orderby[<?php echo ($var2["menu_id"]); ?>]" value="<?php echo ($var2["orderby"]); ?>" type="text" class="remberinput w80" /></td>
                                    <td style="padding-left:20px;"><?php if(($var2["is_show"]) == "0"): ?>影藏<?php else: ?>显示<?php endif; ?></td>
                                    <td>
                                        <?php echo BA('menu/action',array("parent_id"=>$var2['menu_id']),'添加','load','tu-dou-btn',800,500);?>
                                        <?php echo BA('menu/edit',array("menu_id"=>$var2['menu_id']),'编辑','load','tu-dou-btn',600,280);?>
                                        <?php if(($var2["is_show"]) == "0"): echo BA('menu/is_show',array("menu_id"=>$var2["menu_id"]),'开启显示','act','tu-dou-btn gray');?>
                                        <?php else: ?>
                                          <?php echo BA('menu/is_show',array("menu_id"=>$var2["menu_id"]),'关闭显示','act','tu-dou-btn dot'); endif; ?>
                                        <?php echo BA('menu/delete',array("menu_id"=>$var2['menu_id']),'删除','act','tu-dou-btn');?>
                                    </td>
                                </tr><?php endif; endforeach; endif; endif; endforeach; endif; ?>     
                </table>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>