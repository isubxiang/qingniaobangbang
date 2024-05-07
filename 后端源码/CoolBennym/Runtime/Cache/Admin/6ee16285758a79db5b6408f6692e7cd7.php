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
     	<li class="li1">设置</li>
        <li class="li2">上传设置</li>
        <li class="li2">参数配置</li>
    </ul>
</div>
<form target="x-frame" action="<?php echo U('upset/edit',array('id'=>$detail['id']));?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                <tr>
                    <td class="tu-left-td">类型：</td>
                    <td class="tu-right-td"><?php echo (($detail["type"])?($detail["type"]):''); ?>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">存储地区选择<?php echo (($detail[para][region])?($detail[para][region]):''); ?></td>
                    <td class="tu-right-td">
                    <label>
                    	<input type="radio" name="para[region]" <?php if(($detail[para][region]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>华东
                    </label>
                    <label>
                    	<input type="radio" name="para[region]" <?php if(($detail[para][region]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>华北
                    </label>
                    <label>
                    	<input type="radio" name="para[region]" <?php if(($detail[para][region]) == "1"): ?>checked="checked"<?php endif; ?> value="2"/>华南
                    </label>
                    <label>
                    	<input type="radio" name="para[region]" <?php if(($detail[para][region]) == "1"): ?>checked="checked"<?php endif; ?> value="3"/>北美
                    </label>
                    <label>
                    	<input type="radio" name="para[region]" <?php if(($detail[para][region]) == "1"): ?>checked="checked"<?php endif; ?> value="4"/>东南亚
                    </label>
                    <code>地区您申请存储的时候选择的哪里就选择哪里，否则上传失败</code>
                    </td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">是否开启水印：</td>
                    <td class="tu-right-td">
                    	 <label>
                            <input type="radio" name="para[water]" <?php if(($detail[para][water]) == "0"): ?>checked="checked"<?php endif; ?> value="0"/>不开启
                        </label>
                        <label>
                            <input type="radio" name="para[water]" <?php if(($detail[para][water]) == "1"): ?>checked="checked"<?php endif; ?> value="1"/>开启
                        </label>
                        <code>水印图片调用附件位置的水印图片请在附件设置那边配置</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">accessKey</td>
                    <td class="tu-right-td"><input type="text" name="para[accessKey]" value="<?php echo (($detail[para][accessKey])?($detail[para][accessKey]):''); ?>" class="tudou-manageInput tudou-manageInput2" />
                    <code>上面显示那个，不要填写反了，不然无法上传，否则上传失败</code>
                    </td>
                </tr>
				<tr>
                    <td class="tu-left-td">secrectKey：</td>
                    <td class="tu-right-td"><input type="text" name="para[secrectKey]" value="<?php echo (($detail[para][secrectKey])?($detail[para][secrectKey]):''); ?>" class="tudou-manageInput tudou-manageInput2" />
                    <code>下面隐藏那个，注意这一定不要填写反了，否则上传失败</code>
                    </td>
                </tr>
				<tr>
                    <td class="tu-left-td">domain</td>
                    <td class="tu-right-td"><input type="text" name="para[domain]" value="<?php echo (($detail[para][domain])?($detail[para][domain]):''); ?>" class="tudou-manageInput" />
                    <code>您在七牛云通过审核的域名</code>
                    </td>
                </tr>
				<tr>
                    <td class="tu-left-td">bucket：</td>
                    <td class="tu-right-td"><input type="text" name="para[bucket]" value="<?php echo (($detail[para][bucket])?($detail[para][bucket]):''); ?>" class="tudou-manageInput" />
                    <code>空间名，你七牛云创建的空间名称，必须跟后台一致</code>
                    </td>
                </tr>
				<tr>
                    <td class="tu-left-td">timeout：</td>
                    <td class="tu-right-td"><input type="text" name="para[timeout]" value="<?php echo (($detail[para][timeout])?($detail[para][timeout]):''); ?>" class="tudou-manageInput" />
                    <code>超时设置，默认不要更改</code>
                    </td>
                </tr>				
                <tr>
                    <td class="tu-left-td">是否启用：</td>
                    <td class="tu-right-td">
                        <label>
                            <input type="radio" <?php if($detail['status'] == 0) echo "checked='checked'";?> name="status" value="0"  />不启用
                        </label>
                        <label>
                            <input type="radio" <?php if($detail['status'] == 1) echo "checked='checked'";?> name="status" value="1"  />启用
                        </label>
                    </td>
                </tr>

            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认编辑" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>