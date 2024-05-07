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
        <li class="li2 li3">附件设置</li>
    </ul>
</div>
<style>
.tu-left-td {width:150px;}
</style>
<p class="attention"><span>注意：</span>这里是控制全局缩略图大小等设置的，注意格式600X600特别是中间的符号不要写错</p>
<form  target="x-frame" action="<?php echo U('setting/attachs');?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                
                
                 <tr>
                    <td class="tu-left-td">水印图片：</td>
                    <td class="tu-right-td">
                    <div style="width:300px;height:100px;float:left;">
                        <input type="hidden" name="data[water]" value="<?php echo ($CONFIG["attachs"]["water"]); ?>" id="data_photo"/>
                        <input id="photo_file" name="photo_file" type="file" multiple="true" value=""/>
                    </div>
                    <div style="width: 300px; height: 100px; float: left;">
                        <img id="photo_img" width="100" height="80"  src="<?php echo ($CONFIG["attachs"]["water"]); ?>"/>
                    </div>
                    <script type="text/javascript" src="__PUBLIC__/js/uploadify/jquery.uploadify.min.js"></script>
                    <link rel="stylesheet" href="__PUBLIC__/js/uploadify/uploadify.css">
                    <script>
						$("#photo_file").uploadify({
							'swf': '__PUBLIC__/js/uploadify/uploadify.swf?t=<?php echo ($nowtime); ?>',
							'uploader': '<?php echo U("app/upload/uploadify",array("model"=>"setting"));?>',
							'cancelImg': '__PUBLIC__/js/uploadify/uploadify-cancel.png',
							'buttonText': '设置水印图片',
							'fileTypeExts': '*.gif;*.jpg;*.png',
							'queueSizeLimit': 1,
							'onUploadSuccess': function(file,data,response){
								var res = JSON.parse(data);
								$("#data_photo").val(res.url);
								$("#photo_img").attr('src',res.url).show();
							}
						});
                   </script>
                    <code>默认不要管已不用上传</code> 
                </td>
                </tr>
                
               
                
                
                 <tr>
                    <td class="tu-left-td">WAP图片压缩质量：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[pic_wap_quality][thumb]" value="<?php echo ($CONFIG["attachs"]["pic_wap_quality"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/> 
                        <code>WAP上传压缩质量建议80</code>
                    </td>
                </tr>
                
                 <tr>
                    <td class="tu-left-td">电脑端图片压缩质量：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[pic_pc_quality][thumb]" value="<?php echo ($CONFIG["attachs"]["pic_pc_quality"]["thumb"]); ?>" class="tudou-sc-add-text-name w80" /> 
                        <code>电脑端上传压缩质量建议80</code>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">店铺LOGO：</td>
                    <td class="tu-right-td"><input type="text" name="data[shoplogo][thumb]" value="<?php echo ($CONFIG["attachs"]["shoplogo"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/><code>建议600X400</code></td>
                </tr>
               
                <tr>
                    <td class="tu-left-td">商家环境图：</td>
                    <td class="tu-right-td"><input type="text" name="data[shop_environment][thumb]" value="<?php echo ($CONFIG["attachs"]["shop_environment"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/><code>建议600X400</code></td>
                </tr>
                
                <tr>
                    <td class="tu-left-td">网站LOGO：</td>
                    <td class="tu-right-td"><input type="text" name="data[sitelogo][thumb]" value="<?php echo ($CONFIG["attachs"]["sitelogo"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/><code>建议200X150</code></td>
                </tr>
                
                
               
                <tr>
                    <td class="tu-left-td">文章缩略图：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[article][thumb]" value="<?php echo ($CONFIG["attachs"]["article"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/> <code>建议600X400</code>
                    </td>
                </tr>
               
               
              
                <tr>
                    <td class="tu-left-td">用户头像：</td>
                    <td class="tu-right-td">
                        <code>大图</code><input type="text" name="data[user][thumb][thumb]" value="<?php echo ($CONFIG["attachs"]["user"]["thumb"]["thumb"]); ?>" class="tudou-sc-add-text-name w150"/> 
                        <code>中图</code><input type="text" name="data[user][thumb][middle]" value="<?php echo ($CONFIG["attachs"]["user"]["thumb"]["middle"]); ?>" class="tudou-sc-add-text-name w150"/> 
                        <code>小图</code><input type="text" name="data[user][thumb][small]" value="<?php echo ($CONFIG["attachs"]["user"]["thumb"]["small"]); ?>" class="tudou-sc-add-text-name w150"/> 
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">店铺环境图：</td>
                    <td class="tu-right-td"><input type="text" name="data[shopphoto][thumb]" value="<?php echo ($CONFIG["attachs"]["shopphoto"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/> <code>建议600X400</code></td>
                </tr>
              
                
                <tr>
                    <td class="tu-left-td">微信回复：</td>
                    <td class="tu-right-td"><input type="text" name="data[weixin][thumb]" value="<?php echo ($CONFIG["attachs"]["weixin"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/> <code>建议600X400</code></td>
                </tr>


                <tr>
                    <td class="tu-left-td" style="padding-right: 0px;">手机店铺轮播图：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[shopbanner][thumb]" value="<?php echo ($CONFIG["attachs"]["shopbanner"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/><code>建议600X400</code>
                    </td>
                </tr>
               <tr>
                    <td class="tu-left-td">菜单缩略图：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[eleproduct][thumb]" value="<?php echo ($CONFIG["attachs"]["eleproduct"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/> <code>建议600X400</code>
                    </td>
                </tr>
                
                 <tr>
                    <td class="tu-left-td">跑腿发图：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[running][thumb]" value="<?php echo ($CONFIG["attachs"]["running"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/><code>建议600X400</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">会员等级图标：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[rank][thumb]" value="<?php echo ($CONFIG["attachs"]["rank"]["thumb"]); ?>" class="tudou-sc-add-text-name w80"/> 
                       <code>建议600X400</code>
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