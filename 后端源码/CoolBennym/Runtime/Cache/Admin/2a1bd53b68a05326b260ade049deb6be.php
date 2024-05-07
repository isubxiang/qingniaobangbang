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
.tu-inpt-text {width: 92px;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">支付设置</li>
        <li class="li2 li3">支付日志</li>
    </ul>
</div>
<p class="attention"><span>注意：</span>新版这里可以按照支付订单类型查询日志，还可以按照付款状态等查询日志，已可以高级搜索里面按照时间查询，非常方便！</p>
<p class="attention"><span>网站总支付资金统计：</span>
未付款总金额：&yen;<?php echo round($money_is_paid_0/100,2);?>元，已付款：&yen;<?php echo round($money_is_paid_1/100,2);?>元
<a style="color:#F00; font-weight:bold;">
    <?php if(is_array($types)): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i; if($key == $st): ?>【<?php echo ($item); ?>】<?php endif; endforeach; endif; else: echo "" ;endif; ?>
未付款：&yen;<?php echo round($sum_0/100,2);?>元，已付款：&yen;<?php echo round($sum_1/100,2);?>元</a>
</p>
<div class="main-tu-js main-sc">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('paymentlogs/index');?>">
                    <div class="seleHidden" id="seleHidden">
                    <div class="seleK">
                     <label><span>开始时间</span><input type="text" name="bg_date" value="<?php echo (($bg_date)?($bg_date):''); ?>"onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" class="text"/></label>
                        <label><span>结束时间</span><input type="text" name="end_date" value="<?php echo (($end_date)?($end_date):''); ?>"onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd'});" class="text"/></label>
                        
                        
                        <label>
                                <input type="hidden" id="user_id" name="user_id" value="<?php echo (($user_id)?($user_id):''); ?>" />
                                <input type="text" name="nickname" id="nickname"  value="<?php echo ($nickname); ?>"   class="text " />
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="sumit">选择用户</a>
                            </label>
                        
                        <label>
                                <input type="hidden" id="school_id" name="school_id" value="<?php echo (($school_id)?($school_id):''); ?>" />
                                <input type="text" name="Name" id="Nme"  value="<?php echo ($Name); ?>"   class="text "/>
                                <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="sumit">选择学校</a>
                            </label>
                         <label>    
                            
                      <label>
                          <span>支付类型：</span>
                          <select class="select w120" name="type">
                             <option value="999">请选择类型</option>
                             <?php if(is_array($types)): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($key == $type): ?>selected="selected" class="red"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                          </select>
                        </label>
                        <label>
                          <span>支付方式：</span>
                          <select class="select w120" name="code">
                          	  <option value="999">请选择支付方式</option>
                              <?php if(is_array($codes)): $i = 0; $__LIST__ = $codes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><option <?php if($key == $code): ?>selected="selected" class="red"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                          </select>
                        </label>
                       <label>
                          <span>付款状态：</span>
                          <select class="select w120" name="status">
                          	  <option value="999">请选择状态</option>
                              <option <?php if(($status) == "999"): ?>selected="selected"<?php endif; ?> value="999">请选择</option>
                              <option <?php if(($status) == "0"): ?>selected="selected"<?php endif; ?>  value="0">未付款</option>
                              <option <?php if(($status) == "1"): ?>selected="selected"<?php endif; ?>  value="1">已付款</option>
                          </select>
                        </label>
                        <span>支付编号:</span>
                        <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text"/>
                        <input type="submit"value="搜索"class="inpt-button-tudou"/>
                        </div>
                    </div>
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <form method="post" action="<?php echo U('paymentlogs/index');?>">
            <div class="tu-select-nr tu-select-nr2">
                <div class="left">
                    <div class="seleK">
                        
                    </div>
                </div>
                <div class="right">
                    <input type="submit" value="   搜索" class="inpt-button-tudou"/>
                </div>
        </form>

        <div class="clear"></div>
    </div>
    <form target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"
                   style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;">
                <tr>
                    <td><input type="checkbox" class="checkAll" rel="log_id"/></td>
                    <td>ID</td>
                    <td>用户</td>
                    <td>订单编号</td>
                    <td>需要支付金额(元)</td>
                    <td>消费类型</td>
                    <td>创建时间</td>
                    <td>创建IP</td>
                    <td>支付时间</td>
                    <td>支付IP</td>
                    <td>是否已经支付</td>
                    <td>支付类型</td>
                    <td>返回订单号</td>
                    <td>返回交易号</td>
                    <td>原路退款单号</td>
                    <td>原路退款金额</td>
                    <td>原路退款说明</td>
                    <td>原路退款时间</td>
                    <td>操作</td>
                </tr>

                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_log_id" type="checkbox" name="log_id[]" value="<?php echo ($var["log_id"]); ?>"/></td>
                        <td><?php echo ($var["log_id"]); ?></td>
                        <td><?php echo ($var["user_id"]); ?></td>
                        <td><?php echo ($var["order_id"]); ?></td>
                        <td><?php echo round($var['need_pay']/100,2);?></td>
                        <td>
                           <?php echo ($var["type"]); ?>
                        </td>
                        <td>
                        <?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?>
                        </td>
                        <td><?php echo ($var["create_ip"]); ?></td>
                        <td><?php if(!empty($var['pay_time'])): echo (date('Y-m-d H:i:s',$var["pay_time"])); else: ?>未支付无支付时间<?php endif; ?></td>
                        <td><?php echo ($var["pay_ip"]); ?></td>
                        <td>
                            <?php if(($var["is_paid"]) == "1"): ?><font style="color: green">已支付</font><?php endif; ?>
                            <?php if(($var["is_paid"]) == "0"): ?><font>未支付</font><?php endif; ?>
                            <?php if(($var["is_paid"]) == "4"): ?><font style="color:blue">已退款</font><?php endif; ?>
                        </td>
                        <td><span style="color:darkcyan">小程序支付</span>   </td>
                        <td><?php echo ($var["return_order_id"]); ?></td>
                        <td><?php echo ($var["return_trade_no"]); ?></td>
                        <td><?php echo ($var["out_refund_no"]); ?></td>
                        <td><?php echo round($var['refund_fee']/100,2);?></td>
                        <td><?php echo ($var["refund_info"]); ?></td>
                        <td><?php if(!empty($var['refund_time'])): echo (date('Y-m-d H:i:s',$var["refund_time"])); else: ?>未退款无退款时间<?php endif; ?></td>
                        <td>
                        </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
            <div class="left">
                <?php echo BA('paymentlogs/delete','','批量删除','list','a2');?>
            </div>
        </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>