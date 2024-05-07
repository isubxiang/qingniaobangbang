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
        <li class="li1">微信管理</li>
        <li class="li2">微信消息</li>
        <li class="li2 li3">发送日志</li>
    </ul>
</div>
<style>
.tips{color: #fff; background: #1ca290; padding: 0px 5px; border-radius:0px; margin-left:0 5px; display: inline-block; float: right; height: 20px; line-height: 20px;}
</style>
<div class="main-tu-js main-sc">
	<p class="attention"><span>注意：</span>这里是微信模板消息发送列表，每一页排列100条记录，支持批量删除，主要是为了保留方便管理员查询调试作用，这里的返回码对应的错误到微信官方去查询！</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="border-top: none; margin-top: 0px;">
        	<div class="left">
                <a mini="act" href="<?php echo U('weixinmsg/delete_drop');?>">清空微信模板日志【谨慎操作】</a>  
            </div>
            
            <div class="right">
                <form method="post" action="<?php echo U('weixinmsg/index');?>">
                    <div class="seleHidden" id="seleHidden">
                        <div class="seleK">
                            <label>
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                                <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                            </label>
                            <label>
                                <span>  ID：</span>   <input type="text" name="order_id" value="<?php echo (($msg_id)?($msg_id):''); ?>" class="tu-inpt-text" />
                                <input type="submit" class="inpt-button-tudou" value=" 搜索" /></label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="msg_id" /></td>
                        <td class="w50">ID</td>
                        <td>接受用户ID</td>
                        <td>接受用户昵称</td>
                        <td>模板标题</td>
                        <td>返回码</td>
                        <td>返回内容</td>
                        <td>发送内容</td>
                        <td>创建时间</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_msg_id" type="checkbox" name="msg_id[]" value="<?php echo ($var["msg_id"]); ?>" /></td>
                        <td><?php echo ($var["msg_id"]); ?></td>
                        <td><?php echo ($var["user_id"]); ?></td>
                        <td><?php echo ($users[$var['user_id']]['nickname']); ?></td>
                        <td><?php echo ($var["serial"]["title"]); ?></td>
                        <td><?php echo ($var["status"]); ?></td>
                        <td><a class="tips" rel="<?php echo ($var["info"]); ?>" href="javascript:void(0)">查看返回码</a></td>
                        <td style="font-size:10px; height:20px; line-height:20px;"><?php echo ($var["comment"]); ?></td>
                        <td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                        <td>
                            <?php echo BA('weixinmsg/delete',array("msg_id"=>$var["msg_id"]),'删除','act','tu-dou-btn-small-waring');?>
                        </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                    <?php echo BA('weixinmsg/delete','','批量删除','list',' a2');?>
                </div>
            </div>
        </form>
    </div>
</div>
  <script>
       $(document).ready(function (e) {
			$(".tips").click(function () {
				var tipnr = $(this).attr('rel');
				layer.tips(tipnr, $(this), {
					tips: [4, '#1ca290'],
					time: 4000
				});
			})
		});
    </script>
  		</div>
	</body>
</html>