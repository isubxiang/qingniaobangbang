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
        <li class="li2 li3">管理员列表</li>
    </ul>
</div>
<div class="main-tu-js">
    <p class="attention"><span>注意：</span>我们建议管理员密码为：大写字母+小写字母+数字或者标点符号组合，每个角色有对应的权限，默认超级管理员角色不能删除！</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr">
            <div class="left">
            	<?php echo BA('admin/create','','添加管理员');?>
            </div>
            <div class="right">
                <form method="post" action="<?php echo U('admin/index');?>">
                 <div class="seleHidden" id="seleHidden">
                        <div class="seleK">
                        
                             <label>
                                    <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                    <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                    <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                                </label>
                             <label>
                        
                        	<label>
                                <span>管理员类型：</span>
                                <select class="select w120" name="type">
                                    <option <?php if(($type) == "0"): ?>selected="selected"<?php endif; ?> value="0">请选择类型</option>
                                    <option <?php if(($type) == "1"): ?>selected="selected"<?php endif; ?>  value="2">系统管理员</option>
                                    <option <?php if(($type) == "2"): ?>selected="selected"<?php endif; ?>  value="3">分站管理员</option>
                                </select>
                            </label>
                			<label>
                                <span>冻结状态：</span>
                                <select class="select w120" name="is_username_lock">
                                    <option <?php if(($is_username_lock) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                                    <option <?php if(($is_username_lock) == "0"): ?>selected="selected"<?php endif; ?>  value="0">未冻结</option>
                                    <option <?php if(($is_username_lock) == "1"): ?>selected="selected"<?php endif; ?>  value="1">已冻结</option>
                                </select>
                            </label>
                            <label>
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>"/>
                                <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>" class="text"/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择绑定会员</a>
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
                        <td>用户名</td>
                        <td>绑定会员ID</td>
                        <td>绑定会员昵称</td>
                        <td>学校ID</td>
                    	<td>学校名称</td>
                        <td>角色</td>
                        <td>手机</td>
                        <td>创建时间</td>
                        <td>创建IP</td>
                        <td>最后登录时间</td>
                        <td>最后登录IP</td>
                        <td>操作</td>   
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        	<?php $Role = D('Role')->find($var['role_id']); ?>
                            <td><?php echo ($var["admin_id"]); ?></td>
                            <td>
                            	<?php if(($var["type"]) == "0"): ?>管理员错误<?php endif; ?>
                                <?php if(($var["type"]) == "1"): ?>系统管理员<?php endif; ?>
                                <?php if(($var["type"]) == "2"): ?>分站管理员<?php endif; ?>
                                <?php if(($var["type"]) == "2"): if($var.city_id): echo ($var['city']['name']); endif; ?>
                                    <?php if($var.area_id): echo ($var['area']['area_name']); endif; ?>
                                    <?php if($var.business_id): echo ($var['business']['business_name']); endif; endif; ?>
                            </td>
                            <td><?php echo ($var["username"]); ?></td>
                            <td><?php echo ($var["user_id"]); ?></td>
                            <td><?php echo ($var["user"]["nickname"]); ?></td>
                            <td><?php echo ($var["school_id"]); ?></td>
                        	<td><?php echo ($var["school"]["Name"]); ?></td>
                            <td><?php echo ($Role["role_name"]); ?></td>
                            <td><?php echo ($var["mobile"]); ?></td>
                            <td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                            <td><?php echo ($var["create_ip"]); ?>(<?php echo ($var["create_ip_area"]); ?>)</td>
                            <td><?php echo (date("Y-m-d H:i:s",$var["last_time"])); ?></td>
                            <td><?php echo ($var["last_ip"]); ?>(<?php echo ($var["last_ip_area"]); ?>)</td>
                            <td>
                                <?php if(($var["is_username_lock"]) == "0"): echo BA('admin/is_username_lock',array("admin_id"=>$var["admin_id"]),'冻结','act','tu-dou-btn');?>
                                <?php else: ?>
                                <?php echo BA('admin/is_username_lock',array("admin_id"=>$var["admin_id"]),'解冻','act','tu-dou-btn'); endif; ?>
                                
                                <?php echo BA('admin/edit',array("admin_id"=>$var["admin_id"],'p'=>$p),'编辑','','tu-dou-btn');?>
                                <?php echo BA('admin/delete',array("admin_id"=>$var["admin_id"]),'删除','act','tu-dou-btn');?>
                            </td>
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