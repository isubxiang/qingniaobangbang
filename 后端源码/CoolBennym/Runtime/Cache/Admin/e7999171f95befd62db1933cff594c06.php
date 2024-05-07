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
        <li class="li1">商家</li>
        <li class="li2">商家管理</li>
        <li class="li2 li3">商家回收站</li>
    </ul>
</div>
<div class="main-tu-js main-sc">
    <p class="attention"><span>注意：</span>这个删除后商家数据就彻底删除了，请谨慎操作！删除后无法恢复！操作前必须备份数据库，否则后果自负！删除后会员就可以重新添加商家啦~</p>
    <div class="tudou-js-nr">
        <div class="tu-select-nr" style="margin-top: 0px; border-top:none;">
            <div class="left">
                <?php echo BA('shop/create','','添加申请');?>
            </div>
            <div class="right">
                <form class="search_form" method="post" action="<?php echo U('shop/recovery');?>">
                    <div class="seleHidden" id="seleHidden">
                        <span>关键字(电话或商户名称)</span>
                        <input type="text" name="keyword" value="<?php echo ($keyword); ?>" class="tu-inpt-text" /><input type="submit" value="   搜索"  class="inpt-button-tudou" />
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
                    <td class="w50"><input type="checkbox" class="checkAll" rel="shop_id" /></td>
                    <td class="w50">ID</td>
                    <td>管理者(电话)</td>
                    <td>商铺名称(区域商圈)</td>
                    <td>分类(商铺标签)</td>
                    <td>商铺LOGO</td>
                    <td>入住时间</td>
                    <td class="w200">操作</td>
                </tr>
                <?php if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                        <td><input class="child_shop_id" type="checkbox" name="shop_id[]" value="<?php echo ($var["shop_id"]); ?>" /></td>
                        <td><?php echo ($var["shop_id"]); ?></td>
                        <td>
                        ID：<?php echo ($users[$var['user_id']]['account']); ?>(<?php echo ($var["user_id"]); ?>)<br/>
                        电话：<?php echo ($var["tel"]); ?>
                        </td>
                        <td>
                        名称：<?php echo ($var["shop_name"]); ?><br/>
                        城市：<?php echo ($citys[$var['city_id']]['name']); ?>-<?php echo ($areas[$var['area_id']]['area_name']); ?>-<?php echo ($business[$var['business_id']]['business_name']); ?>
                        </td>
                        <td>
                       分类：<?php echo ($cates[$var['cate_id']]['cate_name']); ?><br/>
                       tag: <?php echo ($var["tags"]); ?>
                        </td>
                        <td><img style="padding:2px; height:60px; width:60px;" src="<?php echo config_img($var['logo']);?>" /></td>
                        
                    <td><?php echo (date('Y-m-d H:i:s',$var["create_time"])); ?></td>
                    <td class="w150">
                                 
					<?php echo BA('shop/recovery2',array("shop_id"=>$var["shop_id"]),'恢复','act','tu-dou-btn-small');?>
                    <?php echo BA('shop/edit',array("shop_id"=>$var["shop_id"]),'编辑','','tu-dou-btn-small');?>
                    <?php echo BA('shop/delete2',array("shop_id"=>$var["shop_id"]),'删除','act','tu-dou-btn-small');?>
                    <a target="_blank" class="tu-dou-btn-small" href="<?php echo U('shop/login',array('shop_id'=>$var['shop_id']));?>">管理</a>
                    </td>
                    </tr><?php endforeach; endif; ?>
            </table>
            <?php echo ($page); ?>
        </div>
        <div class="tu-select-nr" style="margin-bottom: 0px; border-bottom: none;">
            <div class="left">
                <?php echo BA('shop/delete','','批量删除','list',' a2');?>
            </div>
        </div>
    </form>
</div>
</div>
  		</div>
	</body>
</html>