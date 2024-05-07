<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<title>商家管理中心-<?php echo ($CONFIG["site"]["sitename"]); ?></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
		<link rel="stylesheet" href="/static/default/wap/css/base.css">
        <link rel="stylesheet" href="<?php echo ($CONFIG['config']['iocnfont']); ?>">
		<link rel="stylesheet" href="/static/default/wap/css/<?php echo ($ctl); ?>.css"/>
        <link rel="stylesheet" href="/static/default/wap/css/seller2.css">
		<script src="/static/default/wap/js/jquery.js"></script>
		<script src="/static/default/wap/js/base.js"></script>
		<script src="/static/default/wap/other/layer.js"></script>
        <script src="/static/default/wap/js/jquery.form.js"></script>
		<script src="/static/default/wap/other/roll.js"></script>
		<script src="/static/default/wap/js/public.js"></script>
        <script src="/static/default/wap/js/dingwei.js"></script>
      
        <style>
			.foot-fixed .active{color:<?php echo ($color); ?>!important}
			.top-fixed {background: <?php echo ($color); ?>!important;}
		</style>
	</head>
	<body>   
<link rel="stylesheet" type="text/css" href="/static/default/wap/other/webuploader.css"> 
<script src="/static/default/webuploader/webuploader.min.js"></script>
<header class="top-fixed bg-yellow bg-inverse">
	<div class="top-back">
		<a class="top-addr" href="javascript:history.back(-1);"><i class="iconfont icon-angle-left"></i></a>
	</div>
	<div class="top-title">
		商家入驻
	</div>
    
    <div class="top-signed">
		<a href="<?php echo U('passport/logout');?>"><i class="iconfont icon-tuichu2"></i></a>
	</div>
    
</header>
<style>
#login-input input.mapinputs{ width:48%; float:left; margin-right:5px;}
#login-input input.mapinputs2{ width:100%; float:left; margin:10px 0px;}
</style>


<?php if(empty($detail)): ?><form class="fabu-form" method="post" id="ajaxForm" action="<?php echo U('passport/apply');?>">

<div class="blank-10"></div>
<div class="Upload-img-box">
   <div  id="fileToUpload">上传店铺外观清晰图</div>
   <div class="Upload-img">
   <div class="list-img loading" style="display:none;"><img src=""></div>
   <div class="list-img jq_photo" style="display:none;"></div>
  </div>
</div>
    <script>
        var uploader = WebUploader.create({                 
			auto: true,                             
			swf: '/static/default/webuploader/Uploader.swf',                             
			server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
			pick: '#fileToUpload',                             
			resize: true,    
			compress : {width:800,height:800,quality:100,allowMagnify:false,crop:true}
        });
        uploader.on('uploadSuccess',function(file,resporse){
            $(".loading").hide();
            var str = '<img src="'+resporse.url+'"><input type="hidden" name="data[photo]" value="' + resporse.url + '"/>';
            $(".jq_photo").show().html(str);
        });
        uploader.on('uploadError',function(file){
            alert('上传出错');
        });

        $(document).ready(function(){
            $(document).on("click", ".photo img",function(){
                $(this).parent().remove();
            });
        });
    </script>

<div class="blank-10 bg"></div>


<div class="blank-10"></div>
<div class="Upload-img-box">
   <div  id="fileToUpload2">上传LOGO(选填)</div>
   <div class="Upload-img">
   <div class="list-img loading_2" style="display:none;"><img src=""></div>
   <div class="list-img jq_photo_2" style="display:none;"></div>
  </div>
</div>
    <script>
        var uploader = WebUploader.create({                 
			auto: true,                             
			swf: '/static/default/webuploader/Uploader.swf',                             
			server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
			pick: '#fileToUpload2',                             
			resize: true,    
			compress : {width:800,height:800,quality:100,allowMagnify: false,crop:true}
        });
        uploader.on('uploadSuccess',function(file,resporse){
            $(".loading_2").hide();
            var str = '<img src="'+resporse.url+'"><input type="hidden" name="data[logo]" value="' + resporse.url + '" />';
            $(".jq_photo_2").show().html(str);
        });
        uploader.on('uploadError',function(file){
            alert('上传出错');
        });

        $(document).ready(function(){
            $(document).on("click", ".photo img",function(){
                $(this).parent().remove();
            });
        });
    </script>

<div class="blank-10 bg"></div>



<div class="row">
	<div class="line">
		<span class="x3">商户名称</span>
		<span class="x9">
			<input type="text" class="text-input" placeholder="需跟营业执照名称一致" name="data[shop_name]"/>
		</span>
	</div>
</div>




<div class="row">
	<div class="line">
		<span class="x3">商家分类</span>
		<span class="x4">
			<select name="parent_id" id="parent_id" class="text-select">
				<option value="0" selected="selected">请选择</option>
				<?php if(is_array($cates)): foreach($cates as $key=>$var): if(($var["parent_id"]) == "0"): ?><option value="<?php echo ($var["cate_id"]); ?>"><?php echo ($var["cate_name"]); ?></option><?php endif; endforeach; endif; ?>
			</select>
		</span>
		<span class="x5">
			<select name="data[cate_id]" id="cate_id" class="text-select">
				<option value="0" selected="selected">← 选择子分类</option>
			</select>
		</span>
         <script>
			$(document).ready(function (e) {
				$("#parent_id").change(function () {
					var url = '<?php echo U("passport/shopcate",array("parent_id"=>"0000"));?>';
					if ($(this).val() > 0) {
						var url2 = url.replace('0000', $(this).val());
						$.get(url2, function (data) {
							$("#cate_id").html(data);
						}, 'html');
					}
				});
			});
		</script>
	</div>
</div>


 

<div class="row">
	<div class="line">
		<span class="x3">选择学校</span>
		<span class="x9">
			<select name="data[school_id]" id="school_id" class="text-select">
				<option value="0" selected="selected">=请选择学校=</option>
				<?php if(is_array($schools)): foreach($schools as $key=>$var): ?><option value="<?php echo ($var["school_id"]); ?>"><?php echo ($var["Name"]); ?></option><?php endforeach; endif; ?>
			</select>
		</span>
	</div>
</div>



<div class="row">
	<div class="line">
		<span class="x3">地区</span>
		<span class="x3">
			<select name="data[city_id]" id="city_id" class="text-select">
				<option value="0" selected="selected">城市</option>
			</select>
		</span>
		<span class="x3">
			<select name="data[area_id]" id="area_id" class="text-select">
				<option value="0" selected="selected">← 地区</option>
			</select>
		</span>
        
        <span class="x3">
			<select name="data[business_id]" id="business_id" class="text-select">
				<option value="0" selected="selected">← 商圈</option>
			</select>
		</span>
        
        
	</div>
  
                
<script src="<?php echo U('app/datas/onecity',array('name'=>'cityareas'));?>"></script> 
<script>
var city_id = "<?php echo ($city_id); ?>";
var area_id = "";
var business_id = "";
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
	$("#city_id").change(function(){
		if ($("#city_id").val() > 0){
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
			 for (a in result){
				if (area_id == result[a].area_id){
					area_str += '<option selected="selected" value="' + result[a].area_id + '">' + result[a].area_name + '</option>';
				}else{
					area_str += '<option value="' + result[a].area_id + '">' + result[a].area_name + '</option>';
				}
			  }
			 $("#area_id").html(area_str);
			}
		});
	}
	$("#area_id").change(function (){
		if ($("#area_id").val() > 0){
			area_id = $("#area_id").val();
				$.ajax({
					  type: 'POST',
					  url: "<?php echo U('app/datas/tbusiness');?>",
					  data:{bid:area_id},
					  dataType: 'json',
					  success: function(result) {
						 var business_str = ' <option value="0">请选择...</option>';
						 for(a in result){
								business_str += '<option value="' + result[a].business_id + '">' + result[a].business_name + '</option>';
						 }
						$("#business_id").html(business_str);
					 }
				   });
		} else {
			$("#business_id").html('<option value="0">请选择...</option>');
		}
	});
	if(area_id > 0){                                    
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
                
                
                
</div>
<div class="row">
	<div class="line">
		<span class="x3">地址</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[addr]"/>
		</span>
	</div>
</div>

<div class="row">
	<div class="line">
		<span class="x3">手机</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[tel]"  />
		</span>
	</div>
</div>
<div class="row">
	<div class="line">
		<span class="x3">联系人</span>
		<span class="x9">
			<input type="text" class="text-input" name="data[contact]" />
		</span>
	</div>
</div>





    <div class="blank-10 bg"></div>    
    <div class="row">
        <div class="line">
            <span class="x12">
                <textarea rows="5" name="details" class="text-area" placeholder="请输入店铺简短介绍，建议不超过100字"></textarea>
            </span>
        </div>
    </div>
    
    
   
    

	<div class="container">
		<div class="blank-10"></div>
		<button  type="submit" class="button button-block button-big bg-dot">确认入驻</button>
		<div class="blank-10"></div>
	</div>
</form>
<?php elseif(!empty($detail) and ($detail['audit'] == 0)): ?>
<div class="container">
		<div class="blank-10"></div>
		<button  type="submit" class="button button-block button-big bg-gray">已申请审核中</button>
        <h5 style="text-align:center">加快审核联系电话：<a href="tel:<?php echo ($CONFIG["site"]["tel"]); ?>"><?php echo ($CONFIG["site"]["tel"]); ?></a></h5>
		<div class="blank-10"></div>
	</div>
<?php elseif(!empty($detail) and ($detail['audit'] == 1)): ?>
	<div class="container">
		<div class="blank-10"></div>
		<a href="<?php echo U('seller/index/index');?>" class="button button-block button-big bg-dot text-center">点击登录商家中心</a>
		<div class="blank-10"></div>
	</div><?php endif; ?>

<div class="container">
    <div class="blank-10"></div>
    <a href="<?php echo U('passport/logout');?>" class="button button-block button-big bg-blue text-center">退出商家中心</a>
    <div class="blank-10"></div>
</div>