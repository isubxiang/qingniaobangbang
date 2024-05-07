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
			<a class="top-addr" href="<?php echo ($backurl); ?>"><i class="iconfont icon-angle-left"></i></a>
		</div>
		<div class="top-title">
			找回密码
		</div>
		<div class="top-share">
			<a href="<?php echo U('passport/register');?>">注册</a>
		</div>
	</header>


<form action="<?php echo U('passport/newpwd');?>" method="post" id="ajaxForm">

	<div class="container">
		<div class="blank-30"></div>
		<p><span class="text-dot">小提示：</span>  请输入您绑定的手机号码，点击“<strong>获取新密码</strong>”您将会受到一条验证短信，如果忘记手机号，请联系管理员电话<?php echo ($CONFIG['site']['tel']); ?>重置</p>
	</div>
	
    
    


	<div class="line padding border-bottom">
		<span class="x3 text-gray">输入手机</span>
		<span class="x5"><input type="text" name="mobile" id="mobile" class="text-input" placeholder="请输入11位手机号"></span>
		<span class="x4"><a class="button button-small bg-dot" id="jq_send" href="javascript:void(0);">获取验证码</a></span>
	</div>
	
    
	<div class="line padding border-bottom">
		<span class="x3 text-gray">验证码</span>
		<span class="x5"><input type="text" name="scode" id="scode" class="text-input" placeholder="验证码"></span>
		<span class="x4"><em class="text-small text-gray">手机收到的验证码<em></span>
	</div>

	
	<div class="line padding border-bottom">
		<span class="x3"><label>输入新密码：</label></span>
		<span class="x9"><input id='password' type="password" class="text-input" name="password" placeholder=""></span>
	</div>
	<div class="line padding border-bottom">
		<span class="x3"><label>确认新密码：</label></span>
		<span class="x9"><input id='password2'  type="password" class="text-input" name="password2" placeholder=""></span>
	</div>
 
	<div class="container">
		<div class="blank-30"></div>
		<button class="button button-big button-block bg-dot">重置密码</button>
		<div class="blank-30"></div>
	</div>
</form>


<script type="text/javascript">
 var mobile_timeout;
        var mobile_count = 100;
        var mobile_lock = 0;
		
        $(function(){
            $("#jq_send").click(function(){
                if (mobile_lock == 0){
                    mobile_lock = 1;
					
					var mobile_ = $("#country").val();
					var mobile = $("#mobile").val();
					
                    $.ajax({
                        url: '<?php echo U("passport/findsms");?>',
                        data: 'mobile=' + mobile,
                        type: 'post',
                        success: function(data){
                            if(data == 1){
                                mobile_count = 60;
                                BtnCount();
                            }else{
                                mobile_lock = 0;
                                alert(data);
                            }
                        }
                    });
                }
            });
        });
		
	BtnCount = function(){
		if(mobile_count == 0){
			$('#jq_send').html("重新发送");
			mobile_lock = 0;
			clearTimeout(mobile_timeout);
		}else{
			mobile_count--;
			$('#jq_send').html("重新发送(" + mobile_count.toString() + ")秒");
			mobile_timeout = setTimeout(BtnCount, 1000);
		}
	};
	
	 $(document).ready(function (e){
        $(document).on('click', '.yzm_code', function(){
        	$("#" + $(this).attr('rel')).attr('src', TU_ROOT + '/index.php?g=app&m=verify&a=index&mt=' + Math.random());
        });
   });



</script>       


<style>
footer {height: inherit;}
</style>
    <footer class="foot-fixed">		
    <a class="foot-item  <?php if(($ctl) == "index"): ?>active<?php endif; ?>" href="<?php echo U('index/index');?>">		
    	<span class="icon-shouye iconfont"></span>		
        	<span class="foot-label">首页</span>		
            </a>
            <a class="foot-item <?php if(($act) == "create"): ?>active<?php endif; ?>" href="<?php echo u('ele/create');?>">		
            	<span class="iconfont icon-fabu"></span>			
                <span class="foot-label">上菜</span>
            </a>	
            <a class="foot-item"  href="<?php echo U('ele/index');?>">			
                <span class="iconfont icon-waimai1"></span>			
                <span class="foot-label">菜品</span>		
            </a>	

            <a class="foot-item <?php if(($act) == "order"): ?>active<?php endif; ?>" href="<?php echo U('ele/order',array('status'=>2));?>">			
                <span class="iconfont icon-kecheng"></span>			
                <span class="foot-label">订单</span>		
            </a>	
            
            <a class="foot-item <?php if(($act) == "gears"): ?>active<?php endif; ?>" href="<?php echo U('ele/gears');?>">		
                <span class="iconfont icon-guanli"></span>			
                <span class="foot-label">设置</span>		
            </a>	
            
            </footer>	
            <script src="/static/default/wap/js/jquery.timers-1.2.js"></script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 


<script>
 $('<audio id="payMp3"><source src="/static/default/mp3/1.mp3" type="audio/mpeg"></audio>').appendTo('body');
 var isIOS = isIOS();
 
  $(document).ready(function(){
	  
	  
	 //ios播放
	 function autoPlayAudio(){
        wx.config({
            debug: false,
            appId: '',
			timestamp: '',
			nonceStr: '',
			signature: '',
            jsApiList: []
        });
		wx.ready(function() {
			var globalAudio=document.getElementById("payMp3");
			globalAudio.play();
		});
    };
	
	//选择播放
	function autoPlayMp3(status,message){
		layer.msg(message,{icon:6,time:5000});
		if(isIOS == true){
			 autoPlayAudio(); 
		 }else{
			 $('#payMp3')[0].play();
		 }
	}

	function showReminds(){
	   var shop_id = "<?php echo ($SHOP['shop_id']); ?>";
	   var type = "1";
       $.post('<?php echo U("app/api/reminds");?>',{shop_id:shop_id,type:type},function(result){
          if(result.status == '1'){
			  autoPlayMp3(result.status,result.message);
          }else if(result.status == '2'){
			 autoPlayMp3(result.status,result.message);
		  }else if(result.status == '3'){
			 autoPlayMp3(result.status,result.message);
		  }else if(result.status == '4'){
			 autoPlayMp3(result.status,result.message);
		  }else if(result.status == '5'){
			 autoPlayMp3(result.status,result.message);
		  }else if(result.status == '6'){
			 autoPlayMp3(result.status,result.message);
		  }else if(result.status == '7'){
			 autoPlayMp3(result.status,result.message);
		  }
      },'json');
	};
	$('body').everyTime('2das','B',function(){
		showReminds()//执行函数
	},5);
  })
</script>




           <iframe id="x-frame" name="x-frame" style="display:none;"></iframe>
          </body>
      </html>