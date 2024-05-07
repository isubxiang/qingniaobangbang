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
        <li class="li1">商家</li>
        <li class="li2">商家管理</li>
        <li class="li2 li3">商家等级</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>商家的等级图标建议传上去，还有商家内容，那个自动升级那个建议写好一点，中途不要随意更改！注意那个等级权重，不要重复，否则后果自负</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('shopgrade/create','','添加等级');?>
            </div>
        </div>
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="grade_id" /></td>
                        <td class="w50">ID</td>
                        <td>等级权重</td>
                        <td>等级名称</td>
                        <td>等级图标</td>
                        <td>独立买需要金额</td>
                        <td>自动升级需资金</td>
                        <td>描述</td> 
                        <td>商家数量</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_grade_id" type="checkbox" name="grade_id[]" value="<?php echo ($var["grade_id"]); ?>" /></td>
                            <td><?php echo ($var["grade_id"]); ?></td>
                            <td><a style="color:#F00; font-size:18px; font-weight:bold"><?php echo ($var["orderby"]); ?></a></td>
                            <td><?php echo ($var["grade_name"]); ?></td>
                            <td><img style="width:20px;" src="<?php echo config_img($var['photo']);?>"/></td>
                            <td> &yen; <?php echo round($var['money']/100,2);?>元</td>
                            <td>&yen; <?php echo round($var['gold']/100,2);?>元</td>
                            <td><?php echo ($var["content"]); ?></td>
                            <td><?php echo ($var["shop_count"]); ?></td>
                            <td>
                             <?php echo BA('shopgrade/edit',array("grade_id"=>$var["grade_id"]),'编辑','','tu-dou-btn');?>
                             <?php echo BA('shopgrade/edit_jurisdiction',array("grade_id"=>$var["grade_id"]),'权限管理','','tu-dou-btn');?>
                                <?php echo BA('shopgrade/delete',array("grade_id"=>$var["grade_id"]),'删除','act','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                    <?php echo BA('shopgrade/delete','','批量删除','list',' a2');?>
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>