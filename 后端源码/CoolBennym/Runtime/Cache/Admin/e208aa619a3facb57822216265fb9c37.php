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
        <li class="li1">商家</li>
        <li class="li2">商家管理</li>
        <li class="li2 li3">编辑商家</li>
    </ul>
</div>
<p class="attention"><span>说明：</span> 请注意显示商家万能代码的时候建议多审核下</p>

<form  target="x-frame" action="<?php echo U('shop/edit',array('shop_id'=>$detail['shop_id']));?>" method="post">
    <div class="main-tudou-sc-add">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            
            	
            	<tr>
                    <td class="tu-left-td">选择学校：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="school_id" name="data[school_id]" value="<?php echo (($detail["school_id"])?($detail["school_id"]):''); ?>"/>
                            <input class="tudou-sc-add-text-name w210 sj" type="text" name="Name" id="Name"  value="<?php echo ($school["Name"]); ?>"/>
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('running/schoolselect');?>" class="seleSj">选择学校</a>
                        <code>中途不要选择学校，请注意编辑</code>
                    </td>
                </tr> 
                
                <tr>
                    <td class="tu-left-td">管理者：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="user_id" name="data[user_id]" value="<?php echo (($detail["user_id"])?($detail["user_id"]):''); ?>" />
                            <input class="tudou-sc-add-text-name w210 sj" type="text" name="nickname" id="nickname"  value="<?php echo ($user["nickname"]); ?>" />
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="seleSj">选择用户</a>
                        <code>注意：中途更换会员商户资金并不会转移，请手动修改商家管理员的商户资金，当然我们不建议亲中途更换会员ID，请见谅！</code>
                    </td>
                </tr>    <tr>

                    <td class="tu-left-td">分类：</td>
                    <td class="tu-right-td">
                        <select id="cate_id" name="data[cate_id]" class="seleFl w210">
                            <?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option>                
                                <?php if(is_array($cates)): foreach($cates as $key=>$var2): if(($var2["parent_id"]) == $var["cate_id"]): ?><option value="<?php echo ($var2["cate_id"]); ?>"  <?php if(($var2["cate_id"]) == $detail["cate_id"]): ?>selected="selected"<?php endif; ?> > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($var2["cate_name"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
                        </select>
                    </td>
                </tr>   
                
                <tr>
                    <td class="tu-left-td">商家等级：</td>
                    <td class="tu-right-td">
                       <select id="grade_id" name="data[grade_id]" class="seleFl w210">
                       <option value="0">请选择...</option>
                        <?php if(is_array($grades)): foreach($grades as $key=>$var): ?><option value="<?php echo ($var["grade_id"]); ?>"  <?php if(($var["grade_id"]) == $detail["grade_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["grade_name"]); ?></option><?php endforeach; endif; ?>
                        </select>
                        <code>商家等级必须选择，如果没有等级显示去》》》商家》》》商家等级管理》》》添加等级》》》添加后才来添加商家</code>
                    </td>
                </tr>   
                 
                   <tr>
                    <td class="tu-left-td">所在区域：</td>
                    <td class="tu-right-td">
                        
                        <select name="data[city_id]" id="city_id" style="float: left;" class="seleFl w210"></select>
                        <select name="data[area_id]" id="area_id" style="float: left;"  class="seleFl w210"></select>
						<select name="data[business_id]" id="business_id" style="float: left;"  class="seleFl w210"></select>
                    </td>
                </tr>
               
                <script src="<?php echo U('app/datas/onecity',array('name'=>'cityareas'));?>"></script> 
                       <script>
								var city_id = <?php echo (int)$detail['city_id'];?>;
								var area_id = <?php echo (int)$detail['area_id'];?>;
								var business_id = <?php echo (int)$detail['business_id'];?>;
                                $(document).ready(function () {
                                    var city_str = ' <option value="0">请选择...</option>';
                                    for (a in cityareas.city) {
                                        if (city_id == cityareas.city[a].city_id) {
                                            city_str += '<option selected="selected" value="' + cityareas.city[a].city_id + '">' + cityareas.city[a].name + '</option>';
                                        } else {
                                            city_str += '<option value="' + cityareas.city[a].city_id + '">' + cityareas.city[a].name + '</option>';
                                        }
                                    }
                                    $("#city_id").html(city_str);
                                    $("#city_id").change(function () {
                                        if ($("#city_id").val() > 0) {
                                            city_id = $("#city_id").val();
     										   $.ajax({
													  type: 'POST',
													  url: "<?php echo U('app/datas/twoarea');?>",
													  data:{cid:city_id},
													  dataType: 'json',
													  success: function(result){
                                                         var area_str = ' <option value="0">请选择...</option>';
                                                        for (a in result) {
                                                          area_str += '<option value="' + result[a].area_id + '">' + result[a].area_name + '</option>';                                                        }
                                                       $("#area_id").html(area_str);
                                                        $("#business_id").html('<option value="0">请选择...</option>');
													  }
												});
                                            $("#area_id").html(area_str);
                                            $("#business_id").html('<option value="0">请选择...</option>');
                                        } else {
                                            $("#area_id").html('<option value="0">请选择...</option>');
                                            $("#business_id").html('<option value="0">请选择...</option>');
                                        }
                                    });
                                    if (city_id > 0) {
                                        var area_str = ' <option value="0">请选择...</option>';
										$.ajax({
										  type: 'POST',
										  url: "<?php echo U('app/datas/twoarea');?>",
										  data:{cid:city_id},
										  dataType: 'json',
										  success: function(result){
                                             for (a in result) {
                                                if (area_id == result[a].area_id) {
                                                    area_str += '<option selected="selected" value="' + result[a].area_id + '">' + result[a].area_name + '</option>';
                                                } else {
                                                    area_str += '<option value="' + result[a].area_id + '">' + result[a].area_name + '</option>';
                                                }
                                              }
                                             $("#area_id").html(area_str);
											}
										});
                                    }
                                    $("#area_id").change(function () {
                                        if ($("#area_id").val() > 0) {
                                            area_id = $("#area_id").val();
                             					$.ajax({
													  type: 'POST',
													  url: "<?php echo U('app/datas/tbusiness');?>",
													  data:{bid:area_id},
													  dataType: 'json',
													  success: function(result) {
                                                         var business_str = ' <option value="0">请选择...</option>';
													     for (a in result) {
												   				business_str += '<option value="' + result[a].business_id + '">' + result[a].business_name + '</option>';
													     }
												     	$("#business_id").html(business_str);
													 }
											       });
                                        } else {
                                            $("#business_id").html('<option value="0">请选择...</option>');
                                        }
                                    });
                                    if (area_id > 0) {                                    
									   $.ajax({
										  type: 'POST',
										  url: "<?php echo U('app/datas/tbusiness');?>",
										  data:{bid:area_id},
										  dataType: 'json',
										  success: function(result){
											var business_str = ' <option value="0">请选择...</option>';
											for (a in result) {
													if (business_id == result[a].business_id) {
														business_str += '<option selected="selected" value="' + result[a].business_id + '">' + result[a].business_name + '</option>';
													} else {
													  business_str += '<option value="' + result[a].business_id + '">' + result[a].business_name + '</option>';
													}
											}
											 $("#business_id").html(business_str);
										  }
									   });
                                    }
                                    $("#business_id").change(function () {
                                        business_id = $(this).val();
                                    });
                                });
                </script> 

             <tr>
                    <td class="tu-left-td">商铺名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[shop_name]" value="<?php echo (($detail["shop_name"])?($detail["shop_name"]):''); ?>" class="tudou-sc-add-text-name w210" />
                    <code>商家名称，建议不超过6字</code>
                    </td>
                </tr>
               
               <tr>
                    <td class="tu-left-td">缩略图：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height: 100px; float: left;">
                        <input type="hidden" name="data[logo]" value="<?php echo ($detail["logo"]); ?>" id="data_logo" />
                        <div id="fileToUpload" >上传缩略图</div>
                    </div>
                    <div style="width: 300px;height: 100px; float: left;">
                        <img id="logo_img" width="120" height="80"  src="<?php echo config_img($detail['logo']);?>" />
                        <a href="<?php echo U('setting/attachs');?>">缩略图设置</a>
                        建议尺寸<?php echo ($CONFIG["attachs"]["shoplogo"]["thumb"]); ?>
                    </div>
                    <script>                                            
						var width_shoplogo = '<?php echo thumbSize($CONFIG[attachs][shoplogo][thumb],0);?>';                         
						var height_shoplogo = '<?php echo thumbSize($CONFIG[attachs][shoplogo][thumb],1);?>';                         
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"shoplogo"));?>',                             
						pick: '#fileToUpload',                             
						resize: true,  
						compress : {width: width_shoplogo,height: height_shoplogo,quality:100,allowMagnify: false,crop: true}                       
					});                                                 
					uploader.on( 'uploadSuccess', function(file,resporse){                             
						$("#data_logo").val(resporse.url);                             
						$("#logo_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function(file){                             
						alert('上传出错');                         
					});                     
                    </script>
                </td>
            </tr> 
                
            
            
             <tr>
                    <td class="tu-left-td">店铺缩略图：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height: 100px; float: left;">
                        <input type="hidden" name="data[photo]" value="<?php echo ($detail["photo"]); ?>" id="data_photo" />
                        <div id="fileToUpload1" >上传缩略图</div>
                    </div>
                    <div style="width: 300px;height: 100px; float: left;">
                        <img id="photo_img" width="120" height="80"  src="<?php echo config_img($detail['photo']);?>" />
                        <a href="<?php echo U('setting/attachs');?>">缩略图设置</a>
                        建议尺寸<?php echo ($CONFIG["attachs"]["shopphoto"]["thumb"]); ?>
                    </div>
                    <script>                                            
						var width_shopphoto = '<?php echo thumbSize($CONFIG[attachs][shoplogo][thumb],0);?>';                         
						var height_shopphoto = '<?php echo thumbSize($CONFIG[attachs][shoplogo][thumb],1);?>';                         
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>"shoplogo"));?>',                             
						pick: '#fileToUpload1',                             
						resize: true,  
						compress : {width: width_shopphoto,height: height_shopphoto,quality:100,allowMagnify: false,crop: true}                       
					});                                                 
					uploader.on( 'uploadSuccess', function(file,resporse){                             
						$("#data_photo").val(resporse.url);                             
						$("#photo_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on( 'uploadError', function(file){                             
						alert('上传出错');                         
					});                     
                    </script>
                </td>
            </tr>
            
            
            <tr>
            <td class="tu-left-td">店铺地址：</td>
            <td class="tu-right-td">
                <input type="text" name="data[addr]" value="<?php echo (($detail["addr"])?($detail["addr"]):''); ?>" class="tudou-sc-add-text-name w210" />
            </td>
        </tr><tr>
            <td class="tu-left-td">店铺电话：</td>
            <td class="tu-right-td"><input type="text" name="data[tel]" value="<?php echo (($detail["tel"])?($detail["tel"]):''); ?>" class="tudou-sc-add-text-name w210" />
            </td>
        </tr>
        <tr>
            <td class="tu-left-td">联系人：</td>
            <td class="tu-right-td">
                <input type="text" name="data[contact]" value="<?php echo (($detail["contact"])?($detail["contact"]):''); ?>" class="tudou-sc-add-text-name w210" />
            </td>
        </tr>
        <tr>
            <td class="tu-left-td">手机号码：</td>
            <td class="tu-right-td">
                <input type="text" name="data[mobile]" value="<?php echo (($detail["mobile"])?($detail["mobile"]):''); ?>" class="tudou-sc-add-text-name w210" />
                 <code>认真填写手机号码，部分场景用来商家接收短信使用</code>
            </td>
        </tr>
        
         
        
        <tr>
            <td class="tu-left-td">固定排名：</td>
            <td class="tu-right-td"><input type="text" name="data[orderby]" value="<?php echo (($detail["orderby"])?($detail["orderby"]):'100'); ?>" class="tudou-sc-add-text-name w210" />
                <code>1就是固定排名在第一位，一般建议不需要设置这个值！</code>
            </td>
        </tr>
        
        

        <tr>

            <td class="tu-left-td">商家坐标：</td>
            <td class="tu-right-td">
                <div class="lt">
                    经度<input type="text" name="data[lng]" id="data_lng" value="<?php echo (($detail["lng"])?($detail["lng"]):''); ?>" class="tudou-sc-add-text-name w210 w100" />
                    纬度 <input type="text" name="data[lat]" id="data_lat" value="<?php echo (($detail["lat"])?($detail["lat"]):''); ?>" class="tudou-sc-add-text-name w210 w100" />
                </div>
                <a style="margin-left: 10px;" mini="select"  w="600" h="600" href="<?php echo U('public/maps',array('lat'=>$detail['lat'],'lng'=>$detail['lng']));?>" class="seleSj">百度地图</a>

        </tr>

			

        

        <tr>
            <td class="tu-left-td">用户id(partner)</td>
            <td class="tu-right-td"><input type="text" name="data[partner]" value="<?php echo ($detail["partner"]); ?>" class="tudou-sc-add-text-name w210" />
            <code>易连云后台【yilianyun.10ss.net】》》》系统集成》》》你的api信息》》》用户id</code>
            </td>
        </tr>
        <tr>
            <td class="tu-left-td">打印标识(apiKey)</td>
            <td class="tu-right-td"><input type="text" name="data[apiKey]" value="<?php echo ($detail["apiKey"]); ?>" class="tudou-sc-add-text-name w210" />
            <code>易连云后台【yilianyun.10ss.net】》》》系统集成》》》你的api信息》》》API 密钥</code>
            </td>
        </tr>

		<tr>
            <td class="tu-left-td">密钥(mKey)</td>
            <td class="tu-right-td"><input type="text" name="data[mKey]" value="<?php echo ($detail["mKey"]); ?>" class="tudou-sc-add-text-name w210" />
            <code>易连云后台【yilianyun.10ss.net】》》》终端管理》》》控制台》》》密室》》》  12位数字谜+数组组合，在购买的打印机后台可看到</code>
            </td>

        </tr>
		
		<tr>
            <td class="tu-left-td">打印机终端号(machine_code)</td>
            <td class="tu-right-td"><input type="text" name="data[machine_code]" value="<?php echo ($detail["machine_code"]); ?>" class="tudou-sc-add-text-name w210" />
            <code>易连云后台【yilianyun.10ss.net】》》》终端管理》》》终端号》》》复制过来，或者购买打印机后面有这个，这是10位数的数字</code>
            </td>
        </tr>
        
        
 
        
          
          <tr>
              <td class="tu-left-td">外卖打印</td>
              <td class="tu-right-td">
                 <input type="radio" name="data[is_ele_print]" value="1" <?php if($detail[is_ele_print] == 1): ?>checked="checked"<?php endif; ?> />开启
				<input type="radio" name="data[is_ele_print]" value="0" <?php if($detail[is_ele_print] == 0): ?>checked="checked"<?php endif; ?> />关闭
                <code>开启状态才能打印外卖订单</code>
               </td>
          </tr>
          
          
     
                
        <tr>
            <td class="tu-left-td">有效期截止：</td>
            <td class="tu-right-td"><input type="text" name="data[end_date]" value="<?php echo (($detailp['end_date'])?($detailp['end_date']):""); ?>" onfocus="WdatePicker();"  class="inputData" />
            </td>
        </tr>
        
 		
        
             <tr><td class="tu-left-td">商家介绍：</td><td class="tu-right-td">
                  <script type="text/plain" id="details" name="details" style="width:800px;height:360px;"><?php echo ($ex["details"]); ?></script>
					</td>
             </tr><link rel="stylesheet" href="__PUBLIC__/qiniu_ueditor/themes/default/css/ueditor.min.css" type="text/css">
                  <script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.config.js"></script>
                  <script type="text/javascript" charset="utf-8" src="__PUBLIC__/qiniu_ueditor/ueditor.all.min.js"></script>
                  <script type="text/javascript" src="__PUBLIC__/qiniu_ueditor/lang/zh-cn/zh-cn.js"></script>
				  <script>
                        um = UE.getEditor('details',{
                            lang: 'zh-cn',
                            	toolbars:[['fullscreen','source','link','undo','redo','bold','italic','underline','fontborder','backcolor','fontsize']],  
                            });
                </script> 
        

    </table>

</div>
<div class="sm-qr-tu"><input type="submit" value="确认编辑" class="sm-tudou-btn-input" /></div>
</div>
</form>
  		</div>
	</body>
</html>