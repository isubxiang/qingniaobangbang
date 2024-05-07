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
        <li class="li2 li3">角色管理</li>
    </ul>
</div>
<div class="main-tu-js">
    <p class="attention"><span>注意：</span>每个角色有对应的权限，默认超级管理员角色不能删除！</p>
    <div class="tudou-js-nr" style="  border-top: 1px solid #e1e6eb;">
        <div class="tu-select-nr">
            <div class="left">
                <?php echo BA('role/create','','添加角色');?>
            </div>
            <form method="post" action="<?php echo U('role/index');?>">
                <div class="right">
                	<div class="seleHidden" id="seleHidden">
                        <div class="seleK">
                        
                            
                            <label>
                                <span>管理员类型：</span>
                                <select class="select w120" name="type">
                                    <option <?php if(($type) == "0"): ?>selected="selected"<?php endif; ?> value="0">请选择类型</option>
                                    <option <?php if(($type) == "1"): ?>selected="selected"<?php endif; ?>  value="1">系统管理员</option>
                                    <option <?php if(($type) == "2"): ?>selected="selected"<?php endif; ?>  value="2">分站管理员</option>
                                </select>
                            </label>
                            
                             <label>
                                    <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                    <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                    <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                                </label>
                             <label>
                             
                            <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text"/>
                            <input type="submit" value="  搜索"  class="inpt-button-tudou" />
                    </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td>角色ID</td>
                    <td>类型</td>
                    <td>角色名称</td>
                    <td>学校ID</td>
                    <td>学校名称</td>
                    <td>操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><?php echo ($var["role_id"]); ?></td>
                        <td>
                        	<?php if(($var["type"]) == "0"): ?>当前角色有误请编辑<?php endif; ?>
                            <?php if(($var["type"]) == "1"): ?>系统管理员<?php endif; ?>
                            <?php if(($var["type"]) == "2"): ?>分站管理员<?php endif; ?>
                        </td>
                        <td><?php echo ($var["role_name"]); ?></td>
                        <td><?php echo ($var["school_id"]); ?></td>
                        <td><?php echo ($var["school"]["Name"]); ?></td>
                        <td>
                        	<?php if(($var["role_id"]) == "1"): ?>系统管理员不能编辑
                            <?php else: ?>
                            	<?php echo BA('role/edit',array("role_id"=>$var["role_id"]),'编辑','','tu-dou-btn');?>
                                <?php echo BA('role/auth',array("role_id"=>$var['role_id']),'角色权限','','tu-dou-btn');?>
                                <?php echo BA('role/delete',array("role_id"=>$var['role_id']),'删除','act','tu-dou-btn'); endif; ?>
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