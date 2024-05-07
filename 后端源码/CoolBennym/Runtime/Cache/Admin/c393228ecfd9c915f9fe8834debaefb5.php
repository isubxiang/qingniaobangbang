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

<style>
.tu-left-td{width: 220px;}
.profit{ text-align:center; color:#000; font-weight:bold; background:#ECECEC;}
</style>



<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">基本设置</li>
        <li class="li2 li3">外卖配置</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>这里配置是外卖相关的信息，请各位看清楚下面配置具体事项，配置不正确直接影响全局</p>
</div>       
<div class="main-tudou-sc-add">
    <div class="tu-table-box">
        <form  target="x-frame" action="<?php echo U('setting/ele');?>" method="post">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            
             	<tr>
                  <td class="tu-right-td profit" colspan="2"> 外卖商家佣金计算模式</td>
                </tr>
                
                <tr>
                     <td class="tu-left-td">外卖商家佣金计算模式：</td>
                     <td class="tu-right-td">
                     <label><input type="radio" name="data[settlementType]" <?php if(($CONFIG["ele"]["settlementType"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>费率模式</label>
                     <label><input type="radio" name="data[settlementType]" <?php if(($CONFIG["ele"]["settlementType"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>菜品结算价模式</label>
                     <code>费率模式费率在外卖商家编辑里面设置，菜品结算价模式在外卖菜品编辑可设置单个菜品的结算价格</code>
                     </td>
			    </tr>
                
                
                <tr>
                  <td class="tu-right-td profit" colspan="2"> 外卖其他功能设置 </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td" >餐具费用最高：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tableware_price_max]" value="<?php echo ($CONFIG["ele"]["tableware_price_max"]); ?>" class="tudou-sc-add-text-name w80" />
                        <code>注意，这里留空或者不填写的话，全局关闭配送费，如果设置了最高配送费，最低配送费就必须设置，商家在设置餐具费用的时候，餐具费用最高不能超过多元，建议设置1-3元，支持小数点，但是不超过2位数</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td" >餐具费用最低：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[tableware_price_mini]" value="<?php echo ($CONFIG["ele"]["tableware_price_mini"]); ?>" class="tudou-sc-add-text-name w80" />
                        <code>
                        
                        <?php if($CONFIG['ele']['tableware_price_max']): if($CONFIG['ele']['tableware_price_mini'] >= $CONFIG['ele']['tableware_price_max']): ?><a style="color:#F00; font-size:16px; font-weight:bold">最低餐具费用不得大于等于最高餐具费用</a><?php endif; endif; ?>
                        
                        商家在设置餐具费用的时候，餐具费用最低不能超过多元，建议设置0.3-0.5元，支持小数点，不超过2位数
                        
                        </code>
                    </td>
                </tr>
                
               <tr>
                    <td class="tu-left-td" >外卖未付款订单自动过期时间：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[past_due_ele_order_time]" value="<?php echo ($CONFIG["ele"]["past_due_ele_order_time"]); ?>" class="tudou-sc-add-text-name w80" />
                        <code>用户下单后，多少分钟不付款，订单自动删除，但是会微信通知买家，这里时间是分钟比如可以设置15分钟，删除后，买家需要重新下单</code>
                    </td>
                </tr>
                
               
              
                
            </table>
            <div class="sm-qr-tu"><input type="submit" value="确认设置" class="sm-tudou-btn-input" /></div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>