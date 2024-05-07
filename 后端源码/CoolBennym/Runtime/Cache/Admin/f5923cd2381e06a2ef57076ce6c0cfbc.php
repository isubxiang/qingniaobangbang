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
        <li class="li1">微信</li>
        <li class="li2">模板消息</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>【模板库编号】填写微信后台相对应编号, 【模板ID】填写相对应微信后台你选择的模板ID,【模板状态】必须是0或1,0代表不使用,1使用</p>
</div>
<div class="main-cate">
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="border-top: 1px solid #e1e6eb;">
            <div class="left">
                <a href="javascript:createRow();" >添加微信模板</a>
            </div>
        </div>
        <style type="text/css">
             td input{height:30px;padding:3px 0 3px 3px;line-height:36px;border:solid 1px #CCCCCC;outline:none;}
            .tr{color:#555; font-size:16px; line-height:48px;height:48px;}
            input[type='submit']{margin:5px 0 0 10px;}
            iframe{display:none;}
        </style>
        <iframe id="iframe_aysc" name="iframe_aysc"></iframe>
        <div class="tu-table-box">
            <form action="<?php echo U('Weixintmpl/index');?>" method="post" target="iframe_aysc">
            <table bordercolor="#e1e6eb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF; text-align:center;">
                 <tr bgcolor="#F5F6FA" height="35px;" style="color:#333; font-size:16px; line-height:35px;">
                    <td>序号</td>
                    <td>模板标题</td>
                    <td>模板库编号</td>
                    <td>模板ID号</td>
                    <td>模板状态</td>
                    <td>排序</td>
                </tr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><tr class="tr">
                    <td><?php echo ($item['tmpl_id']); ?></td>
                        <input type="hidden" name="data[<?php echo ($key+1); ?>][tmpl_id]" value="<?php echo ($item['tmpl_id']); ?>">
                    <td><input type="text" name="data[<?php echo ($key+1); ?>][title]" value="<?php echo ($item['title']); ?>"></td>
                    <td><input type="text" name="data[<?php echo ($key+1); ?>][serial]"  value="<?php echo ($item['serial']); ?>"></td>
                    <td><input type="text" name="data[<?php echo ($key+1); ?>][template_id]" style="width:360px;" value="<?php echo ($item['template_id']); ?>"></td>
                    <td><input type="text" name="data[<?php echo ($key+1); ?>][status]" style="width:70px;text-align:center;" value="<?php echo ($item['status']); ?>"></td>
                    <td><input type="text" name="data[<?php echo ($key+1); ?>][sort]"  style="width:70px;text-align:center;" value="<?php echo ($item['sort']); ?>"></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
               
            </table>
                <input type="submit" class="sm-tudou-btn-input" value="保存全部" />
            </form>
            <?php echo ($page); ?>
        </div>
    </div>
</div>

<!--自定义模板-->
<script type="text/template" id="row">
    <td>%order%</td>
    <td><input type="text" name="data[%key%][title]"  style="width:250px;"></td>
    <td><input type="text" name="data[%key%][serial]" style="width:250px;"></td>
    <td><input type="text" name="data[%key%][template_id]"  style="width:400px;"></td>
    <td><input type="text" name="data[%key%][status]" style="width:70px;text-align:center;" value='1'></td>
    <td><input type="text" name="data[%key%][sort]"  style="width:70px;text-align:center;" value="0"></td>
    <td>
        <a href="javascript:void;" onclick="removeRow(this)">移除</a>
    </td>
</script>

<script type="text/javascript">

function createRow()
{
    with(document){
        var trNode  = createElement('tr'), 
            nodeNum = getElementsByTagName('tr').length,
            ihtml   = (getElementById('row').innerHTML).replace('%order%',nodeNum);
            ihtml   = ihtml.replace(/(%key%)/g,nodeNum);
            trNode.innerHTML = ihtml;
            trNode.setAttribute('class', 'tr');
            querySelector('table').appendChild(trNode);
    }
}    
function removeRow(o)
{
    var tr = o.parentNode.parentNode;
    document.querySelector('table').removeChild(tr);
}
</script>
  		</div>
	</body>
</html>