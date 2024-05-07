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
.tu-left-td{ width:180px;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">微信</li>
        <li class="li2">微信O2O</li>
        <li class="li2 li3">微信配置</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>这里配置是平台微信相关的， 当然 appid 和 appsecret 也影响商户的微信相关,q 12.058.5.022</p>
</div>       
<div class="main-tudou-sc-add">
    <div class="tu-table-box">
        <form  target="x-frame" action="<?php echo U('setting/weixin');?>" method="post">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                <tr>
                    <td class="tu-left-td" width="160" >服务器地址：</td>
                    <td class="tu-right-td">
                        <?php echo ($CONFIG["site"]["host"]); ?>/weixin/index.php
                        <code>复制这里地址，不懂登录www.baidu.com查看教程</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td" >TOKEN：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[token]" value="<?php echo (($CONFIG["weixin"]["token"])?($CONFIG["weixin"]["token"]):''); ?>" class="tudou-sc-add-text-name " />
                        <code>32位的MD5值最适合！但是一般不限制！可以随意填写！这个需要配置到微信公共帐号那边</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td" >APPID：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[appid]" value="<?php echo ($CONFIG["weixin"]["appid"]); ?>" class="tudou-sc-add-text-name " />
						<code>不懂联系</code>
                    </td>
                </tr>
                <tr>
                    <td  class="tu-left-td" >APPSECRET：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[appsecret]" value="<?php echo ($CONFIG["weixin"]["appsecret"]); ?>" class="tudou-sc-add-text-name " />
                        <code>遇到一些奇怪的状态可重置，注意不要用前后空格</code>
                    </td>
                </tr>
         
                <tr>
                    <td class="tu-left-td" >关注时回复模式：</td>
                    <td class="tu-right-td">
                        <select name="data[type]" class="seleFl  jq_type" style="display: inline-block;">
                            <option <?php if(($CONFIG["weixin"]["type"]) == "1"): ?>selected="selected"<?php endif; ?> value="1">文字</option>
                            <option  <?php if(($CONFIG["weixin"]["type"]) == "2"): ?>selected="selected"<?php endif; ?> value="2">图片</option>
                        </select>
                        <code>如果是文字的话就不需要填写标题和图片了</code>
                    </td>
                </tr>
                <script>
                    $(document).ready(function(){
                        $(".jq_type").change(function (){
                            if($(this).val() == 1){
                                $(".jq_type_1").show();
                                $(".jq_type_2").hide();
                            }else{
                                $(".jq_type_2").show();
                                $(".jq_type_1").hide();
                            }
                        });
                        $(".jq_type").change();
                    });
                </script>
                <tr  class="jq_type_1">
                    <td class="tu-left-td" >回复内容：</td>
                    <td class="tu-right-td">
                        <textarea name="data[description]" cols="50" rows="5"><?php echo ($CONFIG["weixin"]["description"]); ?></textarea>
                        <code>文字不要太多，不建议超过80字</code>
                    </td>
                </tr>
                <tr class="jq_type_2">
                    <td >回复标题：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[title]" value="<?php echo ($CONFIG["weixin"]["title"]); ?>" class="tudou-sc-add-text-name " />
                        <code>微信被用户关注后，主动向用户发送一条消息的标题</code>
                    </td>
                </tr>
                <tr class="jq_type_2">
                    <td class="tu-left-td" >链接地址：</td>
                    <td class="tu-right-td">
                        <input type="text" name="data[linkurl]" value="<?php echo ($CONFIG["weixin"]["linkurl"]); ?>" class="tudou-sc-add-text-name " />
                        <code>回复的链接地址</code>
                    </td>
                </tr>
                
                  <tr class="jq_type_2">
                    <td class="tu-left-td">回复图片：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height:150px; float: left;">
                        <input type="hidden" name="data[photo]" value="<?php echo ($CONFIG["weixin"]["photo"]); ?>" id="data_photo" />
                        <div id="fileToUpload" >上传回复图片</div>
                    </div>
                    <div style="width: 300px;height:150px; float: left;">
                        <img id="photo_img" width="120" height="80"  src="<?php echo config_img($CONFIG[weixin][photo]);?>" />
                        <a href="<?php echo U('setting/attachs');?>">缩略图设置</a>
                        建议尺寸<?php echo ($CONFIG["attachs"]["weixin"]["thumb"]); ?>
                    </div>
                    <script>                                            
						var width_weixin = '<?php echo thumbSize($CONFIG[attachs][weixin][thumb],0);?>';                         
						var height_weixin = '<?php echo thumbSize($CONFIG[attachs][weixin][thumb],1);?>';                         
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"weixin"));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
						compress : {width: width_weixin,height: height_weixin,quality:100,allowMagnify: false,crop: true}                       
					});                                                 
					uploader.on('uploadSuccess',function(file,resporse){                             
						$("#data_photo").val(resporse.url);                             
						$("#photo_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on('uploadError',function(ile){                             
						alert('上传出错');                         
					});                     
                    </script>
                </td>
            </tr>
        
        
             <tr>
                <td class="tu-left-td">微信自动登录：</td>
                <td class="tu-right-td">
                <label><input type="radio" name="data[user_auto]" <?php if(($CONFIG["weixin"]["user_auto"]) == "1"): ?>checked="checked"<?php endif; ?> value="1" />开启</label>
                <label><input type="radio" name="data[user_auto]" <?php if(($CONFIG["weixin"]["user_auto"]) == "0"): ?>checked="checked"<?php endif; ?>  value="0" />不开启</label>
                <code>这里是新版微信自动登录，开通后微信访问手机版会自动注册账户，关闭后不能自动注册，一般不建议开启，开启只适合于运营分销模式！</code>
                </td>
            </tr>
                
     
       
              
                </td>
            </table>
            <div class="sm-qr-tu"><input type="submit" value="确认添加" class="sm-tudou-btn-input" /></div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>