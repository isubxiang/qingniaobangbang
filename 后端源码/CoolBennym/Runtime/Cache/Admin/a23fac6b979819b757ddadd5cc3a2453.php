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
		<li class="li2">基本设置</li>
		<li class="li2 li3">站点设置</li>
	</ul>
</div>
<p class="attention">
	<span>注意：</span>这个设置和全局有关系
</p>
<form target="x-frame" action="<?php echo U('setting/site');?>" method="post">
	<div class="main-tudou-sc-add">
		<div class="tu-table-box">
			<table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px" style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#fff;">
			<tr>
				<td class="tu-left-td">
					站点名称：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[sitename]" value="<?php echo ($CONFIG["site"]["sitename"]); ?>" class="tudou-sc-add-text-name " />
					<code>注意这个不是网站的Title，一般建议是网站的品牌名</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					站点网址：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[host]" value="<?php echo ($CONFIG["site"]["host"]); ?>" class="tudou-sc-add-text-name " />
					<code>例如：http://www.baidu.com 如果你在二级目录下面就需要带上二级目录</code>
				</td>
			</tr>
            
            <tr>
				<td class="tu-left-td">
					强制开始https：
				</td>
				<td class="tu-right-td">
					<label><input type="radio" name="data[https]" <?php if(($CONFIG["site"]["https"]) == "1"): ?>checked="checked"<?php endif; ?> value="1"  />开启</label>
					<label><input type="radio" name="data[https]" <?php if(($CONFIG["site"]["https"]) == "0"): ?>checked="checked"<?php endif; ?>  value="0"  />不开启</label>
					<code>如果网站配置了https必须开启</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					站点根域名：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[hostdo]" value="<?php echo ($CONFIG["site"]["hostdo"]); ?>" class="tudou-sc-add-text-name " />
					<code>例如：baidu.com 用于分站二级域名，这里的域名一定要泛解析，否则失效！</code>
				</td>
			</tr>
            <tr>
				<td class="tu-left-td">
					域名前缀：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[host_prefix]" value="<?php echo ($CONFIG["site"]["host_prefix"]); ?>" class="tudou-sc-add-text-name " />
					<code>默认填写www,您已可以修改为bbs，O2O，其他任意字母,这里必须配置，否则访问失败，当前域名：http://<?php echo ($CONFIG["site"]["host_prefix"]); ?>.<?php echo ($CONFIG["site"]["hostdo"]); ?></code>
				</td>
			</tr>
            
            
            <tr>
                <td class="tu-left-td">默认学校：</td>
                <td class="tu-right-td">
                    <select name="data[school_id]" class="select">
                    <option value="">==必须选择默认学校==</option>
                    <?php if(is_array($schools)): foreach($schools as $key=>$item): ?><option <?php if(($CONFIG["site"]["school_id"]) == $item['school_id']): ?>selected="selected"<?php endif; ?>  value="<?php echo ($item['school_id']); ?>"><?php echo ($item['Name']); ?></option><?php endforeach; endif; ?>
                    <code>必须选择，请填写您的默认学校</code>
                </td>
            </tr>
            
            
			
			<tr>
				<td class="tu-left-td">
					网站LOGO：
				</td>
				<td class="tu-right-td">
					<div style="width: 300px;height: 100px; float: left;">
						<input type="hidden" name="data[logo]" value="<?php echo ($CONFIG["site"]["logo"]); ?>" id="data_logo" />
						<div id="fileToUpload">
							上传网站LOGO
						</div>
					</div>
					<div style="width: 300px;height: 100px; float: left;">
						<img id="logo_img" width="120" height="80" src="<?php echo config_img($CONFIG[site][logo]);?>" />
						<a href="<?php echo U('setting/attachs');?>">缩略图设置</a>
                        建议尺寸
						<?php echo ($config["attachs"]["sitelogo"]["thumb"]); ?>
					</div>
					<script>                                            
						var width_sitelogo = '<?php echo thumbSize($CONFIG[attachs][sitelogo][thumb],0);?>';                         
						var height_sitelogo = '<?php echo thumbSize($CONFIG[attachs][sitelogo][thumb],1);?>';                         
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"sitelogo"));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
						compress : {width: width_sitelogo,height: height_sitelogo,quality: 80,allowMagnify: false,crop: true}                       
					});                                                 
					uploader.on( 'uploadSuccess', function( file,resporse) {                             
						$("#data_logo").val(resporse.url);                             
						$("#logo_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function( file ) {                             
						alert('上传出错');                         
					});                     
                    </script>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					微信公众号二维码：
				</td>
				<td class="tu-right-td">
					<div style="width: 300px;height: 100px; float: left;">
						<input type="hidden" name="data[wxcode]" value="<?php echo ($CONFIG["site"]["wxcode"]); ?>" id="data_wxcode" />
						<div id="fileToUpload_wxcode">
							上传微信公众号二维码
						</div>
					</div>
					<div style="width: 300px;height: 100px; float: left;">
						<img id="wxcode_img" width="120" height="80" src="<?php echo config_img($CONFIG[site][wxcode]);?>" />
					</div>
					<script>                                                                
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"shopphoto"));?>',                             
						pick: '#fileToUpload_wxcode',                             
						resize: true,  
					});                                                 
					uploader.on( 'uploadSuccess', function( file,resporse) {                             
						$("#data_wxcode").val(resporse.url);                             
						$("#wxcode_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function( file ) {                             
						alert('上传出错');                         
					});                     
                    </script>
				</td>
			</tr>
            
            
            <tr>
				<td class="tu-left-td">
					微信小程序二维码：
				</td>
				<td class="tu-right-td">
					<div style="width: 300px;height: 100px; float: left;">
						<input type="hidden" name="data[wxappcode]" value="<?php echo ($CONFIG["site"]["wxappcode"]); ?>" id="data_wxappcode" />
						<div id="fileToUpload_wxappcode">
							上传微小程序信二维码
						</div>
					</div>
					<div style="width: 300px;height: 100px; float: left;">
						<img id="wxappcode_img" width="120" height="80" src="<?php echo config_img($CONFIG[site][wxappcode]);?>" />
					</div>
					<script>                                                                
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"shopphoto"));?>',                             
						pick: '#fileToUpload_wxappcode',                             
						resize: true,  
					});                                                 
					uploader.on( 'uploadSuccess', function( file,resporse) {                             
						$("#data_wxappcode").val(resporse.url);                             
						$("#wxappcode_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function( file ) {                             
						alert('上传出错');                         
					});                     
                    </script>
				</td>
			</tr>
            
            
			<tr>
				<td class="tu-left-td">
					客服QQ：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[qq]" value="<?php echo ($CONFIG["site"]["qq"]); ?>" class="tudou-sc-add-text-name " />
					<code>前台模板显示调用</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					电话：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[tel]" value="<?php echo ($CONFIG["site"]["tel"]); ?>" class="tudou-sc-add-text-name " />
					<code>前台模板显示调用！</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					邮件：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[email]" value="<?php echo ($CONFIG["site"]["email"]); ?>" class="tudou-sc-add-text-name " />
					<code>前台模板显示调用！</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					ICP备案：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[icp]" value="<?php echo ($CONFIG["site"]["icp"]); ?>" class="tudou-sc-add-text-name " />
					<code>前台模板显示调用！</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					站点标题：
				</td>
				<td class="tu-right-td">
					<input type="text" name="data[title]" value="<?php echo ($CONFIG["site"]["title"]); ?>" class="tudou-sc-add-text-name " />
					<code>seo设置中调用</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					站点关键字：
				</td>
				<td class="tu-right-td">
					<textarea name="data[keyword]" cols="50" rows="5"><?php echo ($CONFIG["site"]["keyword"]); ?></textarea>
					<code>seo设置中调用，建议认真填写！</code>
				</td>
			</tr>
			<tr>
				<td class="tu-left-td">
					站点描述：
				</td>
				<td class="tu-right-td">
					<textarea name="data[description]" cols="50" rows="5"><?php echo ($CONFIG["site"]["description"]); ?></textarea>
					<code>seo设置中调用</code>
				</td>
			</tr>
			
			<tr>
				<td class="tu-left-td">
					统计代码：
				</td>
				<td class="tu-right-td">
					<textarea name="data[tongji]" cols="50" rows="5"><?php echo ($CONFIG["site"]["tongji"]); ?></textarea>
					<code>模板中调用，有统计代码的填写在这里，或者直接添加模板里面。</code>
				</td>
			</tr>
            
            
           
		
	
			
			
			</table>
		</div>
		<div class="sm-qr-tu">
			<input type="submit" value="确认保存" class="sm-tudou-btn-input"/>
		</div>
	</div>
</form>
  		</div>
	</body>
</html>