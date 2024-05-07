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


        
      


	

<style>
body {
    padding:0;
}
</style>	

	<div class="detail">
		<div class="blank-10"></div>
		<h1><?php echo ($detail["title"]); ?></h1>
		<div class="detail-read">
			<?php echo ($detail["details"]); ?> 
		</div>
	</div>