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
        <li class="li1">跑腿系统</li>
        <li class="li2">配送员管理</li>
        <li class="li2 li3">配送员列表</li>
    </ul>
</div>

<div class="main-tu-js">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin:10px 20px;">
            <div class="left">
                <?php echo BA('running/deliveryPublish',array('delivery_id'=>$list['delivery_id'],'date'=>$date),'刷新本页','','',600,360);?>
            </div>
        </div>
 </div>       
        
<form target="x-frame" action="<?php echo U('running/deliveryPublish',array('delivery_id'=>$detail['delivery_id']));?>" method="post">
    <div class="main-tudou-sc-add" style="margin-top:10px;">
        <div class="tu-table-box">
            <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;" >
            
            	<input type="hidden" name="data[delivery_id]" value="<?php echo (($detail["delivery_id"])?($detail["delivery_id"]):''); ?>" class="tudou-manageInput"/>
            	
                <tr>
                    <td class="tu-left-td">会员：</td>
                    <td class="tu-right-td">
                        <div class="lt">
                            <input type="hidden" id="user_id" name="data[user_id]" value="<?php echo (($detail["user_id"])?($detail["user_id"]):''); ?>" />
                            <input class="tudou-sc-add-text-name w210 sj" type="text" name="nickname" id="nickname"  value="<?php echo ($user["nickname"]); ?>" />
                        </div>
                        <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="seleSj">选择用户</a>
                        <code>配送员绑定的会员ID</code>
                    </td>
                </tr>   
                
                
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
                    <td class="tu-left-td">审核状态：</td>
                    <td class="tu-right-td">
                       <select id="grade_id" name="data[audit]" class="seleFl w210">
                             <option value="0"  <?php if(($detail["audit"]) == "0"): ?>selected="selected"<?php endif; ?> >未认证</option>                
                             <option value="1"  <?php if(($detail["audit"]) == "1"): ?>selected="selected"<?php endif; ?> >审核中</option>         
                             <option value="2"  <?php if(($detail["audit"]) == "2"): ?>selected="selected"<?php endif; ?> >认证成功</option> 
                             <option value="3"  <?php if(($detail["audit"]) == "3"): ?>selected="selected"<?php endif; ?> >认证失败</option>            
                       </select>
                        <code>审核状态0未认证1审核中2已认证3认证失败</code>
                    </td>
                </tr>   
                <tr>
                    <td class="tu-left-td">学生代码：</td>
                    <td class="tu-right-td"><input type="text" name="data[StudentCode]" value="<?php echo (($detail["StudentCode"])?($detail["StudentCode"]):''); ?>" class="tudou-manageInput" />
					<code>学生代码</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">配送员名称：</td>
                    <td class="tu-right-td"><input type="text" name="data[RealName]" value="<?php echo (($detail["RealName"])?($detail["RealName"]):''); ?>" class="tudou-manageInput" />
					<code>配送员名称</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">学校：</td>
                    <td class="tu-right-td"><input type="text" name="data[Major]" value="<?php echo (($detail["Major"])?($detail["Major"]):''); ?>" class="tudou-manageInput" />
					<code>学校名称</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">身份证号码：</td>
                    <td class="tu-right-td"><input type="text" name="data[IdCode]" value="<?php echo (($detail["IdCode"])?($detail["IdCode"]):''); ?>" class="tudou-manageInput" />
					<code>配送员的身份证号码</code>
                    </td>
                </tr>
                <tr>
                   <td class="tu-left-td">男女：</td>
                      <td class="tu-right-td">
                      <input type="radio" name="data[Gender]" value="1" <?php if($detail[Gender] == 1): ?>checked="checked"<?php endif; ?> />男
                      <input type="radio" name="data[Gender]" value="2" <?php if($detail[Gender] == 2): ?>checked="checked"<?php endif; ?> />女
                      <code>选择性别</code>
                      </td>
                 </tr>
                 <tr>
                    <td class="tu-left-td">入学日期：</td>
                    <td class="tu-right-td"><input type="text" name="data[EnrollmentDate]" value="<?php echo (($detail["EnrollmentDate"])?($detail["EnrollmentDate"]):''); ?>"  onfocus="WdatePicker();"  class="inputData"/>
					<code>入学日期EnrollmentDate</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">学校院系：</td>
                    <td class="tu-right-td"><input type="text" name="data[Department]" value="<?php echo (($detail["Department"])?($detail["Department"]):''); ?>" class="tudou-manageInput" />
					<code>配送员学校的院系</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">手机：</td>
                    <td class="tu-right-td"><input type="text" name="data[phoneNumber]" value="<?php echo (($detail["phoneNumber"])?($detail["phoneNumber"]):''); ?>" class="tudou-manageInput" />
					<code>配送员的手机</code>
                    </td>
                </tr>
                <tr>
                    <td class="tu-left-td">PicUrl0：</td>
                 <td class="tu-right-td">
                    <div style="width:300px;height:100px;float:left;">
                        <input type="hidden" name="data[PicUrl0]" value="<?php echo ($detail["PicUrl0"]); ?>" id="data_PicUrl0"/>
                        <div id="fileToUpload" >上传PicUrl0</div>
                    </div>
                    <div style="width:300px;height:100px; float:left;">
                        <img id="PicUrl0_img" width="120" height="80" src="<?php echo config_img($detail['PicUrl0']);?>"/>
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
						$("#data_PicUrl0").val(resporse.url);                             
						$("#PicUrl0_img").attr('src',resporse.url).show();                         
					});                                                
                    </script>
                </td>
             </tr>
              <tr>
                    <td class="tu-left-td">PicUrl1：</td>
                 <td class="tu-right-td">
                    <div style="width:300px;height:100px;float:left;">
                        <input type="hidden" name="data[PicUrl1]" value="<?php echo ($detail["PicUrl1"]); ?>" id="data_PicUrl1"/>
                        <div id="fileToUpload2" >上传PicUrl1</div>
                    </div>
                    <div style="width:300px;height:100px; float:left;">
                        <img id="PicUrl1_img" width="120" height="80" src="<?php echo config_img($detail['PicUrl1']);?>"/>
                    </div>
                    <script>                                            
						var uploader = WebUploader.create({                             
						auto: true,                             
						swf: '/static/default/webuploader/Uploader.swf',                             
						server: '<?php echo U("app/upload/uploadify",array("model"=>""));?>',                             
						pick: '#fileToUpload2',                             
						resize: true,  
					});                                                 
					uploader.on('uploadSuccess',function(file,resporse){                             
						$("#data_PicUrl1").val(resporse.url);                             
						$("#PicUrl1_img").attr('src',resporse.url).show();                         
					});                                                
                    </script>
                </td>
             </tr>
              
              
               
               
                
           
            
            </table>
        </div>
        <div class="sm-qr-tu"><input type="submit" value="确认操作" class="sm-tudou-btn-input" /></div>
    </div>
</form>
  		</div>
	</body>
</html>