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
        <li class="li1">后台管理</li>
        <li class="li2">推送管理</li>
        <li class="li2 li3">推送列表</li>
    </ul>
</div>




<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span> 新版推送超级牛逼，短信宝用户联系：120-585-022 新增推送模板。 </p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('push/create','','新建推送','','',800,400);?>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="tu-select-nr" style="border-top: none; margin-top: 0px;">
            <div class="right">
                <form method="post" action="<?php echo U('push/index');?>">
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
                            <span>推送类型：</span>
                            <select class="select w120" name="type">
                            <option <?php if(($type) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择推送类型</option>
                              <?php if(is_array($types)): foreach($types as $key=>$item): ?><option <?php if(($type) == $key): ?>selected="selected"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; ?>
                            </select>
                         </label>
                         
                         
                         <label>
                          	<span>  搜内容/标题/URL：</span>   
                            <input type="text" name="keyword" value="<?php echo (($keyword)?($keyword):''); ?>" class="tu-inpt-text" />
                            <input type="submit" class="inpt-button-tudou" value="搜索" />
                         </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="push_id" /></td>
                        <td class="w50">ID</td>
                        <td>主体</td>
                        <td>类型</td>
                        <td>用户组</td>
                        <td>标题</td>
                        <td>内容</td>
                        <td>URL</td>
                        <td>创建时间</td>
                        <td>状态</td>
                        <td>推送时间</td>
                        <td>操作</td>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_push_id" type="checkbox" name="push_id[]" value="<?php echo ($var["push_id"]); ?>" /></td>
                            <td><?php echo ($var["push_id"]); ?></td>
                            <td><?php echo ($categorys[$var['category']]); ?></td>
                            <td><?php echo ($types[$var['type']]); ?></td>
                            <td><?php echo (($ranks[$var['rank_id']]['rank_name'])?($ranks[$var['rank_id']]['rank_name']):'未选择等级'); ?></td>
                            <td><?php echo ($var["title"]); ?></td>
                            <td><?php echo ($var["intro"]); ?></td>
                            <td><?php echo ($var["url"]); ?></td>
                            <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                            <td><?php if($var['is_push'] == 1): ?>已推送<?php else: ?>未推送<?php endif; ?></td>
                            <td><?php echo (date('Y-m-d H:i:s',$var["push_time"])); ?></td>
                            <td>
                                <?php if(($var["is_push"]) == "0"): if($var['type'] == 1): echo BA('push/sms',array("push_id"=>$var["push_id"]),'短信推送','act','tu-dou-btn'); endif; ?>
                                	<?php if($var['type'] == 2): echo BA('push/weixin',array("push_id"=>$var["push_id"]),'微信推送','act','tu-dou-btn'); endif; endif; ?>
                                <?php echo BA('push/delete',array("push_id"=>$var["push_id"]),'删除','act','tu-dou-btn');?>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>