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

.tu-left-td{width:180px;}
.profit{text-align:center; color:#000; font-weight:bold; background:#f5f5f5;}
.main-tudou-sc-add .tu-table-box table tr td {
    padding-top: 5px;
    padding-bottom: 5px;
}
.main-tu-js .tudou-js-nr, .main-tudou-sc-add .tudou-js-nr {
    border: 1px solid #fff !important;
}
.main-tu-js .tudou-js-nr, .main-tudou-sc-add .tudou-js-nr {
    border: 1px solid #eee;
}
.tu-left-td {
    line-height:24px;
}
table {
    border-color: #eee;
}
</style>


<div class="tu-main-top-btn">
    <ul>
        <li class="li1">跑腿系统</li>
        <li class="li2">订单管理</li>
        <li class="li2 li3">订单详细</li>
    </ul>
</div>

<div class="main-tu-js">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin:10px 20px;">
            <div class="left">
                <?php echo BA('running/index',array('running_id'=>$var['running_id'],'p'=>$p,'colour'=>'#eee'),'返回订单列表','','',600,360);?>
                <?php echo BA('running/detail',array('running_id'=>$var['running_id'],'p'=>$p),'刷新当前页面','','',600,360);?>
            </div>
        </div>
 </div>       
        

    <div class="main-tudou-sc-add" style="margin-top:10px;">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
       
                <tr>
                	<td class="tu-left-td">订单ID：</td><td class="tu-right-td"><?php echo ($var["running_id"]); ?>  &nbsp;&nbsp; 订单号：<?php echo ($var["Code"]); ?>   &nbsp;&nbsp; 分类ID：<?php echo ($var["cate_id"]); ?></td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">学校名称：</td><td class="tu-right-td"><?php echo ($var["school"]["Name"]); ?></td>
                </tr>
                
                
                <tr>
                	<td class="tu-left-td">付款金额：</td><td class="tu-right-td">&yen;<?php echo round($var['price']/100,2);?>元</td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">垫付金额：</td><td class="tu-right-td">&yen;<?php echo round($var['Money']/100,2);?>元</td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">跑腿金额：</td><td class="tu-right-td">&yen;<?php echo round($var['freight']/100,2);?>元</td>
                </tr>
                
                <tr>
                	<td class="tu-left-td" style="color:#F00">红包使用金额：</td><td class="tu-right-td">&yen;<?php echo round($var['coupon_price']/100,2);?>元</td>
                </tr>
                
                <tr>
                	<td class="tu-left-td" style="color:#F00">外卖配送费满减：</td><td class="tu-right-td">&yen;<?php echo round($var['MoneyFreightFullMoney']/100,2);?>元</td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">付款金额：</td><td class="tu-right-td">&yen;<?php echo round($var['price']/100,2);?>元</td>
                </tr>
                <tr>
                	<td class="tu-left-td">实际金额：</td><td class="tu-right-td">&yen;<?php echo round($var['need_pay']/100,2);?>元</td>
                </tr>
                <tr>
                	<td class="tu-left-td">订单结算给配送员费用：</td><td class="tu-right-td">&yen;<?php echo round($var['money']['money']/100,2);?>元</td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">订单类型：</td><td class="tu-right-td"><?php if($var['Type'] == 2): ?>跑腿订单<?php else: ?>外卖订单<?php endif; ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">配送方式：</td><td class="tu-right-td"><?php if($var['is_ele_pei'] == 1): ?>商家配送<?php else: ?>平台配送<?php endif; ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">外卖订单下单方式：</td><td class="tu-right-td"><?php if($var['orderType'] == 1): ?>在线点餐<?php else: ?>到店消费<?php endif; ?></td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">需求标题：</td><td class="tu-right-td"><?php echo ($var["title"]); ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">订单状态码：</td><td class="tu-right-td"><?php echo ($getOrderStatus[$var['OrderStatus']]); ?>【<?php echo ($var['OrderStatus']); ?>】</td>
                </tr>
                <tr>
                	<td class="tu-left-td">订单重量：</td><td class="tu-right-td"><?php echo ($var['Weight']); ?>公斤</td>
                </tr>
                <tr>
                	<td class="tu-left-td">预期时间：</td><td class="tu-right-td"><?php echo ($var['ExpectTime']); ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">限时时间：</td><td class="tu-right-td"><?php echo ($var['ExpiredMinutes']); ?>分钟</td>
                </tr>
             	<tr>
                	<td class="tu-left-td">下单会员信息：</td><td class="tu-right-td"><?php echo ($var["user"]["nickname"]); ?> 【<?php echo ($var["user_id"]); ?>】</td>
                </tr>
                <tr>
                	<td class="tu-left-td">配送地址：</td><td class="tu-right-td">姓名：<?php echo (($var['name'])?($var['name']):"无"); ?> &nbsp;&nbsp;手机： <?php echo ($var["mobile"]); ?> &nbsp;&nbsp;  地址：<?php echo ($var["addr"]); ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">配送员信息：</td><td class="tu-right-td"><?php echo ($var["deliveryInfo"]); ?></td>
                </tr>
                
                <tr><td class="tu-right-td profit" colspan="2">操作时间</td></tr>
                <tr>
                	<td class="tu-left-td">操作时间：</td><td class="tu-right-td">
                    
                    		创建订单：
                            <?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?>
                            <?php if($var['pay_time']): ?>&nbsp;&nbsp;&nbsp;&nbsp;付款时间：<?php echo (date('Y-m-d H:i:s',$var["pay_time"])); endif; ?>
                            <?php if($var['update_time']): ?>&nbsp;&nbsp;&nbsp;&nbsp;接单时间：<?php echo (date('Y-m-d H:i:s',$var["update_time"])); endif; ?>
                            <?php if($var['end_time']): ?>&nbsp;&nbsp;&nbsp;&nbsp;完成时间<?php echo (date('Y-m-d H:i:s',$var["end_time"])); endif; ?>
                    </td>
                </tr>
                <tr><td class="tu-right-td profit" colspan="2">评价管理</td></tr>
                
                <tr>
                	<td class="tu-left-td">评价内容：</td><td class="tu-right-td"><?php echo ($var["content"]); ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">评价标签：</td><td class="tu-right-td"><?php echo ($var["labels"]); ?></td>
                </tr>
                <tr><td class="tu-right-td profit" colspan="2">退款信息</td></tr>
                <tr>
                	<td class="tu-left-td">退款理由：</td><td class="tu-right-td"><?php echo ($var["OrderRefundInfo"]); ?></td>
                </tr>
                
                
                <tr><td class="tu-right-td profit" colspan="2">其他操作</td></tr>
                <tr>
                	<td class="tu-left-td"></td>
                    <td class="tu-right-td">
                    
                    <?php if(($var["OrderStatus"]) == "1"): echo BA('running/deleteOrder',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn');?>
                        <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn'); endif; ?>
                    
                    <?php if(($var["OrderStatus"]) == "2"): echo BA('running/closedOrder',array("running_id"=>$var["running_id"],"p"=>$p),'关闭订单','act','tu-dou-btn');?>
                        <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn'); endif; ?>
                    
                    <?php if(($var["OrderStatus"]) == "32"): echo BA('running/completeOrder',array("running_id"=>$var["running_id"],"p"=>$p),'完成订单','act','tu-dou-btn'); endif; ?>
                    
                    
                    <?php if(($var["OrderStatus"]) == "256"): echo BA('running/delete',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn-small'); endif; ?>
                    <?php if(($var["OrderStatus"]) == "2048"): echo BA('running/delete',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn-small'); endif; ?>
                    
                    <?php if(($var["OrderStatus"]) == "128"): echo BA('delivery/finance',array("order_id"=>$var["running_id"],"delivery_id"=>$var["cid"],"p"=>$p),'财务','','tu-dou-btn-small'); endif; ?>
                    
                    <?php if(($var["Type"]) == "1"): echo BA('running/product',array("running_id"=>$var["running_id"],"shop_id"=>$var["ShopId"],"source"=>"index","p"=>$p),'订单明细','','tu-dou-btn-small'); endif; ?>
                    
                    <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn-small');?>
                    <?php echo BA('running/complete',array("running_id"=>$var["running_id"],"p"=>$p),'强制完成','act','tu-dou-btn-small');?>
                    
                    
                    
                    <?php if(($var["files"]) == "1"): echo BA('running/files',array("running_id"=>$var["running_id"],"p"=>$p),'附件','','tu-dou-btn-small-waring'); endif; ?>
                    
                    <?php echo BA('running/detail',array("running_id"=>$var["running_id"],"p"=>$p),'刷新当前页面','','tu-dou-btn-small');?>
                    <?php echo BA('running/index',array("running_id"=>$var["running_id"],"p"=>$p),'返回订单列表','','tu-dou-btn-small');?>
                    
                    
                    </td>
                </tr>
             
            </table>
        </div>
    </div>

  		</div>
	</body>
</html>