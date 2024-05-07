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
        <li class="li2 li3">编辑区域</li>
    </ul>
</div>
<div class="listBox clfx">
    <div class="menuManage">
        <form  target="x-frame" action="<?php echo U('area/edit',array('area_id'=>$detail['area_id']));?>" method="post">
            <div class="main-tudou-sc-add">
                <div class="tu-table-box">
                    <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                        <tr>
                            <td class="tu-left-td">区域名称：</td>
                            <td class="tu-right-td"><input type="text" name="data[area_name]" value="<?php echo (($detail["area_name"])?($detail["area_name"]):''); ?>" class="tudou-manageInput" />
                            </td>
                        </tr>
                        
                         <tr>
                            <td class="tu-left-td">所在城市：</td>
                            <td class="tu-right-td">
                            <select id="data[city_id]" name="data[city_id]" class="selectInput">
                            <option value="0">请选择...</option>
                            <?php if(is_array($citys)): foreach($citys as $key=>$var): ?><option value="<?php echo ($var["city_id"]); ?>"  <?php if(($var["city_id"]) == $detail["city_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="tu-left-td">管理账户：</td>
                            <td class="tu-right-td">
                                <div class="lt">
                                    <input type="hidden" id="user_id" name="data[user_id]" value="<?php echo (($detail["user_id"])?($detail["user_id"]):''); ?>" class="tudou-manageInput" />
                                    <input class="tudou-sc-add-text-name sj" type="text" name="nickname" id="nickname"  value="<?php echo ($user["nickname"]); ?>" />
                                </div>
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="seleSj">选择账户</a>
                            </td>
                        </tr> 
                         <tr>
                            <td class="tu-left-td">分成比例：</td>
                            <td class="tu-right-td"><input type="text" name="data[ratio]" value="<?php echo round($detail['ratio']/100,2);?>" class="tudou-sc-add-text-name"/>%
                            <code>预留字段，百分比费率，用于管理员提成</code>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="tu-left-td">城市中心坐标：</td>
                            <td class="tu-right-td">
                                <div class="lt">
                                    经度<input type="text" name="data[lng]" id="data_lng" value="<?php echo (($detail["lng"])?($detail["lng"]):''); ?>" class="tudou-sc-add-text-name w200" />
                                    纬度 <input type="text" name="data[lat]" id="data_lat" value="<?php echo (($detail["lat"])?($detail["lat"]):''); ?>" class="tudou-sc-add-text-name w200" />
                                </div>
                                <a style="margin-left: 10px;" mini="select"  w="600" h="600" href="<?php echo U('public/maps');?>" class="seleSj">百度地图</a>
                        </tr>
                
                
                        <tr>
                            <td class="tu-left-td">排序：</td>
                            <td class="tu-right-td"><input type="text" name="data[orderby]" value="<?php echo (($detail["orderby"])?($detail["orderby"]):''); ?>" class="tudou-manageInput" />
                                <code>数字越小排序越高</code>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="sm-qr-tu"><input type="submit" value="确认保存" class="sm-tudou-btn-input" /></div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>