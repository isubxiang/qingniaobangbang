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
        <li class="li1">跑腿系统</li>
        <li class="li2">跑腿订单</li>
        <li class="li2 li3">明细列表</li>
    </ul>
</div>

<div class="main-tu-js">
    <p class="attention"><span>注意：</span>带外卖菜品的跑腿订单明细表  当前订单ID：<?php echo ($running_id); ?>  当前商家ID：<?php echo ($shop_id); ?></p>
    <div class="tudou-js-nr">
    
    
        <div class="tu-select-nr" style="margin: 10px 20px;">
            <div class="left">
                <?php echo BA('running/product',array('running_id'=>$running_id,'shop_id'=>$shop_id,'source'=>$source),'刷新本页','','',600,360);?>
            </div>
            <form method="post" action="<?php echo U('running/product',array('running_id'=>$running_id,'shop_id'=>$shop_id,'source'=>$source));?>">
            	<input type="hidden" id="running_id" name="running_id" value="<?php echo (($running_id)?($running_id):''); ?>" />
                <input type="hidden" id="shop_id" name="shop_id" value="<?php echo (($shop_id)?($shop_id):''); ?>" />
                <div class="right">
                     <div class="seleK">
                     
                      	<label>
                            <input type="hidden" id="shop_id" name="shop_id" value="<?php echo (($shop_id)?($shop_id):''); ?>"/>
                            <input type="text"   id="shop_name" name="shop_name" value="<?php echo ($shop_name); ?>" class="text " />
                            <a mini="select"  w="1000" h="600" href="<?php echo U('shop/select');?>" class="sumit">选择商家</a>
                        </label>
                        <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label>     
                            
                        <label>
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                            <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                            <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                        </label>
                        <input type="text" name="keyword" placeholder=" 输入关键字"  value="<?php echo ($keyword); ?>" class="tu-inpt-text"/>
                        <input type="submit" value="  搜索"  class="inpt-button-tudou"/>
                    </div>
                </div>
           </form>
        </div>
 
 
 
        <form target="x-frame" method="post">
            <div class="tu-table-box">
                <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                    <tr>
                        <td class="w50"><input type="checkbox" class="checkAll" rel="id" /></td>
                        <td class="w50">ID</td>
                        <td>订单ID</td>
                        <td>订单类型</td>
                        <td>学校ID</td>
                        <td>会员ID</td>
                        <td>商家ID</td>
                        <td>菜品ID</td>
                        <td>地址ID</td>
                        <td>菜品图片</td>
                        <td>菜品名称</td>
                        <td>菜品价格</td>
                        <td>数量</td>
                        <td>操作</td>
                    </tr>
                    <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                            <td><input class="child_id" type="checkbox" name="id[]" value="<?php echo ($var["id"]); ?>"/></td>
                            <td><?php echo ($var["id"]); ?></td>
                            <td><?php echo ($var["running_id"]); ?></td>
                            <td><?php if($var['orderType'] == 1): ?>外卖配送<?php else: ?>到店自取<?php endif; ?></td>
                            <td><?php echo ($var["school_id"]); ?></td>
                            <td><?php echo ($var["user_id"]); ?></td>
                            <td><?php echo ($var["shop_id"]); ?></td>
                            <td><?php echo ($var["product_id"]); ?></td>
                            <td><?php echo ($var["addr_id"]); ?></td>
                            <td><a href="<?php echo config_weixin_img($var['product']['photo']);?>" class="tooltip"><img src="<?php echo config_img($var['product']['photo']);?>" class="w80"></a></td>
                            <td><?php echo ($var["product_name"]); ?></td>
                            <td><?php echo round($var['Price']/100,2);?></td>
                            <td><?php echo ($var["Quantity"]); ?></td>
                            <td>
                            </td>
                        </tr><?php endforeach; endif; ?>
                </table>
                <?php echo ($page); ?>
            </div>
            <div class="tu-select-nr">
                <div class="left">
                </div>
            </div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>