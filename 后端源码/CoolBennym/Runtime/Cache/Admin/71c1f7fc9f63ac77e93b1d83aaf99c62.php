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
.main-tu-js .tudou-js-nr .tu-select-nr .left a, .piliangcaozuo{
	height:20px;
    line-height: 20px;
    border-radius:2px;
    padding-left: 5px;
    background-image: none;
    padding-right: 5px;
}
.main-sc .tudou-js-nr .tu-select-nr .right .select {
    margin-right: 5px;
}
</style>

<div class="tu-main-top-btn">
    <ul>
        <li class="li1">跑腿管理</li>
        <li class="li2">跑腿订单</li>
        <li class="li2 li3">跑腿列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
  <p class="attention"><span>注意：</span>状态码：'1'='待付款','2'='待处理','4'='制作中','8'='待配送','16'='已接单','32'='配送中','64'='待评价','128'='已完成','256'='付款超时','512'='用户取消','1024'='商家取消','2048'='过期取消','4096'='后台取消','8192'='退款失败'</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
        
        
            <div class="left">
            	<a href="<?php echo U('running/index',array('p'=>$p));?>">刷新订单</a>
                <a <?php if(($OrderStatus) == "999"): ?>class="on"<?php endif; ?> href="<?php echo U('running/index',array('OrderStatus'=>$key));?>">全部订单</a>
                <?php if(is_array($getOrderStatus)): foreach($getOrderStatus as $key=>$item): ?><a <?php if(($OrderStatus) == $key): ?>class="on"<?php endif; ?> href="<?php echo U('running/index',array('OrderStatus'=>$key));?>"><?php echo ($item); ?></a><?php endforeach; endif; ?>
            </div>
            
            
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('running/index');?>">
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
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                            <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text"/>
                            <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                        </label>
                        
                        <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                         </label> 


                     	<label>
                            <span>订单状态</span>
                            <select name="OrderStatus" class="select w110">
                                <option value="999">=请选择状态=</option>
                                <?php if(is_array($getOrderStatus)): foreach($getOrderStatus as $key=>$item): ?><option <?php if(($OrderStatus) == $key): ?>selected="selected"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; ?>
                            </select>
                        </label>
                        
                        <label>
                            <span>订单类型</span>
                            <select name="Type" class="select w110">
                               <option value="999">=请选择类型=</option>
                               <option <?php if(($Type) == "2"): ?>selected="selected"<?php endif; ?>  value="2">跑腿订单</option>
                               <option <?php if(($Type) == "1"): ?>selected="selected"<?php endif; ?>  value="1">外卖订单</option>
                            </select>
                        </label>
                        
                        <label>
                            <span>配送方式</span>
                            <select name="is_ele_pei" class="select w110">
                               <option value="999">=请选择配==</option>
                               <option <?php if(($is_ele_pei) == "0"): ?>selected="selected"<?php endif; ?>  value="0">网站配送</option>
                               <option <?php if(($is_ele_pei) == "1"): ?>selected="selected"<?php endif; ?>  value="1">商家配送</option>
                            </select>
						</label>
                        
                        <label>
                            <span>外卖下单方式</span>
                            <select name="orderType" class="select w110">
                               <option value="999">=外卖下单方式==</option>
                               <option <?php if(($orderType) == "1"): ?>selected="selected"<?php endif; ?>  value="1">在线点餐</option>
                               <option <?php if(($orderType) == "2"): ?>selected="selected"<?php endif; ?>  value="2">到店消费</option>
                            </select>
						</label>



                                                
                        <label>
                            <span>关键字</span>
                            <input type="text" name="keyword" value="<?php echo (($keyword)?($keyword):''); ?>" class="tu-inpt-text" />
                            <input type="submit" value="搜索"  class="inpt-button-tudou" />
                            
                            <a href="<?php echo U('running/export');?>" class="inpt-button-tudou">&nbsp;&nbsp;导出</a>
                            
                            
                            
                        </label>
                        </div> 
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

    
    <form  target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50"><input type="checkbox" class="checkAll" rel="running_id" /></td>
                    <td class="w50">ID</td>
                    <td>学校名称</td>
                    <td>订单类型</td>
                    <td>配送方式</td>
                    <td>外卖下单方式</td>
                    <td>订单状态</td>
                    <td>限制时间</td>
                    <td>买家昵称</td>
                    <td>配送员信息</td>
                    <td>跑腿费用</td>
                    <td>实际支付</td>
                    <td>结算费用</td>
                    <td>操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr <?php if($var['running_id'] == $running_id): ?>style="background:#eee"<?php endif; ?>>
                        <td><input class="child_running_id" type="checkbox" name="running_id[]" value="<?php echo ($var["running_id"]); ?>" /></td>
                        <td><?php echo ($var["running_id"]); ?></td>
                        <td><?php echo ($var["school"]["Name"]); ?></td>
                        <td><?php if($var['Type'] == 2): ?>跑腿订单<?php else: ?>外卖订单<?php endif; ?></td>
                        <td><?php if($var['is_ele_pei'] == 1): ?>商家配送<?php else: ?>平台配送<?php endif; ?></td>
                        <td>
                            <?php if($var['Type'] == 1): if($var['orderType'] == 1): ?>在线点餐<?php else: ?>到店消费<?php endif; endif; ?>
                            <?php if($var['Type'] == 2): ?>---<?php endif; ?>
                        </td>
                        <td><?php echo ($getOrderStatus[$var['OrderStatus']]); ?></td>
                        <td><?php echo ($var['ExpiredMinutes']); ?>分钟</td>
                        <td><?php echo ($var["user"]["nickname"]); ?></td>
                        <td><?php echo ($var["deliveryInfo"]); ?></td>
                        <td> &yen;<?php echo round($var['freight']/100,2);?></td>
                        <td> &yen;<?php echo round($var['need_pay']/100,2);?></td>
                        <td style="color:#F00">&yen;<?php echo round($var['money']['money']/100,2);?></td>
                        <td>
                        	<?php if(($var["OrderStatus"]) == "1"): echo BA('running/deleteOrder',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn');?>
                                <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn'); endif; ?>
                            
                            <?php if(($var["OrderStatus"]) == "2"): echo BA('running/closedOrder',array("running_id"=>$var["running_id"],"p"=>$p),'关闭订单','act','tu-dou-btn');?>
                                <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn'); endif; ?>
                            
                            <?php if(($var["files"]) == "1"): echo BA('running/files',array("running_id"=>$var["running_id"],"p"=>$p),'附件','','tu-dou-btn-small-waring'); endif; ?>
                            
                            <?php if(($var["OrderStatus"]) == "32"): echo BA('running/completeOrder',array("running_id"=>$var["running_id"],"p"=>$p),'完成订单','act','tu-dou-btn'); endif; ?>
                            
                            
                            <?php if(($var["OrderStatus"]) == "256"): echo BA('running/delete',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn-small'); endif; ?>
                            <?php if(($var["OrderStatus"]) == "2048"): echo BA('running/delete',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn-small'); endif; ?>
                            
                            <?php if(($var["OrderStatus"]) == "128"): echo BA('delivery/finance',array("order_id"=>$var["running_id"],"delivery_id"=>$var["cid"],"p"=>$p),'财务','','tu-dou-btn-small'); endif; ?>
                            
                            <?php if(($var["Type"]) == "1"): echo BA('running/product',array("running_id"=>$var["running_id"],"shop_id"=>$var["ShopId"],"source"=>"index","p"=>$p),'订单明细','','tu-dou-btn-small'); endif; ?>
                            
                            <?php echo BA('running/detail',array("running_id"=>$var["running_id"],"p"=>$p),'详细','','tu-dou-btn-small-waring');?>
                            
                            <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn-small');?>
                            <?php echo BA('running/complete',array("running_id"=>$var["running_id"],"p"=>$p),'强制完成','act','tu-dou-btn-small');?>
                            
                            <?php if(($var["Type"]) == "2"): echo BA('running/Printing',array("running_id"=>$var["running_id"],"p"=>$p),'打印操作','','tu-dou-btn-small-waring'); endif; ?>
                            
                            <?php echo BA('paymentlogs/index',array("type"=>"running","order_id"=>$var["running_id"],"p"=>$p),'支付日志','','tu-dou-btn-small');?>
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