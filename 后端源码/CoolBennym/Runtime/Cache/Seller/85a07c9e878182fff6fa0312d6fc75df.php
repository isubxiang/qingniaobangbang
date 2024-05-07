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
	<header class="top-fixed bg-yellow bg-inverse">
		<div class="top-back">
			<a class="top-addr" href="<?php echo U('index/index');?>"><i class="iconfont icon-angle-left"></i></a>
		</div>
		<div class="top-title">
			提示信息
		</div>
	</header>
	<div class="container">
		<div class="blank-100"></div>
		<?php if($message != null): ?><div class="alert alert-red">
			<p><?php echo($message); ?><span class="text-dot" id="wait-time"></span>秒后自动跳转。</p>
			<a class="button bg" href="<?php echo U('index/index');?>">返回首页</a>
			<a class="button bg-green" href="<?php echo ($jumpUrl); ?>">知道啦~</a>
            
           
            
		</div>
		<?php else: ?>
		<div class="alert alert-green">
			 <p><?php echo($error); ?> <span class="text-dot" id="wait-time"></span>秒后自动跳转。</p>
			<a class="button bg" href="<?php echo U('index/index');?>">返回首页</a>
			<a class="button bg-red" href="<?php echo ($jumpUrl); ?>">知道啦~</a>
		</div><?php endif; ?>
		<div class="blank-40"></div>
	</div>
	<script> 
		var secs = 1; //倒计时的秒数 
		var URL ; 
		function Load(url){ 
			URL =url; 
			for(var i=secs;i>=0;i--){ 
				window.setTimeout('timeUpdate(' + i + ')', (secs-i) * 1000); 
			} 
		} 
		function timeUpdate(num){ 
			$("#wait-time").html(num); 
			if(num == 0){
				window.location=URL;
			} 
		}
		Load("<?php echo($jumpUrl); ?>"); //要跳转到的页面 
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