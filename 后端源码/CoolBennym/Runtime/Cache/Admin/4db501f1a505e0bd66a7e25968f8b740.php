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
        <li class="li2">管理员设置</li>
        <li class="li2 li3">编辑管理员</li>
    </ul>
</div>

<div class="listBox clfx" style="width:800px;">
    <div class="menuManage">
        <form target="x-frame" action="<?php echo U('admin/edit',array('admin_id'=>$detail['admin_id']));?>" method="post">
            <div class="main-tudou-sc-add">
                <div class="tu-table-box">
                    <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;">

                        <tr>
                            <td class="tu-left-td">用户名：</td>
                            <td class="tu-right-td"><?php echo (($detail["username"])?($detail["username"]):''); ?>
                            </td>
                        </tr>
                        
                         <tr>
                            <td class="tu-left-td">密码：</td>
                            <td class="tu-right-td"><input type="password" name="data[password]" value="******" class="tudou-sc-add-text-name w300"/>
							<code>后台登录密码，建议越复杂越好</code>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="tu-left-td">绑定会员账户：</td>
                            <td class="tu-right-td">
                                <div class="lt">
                                    <input type="hidden" id="user_id" name="data[user_id]" value="<?php echo (($detail["user_id"])?($detail["user_id"]):''); ?>" class="tudou-manageInput"/>
                                    <input class="tudou-sc-add-text-name sj" type="text" name="nickname" id="nickname"  value="<?php echo ($user["nickname"]); ?>" />
                                </div>
                                <a mini="select"  w="800" h="600" href="<?php echo U('user/select');?>" class="seleSj">选择绑定会员账户</a>
                            </td>
                        </tr> 
                        
                        <tr>
                            <td class="tu-left-td">管理员类型：</td>
                            <td class="tu-right-td">
                                <select name="data[type]" class="seleFl w150" id="type">
                                   <option value="0" <?php if(($detail["type"]) == "0"): ?>selected="selected"<?php endif; ?> >==请选择管理员==</option>
                                   <option value="1" <?php if(($detail["type"]) == "1"): ?>selected="selected"<?php endif; ?> >系统管理员</option>
                                   <option value="2" <?php if(($detail["type"]) == "2"): ?>selected="selected"<?php endif; ?> >分站管理员</option>
                                </select>
                                <code>管理员类型必须选择</code>
                            </td>
                        </tr>
                        
                        
                        
                        
                        
                        
                        
                        </table>
                        
                    <table class="school" style="display:none;" bordercolor="#F00"  cellspacing="0" width="100%" style="border-collapse:collapse; margin:0px; vertical-align:middle; background-color:#FFF; color:#F00">
                        <tr>
                            <td class="tu-left-td">选择学校：</td>
                            <td class="tu-right-td">
                               <select id="school_id" name="data[school_id]" class="seleFl w210">
                               <option value="0">请选择学校...</option>
                                <?php if(is_array($schools)): foreach($schools as $key=>$var): ?><option value="<?php echo ($var["school_id"]); ?>"  <?php if(($var["school_id"]) == $detail["school_id"]): ?>selected="selected"<?php endif; ?> ><?php echo ($var["Name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                                <code>学校必须选择否则报错</code>
                            </td>
                          </tr>  
                
                    </table>
                        
                        
                        
                     <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;">
                        <tr>
                            <td class="tu-left-td">管理员角色：</td>
                            <td class="tu-right-td">
                                <select name="data[role_id]" class="seleFl w150" id="role_id">
                                <option value="0">==请选择角色==</option> 
                                </select>
                                <code>必须选择正确的角色</code>
                            </td>
                        </tr>
                      </table>  
                        
                        
                     <table  bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;">
                        <tr>
                            <td class="tu-left-td">手机：</td>
                            <td class="tu-right-td"><input type="text" name="data[mobile]" value="<?php echo (($detail["mobile"])?($detail["mobile"]):''); ?>" class="tudou-sc-add-text-name w150" />
                                <code>手机不能为空</code>
                            </td>
                        </tr>
                    </table>


                    </table>
                </div>
                <div class="sm-qr-tu"><input type="submit" value="确定编辑" class="sm-tudou-btn-input" /></div>
            </div>
        </form>
    </div>
</div>



<script>
	$(document).ready(function(e){
		$("#school_id").change(function(){
			var type = $('#type').val();
			if(type == '2'){
				$('.school').css('display','block'); 
			}else{
				$('.school').css('display','none'); 
			}
			var school_id = $('#school_id').val();
			_getRoleIdHtml(type,school_id);
		});

		var school_id =$('#school_id').val();
		var type =$('#type').val();
		_getRoleIdHtml(type,school_id);
	
		$("#type").change(function(){
			var type = $(this).val();
			if(type == '2'){
				$('.school').css('display','block'); 
			}else{
				$('.school').css('display','none'); 
			}
			var school_id = $('#school_id').val();
			_getRoleIdHtml(type,school_id);
		});
	});		
	
	//获取表单
	function _getRoleIdHtml(type,school_id){
		
		if(type == '2'){
			$('.school').css('display','block'); 
		}
			
		var admin_id ="9999";
		var url = '/admin/admin/getRoles/admin_id/'+admin_id+'/type/'+type+'/school_id/'+school_id;
		$.get(url,function (data){
			$("#role_id").html(data);
		}, 'html');
	}
	
</script>
  		</div>
	</body>
</html>