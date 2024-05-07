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
        <li class="li2">商家等级</li>
        <li class="li2 li3">购买等级订单</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>显示商家购买的等级列表</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
         	<div class="left">
                <?php echo BA('shopgrade/index','','商家等级列表');?>
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('shopgradeorder/index');?>">
                    <div class="seleHidden" id="seleHidden">
                        <div class="seleK">
                            <label>
                                <span>订单编号 </span>
                                <input type="text" placeholder=" 输入订单编号" name="keyword" value="<?php echo ($keyword); ?>"  class="tu-inpt-text">
                            </label>
                            <label>
                                <input type="hidden" id="shop_id" name="shop_id" value="<?php echo (($shop_id)?($shop_id):''); ?>"/>
                                <input type="text"   id="shop_name" name="shop_name" value="<?php echo ($shop_name); ?>" class="text" />
                                <a mini="select"  w="1000" h="600" href="<?php echo U('shop/select');?>" class="sumit">选择商家</a>
                                <input style="float: right;" type="submit" value="   搜索"  class="inpt-button-tudou" />
                            </label>
                        </div>
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
        </div>
        <form  target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="order_id" /></td>
                        <td class="w50">订单ID</td>
                        <td>买单用户</td>
                        <td>商家</td>
                        <td>支付金额</td>
                        <td>购买等级</td>
                        <td>付款状态</td>
                        <td>下单时间</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_order_id" type="checkbox" name="order_id[]" value="<?php echo ($var["order_id"]); ?>" /></td>
                            <td><?php echo ($var["order_id"]); ?></td>
                            <td><?php echo ($users[$var['user_id']]['account']); ?>(ID:<?php echo ($var["user_id"]); ?>)</td>
                            <td><?php echo ($shops[$var['shop_id']]['shop_name']); ?></td>
                            <td>&yen; <?php echo round($var['money']/100,2);?> 元</td>
                            <td><?php echo ($shopgrades[$var['grade_id']]['grade_name']); ?></td>
                            <td><?php if($var["status"] == 1): ?><span class="text-green">已支付</span><?php else: ?>未支付<?php endif; ?></td>
                            <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                            <td>
                                <?php if(($var["status"]) == "0"): echo BA('shopgradeorder/delete',array("order_id"=>$var["order_id"]),'删除','act','tu-dou-btn'); endif; ?>
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