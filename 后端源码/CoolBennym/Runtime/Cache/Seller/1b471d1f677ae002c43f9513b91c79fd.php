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
<style>
.input {font-size: 16px;}
</style>
	<header class="top-fixed bg-yellow bg-inverse">
		<div class="top-back">
			<a class="top-addr" href="<?php echo U('index/index');?>"><i class="iconfont icon-angle-left"></i></a>
		</div>
		<div class="top-title">
			用户注册
		</div>
		<div class="top-share">
			<a href="<?php echo U('passport/login');?>">登录</a>
		</div>
	</header>

		<form class="reg-form" id="ajaxForm" action="<?php echo U('passport/register');?>" method="post">
        <input type="hidden" name="backurl" value="<?php echo ($backurl); ?>">
           
           
            
        
            
            
			<div class="line padding border-bottom">
				<span class="x3"><label>手机号码：</label></span>
				<span class="x9"><input id="mobile" type="text" class="text-input" name="account" placeholder="请输入手机号码"></span>
			</div>
           

			<div class="line padding border-bottom">
				<span class="x3"><label>短信验证：</label></span>
				<span class="x6"><input id='yzm' type="text" class="text-input" name="scode" placeholder="请输入短信验证码"></span>
				<span class="x3"><button id="m_zcyz" type="button" class="button button-little bg-dot m_zcyz">获取验证码</button></span>
			</div>

			<div class="line padding border-bottom">
				<span class="x3"><label>输入密码：</label></span>
				<span class="x9"><input id='password' type="password" class="text-input" name="data[password]" placeholder="请输入<?php echo ($CONFIG['register']['register_password']); ?>-20位密码"></span>
			</div>
            
            
			<div class="blank-20"></div>
			<div class="container">
				<button type="submit" class="button button-block button-big bg-dot">提交并注册</button>
			</div>

		</form>

	<?php $time = time(); $info = $CONFIG['register']['register_service_info'] ? $CONFIG['register']['register_service_info'] : '网站未填写注册协议'; ?>
    

    <script>
        var mobile_timeout;
        var mobile_count = 100;
        var mobile_lock = 0;
		var dxapi = "<?php echo ($CONFIG[sms][dxapi]); ?>";
		
        $(function (){
			var time = "<?php echo ($time); ?>";
			$('.yzm_code').click(function(){
				var l = "__ROOT__/index.php?g=app&m=verify&a=index&mt=";
				time = time + 1;
				$('#tu_img_code').attr('src',l+time);
			})
		
            $("#m_zcyz").click(function(){
                if(mobile_lock == 0){
                    mobile_lock = 1;
					
					var mobile = $("#mobile").val();
				
					
                    $.ajax({
                        url: '<?php echo U("passport/sendsms");?>',
						data: 'mobile=' + mobile + '&sms_yzm=' + $("#sms_yzm").val(),
                        type: 'post',
                        success: function(data){
                            if(data == 1){
                                mobile_count = 60;
                                $(".m_zcyz").addClass("on");
                                $('#m_zcyz').attr("disabled","disabled");
                                BtnCount();
                            }else{
                                mobile_lock = 0;
                                layer.msg('发送短信失败:'+data)
                            }
                        }
                    });
                }
            });
        });

        BtnCount = function(){
            if(mobile_count == 0){
                $(".m_zcyz").removeClass("on");
                $('#m_zcyz').removeAttr("disabled");
                $('#m_zcyz').html("重新发送");
                mobile_lock = 0;
                clearTimeout(mobile_timeout);
            }else{
                mobile_count--;
                $('#m_zcyz').html("获取(" + mobile_count.toString() + ")秒");
                mobile_timeout = setTimeout(BtnCount, 1000);
            }
        };
		
	
			
			
    </script>