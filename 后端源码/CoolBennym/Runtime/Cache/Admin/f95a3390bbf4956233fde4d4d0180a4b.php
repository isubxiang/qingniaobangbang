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
        <li class="li2 li3">城市站点</li>
    </ul>
</div>
<div class="main-tu-js">
    <p class="attention"><span>注意：不支持软删除，请不要随意删除城市</span>城市站点设置后，启用后前台才能看到该站点，显示当前城市下面商家数量等信息！点击审核城市后自动短信通知申请的商家</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin: 10px 20px;">
            <div class="left">
                <?php echo BA('city/create','','添加站点');?>
            </div>
            <form method="post" action="<?php echo U('city/index');?>">
            
                <div class="right">
                 <div class="seleK">
                 <label>
                      <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                      <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                      <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                  </label>
                   <label>
                          <span>审核状态：</span>
                          <select class="select w120" name="is_open">
                          	  <option value="999">请选择状态</option>
                              <option <?php if(($is_open) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                              <option <?php if(($is_open) == "0"): ?>selected="selected"<?php endif; ?>  value="0">未审核</option>
                              <option <?php if(($is_open) == "1"): ?>selected="selected"<?php endif; ?>  value="1">已审核</option>
                          </select>
                        </label>
                    <input type="text" name="keyword" placeholder=" 输入城市名称‘拼音"  value="<?php echo ($keyword); ?>" class="tu-inpt-text" />
                    <input type="submit" value="  搜索"  class="inpt-button-tudou" />
                </div>
                
                </div>
            </form>
        </div>
        <form target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="city_id" /></td>
                        <td class="w50">ID</td>
                        <td>站点名称</td>
                        <td>管理账户名称</td>
                        <td>分成费率</td>
                        <td>所属二级代理</td>
                        <td>城市LOGO</td>
                        <td>站点拼音</td>
                        <td>商家数量</td>
                        <td>地区数量</td>
                        <td>审核状态</td>
                        <td>子域名状态</td>
                        <td>排序</td>
                        <td>创建时间</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_city_id" type="checkbox" name="city_id[]" value="<?php echo ($var["city_id"]); ?>"/></td>
                            <td><?php echo ($var["city_id"]); ?></td>
                            <td><?php echo ($var["name"]); ?></td>
                            <td><?php echo ($users[$var['user_id']]['nickname']); ?></td>
                            <td><?php echo round($var['ratio']/100,2);?>%</td>
                            <td><?php echo ($agents[$var['agent_id']]['agent_name']); ?></td>
                            <td>
                                <?php if(empty($var['photo'])): ?>暂无图片
                                <?php else: ?>
                                    <img style="width:40px;" src="<?php echo config_img($var['photo']);?>" /><?php endif; ?>
                            </td>
                            <td><?php echo ($var["pinyin"]); ?></td>
                            <td><?php echo ($var["shop_num"]); ?></td>
                            <td><?php echo ($var["area_num"]); ?></td>
                            <td><?php if(($var["is_open"]) == "0"): ?>未审核<?php else: ?><a style="color:#F00">已审核</a><?php endif; ?></td>
                            <td><?php if(($var["domain"]) == "0"): ?>已关闭<?php else: ?>开启中<?php endif; ?></td>
                            <td><?php echo ($var["orderby"]); ?></td>
                            <td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                            <td>
                                <?php if(($var["is_open"]) == "0"): echo BA('city/is_open',array("city_id"=>$var["city_id"]),'审核','act','tu-dou-btn'); endif; ?>
                           		<?php echo BA('area/index',array("city_id"=>$var["city_id"]),'区域','','tu-dou-btn');?>
                                <?php echo BA('city/edit',array("city_id"=>$var["city_id"]),'编辑','','tu-dou-btn');?>
                                <?php echo BA('city/delete',array("city_id"=>$var["city_id"]),'删除','act','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr">
                <div class="left">
                    <!--防止误删除<?php echo BA('city/delete','','批量删除','list','a2');?>-->
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>