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
<style>
.tu-left-td { width: 200px;}
.profit{ text-align:center; color:#000; font-weight:bold; background:#ECECEC;}
</style>
<div class="tu-main-top-btn">
    <ul>
        <li class="li1">微信</li>
        <li class="li2">微信管理</li>
        <li class="li2 li3">自定义菜单</li>
    </ul>
</div>
<div class="main-cate">
    <p class="attention"><span>注意：</span>新版自定义菜单，【Title】【Appid】【Pagepath】小程序跳转值，【Key】关键字回复值，【Url】菜单连接值必须是http或者https开头	</p>
</div>  


<style>
.w380{width:380px !important;}
.tudou-sc-add-text-name, .admin-sc-add-text-name{margin-right: 5px;}
</style>

       <div class="main-tudou-sc-add">
    <div class="tu-table-box">
        <form  target="x-frame" action="<?php echo U('setting/weixinmenu');?>" method="post">
            <table bordercolor="#dbdbdb" cellspacing="0" width="100%" border="1px"  style=" border-collapse: collapse; margin:0px; vertical-align:middle; background-color:#FFF; text-align:center;">
                <tr bgcolor="#f5f5f5" height="48px;" style="color:#333; font-size:16px; line-height:48px;">
                    <td style="text-align: center;">项目</td>
                    <td>名称</td>
                    <td>类别</td>
                    <td>Key</td>
                    <td>Title</td>
                    <td>Appid</td>
                    <td>Pagepath</td>
                    <td>Url</td>
                </tr>
                <tr bgcolor="#f1f1f1" style="font-size:14px; color:#545454; text-align:left; line-height:48px;">
                
                    <td align="center">BUTTON_1</td>
                    <td  class="tu-right-td">
                        <input class="tudou-sc-add-text-name w80" name="data[button][1][name]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][1][name])?($CONFIG['weixinmenu']['button'][1][name]):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                        <select name="data[button][1][types]" class="seleFl" id="<?php echo ($CONFIG['weixinmenu']['button'][1]['types']); ?>">
                        	<option <?php if(($CONFIG['weixinmenu']['button'][1]['types']) == "0"): ?>selected="selected"<?php endif; ?>  value="0">请选择类别</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][1]['types']) == "1"): ?>selected="selected"<?php endif; ?>  value="1">URL菜单链接</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][1]['types']) == "2"): ?>selected="selected"<?php endif; ?>  value="2">关键词回复菜单</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][1]['types']) == "3"): ?>selected="selected"<?php endif; ?>  value="3">小程序跳转</option>
                        </select>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][1][key]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][1]['key'])?($CONFIG['weixinmenu']['button'][1]['key']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][1][title]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][1]['title'])?($CONFIG['weixinmenu']['button'][1]['title']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][1][appid]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][1]['appid'])?($CONFIG['weixinmenu']['button'][1]['appid']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w200" name="data[button][1][pagepath]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][1]['pagepath'])?($CONFIG['weixinmenu']['button'][1]['pagepath']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w300" name="data[button][1][url]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][1]['url'])?($CONFIG['weixinmenu']['button'][1]['url']):""); ?>"/>
                    </td>
                </tr>
                <?php $__FOR_START_445757295__=1;$__FOR_END_445757295__=6;for($i=$__FOR_START_445757295__;$i < $__FOR_END_445757295__;$i+=1){ ?><tr style="font-size:14px; color:#545454; text-align:center; line-height:48px;">
                        <td>子菜单<?php echo ($i); ?></td>
                        <td class="tu-right-td">
                            <input class="tudou-sc-add-text-name w80" name="data[child][1][<?php echo ($i); ?>][name]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][1][$i]['name'])?($CONFIG['weixinmenu']['child'][1][$i]['name']):""); ?>"  />
                        </td>
                        
                        <td class="tu-right-td">
                            <select name="data[child][1][<?php echo ($i); ?>][types]" class="seleFl" id="<?php echo ($CONFIG['weixinmenu']['child'][1][$i]['types']); ?>">
                            <option <?php if(($CONFIG['weixinmenu']['child'][1][$i]['types']) == "0"): ?>selected="selected"<?php endif; ?>  value="0">请选择类别</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][1][$i]['types']) == "1"): ?>selected="selected"<?php endif; ?>  value="1">URL菜单链接</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][1][$i]['types']) == "2"): ?>selected="selected"<?php endif; ?>  value="2">关键词回复菜单</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][1][$i]['types']) == "3"): ?>selected="selected"<?php endif; ?>  value="3">小程序跳转</option>
                        </select>
                        </td>
                        <td class="tu-right-td">
                         	<input class="tudou-sc-add-text-name w120" name="data[child][1][<?php echo ($i); ?>][key]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][1][$i]['key'])?($CONFIG['weixinmenu']['child'][1][$i]['key']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w120" name="data[child][1][<?php echo ($i); ?>][title]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][1][$i]['title'])?($CONFIG['weixinmenu']['child'][1][$i]['title']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w120" name="data[child][1][<?php echo ($i); ?>][appid]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][1][$i]['appid'])?($CONFIG['weixinmenu']['child'][1][$i]['appid']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w200" name="data[child][1][<?php echo ($i); ?>][pagepath]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][1][$i]['pagepath'])?($CONFIG['weixinmenu']['child'][1][$i]['pagepath']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w300" name="data[child][1][<?php echo ($i); ?>][url]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][1][$i]['url'])?($CONFIG['weixinmenu']['child'][1][$i]['url']):""); ?>"/>
                        </td>
                    </tr><?php } ?>   
                <tr bgcolor="#f1f1f1" style="font-size:14px; color:#545454; text-align:left; line-height:48px;">
                    <td width="140" align="center">BUTTON_2</td>
                    <td  class="tu-right-td">
                        <input class="tudou-sc-add-text-name w80" name="data[button][2][name]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][2][name])?($CONFIG['weixinmenu']['button'][2][name]):""); ?>"  />
                    </td>
                    <td class="tu-right-td">
                        <select name="data[button][2][types]" class="seleFl" id="<?php echo ($CONFIG['weixinmenu']['button'][2]['types']); ?>">
                        	<option <?php if(($CONFIG['weixinmenu']['button'][2]['types']) == "0"): ?>selected="selected"<?php endif; ?>  value="0">请选择类别</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][2]['types']) == "1"): ?>selected="selected"<?php endif; ?>  value="1">URL菜单链接</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][2]['types']) == "2"): ?>selected="selected"<?php endif; ?>  value="2">关键词回复菜单</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][2]['types']) == "3"): ?>selected="selected"<?php endif; ?>  value="3">小程序跳转</option>
                        </select>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][2][key]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][2]['key'])?($CONFIG['weixinmenu']['button'][2]['key']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][2][title]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][2]['title'])?($CONFIG['weixinmenu']['button'][2]['title']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][2][appid]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][2]['appid'])?($CONFIG['weixinmenu']['button'][2]['appid']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w200" name="data[button][2][pagepath]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][2]['pagepath'])?($CONFIG['weixinmenu']['button'][2]['pagepath']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w300" name="data[button][2][url]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][2]['url'])?($CONFIG['weixinmenu']['button'][2]['url']):""); ?>"/>
                    </td>
                </tr>
                <?php $__FOR_START_2112784019__=1;$__FOR_END_2112784019__=6;for($i=$__FOR_START_2112784019__;$i < $__FOR_END_2112784019__;$i+=1){ ?><tr style="font-size:14px; color:#545454; text-align:center; line-height:48px;">
                        <td>子菜单<?php echo ($i); ?></td>
                        <td class="tu-right-td">
                            <input class="tudou-sc-add-text-name w80" name="data[child][2][<?php echo ($i); ?>][name]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][2][$i]['name'])?($CONFIG['weixinmenu']['child'][2][$i]['name']):""); ?>"  />
                        </td>
                        <td class="tu-right-td">
                            <select name="data[child][2][<?php echo ($i); ?>][types]" class="seleFl" id="<?php echo ($CONFIG['weixinmenu']['child'][2][$i]['types']); ?>">
                            <option <?php if(($CONFIG['weixinmenu']['child'][2][$i]['types']) == "0"): ?>selected="selected"<?php endif; ?>  value="0">请选择类别</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][2][$i]['types']) == "1"): ?>selected="selected"<?php endif; ?>  value="1">URL菜单链接</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][2][$i]['types']) == "2"): ?>selected="selected"<?php endif; ?>  value="2">关键词回复菜单</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][2][$i]['types']) == "3"): ?>selected="selected"<?php endif; ?>  value="3">小程序跳转</option>
                        </select>
                        </td>
                        <td class="tu-right-td">
                         	<input class="tudou-sc-add-text-name w120" name="data[child][2][<?php echo ($i); ?>][key]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][2][$i]['key'])?($CONFIG['weixinmenu']['child'][2][$i]['key']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w120" name="data[child][2][<?php echo ($i); ?>][title]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][2][$i]['title'])?($CONFIG['weixinmenu']['child'][2][$i]['title']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w120" name="data[child][2][<?php echo ($i); ?>][appid]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][2][$i]['appid'])?($CONFIG['weixinmenu']['child'][2][$i]['appid']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w200" name="data[child][2][<?php echo ($i); ?>][pagepath]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][2][$i]['pagepath'])?($CONFIG['weixinmenu']['child'][2][$i]['pagepath']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w300" name="data[child][2][<?php echo ($i); ?>][url]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][2][$i]['url'])?($CONFIG['weixinmenu']['child'][2][$i]['url']):""); ?>"/>
                        </td>
                    </tr><?php } ?>    
                <tr bgcolor="#f1f1f1" style="font-size:14px; color:#545454; text-align:left; line-height:48px;">
                    <td align="center">BUTTON_3</td>
                    <td  class="tu-right-td">
                        <input class="tudou-sc-add-text-name w80" name="data[button][3][name]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][3][name])?($CONFIG['weixinmenu']['button'][3][name]):""); ?>"  />
                    </td>
                    <td class="tu-right-td">
                        <select name="data[button][3][types]" class="seleFl" id="<?php echo ($CONFIG['weixinmenu']['button'][3]['types']); ?>">
                        	<option <?php if(($CONFIG['weixinmenu']['button'][3]['types']) == "0"): ?>selected="selected"<?php endif; ?>  value="0">请选择类别</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][3]['types']) == "1"): ?>selected="selected"<?php endif; ?>  value="1">URL菜单链接</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][3]['types']) == "2"): ?>selected="selected"<?php endif; ?>  value="2">关键词回复菜单</option>
                            <option <?php if(($CONFIG['weixinmenu']['button'][3]['types']) == "3"): ?>selected="selected"<?php endif; ?>  value="3">小程序跳转</option>
                        </select>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][3][key]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][3]['key'])?($CONFIG['weixinmenu']['button'][3]['key']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][3][title]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][3]['title'])?($CONFIG['weixinmenu']['button'][3]['title']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w120" name="data[button][3][appid]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][3]['appid'])?($CONFIG['weixinmenu']['button'][3]['appid']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w200" name="data[button][3][pagepath]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][3]['pagepath'])?($CONFIG['weixinmenu']['button'][3]['pagepath']):""); ?>"/>
                    </td>
                    <td class="tu-right-td">
                         <input class="tudou-sc-add-text-name w300" name="data[button][3][url]" type="text" value="<?php echo (($CONFIG['weixinmenu']['button'][3]['url'])?($CONFIG['weixinmenu']['button'][3]['url']):""); ?>"/>
                    </td>
                </tr>
                <?php $__FOR_START_1318181312__=1;$__FOR_END_1318181312__=6;for($i=$__FOR_START_1318181312__;$i < $__FOR_END_1318181312__;$i+=1){ ?><tr style="font-size:14px; color:#545454; text-align:center; line-height:48px;">
                        <td>子菜单<?php echo ($i); ?></td>
                        <td class="tu-right-td">
                            <input class="tudou-sc-add-text-name w80" name="data[child][3][<?php echo ($i); ?>][name]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][3][$i]['name'])?($CONFIG['weixinmenu']['child'][3][$i]['name']):""); ?>"  />
                        </td>
                        <td class="tu-right-td">
                            <select name="data[child][3][<?php echo ($i); ?>][types]" class="seleFl" id="<?php echo ($CONFIG['weixinmenu']['child'][3][$i]['types']); ?>">
                            <option <?php if(($CONFIG['weixinmenu']['child'][3][$i]['types']) == "0"): ?>selected="selected"<?php endif; ?>  value="0">请选择类别</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][3][$i]['types']) == "1"): ?>selected="selected"<?php endif; ?>  value="1">URL菜单链接</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][3][$i]['types']) == "2"): ?>selected="selected"<?php endif; ?>  value="2">关键词回复菜单</option>
                            <option <?php if(($CONFIG['weixinmenu']['child'][3][$i]['types']) == "3"): ?>selected="selected"<?php endif; ?>  value="3">小程序跳转</option>
                        </select>
                        </td>
                        <td class="tu-right-td">
                         	<input class="tudou-sc-add-text-name w120" name="data[child][3][<?php echo ($i); ?>][key]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][3][$i]['key'])?($CONFIG['weixinmenu']['child'][3][$i]['key']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w120" name="data[child][3][<?php echo ($i); ?>][title]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][3][$i]['title'])?($CONFIG['weixinmenu']['child'][3][$i]['title']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w120" name="data[child][3][<?php echo ($i); ?>][appid]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][3][$i]['appid'])?($CONFIG['weixinmenu']['child'][3][$i]['appid']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w200" name="data[child][3][<?php echo ($i); ?>][pagepath]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][3][$i]['pagepath'])?($CONFIG['weixinmenu']['child'][3][$i]['pagepath']):""); ?>"/>
                        </td>
                        <td class="tu-right-td">
                             <input class="tudou-sc-add-text-name w300" name="data[child][3][<?php echo ($i); ?>][url]" type="text" value="<?php echo (($CONFIG['weixinmenu']['child'][3][$i]['url'])?($CONFIG['weixinmenu']['child'][3][$i]['url']):""); ?>"/>
                        </td>
                    </tr><?php } ?>    
            </table>
            <div class="sm-qr-tu"><input type="submit" value="确认添加" class="sm-tudou-btn-input" /></div>
        </form>
    </div>
</div>
  		</div>
	</body>
</html>