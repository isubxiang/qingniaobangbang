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
.profit {text-align: center;color: #333;font-weight: bold; background: #F5F5FB;}
.tu-left-td{width:200px;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">设置</li>
        <li class="li2">基本设置</li>
        <li class="li2 li3">充值提现设置</li>
    </ul>
</div>
<p class="attention">这里可以设置会员提现至少满多少钱才可以提现，或者单笔不超过多少钱！<span>注意：单笔提现设置必须比满多少元大！记住了！不能设置为0，可以留空！</span></p>
<form  target="x-frame" action="<?php echo U('setting/cash');?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                    <?php $config = D('Setting')->fetchAll(); ?>
                    
                 <tr>
                     <td class="tu-left-td">是否开启会员提现：</td>
                     <td class="tu-right-td">
                     <label><input type="radio" name="data[is_cash]" <?php if(($CONFIG["cash"]["is_cash"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                     <label><input type="radio" name="data[is_cash]" <?php if(($CONFIG["cash"]["is_cash"]) == "0"): ?>checked="checked"<?php endif; ?>  value="0"/>关闭</label>
                        <code>开启后才会出现会员提现按钮，否则会员无法提现，商家提现不受任何影响，商家已没有必要关闭提现功能。</code>
                      </td>
			    </tr>
                <tr>
                     <td class="tu-left-td">是否开启会员支付宝提现：</td>
                     <td class="tu-right-td">
                     <label><input type="radio" name="data[is_alipay_cash]" <?php if(($CONFIG["cash"]["is_alipay_cash"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启</label>
                     <label><input type="radio" name="data[is_alipay_cash]" <?php if(($CONFIG["cash"]["is_alipay_cash"]) == "0"): ?>checked="checked"<?php endif; ?>  value="0"/>关闭</label>
                     <code>首先开启会员提现功能后，再开启支付宝提现方式后，这功能需要支付设置》》》支付列表》》》支付宝编辑里面配置参数</code>
                     </td>
			    </tr>
                
                    
                <tr>
                    <td class="tu-left-td">会员提现设置：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[user]" value="<?php echo ($CONFIG["cash"]["user"]); ?>" class="tudou-sc-add-text-name w80" />
						<code>←会员单笔提现最低</code>
                        <input type="text" name="data[user_big]" value="<?php echo ($CONFIG["cash"]["user_big"]); ?>" class="tudou-sc-add-text-name w80" />
                        <code>←会员单笔提现最高
                            <?php if($config['cash']['user_big'] <= $config['cash']['user']): ?><a style="color:#F00">会员最低提现设置不合法 </a><?php endif; ?>
                        </code>
                        <input type="text" name="data[user_cash_commission]" value="<?php echo ($CONFIG["cash"]["user_cash_commission"]); ?>" class="tudou-sc-add-text-name w80" />%
                        <code>单笔提现手续费，设置3%，100元扣除3元，实际到账97元，留空不扣除手续费</code>
                    </td>
                </tr>
                 

                <tr>
                    <td class="tu-left-td">商户提现设置：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[shop]" value="<?php echo ($CONFIG["cash"]["shop"]); ?>" class="tudou-sc-add-text-name w80" />
						<code>←商家单笔提现最低</code>
                        <input type="text" name="data[shop_big]" value="<?php echo ($CONFIG["cash"]["shop_big"]); ?>" class="tudou-sc-add-text-name w80" />
						<code>←商家单笔提现最高
                        	<?php if($config['cash']['shop_big'] <= $config['cash']['shop']): ?><a style="color:#F00">商户最低提现不合法 </a><?php endif; ?>
                        </code>
                        <input type="text" name="data[shop_cash_commission]" value="<?php echo ($CONFIG["cash"]["shop_cash_commission"]); ?>" class="tudou-sc-add-text-name w80" />%
                        <code>特别说明，如果此会员已开通商户，按照这里配置走，单笔提现手续费，设置3%，100元扣除3元，实际到账97元，留空不扣除手续费</code>
                    </td>
                </tr>


                
                <tr> 
                    <td class="tu-left-td">每日申请提现次数：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[user_cash_second]" value="<?php echo ($CONFIG["cash"]["user_cash_second"]); ?>" class="tudou-sc-add-text-name w80" />
						<code>会员每日最多申请多少次提现</code>
                        <input type="text" name="data[shop_cash_second]" value="<?php echo ($CONFIG["cash"]["shop_cash_second"]); ?>" class="tudou-sc-add-text-name w80" />
						<code>商家每日最多申请多少次提现</code>
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