<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
	<head>
		<meta charset="utf-8">
		<title><?php echo ($CONFIG["site"]["sitename"]); ?></title>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
		<link rel="stylesheet" href="/static/default/wap/css/base.css">
        <link rel="stylesheet" href="<?php echo ($CONFIG['config']['iocnfont']); ?>">
        <link rel="stylesheet" href="<?php echo ($CONFIG['config']['iocnfont2']); ?>">
        <link rel="stylesheet" href="/static/default/wap/css/<?php echo ($ctl); ?>.css?v=<?php echo ($today); ?>"/>
        <script src="/static/default/wap/js/jquery.js?version=<?php echo ($version); ?>"></script>
		<script src="/static/default/wap/other/layer.js?version=<?php echo ($version); ?>"></script>
    </head>
<body>


        
      


	     
	
    
	<div class="container">
		<div class="blank-100"></div>
		<?php if($message != null): ?><div class="alert alert-green">
			<p><?php echo($message); ?></p>
			<a class="button bg-green" href="<?php echo ($jumpUrl); ?>">知道啦~点击左上角按钮返回</a>
		</div>
		<?php else: ?>
		<div class="alert alert-red">
			 <p><?php echo($error); ?></p>
			<a class="button bg-red" href="<?php echo ($jumpUrl); ?>">知道啦~点击左上角按钮返回</a>
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