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
        <li class="li1">红包优惠券</li>
        <li class="li2">红包优惠券管理</li>
        <li class="li2 li3">新增</li>
    </ul>
</div>
<div class="main-tudou-sc-add ">

    <div class="tu-table-box">

        <form  target="x-frame" action="<?php echo U('coupon/create');?>" method="post">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
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
                    <td  class="tu-left-td">商家：</td>
                    <td class="tu-right-td"> <div class="lt">
                            <input type="hidden" id="shop_id" name="data[shop_id]" value="<?php echo (($detail["shop_id"])?($detail["shop_id"]):''); ?>"/>
                            <input type="text" id="shop_name" name="shop_name" value="<?php echo ($shop["shop_name"]); ?>" class="tudou-manageInput" />
                        </div>
                        <a mini="select"  w="1000" h="600" href="<?php echo U('shop/select');?>" class="tu-dou-btn">选择商家</a>
                    </td>
                </tr>
                
                
                <tr>
                    <td class="tu-left-td">标题：</td>
                    <td class="tu-right-td"><input type="text" name="data[title]" value="<?php echo (($detail["title"])?($detail["title"]):''); ?>" class="tudou-manageInput" />

                    </td>
                </tr>      <tr>
                    <td class="tu-left-td">缩略图：</td>
                 <td class="tu-right-td">
                    <div style="width: 300px;height: 100px; float: left;">
                        <input type="hidden" name="data[photo]" value="<?php echo ($detail["photo"]); ?>" id="data_photo" />
                        <div id="fileToUpload" >上传缩略图</div>
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
					});                                                 
					uploader.on('uploadSuccess',function(file,resporse){                             
						$("#data_photo").val(resporse.url);                             
						$("#photo_img").attr('src',resporse.url).show();                         
					});                                                
					uploader.on('uploadError',function(file){                             
						alert('上传出错');                         
					});                     
                    </script>
                </td>
            </tr>
            <tr>
                <td  class="tu-left-td">总数量：</td>
                <td class="tu-right-td"><input type="text" name="data[num]" value="<?php echo (($detail["num"])?($detail["num"]):''); ?>"   class="tudou-manageInput w80"/>
                    <code>总数量为0的时候，就不能下载了</code>
                </td>
            </tr>
             <tr>
                <td  class="tu-left-td">单人限制下载：</td>
                <td class="tu-right-td"><input type="text" name="data[limit_num]" value="<?php echo (($detail["limit_num"])?($detail["limit_num"]):''); ?>"   class="tudou-manageInput w80"/>
                    <code>0为不限制，如果填入数字代码该用户只能下载多少次</code>
                </td>
            </tr>
            <tr>
                <td  class="tu-left-td">满多少钱：</td>
                <td class="tu-right-td"><input type="text" name="data[full_price]" value="<?php echo round($detail['full_price']/100,2);?>"   class="tudou-manageInput w80"/>
                    <code>就是当前优惠劵满多钱，暂时不支持打折</code>
                </td>
            </tr>
            
            <tr>
                <td  class="tu-left-td">减多少钱：</td>
                <td class="tu-right-td"><input type="text" name="data[reduce_price]" value="<?php echo round($detail['reduce_price']/100,2);?>"   class="tudou-manageInput w80"/>
                    <code>用户付款的时候减去多少钱</code>
                </td>
            </tr>
			<tr>
                <td  class="tu-left-td">购买金额：</td>
                <td class="tu-right-td"><input type="text" name="data[money]" value="<?php echo round($detail['money']/100,2);?>"   class="tudou-manageInput w80"/>
                    <code>用户领取的时候需要花费多少钱，如果免费领取就填写0</code>
                </td>
            </tr>
            
            <tr>
            <td class="tu-left-td">过期日期：</td>
            <td class="tu-right-td"><input type="text" name="data[expire_date]" value="<?php echo (($detail["expire_date"])?($detail["expire_date"]):''); ?>" onfocus="WdatePicker();"  class="inputData"/>

            </td>
        </tr><tr>
            <td class="tu-left-td">红包优惠券描述：</td>
            <td class="tu-right-td"><textarea  name="data[intro]" cols="50" rows="10" ><?php echo (($detail["intro"])?($detail["intro"]):''); ?></textarea>

            </td>
        </tr>

    </table>
                 <div style="margin-left:140px;margin-top:20px">
                <input type="submit" value="确认添加" class="sm-tudou-btn-input" />
                <div>
	</form>
</div>
</div>
  		</div>
	</body>
</html>