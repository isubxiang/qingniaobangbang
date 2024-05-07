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
<div class="main-tu-js main-sc">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="right">    
                <form class="search_form" method="post" action="<?php echo U('running/schoolselect');?>">
                    <div class="seleHidden" id="seleHidden">
                    	<div class="seleK">
                        <span>关键字</span>
                        <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" />
                        <input type="submit" value="   搜索"  class="inpt-button-tudou" />
                        </div> 
                    </div> 
                </form>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
            
    <form  target="x-frame" method="post">
        <div class="tu-table-box">
            <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF;"  >
                <tr>
                    <td class="w50">ID</td>
                    <td>学校名称</td>
                    <td>区域</td>
                    <td>运费说明</td>
                    <td>最小运费</td>
                    <td>添加时间</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input type="radio" name="school_id" rel="<?php echo ($var["Name"]); ?>" value="<?php echo ($var["school_id"]); ?>" /><?php echo ($var["school_id"]); ?></td>
                        <td><?php echo ($var["Name"]); ?></td>
                        <td><?php echo ($var["Region"]); ?></td>
                        <td><?php echo ($var['FreightMoneyCaption']); ?></td>
                        <td><?php echo ($var['MinFreightMoney']); ?></td>
                         <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom:none;">
            <div class="left">
                <input style="border:1px solid #dbdbdb; width: 100px; height: 38px; line-height: 38px; text-align: center;" type="button" id="select_btn" class="tu-dou-btn" value="确定选择" />
            </div>
        </div>
    </form>
</div>
</div>

<script>
    $(document).ready(function(e){
        $("#select_btn").click(function(){
            $("input[name='school_id']").each(function(a){
                if($(this).prop("checked") == true){
                    parent.selectCallBack('school_id', 'Name', $(this).val(),$(this).attr('rel'));
                }
            });
        });

    });
</script>
  		</div>
	</body>
</html>