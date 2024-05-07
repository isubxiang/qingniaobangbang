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
        <li class="li1">功能</li>
        <li class="li2">广告</li>
        <li class="li2 li3">新增</li>
    </ul>
</div>
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
        <form target="x-frame" action="<?php echo U('ad/edit',array('ad_id'=>$detail['ad_id']));?>" method="post">
                    <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
                <tr>
                    <td class="tu-left-td">所属广告位：</td>
                    <td  class="tu-right-td">
                        <select name="data[site_id]" class="tu-manage-select">
                              <?php if(is_array($sites)): foreach($sites as $key=>$var): ?><option value="<?php echo ($var["site_id"]); ?>" <?php if(($var["site_id"]) == $detail["site_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["site_name"]); ?>（<?php echo ($types[$var['site_type']]); ?>）</option><?php endforeach; endif; ?>
                        </select>

                    </td>
                </tr>
                
              <tr>
                <td class="tu-left-td">选择学校：</td>
                <td class="tu-right-td">
                    <div class="lt">
                        <input type="hidden" id="school_id" name="data[school_id]" value="<?php echo (($detail["school_id"])?($detail["school_id"]):''); ?>" class="tudou-manageInput"/>
                        <input class="tudou-sc-add-text-name sj" type="text" name="Name" id="Name"  value="<?php echo ($school["Name"]); ?>" />
                    </div>
                    <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="seleSj">选择学校</a>
                </td>
            </tr> 
            
        	<tr>
                    <td class="tu-left-td">广告名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[title]" value="<?php echo (($detail["title"])?($detail["title"]):''); ?>" class="tudou-manageInput" />

                    </td>
                </tr><tr>
                    <td class="tu-left-td">链接地址：</td>
                    <td class="tu-right-td"><input type="text" name="data[link_url]" value="<?php echo (($detail["link_url"])?($detail["link_url"]):''); ?>" class="tudou-manageInput" />

                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td"> 广告图片：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height: 100px; float: left;">
                        <input type="hidden" name="data[photo]" value="<?php echo ($detail["photo"]); ?>" id="data_photo" />
                        <div id="fileToUpload" >上传广告图片</div>
                    </div>
                    <div style="width: 300px;height: 100px; float: left;">
                        <img id="photo_img" width="80" height="80"  src="<?php echo config_img($detail['photo']);?>" />
                    </div>
                    <script>                                                                  
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
						compress : {quality: 100}                                         
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
            <td class="tu-left-td">代码广告：</td>
            <td class="tu-right-td"><textarea  name="data[code]" cols="50" rows="10" ><?php echo (($detail["code"])?($detail["code"]):''); ?></textarea>

            </td>
        </tr>
        
        <tr>
            <td class="tu-left-td">开始时间：</td>
            <td class="tu-right-td"><input type="text" name="data[bg_date]" value="<?php echo (($detail["bg_date"])?($detail["bg_date"]):''); ?>" onfocus="WdatePicker();"  class="inputData" />

            </td>
        </tr><tr>
            <td class="tu-left-td">结束时间：</td>
            <td class="tu-right-td"><input type="text" name="data[end_date]" value="<?php echo (($detail["end_date"])?($detail["end_date"]):''); ?>" onfocus="WdatePicker();"  class="inputData" />

            </td>
        </tr>
        <tr>
          <td class="tu-left-td">是否新窗口打开</td>
          <td class="tu-right-td">
             <input type="radio" name="data[is_target]" value="0" <?php if($detail[is_target] == 0): ?>checked="checked"<?php endif; ?> />当前窗口
			 <input type="radio" name="data[is_target]" value="1" <?php if($detail[is_target] == 1): ?>checked="checked"<?php endif; ?> />新窗口
             <code>开启后再新窗口打开，默认当前窗口打开哦</code>
          </td>
      </tr>
      
      
				<style>
                    .profit{text-align: center;color: #333;font-weight: bold; background: #F5F5FB;}
                </style>
                    
                 <tr>
                    <td class="tu-right-td profit" colspan="2">小程序广告配置</td>
                 </tr>
     
      
      
     			 <tr>
					  <td class="tu-left-td">是否小程序</td>
					  <td class="tu-right-td">
                         <input type="radio" name="data[is_wxapp]" value="0" <?php if($detail[is_wxapp] == 0): ?>checked="checked"<?php endif; ?>/>否
			 			 <input type="radio" name="data[is_wxapp]" value="1" <?php if($detail[is_wxapp] == 1): ?>checked="checked"<?php endif; ?>/>是
						 <code>如果是小程序则配置下面的选项</code>
					  </td>
				  </tr>
				  
				  
				   <tr>
					  <td class="tu-left-td">选择跳转路径：</td>
					  <td class="tu-right-td">
						   <select name="data[state]" class="admin-sele-Fl  jq_type" style="display: inline-block;">
							   <option <?php if(($detail[state]) == "1"): ?>selected="selected"<?php endif; ?>  value="1">内部网页跳转</option>
							   <option <?php if(($detail[state]) == "2"): ?>selected="selected"<?php endif; ?>  value="2">外部网页跳转</option>
							   <option <?php if(($detail[state]) == "3"): ?>selected="selected"<?php endif; ?>  value="3">关联小程序跳转</option>
						   </select>
						  <code>这里必须选择</code>
					   </td>
				   </tr>
				   <script>
					   $(document).ready(function (){
						 $(".jq_type").change(function (){
							if($(this).val() == 1){
								 $(".jq_type_1").show();
								 $(".jq_type_2").hide();
								 $(".jq_type_3").hide();
							 }else if($(this).val() == 2){
								 $(".jq_type_1").hide();
								 $(".jq_type_2").show();
								 $(".jq_type_3").hide();;
							  }else{
								 $(".jq_type_1").hide();
								 $(".jq_type_2").hide();
								 $(".jq_type_3").show();
							  }
						   });
						   $(".jq_type").change();
						});
					 </script>
					 <tr class="jq_type_1">
						 <td class="tu-left-td">内部链接：</td>
						 <td class="tu-right-td">
							   <input type="text" name="data[src]" value="<?php echo ($detail["src"]); ?>" class="tudou-manageInput tudou-manageInput2"/>
                                <code>分类格式列如：/pages/errand/apply/index?type=1&remark=请直接将取件短信粘贴此处。示例：【菜鸟驿站】取件码8888，这里的type就是当前分类的ID，后面的remark为描述</code><br>
                                <code>多商家格式列如：/pages/shop/_/index?type=1   后面这个1数字就是外卖分类列表的ID分别是1-8的数字不要乱写</code><br>
                                <code>单商家格式列如：/pages/shop/_/index?id=1  后面这个1数字就是外卖商家的ID，请不要写错，否则打不开</code><br>
                                <code>拼车：/pages/shun/shun?id=1 【固定格式请勿修改】</code><br>
                                <code>拼团：/pages/collage/index?id=1 【固定格式请勿修改】</code><br>
                                <code>信息：/pages/thread/index?id=1 【固定格式请勿修改，如果设置tabBar请勿填写该链接】</code><br>
                                <code>个人中心推广海报：/pages/mine/canvas/canvas?id=1 【固定格式请勿修改】</code><br>
						 </td>
					 </tr>
					
					<tr class="jq_type_2">
						 <td class="tu-left-td">外部链接：</td>
						 <td class="tu-right-td">
							   <input type="text" name="data[wb_src]" value="<?php echo ($detail["wb_src"]); ?>" class="tudou-manageInput  tudou-manageInput2"/>
								<code>
									*此链接为网页外部跳转链接，需要在小程序后台配置业务域名。
								</code>
						 </td>
					 </tr>
							
					 <tr class="jq_type_3">
						 <td class="tu-left-td">跳转小程序名称：</td>
						 <td class="tu-right-td">
							   <input type="text" name="data[xcx_name]" value="<?php echo ($detail["xcx_name"]); ?>" class="tudou-manageInput"/>
								<code>跳转的小程序名称</code>
						 </td>
					 </tr> 
					 <tr class="jq_type_3">
						 <td class="tu-left-td">小程序appid：</td>
						 <td class="tu-right-td">
							   <input type="text" name="data[appid]" value="<?php echo ($detail["appid"]); ?>" class="tudou-manageInput"/>
								<code>要跳转的小程序appid(只有同一公众号下的关联的小程序之间才可相互跳转)</code>
						 </td>
					 </tr>   
                     
                     
        
        <tr>
            <td class="tu-left-td">排序：</td>
            <td class="tu-right-td"><input type="text" name="data[orderby]" value="<?php echo (($detail["orderby"])?($detail["orderby"]):''); ?>" class="tudou-manageInput" />

            </td>
        </tr>
         
    </table>
     <div style="margin-left:140px;margin-top:20px">
             
               <td>  <input type="submit" value="确定编辑" class="sm-tudou-btn-input" /></td>
            </div> 
</form>
            <div></div>

  		</div>
	</body>
</html>