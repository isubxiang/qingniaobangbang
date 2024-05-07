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
        <li class="li2">区域设置</li>
        <li class="li2 li3">区域管理</li>
    </ul>
</div>
<div class="main-tu-js">
	<p class="attention"><span>注意：</span>这里是管理地区的，支持搜索功能，注意这里可为区域设置管理员，设置后会员就可以登录区域管理上代理后台管理</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr">
            <div class="left">
                <?php echo BA('area/create','','新增区域','load','',600,300);?>
            </div>
               <div class="right">
              <form method="post" action="<?php echo U('area/index');?>">
                    <div class="seleHidden" id="seleHidden">
                    <div class="seleK">
                        <span>城市</span>
                        <select id="city_id" name="city_id" class="select">
                            <option value="0">请选择...</option>
                            <?php if(is_array($citys)): foreach($citys as $key=>$var): ?><option value="<?php echo ($var["city_id"]); ?>"  <?php if(($var["city_id"]) == $city_id): ?>selected="selected"<?php endif; ?> ><?php echo ($var["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <label>
                      <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                      <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                      <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                     </label>
                    <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" /><input type="submit" value="  搜索"  class="inpt-button-tudou" />
                    </div>
                </div>
            </form>
            </div>
        </div>
        <form target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td><input type="checkbox" class="checkAll" rel="area_id" /></td>
                        <td>ID</td>
                        <td>区域名称</td>
                        <td>所在城市</td>
                        <td>管理员ID</td>
                        <td>管理员昵称</td>
                        <td>分成费率</td>
                        <td>商圈数量</td>
                        <td>商家数量</td>
                        <td>lng</td>
                        <td>lat</td>
                        <td>排序</td>
                        <td>操作</td>   
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_area_id" type="checkbox" name="area_id[]" value="<?php echo ($var["area_id"]); ?>" /></td>
                            <td><?php echo ($var["area_id"]); ?></td>
                            <td><?php echo ($var["area_name"]); ?></td>
                            <td><?php echo ($citys[$var['city_id']]['name']); ?>→<?php echo ($var["area_name"]); ?></td>
                            <td><?php echo ($var['user_id']); ?></td>
                            <td><?php echo ($users[$var['user_id']]['nickname']); ?></td>
                            <td><?php echo round($var['ratio']/100,2);?>%</td>
                            <td><?php echo ($var["business_num"]); ?></td>
                            <td><?php echo ($var["shop_num"]); ?></td>
                            <td><?php echo ($var["lng"]); ?></td>
                            <td><?php echo ($var["lat"]); ?></td>
                            <td><?php echo ($var["orderby"]); ?></td>
                            <td>
                                <?php echo BA('area/edit',array("area_id"=>$var["area_id"]),'编辑','','tu-dou-btn');?>
                                <?php echo BA('business/index',array("area_id"=>$var["area_id"]),'商圈','','tu-dou-btn');?>
                                <?php echo BA('area/delete',array("area_id"=>$var["area_id"]),'删除','act','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                  <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr">
                <div class="left">
                    <?php echo BA('area/delete','','批量删除','list',' a2');?>
                </div>
            </div>
        </form>

    </div>
</div>
  		</div>
	</body>
</html>