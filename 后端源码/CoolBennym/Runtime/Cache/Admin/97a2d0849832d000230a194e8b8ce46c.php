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
        <li class="li1">主题</li>
        <li class="li2">主题管理</li>
        <li class="li2 li3">主题列表</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>这里管理主题哈【这里又称为子分类列表】</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('thread/create','','添加主题');?>
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('thread/index');?>">
                    <div class="seleHidden" id="seleHidden">
                    	<div class="seleK">
                     	<label>
                            <span>分类：</span>
                            <select id="cate_id" name="cate_id" class="select w100">
                                <option value="0">请选择...</option>
                                <?php if(is_array($cates)): foreach($cates as $key=>$var): ?><option value="<?php echo ($var["cate_id"]); ?>"  <?php if(($var["cate_id"]) == $cate_id): ?>selected="selected"<?php endif; ?> ><?php echo ($var["cate_name"]); ?></option><?php endforeach; endif; ?>
                            </select>
                        </label>
                        
                        
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
                    <td class="w50"><input type="checkbox" class="checkAll" rel="thread_id" /></td>
                    <td class="w50">ID</td>
                    <td>主题[子分类]名称</td>
                    <td>管理员</td>
                    <td>主题分类</td>
                    <td>主题图片</td>
                    <td>主题banner</td>
                    <td>主题帖子数</td>
                    <td>主题关注数</td>
                    <td>是否热门</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_thread_id" type="checkbox" name="thread_id[]" value="<?php echo ($var["thread_id"]); ?>" /></td>
                        <td><?php echo ($var["thread_id"]); ?></td>
                        <td><?php echo ($var["thread_name"]); ?></td>
                        <td><?php echo ($users[$var['user_id']]['nickname']); ?></td>
                        <td><?php echo ($cates[$var['cate_id']]['cate_name']); ?></td>
                        <td><a target="_blank" href="<?php echo config_img($var['photo']);?>"><img src="<?php echo config_img($var['photo']);?>" class="w80" /></a></td>
                        <td><a target="_blank"  href="<?php echo config_img($var['banner']);?>"><img src="<?php echo config_img($var['banner']);?>" class="w80" /></a></td>
                        <td><?php echo ($var["posts"]); ?></td>
                        <td><?php echo ($var["fans"]); ?></td>
                        <td><?php if(($var["is_hot"]) == "1"): ?>是<?php else: ?>否<?php endif; ?></td>
                        <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                        <td>
                            <?php echo BA('thread/edit',array("thread_id"=>$var["thread_id"]),'编辑','','tu-dou-btn');?>
                            <?php echo BA('threadpost/create',array("thread_id"=>$var["thread_id"]),'发帖','','tu-dou-btn');?>
                            <?php echo BA('thread/delete',array("thread_id"=>$var["thread_id"]),'删除','act','tu-dou-btn');?>
                        </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
            <div class="left">
                <?php echo BA('thread/delete','','批量删除','list',' a2');?>
            </div>
        </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>