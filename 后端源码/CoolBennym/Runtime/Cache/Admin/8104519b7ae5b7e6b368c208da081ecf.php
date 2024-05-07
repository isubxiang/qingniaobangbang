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
.profit{text-align: center;color: #333;font-weight: bold; background: #F5F5FB;}
.tu-left-td{width:200px;}
</style>

<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">基本设置</li>
        <li class="li2 li3">跑腿设置</li>
    </ul>
</div>
<p class="attention"><span>注意：</span>跑腿设置写在这里</p>
<form  target="x-frame" action="<?php echo U('setting/running');?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
                <tr>
                    <td class="tu-left-td">跑腿基础费用：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[freight]" value="<?php echo ($CONFIG["running"]["freight"]); ?>" class="tudou-sc-add-text-name w150" />
						<code>每一次跑腿的基本费用，会员发布的时候收取，支持在线支付，新版如果筛选分类，在跑腿分类那调用费用</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">发布跑腿间隔时间：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[interval_time]" value="<?php echo ($CONFIG["running"]["interval_time"]); ?>" class="tudou-sc-add-text-name w150" />
						<code>秒为单位，建议填写60-900秒</code>
                    </td>
                </tr>
                
               <tr>
                    <td class="tu-left-td">会员发布跑腿小提示：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[prompt]" value="<?php echo ($CONFIG["running"]["prompt"]); ?>" class="tudou-manageInput tudou-manageInput2" />
						<code>会员中心发布跑腿的时候的小提示，不超过30字！</code>
                    </td>
                </tr>
                
                
                <tr>
                  <td class="tu-right-td profit" colspan = "2"  style="color:#F00"> 请注意没有配置微信证书的千万不要开启，证书路径    根目录/cret/</a></td>
                </tr>
                    
				<tr>
                    <td class="tu-left-td">开启取消订单原路退款：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[running_weixin_original_refund]" <?php if(($CONFIG["running"]["running_weixin_original_refund"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <label><input type="radio" name="data[running_weixin_original_refund]" <?php if(($CONFIG["running"]["running_weixin_original_refund"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <code style="color:#F00">证书路径/根目录/cret/需要上传3个文件</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">原路退款最大退款金额：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[running_weixin_original_refund_mix]" value="<?php echo ($CONFIG["running"]["running_weixin_original_refund_mix"]); ?>" class="tudou-sc-add-text-name w80"/>
						<code style="color:#F00">用户申请退款金额最大超过此数字后无法申请退款，不支持小数点，建议设置10元-100元只能填写数字，不要填写中文之类的</code>
                    </td>
                </tr>
                
                
                <tr>
                	<td class="tu-right-td profit" colspan="2"> 配送员跑腿结算费率</td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">结算佣金：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[rate]" value="<?php echo ($CONFIG["running"]["rate"]); ?>" class="tudou-sc-add-text-name w80"/>%
						<code>结算给跑腿的会员账户比如填写10%实际付款100元，给跑腿90元，网站扣除佣金100元</code>
                    </td>
                </tr>
                
               <tr>
                  <td class="tu-right-td profit" colspan="2"> 校园跑腿小程序配置</td>
               </tr>
               
               <tr>
                  <td class="tu-left-td">可同时抢几个订单：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[tongshiNum]" value="<?php echo ($CONFIG["running"]["tongshiNum"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>可同时配送几个订单，建议设置1-3个，默认3个订单，不支持小数点</code>
                  </td>
               </tr>
               <tr>
                  <td class="tu-left-td">抢单间隔时间：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[tongshiTime]" value="<?php echo ($CONFIG["running"]["tongshiTime"]); ?>" class="tudou-sc-add-text-name w80"/>分钟
                    <code>抢单一个后，再去抢单第二个需要间隔多少分钟才行，不填写默认1分钟，不支持小数点</code>
                  </td>
               </tr>
               
               <tr>
                  <td class="tu-left-td">执行价格开始时间：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[ErrandTimeRangeBegin]" value="<?php echo ($CONFIG["running"]["ErrandTimeRangeBegin"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>比如：8:00主题英文小写的符号不要有空格不要乱写</code>
                  </td>
               </tr>
              <tr>
                  <td class="tu-left-td">执行价格结束时间：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[ErrandTimeRangeEnd]" value="<?php echo ($CONFIG["running"]["ErrandTimeRangeEnd"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>比如：22:00主题英文小写的符号不要有空格不要乱写</code>
                  </td>
               </tr>
               <tr>
                  <td class="tu-left-td">运费起价说明：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[FreightMoneyCaption]" value="<?php echo ($CONFIG["running"]["FreightMoneyCaption"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>比如填写，雨雪天等关键字</code>
                  </td>
               </tr>
               <tr>
                  <td class="tu-left-td">最低运费：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[MinFreightMoney]" value="<?php echo ($CONFIG["running"]["MinFreightMoney"]); ?>" class="tudou-sc-add-text-name w80"/>元
                    <code>填写2-6不支持小数点</code>
                  </td>
               </tr>
               <tr>
                  <td class="tu-left-td">永许投递类型：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[NormalDeliveryAllowOrderTypes]" value="<?php echo ($CONFIG["running"]["NormalDeliveryAllowOrderTypes"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>填写数字1-3不要乱填写</code>
                  </td>
               </tr>
               
                <tr>
                	<td class="tu-right-td profit" colspan="2"> 服务协议文章等设置</td>
                </tr>
                
                <tr>
                  <td class="tu-left-td">运费说明文章ID：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[ExpressCostArticleId]" value="<?php echo ($CONFIG["running"]["ExpressCostArticleId"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>请去文章列表抄ID，请不要乱写</code>
                  </td>
               </tr>
              <tr>
                  <td class="tu-left-td">服务协议文章ID：</td>
                  <td class="tu-right-td">
                    <input type="text" name="data[ErrandServiceArticleId]" value="<?php echo ($CONFIG["running"]["ErrandServiceArticleId"]); ?>" class="tudou-sc-add-text-name w80"/>
                    <code>请去文章列表抄ID，请不要乱写</code>
                  </td>
               </tr>
               
                <tr>
                	<td class="tu-right-td profit" colspan="2"> 跑腿认证时候的配置</td>
                </tr>
               <tr>
                    <td class="tu-left-td">认证配送员是都开启身份证上传：</td>
                    <td class="tu-right-td">
                    <label><input type="radio" name="data[card_open]" <?php if(($CONFIG["running"]["card_open"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                    <label><input type="radio" name="data[card_open]" <?php if(($CONFIG["running"]["card_open"]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>关闭</label>
                    <code style="color:#F00">认证配送员是都开启身份证上传身份证，不开启就不上传</code>
                    </td>
                </tr>
                
              
            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认保存" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>