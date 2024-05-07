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


        
      


	

<script src="https://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>

<div class="blank-20"></div>


<div class="list-media-x" id="list-media">
	<ul>
		<div class="container">
			<div class="container text-center">
				<a class="button button-block button-big bg-dot" onclick="navigateTo('<?php echo ($src); ?>')">公众号菜单进入小程序</a>
			</div>
		</div>
        
        <div class="container">

			<div class="padding-large">
				<img src="<?php echo config_weixin_img($CONFIG['site']['wxcode']);?>" width="90%"/>
			</div>
			<p class="text-center">关注公众号</p>

		</div>
        
	</ul>
</div>

<script>

wx.config({
	debug: false,
	appId: '<?php echo ($signPackage["appId"]); ?>',
	timestamp: '<?php echo ($signPackage["timestamp"]); ?>',
	nonceStr: '<?php echo ($signPackage["nonceStr"]); ?>',
	signature: '<?php echo ($signPackage["signature"]); ?>',
	jsApiList: ['invokeMiniProgramAPI','checkJsApi','onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone']
});

function navigateTo(src){
	var src = src;
	console.log(src)
	
	if(src){
		wx.miniProgram.navigateTo({
			url:'/pages/errand/_/index',
			success: function(){
			layer.closeAll();
				console.log('success')
			},
			fail: function(){
				console.log('fail');
			},
			complete:function(){
				console.log('complete');
			}
      	});
 	}
}	

</script>