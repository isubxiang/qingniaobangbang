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
        <li class="li1">频道</li>
        <li class="li2">跑腿管理</li>
        <li class="li2 li3">跑腿统计</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
  <p class="attention"><span>注意：</span>可根据下面的内容进行搜索</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('running/tongji');?>">
                    <div class="seleHidden" id="seleHidden">
                    <div class="seleK">
                     	<label>
                            <span>状态</span>
                            <select name="OrderStatus" class="select w100">
                                <option value="999">请选择</option>
                                <?php if(is_array($getOrderStatus)): foreach($getOrderStatus as $key=>$item): ?><option <?php if(($OrderStatus) == $key): ?>selected="selected"<?php endif; ?>  value="<?php echo ($key); ?>"><?php echo ($item); ?></option><?php endforeach; endif; ?>
                            </select>
                        </label>
                        
                        <label>
                            <input type="submit" value="搜索"  class="inpt-button-tudou" />
                        </label>
                        
                        
                        </div> 
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

    
    <form  target="x-frame" method="post">
        <div class="tu-table-box">
          <script src="__PUBLIC__/js/highcharts/highcharts.js"></script>
        <script src="__PUBLIC__/js/highcharts/modules/exporting.js"></script>
        <li class="tudou-19forum-one tudou-19forum_style0 masonry-brick  w1080">
        <div class="tudou-19forum_top tudou-19forum_id1 w1080">
            <div class="tudou-19forum-div">
                <script>
                    $(function (){
                        $('#container2').highcharts({
                            title:{
                            text: '订单时间段（<?php echo ($bg_date); ?> - <?php echo ($end_date); ?>）内趋势',x: - 20},
                            subtitle:{
                            text: "<?php echo ($CONFIG['site']['sitename']); ?>",x: - 20},
                            xAxis:{
                            categories:[<?php echo ($data["day"]); ?>]},
                            yAxis:{title:{text:'单位元'},
                                plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                            },
                            series: [{
                            name: '当日订单总金额',
                            data: [<?php echo ($data['num']); ?>]
                            }]
                        });
                    });
                </script>
                <div id="container2"></div>
                </div>
            </div>
           
        </div>
        
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
                <div class="left">
                </div>
            </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>