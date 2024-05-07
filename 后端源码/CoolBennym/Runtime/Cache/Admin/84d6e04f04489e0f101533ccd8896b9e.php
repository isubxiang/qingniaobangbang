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
        <li class="li1">会员管理</li>
        <li class="li2">会员绑定</li>
        <li class="li2 li3">会员绑定列表</li>
    </ul>
</div>
<style>
.shang, .delivery, .weibo, .qq, .weixin{color:#FFF; padding:0 3px; margin:0 2px;}
.shang{ background:#F00; }
.delivery{ background: #00F;}
.weibo{ background:#903;}
.qq{ background:#09F}
.weixin{ background:#0C0;}
.headimgurl{ width:38px; height:38px; border-radius:100%;}
</style>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>这里是取消用户绑定微信，QQ，微博的，如果点击解绑后，用户就不存在绑定</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('user/create','','添加会员','load','',700,600);?>
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('user/binding');?>">
                    <div class="seleHidden" id="seleHidden">
                        <label>
                            <span>绑定类型：</span>
                            <select class="select w120" name="type">
                               <option <?php if(($type) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                               <option <?php if(($type) == "1"): ?>selected="selected"<?php endif; ?>  value="1">微信</option>
                               <option <?php if(($type) == "2"): ?>selected="selected"<?php endif; ?>  value="2">qq</option>
                               <option <?php if(($type) == "3"): ?>selected="selected"<?php endif; ?>  value="3">微博</option>
                            </select>
                           </label>
                         <label>
                            <span>账户/uid/open_id</span>
                            <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" />
                            <input type="submit" value="   搜索"  class="inpt-button-tudou" />
                        </label>
                        
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
        <div class="clear"></div>
    </div>
    <form  target="dantiaow_frm" method="post">
        <div class="tu-table-box">
            <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50"><input type="checkbox" class="checkAll" rel="connect_id" /></td>
                    <td class="w50">ID</td>
                    <td>USER_ID</td>
                    <td>账户</td>
                    <td>类型</td>
                    <td>open_id</td>
                    <td>openid</td>
                    <td>unionid</td>
                    <td>昵称</td>
                    <td>头像</td>
                    <td>创建时间</td>
                    <td class="w150">操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_connect_id" type="checkbox" name="connect_id[]" value="<?php echo ($var["connect_id"]); ?>" /></td>
                        <td><?php echo ($var["connect_id"]); ?></td>
                        <td>
                            <?php if(!empty($var['uid'])): echo ($var['uid']); ?>
                            <?php else: ?>
                                未绑定<?php endif; ?>
                        </td>
                        <td><?php echo ($users[$var['uid']]['account']); ?></td>
                        <td>
                            <?php if(($var["type"]) == "weixin"): ?><span class="weixin">微信</span><?php endif; ?>
                            <?php if(($var["type"]) == "qq"): ?><span class="qq">qq</span><?php endif; ?>
                            <?php if(($var["type"]) == "weibo"): ?><span class="weibo">微博</span><?php endif; ?>
                        </td>
                        <td><?php echo ($var['open_id']); ?></td>
                        <td><?php echo ($var['openid']); ?></td>
                        <td><?php echo ($var['unionid']); ?></td>
                        <td><?php echo ($var['nickname']); ?></td>
                        <td><img class="headimgurl" src="<?php echo config_img($var['headimgurl']);?>" /></td>
						<td><?php echo (date("Y-m-d H:i:s",$var["create_time"])); ?></td>
                        <td>
                            <?php echo BA('user/binding_edit',array("connect_id"=>$var["connect_id"]),'编辑','','tu-dou-btn');?>
                            <?php echo BA('user/binding_delete',array("connect_id"=>$var["connect_id"]),'解绑','act','tu-dou-btn');?>
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