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
.sogn{ height:30px}
</style>


<div class="tu-main-top-btn">
    <ul>
        <li class="li1">跑腿系统</li>
        <li class="li2">订单管理</li>
        <li class="li2 li3">打印操作</li>
    </ul>
</div>

<div class="main-tu-js">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin:10px 20px;">
            <div class="left">
                <?php echo BA('running/index',array('running_id'=>$var['running_id'],'p'=>$p,'colour'=>'#eee'),'返回订单列表','','',600,360);?>
                <?php echo BA('running/Printing',array('running_id'=>$var['running_id'],'p'=>$p),'刷新当前页面','','',600,360);?>
                <?php echo BA('setting/config',array('running_id'=>$var['running_id'],'p'=>$p),'快递API配置','','',600,360);?>
            </div>
        </div>
 </div>       
        

    <div class="main-tudou-sc-add" style="margin-top:10px;">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
       
                <tr>
                	<td class="tu-left-td">订单信息：</td>
                    <td class="tu-right-td"> 订单号： <?php echo ($var["running_id"]); ?>  &nbsp;&nbsp;&nbsp; 订单号：<?php echo ($var["Code"]); ?>  &nbsp;&nbsp;&nbsp; 分类ID：<?php echo ($var["cate_id"]); ?> &nbsp;&nbsp;&nbsp; 学校ID：<?php echo ($var["school_id"]); ?></td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">快递公司编码：</td><td class="tu-right-td"><?php echo ($var['ShipperCode']); ?></td>
                </tr>
                <tr>
                	<td class="tu-left-td">快递单号：</td><td class="tu-right-td"><?php echo ($var['OrderCode']); ?></td>
                </tr>
                
                <tr>
                	<td class="tu-left-td">电子面单打印状态：</td><td class="tu-right-td">
                        <?php if(($IsPrinting) == "1"): ?>已打印 
                        <?php else: ?>
                            未打印<?php endif; ?>
                    </td>
                </tr>
                 <tr>
                	<td class="tu-left-td">电子面单打印返回码：</td><td class="tu-right-td"><?php echo ($var['PrintingInfo']); ?></td>
                </tr>
                
                
                
                <tr><td class="tu-right-td profit" colspan="2">打印电子面单，必须先申请快递鸟的API信息后能成功打印电子面单，当前功能仅限于跑腿订单</td></tr>
                <input type="hidden" id="running_id" name="running_id" value="<?php echo ($var['running_id']); ?>"/>
                <input type="hidden" id="p" name="p" value="<?php echo ($p); ?>"/>
                <tr>
                    <td class="tu-left-td">选择快递公司：</td>
                    <td class="tu-right-td">
                        <select name="ShipperCode" id="ShipperCode" class="tudou-sc-add-text-name sogn">
                            <option value="0">==请选择快递接口==</option>
                            <option <?php if(($ShipperCode) == "EMS"): ?>selected="selected"<?php endif; ?> value="EMS">EMS</option>
                            <option <?php if(($ShipperCode) == "SF"): ?>selected="selected"<?php endif; ?> value="SF">顺丰速运</option>
                            <option <?php if(($ShipperCode) == "YZBK"): ?>selected="selected"<?php endif; ?> value="YZBK">邮政国内标快</option>
                            <option <?php if(($ShipperCode) == "YZPY"): ?>selected="selected"<?php endif; ?> value="YZPY">邮政快递包裹</option>
                            <option <?php if(($ShipperCode) == "ZJS"): ?>selected="selected"<?php endif; ?> value="ZJS">宅急送</option>
                            <option <?php if(($ShipperCode) == "STO"): ?>selected="selected"<?php endif; ?> value="STO">申通快递</option>
                            <option <?php if(($ShipperCode) == "DBL"): ?>selected="selected"<?php endif; ?> value="DBL">德邦快递</option>
                            <option <?php if(($ShipperCode) == "SF"): ?>selected="selected"<?php endif; ?> value="SF">顺丰速运</option>
                            <option <?php if(($ShipperCode) == "JD"): ?>selected="selected"<?php endif; ?> value="JD">京东快递</option>
                            <option <?php if(($ShipperCode) == "HHTT"): ?>selected="selected"<?php endif; ?> value="HHTT">天天快递</option>
                            <option <?php if(($ShipperCode) == "ZTO"): ?>selected="selected"<?php endif; ?> value="ZTO">申通快递</option>
                            <option <?php if(($ShipperCode) == "YD"): ?>selected="selected"<?php endif; ?> value="YD">韵达快递</option>
                            <option <?php if(($ShipperCode) == "HTKY"): ?>selected="selected"<?php endif; ?> value="HTKY">百世快递</option>
                            <option <?php if(($ShipperCode) == "YTO"): ?>selected="selected"<?php endif; ?> value="YTO">圆通快递</option>
                            <option <?php if(($ShipperCode) == "UC"): ?>selected="selected"<?php endif; ?> value="UC">优速快递</option>
                            <option <?php if(($ShipperCode) == "ANE"): ?>selected="selected"<?php endif; ?> value="ANE">安能快递</option>
                        </select>
                        <code>快递公司必选</code>
                        <input type="text" name="OrderCode" id="OrderCode" value="" placeholder="填写快递单号" class="tudou-sc-add-text-name"/>
                        <code>←这填写单号</code>
                        <div class="sm-qr-tu"><a style="text-align:center; height:30px; line-height:30px" onclick="PrintOrder(<?php echo ($var['running_id']); ?>)" class="tu-dou-btn-small-waring w360">打印电子面单</a></div>
                    </td>
                </tr>   
               
               <script>
			   //打印操作
				function PrintOrder(running_id){
					var running_id = $('#running_id').val();
					var ShipperCode = $('#ShipperCode').val();
					var OrderCode = $('#OrderCode').val();
					var p = $('#p').val();
					
					if(running_id == ''){
						parent.layer.msg('ID不存在',{icon:2});
						return false;
					}
					if(ShipperCode == '' || ShipperCode == '0'){
						parent.layer.msg('物流公司必须选择',{icon:2});
						return false;
					}
					if(OrderCode == ''){
						parent.layer.msg('物流单号必须填写',{icon:2});
						return false;
					}
					
					var senddata = 'running_id=' + running_id + '&ShipperCode=' + ShipperCode+ '&OrderCode=' + OrderCode + '&p=' + p;
					
					$.ajax({
						url: "<?php echo U('running/PrintOrder');?>",
						type: 'post',
						data: senddata,
						success: function(data){
						  if(data.status == 'success'){
							 parent.layer.msg(data.msg,{icon:1});
								setTimeout(function () {
								  window.location.href = data.url;
								}, 1000)
						  }else{
							parent.layer.msg(data.msg,{icon:2});
						  }
						}
					});
				}
			</script>
              
                
                <tr><td class="tu-right-td profit" colspan="2">其他操作</td></tr>
                <tr>
                	<td class="tu-left-td"></td>
                    <td class="tu-right-td">
                    
                    <?php if(($var["OrderStatus"]) == "1"): echo BA('running/deleteOrder',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn');?>
                        <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn'); endif; ?>
                    
                    <?php if(($var["OrderStatus"]) == "2"): echo BA('running/closedOrder',array("running_id"=>$var["running_id"],"p"=>$p),'关闭订单','act','tu-dou-btn');?>
                        <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn'); endif; ?>
                    
                    <?php if(($var["OrderStatus"]) == "32"): echo BA('running/completeOrder',array("running_id"=>$var["running_id"],"p"=>$p),'完成订单','act','tu-dou-btn-small-waring'); endif; ?>
                    
                    
                    <?php if(($var["OrderStatus"]) == "256"): echo BA('running/delete',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn-small'); endif; ?>
                    <?php if(($var["OrderStatus"]) == "2048"): echo BA('running/delete',array("running_id"=>$var["running_id"],"p"=>$p),'删除订单','act','tu-dou-btn-small'); endif; ?>
                    
                    <?php if(($var["OrderStatus"]) == "128"): echo BA('delivery/finance',array("order_id"=>$var["running_id"],"delivery_id"=>$var["cid"],"p"=>$p),'财务','','tu-dou-btn-small'); endif; ?>
                    
                    <?php if(($var["Type"]) == "1"): echo BA('running/product',array("running_id"=>$var["running_id"],"shop_id"=>$var["ShopId"],"source"=>"index","p"=>$p),'订单明细','','tu-dou-btn-small'); endif; ?>
                    
                    <?php echo BA('running/delete2',array("running_id"=>$var["running_id"],"p"=>$p),'强制删除','act','tu-dou-btn-small');?>
                    <?php echo BA('running/complete',array("running_id"=>$var["running_id"],"p"=>$p),'强制完成','act','tu-dou-btn-small');?>
                    
                    
                    
                    <?php if(($var["files"]) == "1"): echo BA('running/files',array("running_id"=>$var["running_id"],"p"=>$p),'附件','','tu-dou-btn-small-waring'); endif; ?>
                    <?php echo BA('running/detail',array("running_id"=>$var["running_id"],"p"=>$p),'订单详情','','tu-dou-btn-small-waring');?>
                    <?php echo BA('running/Printing',array("running_id"=>$var["running_id"],"p"=>$p),'刷新当前页面','','tu-dou-btn-small');?>
                    <?php echo BA('running/index',array("running_id"=>$var["running_id"],"p"=>$p),'返回订单列表','','tu-dou-btn-small');?>
                    
                    
                    </td>
                </tr>
             
            </table>
        </div>
    </div>

  		</div>
	</body>
</html>