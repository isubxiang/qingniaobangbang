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
        <li class="li2">结算管理</li>
        <li class="li2 li3">资金记录</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>新版商户资金记录，当前结果总计：<a style="color:#f60">&yen;<?php echo round($money/100,2);?> 元</a>佣金总计：<a style="color:#f60">&yen;<?php echo round($commission/100,2);?> 元</a></p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('shopmoney/index');?>">
                    <div class="seleHidden" id="seleHidden">
                     <div class="seleK">
                        <label>
                            <span>开始时间</span>
                            <input type="text" name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text" />
                        </label>
                        <label>
                            <span>结束时间</span>
                            <input type="text" name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});"  class="text" />
                        </label>
                        <label>
                            <input type="hidden" id="shop_id" name="shop_id" value="<?php echo (($shop_id)?($shop_id):''); ?>"/>
                            <input type="text"   id="shop_name" name="shop_name" value="<?php echo ($shop_name); ?>" class="text w150 sj" />
                            <a mini="select"  w="1000" h="600" href="<?php echo U('shop/select');?>" class="seleSj">选择商家</a>
                        </label>
                        
                        
                        <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label>
                        
                        <label>
                            <span>订单编号</span>
                        <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" /><input type="submit" value="   搜索"  class="inpt-button-tudou" />
                        </label>
                        </div>
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
            <div class="tu-select-nr tu-select-nr2">
                <div class="left"> <div class="seleK"></div> </div>
                <div class="right"></div>
        <div class="clear"></div>
    </div>
    <form  target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50"><input type="checkbox" class="checkAll" rel="money_id" /></td>
                    <td class="w50">ID</td>
                    <td>地区</td>
                    <td>商家</td>
                    <td>结算金额</td>
                    <td>佣金</td>
                    <td>类型</td>
                    <td>原始订单ID</td>
                    <td>说明</td> 
                    <td>时间</td>
                    <td>IP</td>   
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_money_id" type="checkbox" name="money_id[]" value="<?php echo ($var["money_id"]); ?>" /></td>
                        <td><?php echo ($var["money_id"]); ?></td>
                        <td> 
                            <?php if($var['city_name']): echo ($var['city_name']); endif; ?>
                            <?php if($var['area_name']): ?>→<?php echo ($var['area_name']); endif; ?>
                        </td>
                        <td><?php echo ($shops[$var['shop_id']]['shop_name']); ?></td>
                        <td><?php echo round($var['money']/100,2);?></td>
                        <td><?php echo round($var['commission']/100,2);?></td>
                        <td><a style="color:#f60"> <?php echo ($var["type"]); ?></a></td>
                        <td><?php echo ($var["order_id"]); ?></td>
                        <td><?php echo ($var["intro"]); ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                        <td><?php echo ($var["create_ip"]); ?></td>
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