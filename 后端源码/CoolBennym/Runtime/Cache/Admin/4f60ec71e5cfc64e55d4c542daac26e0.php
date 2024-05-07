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
        <li class="li2">区域设置</li>
        <li class="li2 li3">城市站点</li>
    </ul>
</div>
<form target="x-frame" action="<?php echo U('city/edit',array('city_id'=>$detail['city_id']));?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                   <tr>
                    <td class="tu-left-td">城市名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[name]" value="<?php echo (($detail["name"])?($detail["name"]):''); ?>" class="tudou-manageInput" />
					&nbsp;&nbsp;<code>当前城市的名称</code>
                    </td>
                </tr><tr>
                    <td class="tu-left-td">城市拼音：</td>
                    <td class="tu-right-td"><input type="text" name="data[pinyin]" value="<?php echo (($detail["pinyin"])?($detail["pinyin"]):''); ?>" class="tudou-manageInput" />
					&nbsp;&nbsp;<code>不要用大写字母，这里是全部小写</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">管理账户：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="user_id" name="data[user_id]" value="<?php echo (($detail["user_id"])?($detail["user_id"]):''); ?>" class="tudou-manageInput" />
                            <input class="tudou-sc-add-text-name sj" type="text" name="nickname" id="nickname"  value="<?php echo ($user["nickname"]); ?>" />
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="seleSj">选择账户</a>
                    </td>
                </tr> 
                
                 <tr>
                            <td class="tu-left-td">分成比例：</td>
                            <td class="tu-right-td"><input type="text" name="data[ratio]" value="<?php echo round($detail['ratio']/100,2);?>" class="tudou-sc-add-text-name"/>%
                            <code>预留字段，百分比费率，用于管理员提成</code>
                            </td>
                        </tr>
                
                 <tr>
                    <td class="tu-left-td">城市LOGO：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height: 100px; float: left;">
                        <input type="hidden" name="data[photo]" value="<?php echo ($detail["photo"]); ?>" id="data_photo" />
                        <div id="fileToUpload" >上传缩略图</div>
                    </div>
                    <div style="width: 300px;height: 100px; float: left;">
                        <img id="photo_img" width="120" height="80"  src="<?php echo config_img($detail['photo']);?>" />
                        <a href="<?php echo U('setting/attachs');?>">缩略图设置</a>
                        建议尺寸<?php echo ($CONFIG["attachs"]["sitelogo"]["thumb"]); ?>
                    </div>
                    <script>                                            
						var width = '<?php echo thumbSize($CONFIG[attachs][sitelogo][thumb],0);?>';                         
						var height = '<?php echo thumbSize($CONFIG[attachs][sitelogo][thumb],1);?>';                         
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"sitelogo"));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
						compress : {width:width,height:height,quality: 80,allowMagnify: false,crop: true}                       
					});                                                 
					uploader.on( 'uploadSuccess', function( file,resporse) {                             
						$("#data_photo").val(resporse.url);                             
						$("#photo_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function( file ) {                             
						alert('上传出错');                         
					});                     
                    </script>
                </td>
            </tr>
                
                
                <tr>
                    <td class="tu-left-td">城市模板风格：</td>
                    <td class="tu-right-td">
                        <select name="data[theme]" class="select">
                            <option value="">请选择</option>
                            <?php if(is_array($themes)): foreach($themes as $key=>$item): ?><option <?php if(($detail["theme"]) == $item["theme"]): ?>selected="selected"<?php endif; ?> value="<?php echo ($item["theme"]); ?>"><?php echo ($item["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        &nbsp;&nbsp;<code>一般不用管</code>
                    </td>
                </tr>
                 <tr>
                    <td class="tu-left-td">首字母：</td>
                    <td class="tu-right-td"><input type="text" name="data[first_letter]" value="<?php echo (($detail["first_letter"])?($detail["first_letter"]):''); ?>" class="tudou-manageInput" />
                        <code>大写字母</code>
                    </td>
                </tr>
                  <tr>
                    <td class="tu-left-td">城市中心坐标：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            经度<input type="text" name="data[lng]" id="data_lng" value="<?php echo (($detail["lng"])?($detail["lng"]):''); ?>" class="tudou-sc-add-text-name w200" />
                            纬度 <input type="text" name="data[lat]" id="data_lat" value="<?php echo (($detail["lat"])?($detail["lat"]):''); ?>" class="tudou-sc-add-text-name w200" />
                        </div>
                        <a style="margin-left: 10px;" mini="select"  w="600" h="600" href="<?php echo U('public/maps');?>" class="seleSj">百度地图</a>
                        
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">排序：</td>
                    <td class="tu-right-td"><input type="text" name="data[orderby]" value="<?php echo (($detail["orderby"])?($detail["orderby"]):''); ?>" class="tudou-manageInput" />
                    &nbsp;&nbsp;<code>城市排序</code>

                    </td>
                </tr>
                 <tr>
                    <td class="tu-left-td">是否启用：</td>
                    <td class="tu-right-td">
                        <label>
                            <input type="radio" <?php if($detail['is_open'] == 0) echo "checked='checked'";?> name="data[is_open]" value="0"  />
                                   不启用
                        </label>
                        <label>
                            <input type="radio" <?php if($detail['is_open'] == 1) echo "checked='checked'";?> name="data[is_open]" value="1"  />
                                   启用
                        </label>
                        &nbsp;&nbsp;<code>选择不启用后相当于关闭当前站点</code>
                    </td>
                </tr>
                                                 <tr>
                    <td class="tu-left-td">启用二级域名：</td>
                    <td class="tu-right-td">
                        <label>
                            <input type="radio" <?php if($detail['domain'] == 0) echo "checked='checked'";?> name="data[domain]" value="0"  />
                                   不启用
                        </label>
                        <label>
                            <input type="radio" <?php if($detail['domain'] == 1) echo "checked='checked'";?> name="data[domain]" value="1"  />
                                   启用
                        </label>
                        &nbsp;&nbsp;<code>这里需要设置域名泛解析，在您的VPS主机上已需要对域名解析，虚拟主机不要启用，一般建议不启用</code>
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